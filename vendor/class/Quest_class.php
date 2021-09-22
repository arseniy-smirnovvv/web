<?php  
	require_once 'build_class.php';

	/**
	 *  Класс, для работы с коментариями
	 */ 
	class Quest 
	{
		private $db;
		private $table;

		function __construct($db, $table)
		{
			$this->db = $db;
			$this->table = $table;
		}


		# Контроллер добавления коментария
		public function add($text, $theme_id, $user_id)
		{
			try {
				$text = $this->validText($text);
				$this->addToDb($text, $theme_id, $user_id);
			} catch (Exception $e) {	
				throw new Exception($e->getMessage(), 1);
			}
		}

		# Метод, который проверяет сообщение, который передал пользователь
		private function validText($text)
		{
			$len = mb_strlen($text);

			if($len <= 0) throw new Exception("Поле с коментарием пусто!");
			if($len > 10000) throw new Exception("Поле с коментарием слишком длинное!");
			
			return htmlspecialchars($text);
		}

		# Метод, который добавляет ответ в базу данных
		public function addToDb($text, $theme_id, $user_id)
		{
			$query = "INSERT INTO `$this->table` (`text`, `theme-id`, `user-create`, `date`) VALUES ('$text', '$theme_id', '$user_id', UNIX_TIMESTAMP())";

			if (!$this->db->query($query)) throw new Exception("Не удалось добавить ваш коменатрий в базу данных!");

			return true;
		}

		# Метод, который возвращает ассоциативный массив коментариев по id темы
		public function showToThemeId($id)
		{
			$query = "SELECT * FROM `$this->table` WHERE `theme-id` = '$id'";

			if(!($set_comment = $this->db->query($query))) throw new Exception("Не удалось получить коментарии из базы данных!", 1);

			$comment = Build::BuildDataDb($set_comment);
			
			return $comment;
		}

		# Метод, который возвращает количество ответов в теме по её id
		public function countQuestToThemeId($id)
		{
			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `theme-id` = '$id'";

			if(!($set_count = $this->db->query($query))) return 0;

			$count = Build::BuildOneDataDb($set_count);

			return $count['COUNT(*)'];
		}

		# Метод, который возвращает последние ответы пользователя
		

		# Метод, который возвраащет кол-во ответов пользователя
		public function countQuestToUserId($id)
		{
			$query = "SELECT COUNT(*) FROM `$this->table` WHERE `user-create` = '$id'";

			if(!($set_count = $this->db->query($query))) throw new Exception("Не удалось получить кол-во ответов пользователя!", 1);

			$count = Build::BuildOneDataDb($set_count);

			return $count["COUNT(*)"];
		}

		# Метод, который удаляет все ответы созданные в темах, которые передал пользователь (id-тем)
		public function delQuestToThemeId($themes_id)
		{
			$maxId = count($themes_id);

			for ($i=0; $i < $maxId; $i++) { 
				$id = $themes_id[$i]['id'];
				$query = "DELETE FROM `$this->table` WHERE `$this->table`.`theme-id` = '$id'";

				if(!$this->db->query($query)) throw new Exception("Не удалось удалить все ответы, которые были в теме!", 1);
			}
		}
	}
	
?>