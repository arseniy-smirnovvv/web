<?php  
	require_once '../vendor/autoload.php';

	$route->defSqlAdminAdd($_GET['mode'], $_GET['type'], $_GET['id']);//Проверяет, нет ли лишних GET зарпросов
	$title = Title::constructAdminAdd($_GET['mode'], $_GET['type']);//Получает название страниц
	$mode = Title::getModeAdminADd();//Получает секцию, с которой надо взаимодействовать

	$categoryClass = new Category($mysql, TABLE_CATEGORY);
	$sectionClass = new Section($mysql, TABLE_SECTION, $categoryClass);
	$themeClass = new Theme($mysql, TABLE_THEMES);

	$sectionList = $sectionClass->ShowAll();

	$redirectToIndex = 'Location: index.php';

	$page = Page::PageList('Главная на форуме', '../index.php', 'Назад', 'index.php');

	try {

		if(substr($mode, 0, 4) == 'edit'){
			if(substr($mode, 4) == 'section') $sectionToId =  $sectionClass->ShowToId($_GET['id']);
			if(substr($mode, 4) == 'category') $categoryToId = $categoryClass->ShowToId($_GET['id']);
		}

		if (isset($_POST['add-section'])) {
			$sectionClass->add($_POST['add-name-section'],$_POST['add-tag-section'], $_POST['add-des-section'], $adminName, $_POST['add-check-user-section']);
			$message = 'Раздел успешно создан!';
			header($redirectToIndex);
		}

		if (isset($_POST['add-category'])) {
			$categoryClass->add($_POST['add-name-category'], $_POST['add-des-category'], $_POST['add-tag-category'], $_POST['add-list-category'], $adminName, $sectionList);
			$message = 'Категория успешно создана!';
			header($redirectToIndex);
		}

		if (isset($_POST['edit-section'])){
			$sectionClass->edit($sectionToId['id'], $_POST['edit-name-section'], $_POST['edit-desc-section'], $_POST['edit-tag-section'], $_POST['add-check-user-section'], $sectionToId['get-section']);
			$categoryClass->editGetSection($_POST['edit-tag-section'], $sectionToId['get-section']);
			$themeClass->editGetSection($_POST['edit-tag-section'], $sectionToId['get-section']);
			$sectionToId = $sectionClass->ShowToId($_GET['id']);
			$message = 'Секция успешно измененна!';
			header($redirectToIndex);
		}

		if (isset($_POST['edit-category'])) {
			$categoryClass->edit($categoryToId['id'], $_POST['edit-name-category'], $_POST['edit-desc-category'], $_POST['edit-tag-category'], $_POST['edit-get-section-category'], $sectionList);
			$themeClass->editGetSection($_POST['edit-get-section-category'], $categoryToId['get-section']);
			$themeClass->editGetCategory($_POST['edit-tag-category'], $categoryToId['get-category']);
			$categoryToId = $categoryClass->ShowToId($_GET['id']);
			$message = 'Категория успешно измененна!';
			header($redirectToIndex);
		}
	
	} catch (Exception $e) {
		if($e->getCode() == '1') {
			$message = $e->getMessage();	
		} else {
			exit($e->getMessage());
		}
	}	

?>