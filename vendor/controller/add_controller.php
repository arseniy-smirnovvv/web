<?php  
	
	require_once './vendor/autoload.php';

	$route->defSqlAdd($_GET['section'], $_GET['category']);

	$userClass = new User($mysql, TABLE_USER, TABLE_CDK);
	$userClass->loginUser();

	$sectionClass = new Section($mysql, TABLE_SECTION);
	$categoryClass = new Category($mysql, TABLE_CATEGORY);
	$themeClass = new Theme($mysql, TABLE_THEMES);

	$sectionGet = $_GET['section'];
	$categoryGet = $_GET['category'];
	$user_id = $_SESSION['id'];

	$page = Page::PageList('Главная', 'index.php', 'Назад к категории', 'category.php?category='.$categoryGet, 'Личный Кабинет','user.php?id=' . $_SESSION['id']);

	try {

		$sectionClass->issetSectionToGet($sectionGet, "Раздела не существует!");	
		$categoryClass->issetCategoryToGet($categoryGet);

		$set_section = $sectionClass->getSectionToGetSection($sectionGet);
		$set_category = $categoryClass->getCategoryToGetCategory($categoryGet);
			
		$section = $set_section['name'];
		$category = $set_category['name'];	

		if($_POST['add-theme']) {
			$themeClass->add($_POST['title'], $_POST['text'], $sectionGet, $categoryGet, $user_id);
			$new_id = $themeClass->getIdToNewTheme($_POST['title'], $_POST['text']);
			if($new_id != '' and isset($new_id)) header("Location: theme.php?id=". $new_id);
		}

	} catch (Exception $e) {
		
		if($e->getCode() == 1) 
			$message = $e->getMessage();
		else 
			exit($e->getMessage());
	}
?>