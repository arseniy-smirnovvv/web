<?php  require_once './vendor/autoload.php';

	$route->defSqlCategory($_GET['category']);

	$userClass = new User($mysql, TABLE_USER, TABLE_CDK);
	$userClass->loginUser();

	$categoryClass = new Category($mysql, TABLE_CATEGORY);
	$themeClass = new Theme($mysql, TABLE_THEMES);
	$questClass = new Quest($mysql, TABLE_QUEST);

	$errorNotFound = false;

	$page = Page::PageList('Главная', 'index.php', 'Личный Кабинет', 'user.php?id=' . $_SESSION['id']);

	try {
		
		$category = $categoryClass->getCategoryToGetCategory($_GET['category']);
		$themeList = $themeClass->showToGetCategory($_GET['category']);
	
	} catch (Exception $e) {

		if($e->getCode() == '1') {
			$message = $e->getMessage();	
		} elseif ($e->getCode() == '3') {
			$errorNotFound = true;
		} else {
			exit($e->getMessage());
		}
	}
?>