<?php  
	
	require_once './vendor/autoload.php';

	$theme_id = $_GET['id'];
	$user_id = $_SESSION['id'];

	$route->defSqlEdit($theme_id);

	$userClass = new User($mysql, TABLE_USER, TABLE_CDK);
	$userClass->loginUser();

	$sectionClass = new Section($mysql, TABLE_SECTION);
	$categoryClass = new Category($mysql, TABLE_CATEGORY);
	$themeClass = new Theme($mysql, TABLE_THEMES);

	$page = Page::PageList('Главная', 'index.php', 'Назад к теме', 'theme.php?id='.$theme_id, 'Личный Кабинет', 'user.php?id='.$user_id);

	try {
		$themeClass->issetThemeToUserId($user_id, $theme_id);

		if($_POST['edit-theme']) {
			$themeClass->edit($user_id, $_POST['title'], $_POST['text']);
			header('Location: theme.php?id='.$theme_id);
		}

		$editTheme = $themeClass->showThemeToId($theme_id);
	} catch (Exception $e) {
		
		if($e->getCode() == 1) 
			$message = $e->getMessage();
		elseif($e->getCode() == 4)
			header("Location: theme.php?id=" . $theme_id);
		else 
			exit($e->getMessage());
	}
	
?>