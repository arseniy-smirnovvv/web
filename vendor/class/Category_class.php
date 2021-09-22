<?php  

	require_once 'build_class.php';

	/**
	 * Класс, для работы с категориями сайта
	 */
	class Category
	{
		private $db;
		private $table;

		function __construct($db, $table)
		{
			$this->db = $db;
			$this->table = $table;
		}

		# Контроллер добавление категории в базу данных
		public function add($name, $desc, $getCategory, $getSection, $adminName, $sectionList)
		{
			try {
				$name = $this->validData($name, 'Поле с названием пусто!', 'Поле с названием слишком длинное!');
				$desc = $this->validData($desc, 'Поле с описанием пусто!', 'Поле с описанием слишком длинное!');
				$getCategory = $this->validData($getCategory, 'Поле с ГетТэгом пусто!', 'Поле с ГетТэгом слишком длинное!');
				$getSection = $this->validGetList($getSection, $sectionList);
				$this->addToDb($name, $desc, $getCategory, $getSection, $adminName);
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}

		# Контроллер изменение категории
		public function edit($id, $name, $desc, $getCategory, $getSection, $sectionList)
		{
			try {
				$name = $this->validData($name, "Поле с названием пусто!", "Поле с названием слишком длинное!");
				$desc = $this->validData($desc, "Поле с описанием пусто!", "Поле с описанием слишком длинное!");
				$getCategory = $this->validData($getCategory, "Поле с ГетТэгом пусто!", "Поле с ГетТэгом слишком длинное!");
				$getSection = $this->validGetList($getSection, $sectionList);
				$this->editToDb($id ,$name, $desc, $getCategory, $getSection);
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}

		public function delToId($id)
		{
			try {
				$this->issetCategory($id);
				$category = $this->ShowToId($id);
				$this->delToDb($id);
				return $category['get-section'];
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}

		# Метод, проверяющие переданные данные
		private function validData($val, $error1, $error2)
		{
			$len = mb_strlen($val);

			if ($len <= 0) throw new Exception($error1);
			if ($len > 225) throw new Exception($error2);
			
			return htmlspecialchars($val);
		}

		private function validGetList($get, $getList)
		{
			for ($i=0; $i < count($getList); $i++) { 
				if ($getList[$i]['get-section'] == $get) return htmlspecialchars($get);
			}

			throw new Exception("Выберите раздел!");
		}

		# Метод, добавляющий категорию в базу данных
		private function addToDb($name, $desc, $getCategory, $getSection, $adminName)
		{
			$query = "INSERT INTO `$this->table` (`name`, `des`, `get-category`, `get-section`, `create_user`, `date`) VALUES ('$name', '$desc', '$getCategory', '$getSection', '$adminName', UNIX_TIMESTAMP())";

			if(!$this->db->query($query)) throw new Exception("Категория с данным ГетТэгом уже существует!");

			return true;
		}

		# Метод, который обновляет категорию по его id
		private function editToDb($id, $name, $desc, $getCategory, $getSection)
		{
			$query = "UPDATE `$this->table` SET `name` = '$name', `des` = '$desc', `get-category` = '$getCategory', `get-section` = '$getSection' WHERE `$this->table`.`id` = '$id'";

			if (!$this->db->query($query)) throw new Exception("Не удалось обновить категорию!");
		}

		# Метод, который удаляет категорию, по его id
		public function delToDb($id)
		{
			$query = "DELETE FROM `$this->table` WHERE `$this->table`.`id` = '$id'";

			if(!$this->db->query($query)) throw new Exception("Не удалось удалить категорию!");
		}

		# Метод, который удаляет категорию по геттегу раздела
		public function delToGetSection($get)
		{

			$query = "DELETE FROM `$this->table` WHERE `$this->table`.`get-section` = '$get'";

			if(!$this->db->query($query)) throw new Exception("Не удалось удалить категории", 1);

		}


		# Метод, который возвращает  массив с даннымми о  разделом по его ID
		public function ShowToId($id)
		{
			try {
				$this->issetCategory($id);	
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}

			$query = "SELECT * FROM `$this->table` WHERE `id` = $id";

			if(!($set_category = $this->db->query($query))) throw new Exception("Не удалось получить нужную вам категорию!", 1);

			$category = Build::BuildOneDataDb($set_category);

			return $category;
		}


		# Метод, который возвращает ассцоциативный массив со всеми категориями
		public function ShowAll()
		{
			$query = "SELECT * FROM `$this->table`";

			$set_category = $this->db->query($query);

			$category = Build::BuildDataDb($set_category);

			return $category;
		}

		# Метод, который проверяет по id, существует ли данная категория
		private function issetCategory($id)
		{
			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `id` = $id";

			if (!($set_count = $this->db->query($query))) throw new Exception("Не удалось получить нужную вам категорию!");
			
			$count = Build::BuildOneDataDb($set_count);

			if ($count['COUNT(*)'] == 0) throw new Exception("Категории по указанному id не существует!");
		}

		# Метод, который проверяет по getcategory существует ли данная категория
		public function issetCategoryToGet($get)
		{
			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `get-category` = '$get'";

			if (!($set_count = $this->db->query($query))) throw new Exception('Не удалось получить нужную вам категорию');
			
			$count = Build::BuildOneDataDb($set_count);

			if ($count['COUNT(*)'] == 0) throw new Exception("Такой категории не существует");
		}

		# Метод, который меняет старый get-section на новый
		public function editGetSection($new, $last)
		 {
		 	$query = "UPDATE `$this->table` SET `get-section` = '$new' WHERE `$this->table`.`get-section` = '$last'";

		 	if (!$this->db->query($query)) throw new Exception("Не удалось изменить параметр у категорий!");
		 } 

		public function delGetSection($getSection)
		{
			$query = "DELETE FROM `$this->table` WHERE `$this->table`.`get-section` = '$getSection'";

			if(!$this->db->query($query)) throw new Exception("Не удалось удалить категории!");
		}

		# Метод, который получает категория по её Геттеру
		public function getCategoryToGetCategory($get)
		{
			try {
				$this->issetCategoryToGet($get);

				$query = "SELECT * FROM `$this->table` WHERE `get-category` = '$get'";

				if(!($set_category = $this->db->query($query))) throw new Exception('Запршиваемая категория не найдена',3);
				
				$category = Build::BuildOneDataDb($set_category);

				return $category;

			} catch (Exception $e) {
				throw new Exception('Error',3);
			}
		}

		# Метод, который возвращает имя категории по её getCategory
		public function getNameToGetCategory($get)
		{
			try {
				$category =  $this->ShowAll();

				for ($i=0; $i < count($category); $i++) { 
					if($get == $category[$i]['get-category']) return $category[$i]['name'];
				}

			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}

	}
?>