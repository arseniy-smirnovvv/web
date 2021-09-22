<?php  

	class Router
	{
		# Метод, для проверки входимых GET запросом
		public function defSqlAdminAdd($mode, $type, $id)
		{
			$key1 = false;
			$key2 = false;

			$reg = "#UNION#";
			$redirect = 'Location: add.php?mode=add&type=section';
			$redirectEdit = "Location: add.php?mode=edit&type=section&id=1";
			$blackList = [
				'mode' => [
					'add',
					'edit'
				],
				'type' => [
					'section',
					'category'
				]
			]; 

			for ($i=0; $i < count($blackList['mode']); $i++) { 
				if($blackList['mode'][$i] == $mode) $key1 = true;
			}

			for ($i=0; $i < count($blackList['type']); $i++) { 
				if($blackList['type'][$i] == $type) $key2 = true;
			}

			if (!$key1 || !$key2) header($redirect);

			if($mode == 'edit'){
				if (!isset($id)) {
					header($redirect. '&id=1');
				}

				if (!is_numeric($id) || preg_match($reg, $id)) {
					header("Location: add.php?mode=edit&type=".$type."&id=1");
				}
			}

			return true;
		}

		# Метод, для проверки GET запросов, при удалении записи
		public function defSqlAdminIndex($type,$id)
		{
			$key1 = false;
			$key2 = false;

			$whiteList = [
				'type' => [
					'section',
					'category'
				]
			]; 

			if (isset($id) || isset($type)) {


				for ($i=0; $i < count($whiteList['type']); $i++) {
					if ($whiteList['type'][$i] == $type) $key1 = true;
				}

				if (is_numeric($id)) {
					$key2 = true;
				}

				if (!$key1 || !$key2) {
					header("Location: index.php");
				}
			}
		}

		public function defSqlCategory($getCategory)
		{
			if(!isset($getCategory)) header("Location: category.php?category=all");

			if(is_numeric($getCategory) || !is_string($getCategory) || preg_match("#UNION#", $getCategory))
				header("Location: category.php?category=all");
		}

		public function defSqlUser($id)
		{
			$redirect = "Location: user.php?id=". $_SESSION['id'];

			if(!isset($id) || !is_numeric($id)) header($redirect); 
		}

		public function defSqlAdd($section, $category)
		{
			$redirect = "Location: index.php";

			if(!isset($section) || !isset($category)) header($redirect);

			if(preg_match("#UNION#", $section) || preg_match("#UNION#", $category)) header($redirect);
		}

		public function defSqlTheme($id)
		{
			$redirect = "Location: index.php";

			if(!isset($id) || $id == NULL) header($redirect);
			if(!is_numeric($id) || preg_match("#UNION#", $id)) header($redirect);
		}

		public function defSqlEdit($id)
		{
			$redirect = "Location: category.php";

			if(!isset($id)  || preg_match("#UNION#", $id) || !is_numeric($id)) header($redirect);
		}
	}
	
?>