<?php  
	
	require_once './vendor/autoload.php';
		
	$userClass = new User($mysql, TABLE_USER, TABLE_CDK);
	$userClass->loginUser();


	$sectionClass = new Section($mysql, TABLE_SECTION);
	$categoryClass = new Category($mysql, TABLE_CATEGORY);
	$themeClass = new Theme($mysql, TABLE_THEMES);

	$page = Page::PageList('Главная', 'index.php', 'Личный Кабинет', 'user.php?id=' . $_SESSION['id']);

	try {
		
		$sectionList = $sectionClass->ShowAll();
		$categoryList = $categoryClass->ShowAll(); 

	} catch (Exception $e) {
		if($e->getCode() == '1') {
			$message = $e->getMessage();	
		} else {
			exit($e->getMessage());
		}
	}
?>