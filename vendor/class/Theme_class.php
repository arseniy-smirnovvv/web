<?php 
	
	require_once 'build_class.php';

	/**
	 * Класс, для работы с темами
	 */
	class Theme
	{
		private $db;
		private $table;
		private $table_visit_log;

		function __construct($db, $table, $table_visit_log = '')
		{
			$this->db = $db;
			$this->table = $table;
			$this->table_visit_log = $table_visit_log;
		}

		# Контроллер, для добавлений новый темы
		public function add($title, $text, $getSection, $getCategory, $user_id)
		{
			try {
				$title = $this->validData($title, "Поле с заголовком пустое!", "Поле с заголовком слишком длинное!");
				$text = $this->validText($text, "Поле с содержимом темы пустое!", "Поле с содержимом темы слишком длинное!");
				$this->addToDb($title, $text, $getSection, $getCategory, $user_id);
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}

		# Контроллер, для обновления темы
		public function edit($theme_id, $title, $text)
		{
			try {
				$title = $this->validData($title, "Поле с заголовком пустое!","Поле с заголовком слишком длинное!");
				$text = $this->validText($text, "Поле с содержимом темы пустое!", "Поле с содержимом темы слишком длинное!");
				$this->editToDb($theme_id, $title, $text);
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}

		# Проверяет вхожденные данные
		private function validData($data, $error1, $error2)
		{
			$len = mb_strlen($data);

			if($len <= 0) throw new Exception($error1);
			if($len > 255) throw new Exception($error2);

			return htmlspecialchars($data);
		}

		# Тоже самое что и validData, толлько макс.символов 10 к
		private function validText($text, $error1, $error2)
		{
			$len = mb_strlen($text);

			if($len <= 0) throw new Exception($error1);
			if($len > 10000) throw new Exception($error2);			

			return htmlspecialchars($text);
		}

		# Проверяет, объявлен ли класс
		private function issetClass($className, $error)
		{
			if (!$className || $className == '') throw new Exception($error);
			
			return true;
		}

		# Добалвяет тему в базу данных
		private function addToDb($title, $text, $getSection, $getCategory, $user_id)
		{
			$query = "INSERT INTO `$this->table` (`title`, `text`, `get-category`, `get-section`, `user-id`, `date`) VALUES ('$title', '$text', '$getCategory', '$getSection', '$user_id', UNIX_TIMESTAMP())";		

			if (!$this->db->query($query)) throw new Exception("Не удалось добавить тему!");
		}

		# Обновляет тему в базе данных по её id
		public function editToDb($theme_id, $title, $text)
		{
			$query = "UPDATE `$this->table` SET `title` = '$title', `text` = '$text', `last-edit` = UNIX_TIMESTAMP() WHERE `$this->table`.`id` = '$theme_id'";

			if(!$this->db->query($query)) throw new Exception("Не удалось обновить тему!");

			return true;
		}

		# Метод, который возрващает асоциативный массив с темами по getCategory
		public function showToGetCategory($get)
		{
			$query = "SELECT * FROM `$this->table` WHERE `get-category` = '$get'";

			if (!($set_themes = $this->db->query($query))) throw new Exception("Не удалось получить темы", 1);
			
			$themes = Build::BuildDataDb($set_themes);
			return $themes;
		}

		# Метод, который возвращает массив с темой по его id
		public function showThemeToId($id)
		{
			try {
				$query = "SELECT * FROM `$this->table` WHERE `id` = '$id'";

				if (!($set_themes = $this->db->query($query))) throw new Exception("Не удалось получить тему из базы данных!", 3);

				$theme = Build::BuildOneDataDb($set_themes);

				return $theme;
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 3);
			}
			
		}

		# Метод, который проверяет, существует ли данная тема
		public function issetThemeToId($id)
		{
			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `id` = '$id'";

			if(!($set_count = $this->db->query($query))) throw new Exception("Не удалось проверить тему на существуетвование!");

			$count = Build::BuildOneDataDb($set_count);

			if($count["COUNT(*)"] == 0) throw new Exception("Темы с таким id не существует!");
			
			return true;
		}

		# Метод, который возвращает кол-во тем какой-то категории по её getCategory
		public function countThemeToGetCategory($get)
		{
			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `get-category` = '$get'";
			
			if(!($set_count = $this->db->query($query))) return 0;

			$count = Build::BuildOneDataDb($set_count);

			return $count['COUNT(*)'];
		}

		# Метод, который возвращает ассоциавтиный массив с темами, которые создал пользователь
		public function getThemeToUserId($user_id)
		{
			$query = "SELECT * FROM `$this->table` WHERE `user-id` = '$user_id'";

			if(!($set_themes = $this->db->query($query))) throw new Exception("Не удалось получить темы, которые создал пользователь!", 1);

			return Build::BuildDataDb($set_themes);
		}

		#Метод, который проверяет, является ли вхожденный ид создателем данной темы
		public function issetThemeToUserId($user_id, $theme_id)
		{
			try {
				$this->issetThemeToId($theme_id);
				$theme = $this->showThemeToId($theme_id);
				if($theme['user-id'] != $user_id) throw new Exception("Данная тема не принадлежит вам!");	
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 4);
			}
		}

		# Метод, который возвращает кол-во созданны тем
		public function countTheme()
		{
			$query = "SELECT COUNT(*) FROM `$this->table`";

			if(!($set_count = $this->db->query($query))) throw new Exception("Не удалось получить кол-во тем!", 1);
			
			$count = Build::BuildOneDataDb($set_count);

			return $count['COUNT(*)'];
		}

		# Метод, который возвращает самый просматриваемую тему
		public function countMaxTheme()
		{
			$query = "SELECT MAX(views) FROM `$this->table`";

			if(!($set_views = $this->db->query($query))) throw new Exception("Не удалось получить самую просматриваемую тему!", 1);

			$views = Build::BuildOneDataDb($set_views);
			$maxViews = $views['MAX(views)'];

			$query = "SELECT * FROM `$this->table` WHERE `views` = '$maxViews'";

			if(!($set_theme = $this->db->query($query))) throw new Exception("Не удалось получить самую просматриваемую тему!", 1);
				
			$theme = Build::BuildOneDataDb($set_theme);

			return $theme;
		}

		# Метод, который возвращает кол-во тем  созданныъ пользователем
		public function countThemeToUserId($user_id)
		{
			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `user-id` = '$user_id'";

			if(!($set_count = $this->db->query($query))) throw new Exception("Не удалось получить кол-во тем, которые создал пользователь", 1);
			$count = Build::BuildOneDataDb($set_count);

			return $count['COUNT(*)'];
		}

		# Котроллер, который удаляет все темы, которые создал пользователь
		public function delThemeToUserId($id)
		{
			try {
				if($this->countThemeToUserId($id) == 0 ) throw new Exception("Пользователь еще не создал ни одной темы!");
 				$themes_id = $this->getThemesIdToUserId($id);	
				$this->delToDbToUserId($id);
				return $themes_id;
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}


		# Метод, который возвращает массив со всеми id темами, которые создал пользователь
		private function getThemesIdToUserId($id)
		{
			$query = "SELECT `id` FROM `$this->table` WHERE `user-id` = '$id'";

			if(!($set_themes = $this->db->query($query))) throw new Exception("Не удалось получить список тем, которые создал пользователь!");

			$themes = Build::BuildDataDb($set_themes);

			return $themes;
		}

		# Метод, который удаляет созданные темы пользователя
		public function delToDbToUserId($id)
		{
			$query = "DELETE FROM `$this->table` WHERE `$this->table`.`user-id` = '$id'";

			if(!$this->db->query($query)) throw new Exception("Не удалось удалить темы, которые создал пользователь!");
		}

		# Контроллер счетчика просмотров
		public function viewsController($id, $theme_id)
		{
			try {				
				$user_ip = $_SERVER['REMOTE_ADDR'];
				$nowDate = time();

				$query = "SELECT * FROM `$this->table_visit_log` WHERE `theme-id` = '$theme_id' AND `user-ip` = '$user_ip'";

				if(!($set_visit = $this->db->query($query))) throw new Exception("Техническая ошибка. Не удалось увеличить просмотры!", 1);

				$visit = Build::BuildOneDataDb($set_visit);
				$days_1 = 60*60*24;

				if($visit != ''){ // Если после запроса в базу данных, есть хоть какие-то данные, то выполняется следующие
					$diff = $nowDate - $visit['date'];//Вычисляется разница во времмя, из текущего времени вычитается время, когда был записан в базу данных лог
					if($diff > $days_1){//Если разница больше 1 дня, то выполняется следующие
						$this->visitLog($id, $theme_id, $user_ip);//Записывается лог в базу данных
						$this->views($theme_id);//Увеличвается просмотр базы данных
					}
				} else {//Если же никакого запроса нет,  то выполняется тоже самое, если бы разница была больше 1 дня
					$this->visitLog($id, $theme_id, $user_ip);
					$this->views($theme_id);
				}

				return true;
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 1);
			}
		}

		# Метод, который записывает лог в таблицу лог-visit
		public function visitLog($user_id, $theme_id, $user_ip)
		{
			$query = "INSERT INTO `$this->table_visit_log` (`theme-id`, `user-id`, `user-ip`, `date`) VALUES ('$theme_id', '$user_id', '$user_ip', UNIX_TIMESTAMP())";

			if(!$this->db->query($query)) throw new Exception("Не удалось увеличить просмотр темы!");
		}

		# Метод, который увеличивает счетчик просмотров на 1
		public function views($theme_id)
		{
			$query = "UPDATE `$this->table` SET `views` = `views` + 1 WHERE `id` = '$theme_id'";

			$this->db->query($query);
		}

		# Метод, который получает все просмотры по get-category
		public function getViewsToGetCategory($get)
		{
			$query = "SELECT `views` FROM `$this->table` WHERE `get-category` = '$get'";

			if(!($set_views =  $this->db->query($query))) throw new Exception("Техническая ошибка при получении просмотров!", 1);

			$viewsD = Build::BuildDataDb($set_views);
			$views = 0;
			$maxViews = count($viewsD);

			for ($i=0; $i < $maxViews; $i++) { 
				$views += $viewsD[$i]['views'];
			}

			return $views;
		}


		# Метод, который получает все id тем, которые связанны с геттегом раздела
		public function getIdToGetSection($get)
		{

			$query = "SELECT `id` FROM `$this->table` WHERE `get-section` = '$get'";

			if(!($set_id = $this->db->query($query))) throw new Exception("Не удалось получить id тем для их удаления!", 1);
				
			return Build::BuildDataDb($set_id);	
		}

		# Метод, который удаляет все темы, которые
		public function delToGetSection($get)
		{
			$query = "DELETE FROM `$this->table` WHERE `get-section` = '$get'";

			if(!$this->db->query($query)) throw new Exception("Не удалось удалить темы!", 1);	
		}

		# Метод, который получает id только что созданной темы (для точности данных выборка идет по название темы, и её содержимому)
		public function getIdToNewTheme($title, $text)
		{
			$query = "SELECT `id` FROM `$this->table` WHERE `title` = '$title' AND `text` = '$text'";

			if(!($set_id = $this->db->query($query))) throw new Exception("Не удалось получить данные о созданной темы", 1);
			$id = Build::BuildOneDataDb($set_id);
			return $id['id'];
		}


		# Метод, который меняет параметр get-section на новый
		public function editGetSection($new, $last)
		{
			$query = "UPDATE `$this->table` SET `get-section` = '$new' WHERE `$this->table`.`get-section` = '$last'";

		 	if (!$this->db->query($query)) throw new Exception("Не удалось изменить параметр у темы!");
		}

		# Метод, который меняет параметр get-category на новый
		public function editGetCategory($new, $last)
		{
			$query = "UPDATE `$this->table` SET `get-category` = '$new' WHERE `$this->table`.`get-category` = '$last'";

			if(!$this->db->query($query)) throw new Exception("Не удалось изменить параметр у темы!", 1);
		}
	}
	
?>