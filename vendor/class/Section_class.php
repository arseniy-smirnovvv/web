<?php  

	require_once 'build_class.php';

	/**
	 * Класс, для работы с разделами сайта
	 */
	class Section
	{
		private $db;
		private $table;
		private $categoryClass;

		function __construct($db, $table, $categoryClass = false)
		{
			$this->db = $db;
			$this->table = $table;
			$this->categoryClass = $categoryClass;
		}

		# Контроллер добавление записи в бд
		public function add($name, $getTag, $desc, $adminName, $checkUser)
		{
			try {
			 	$name = $this->validData($name, "Поле с названием пустое!", "Поле с названием слишком длинное!");
			 	$getTag = $this->validData($getTag, "Поле с ГетТэгом пустое!", "Поле с ГетТэгом слишком длинное!");
			 	$desc = $this->validData($desc, "Поле с описанием пустое!", "Поле с описанием слишком длинное!");
			 	$checkUser = $this->validCheckbox($checkUser);
			 	$this->addToDb($name, $getTag, $desc, $adminName, $checkUser);
			 } catch (Exception $e) {
			 	throw new Exception($e->getMessage(), 1);
			 } 
		}

		# Контроллер изменение раздела
		public function edit($id, $name, $desc, $getTag, $checkUser, $lastGetSection)
		{
			try {
				$this->issetSection($id);
				$name = $this->validData($name, "Поле с названием пустое!", "Поле с названием слишком длинное!");
				$desc = $this->validData($desc, "Поле с описанием пустое!", "Поле с описанием слишком длинное!");
				$getTag = $this->validData($getTag, "Поле с ГетТэгом пустое!", "Поле с ГетТэгом слишком длинное!");
				$checkUser = $this->validCheckbox($checkUser);
				$this->editToDb($id, $name, $desc, $getTag, $checkUser);
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}


		public function delToId($id)
		{
			try {
				$this->issetSection($id);
				$section = $this->ShowToId($id);
				$this->delToDb($id);				
				return $section['get-section'];
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}

		#Метод, который возвращает асоциативный массив со всеми разделами
		public function ShowAll()
		{
			$query = "SELECT * FROM `$this->table`";

			$set_section = $this->db->query($query);

			$section = Build::BuildDataDb($set_section);

			return $section;
		}


		# Методы, проверяющие переданные данные
		private function validData($data, $error1, $error2)
		{
			$len = mb_strlen($data);

			if ($len <= 0) throw new Exception($error1);
			if ($len > 225) throw new Exception($error2);
			
			return htmlspecialchars($data);
		}

		private function validCheckbox($check)
		{
			if ($check == 'on') return 1;

			return 0; 
		}



		# Метод, который добавляет запись в базу данных
		private function addToDb($name, $get, $desc, $adminName, $checkUser)
		{
			$query = "INSERT INTO `section` ( `name`,`get-section`, `des`, `create_user`, `hidden_user`, `date`) VALUES ('$name', '$get', '$desc', '$adminName', '$checkUser', UNIX_TIMESTAMP())";

			if(!$this->db->query($query)) throw new Exception("Раздел с данным ГетТэгом уже существует!");

			return true;
		}

		# Метод, который обновляет данные 
		private function editToDb($id, $name, $desc, $getTag, $checkUser)
		{
			$query = "UPDATE `$this->table` SET `name` = '$name', `des` = '$desc', `get-section` = '$getTag', `hidden_user` = '$checkUser' WHERE `$this->table`.`id` = '$id'";

			if (!$this->db->query($query)) throw new Exception("Не удалось обновить раздел!");
		}

		# Метод, который удаляет раздел 
		private function delToDb($id)
		{
			$query = "DELETE FROM `$this->table` WHERE `section`.`id` = '$id'";

			if(!$this->db->query($query)) throw new Exception("Не удалось удалить раздел!");
		}


		# Метод, который возвращает  массив с даннымми о  разделом по его ID
		public function ShowToId($id)
		{
			try {
				$this->issetSection($id);	
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}

			$query = "SELECT * FROM `$this->table` WHERE `id` = $id";

			if(!($set_section = $this->db->query($query))) throw new Exception("Не удалось получить нужный вам раздел!", 1);

			$section = Build::BuildOneDataDb($set_section);

			return $section;
		}

		# Метод, который проверяет по id, существует ли данный раздел
		private function issetSection($id)
		{
			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `id` = $id";

			if (!($set_count = $this->db->query($query))) throw new Exception("Не удалось получить нужный вам раздел!");
			
			$count = Build::BuildOneDataDb($set_count);

			if ($count['COUNT(*)'] == 0) throw new Exception("Раздела по указанному id не существует!");
		}

		private function getGetSectionToList($id, $list)
		{
			for ($i=0; $i < count($list); $i++) { 
				if ($list[$i]['id'] == $id) return $list[$i]['get-section'];
			}
			return true;
		}

		public function issetSectionToGet($get, $error)
		{
			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `get-section` = '$get'";

			if (!($set_count = $this->db->query($query))) throw new Exception("Техническая ошибка. Не получилось подключится к базе данных.");
			
			$count = Build::BuildOneDataDb($set_count);

			if ($count['COUNT(*)'] == 0) throw new Exception($error);
		}

		public function getSectionToGetSection($get)
		{
			$query = "SELECT * FROM `$this->table` WHERE `get-section` = '$get'";

			if(!($set_section = $this->db->query($query))) throw new Exception("Не удалось получить раздел!");

			$section = Build::BuildOneDataDb($set_section);

			return $section;
		}

		public function getNameToGetSection($getSection)
		{
			try {
				$section =  $this->ShowAll();

				for ($i=0; $i < count($section); $i++) { 
					if($getSection == $section[$i]['get-section']) return $section[$i]['name'];
				}

			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}

		# Метод, который возвращает раздел, с максимальном кол-вом просмотров
		public function countMaxSection()
		{
			$query = "SELECT MAX(views) FROM `$this->table`";

			if(!($set_views = $this->db->query($query))) throw new Exception("Не удалось получить самый просматриваемый раздел!", 1);

			$views = Build::BuildOneDataDb($set_views);
			$maxViews = $views['MAX(views)'];

			$query = "SELECT * FROM `$this->table` WHERE `views` = '$maxViews'";

			if(!($set_section = $this->db->query($query))) throw new Exception("Не удалось получить самый просматриваемый раздел", 1);
				
			$section = Build::BuildOneDataDb($set_section);

			return $section;
		}
	}

?>