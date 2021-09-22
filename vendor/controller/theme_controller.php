<?php  
	
	require_once './vendor/autoload.php';

	$route->defSqlTheme($_GET['id']);

	$userClass = new User($mysql, TABLE_USER, TABLE_CDK);
	$userClass->loginUser();

	$theme_id = $_GET['id'];
	$id = $_SESSION['id'];

	$notFound = false;
	$last_edit = false; 

	$sectionClass = new Section($mysql, TABLE_SECTION);
	$categoryClass = new Category($mysql, TABLE_CATEGORY);
	$themeClass = new Theme($mysql, TABLE_THEMES, TABLE_LOG_VISIT);
	$questClass = new Quest($mysql, TABLE_QUEST);

	$page = Page::PageList('Главная', 'index.php', 'Личный Кабинет', 'user.php?id='.$id);

	try {
		
		try {
			$themeClass->issetThemeToId($theme_id); 	
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 3);
		}
		
		$theme = $themeClass->showThemeToId($theme_id);
		$themeClass->viewsController($id, $theme_id);

		$userModer = $userClass->issetModerToId($_SESSION['id']);
		$createUser = false;
		if($theme['user-id'] == $_SESSION['id']) $createUser = true;

		if(isset($_POST['add-comment'])) {
			$questClass->add($_POST['text'], $theme_id, $_SESSION['id']);
			$message = "Ваш коменатарий успешно добавлен!";
		}

		$comment = $questClass->showToThemeId($theme_id);

	} catch (Exception $e) {

		if($e->getCode() == '1') {
			$message = $e->getMessage();
		} elseif($e->getCode() == '3') {
			$notFound = $e->getMessage();
		} else {
			exit($e->getMessage());
		}
	}
?>