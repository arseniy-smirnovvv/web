<?php  

	require_once './vendor/autoload.php';

	$userClass = new User($mysql, TABLE_USER, TABLE_CDK);
	if($userClass->loginUser()){
		$route->defSqlUser($_GET['id']);
	}

	if($_GET['account'] and $_GET['account'] == 'exit') $userClass->exitToAccount();

	$user_id = $_SESSION['id'];
	
	$themeClass = new Theme($mysql, TABLE_THEMES);
	$questClass = new Quest($mysql, TABLE_QUEST);

	$message['global'] = false;

	$message['edit'] = false;

	$page = Page::PageList('Главная', 'index.php', 'Выход', '?account=exit');

	try {
		$user_id = $_SESSION['id'];
	
		if(isset($_POST['edit'])){
			$userClass->edit($_SESSION['id'], $_POST['login'], $_POST['email'], $_POST['status'], $_POST['new-password'], $_POST['sur-new-password'], $_FILES['photo']);
			$message['edit'] = 'Ваши данные успешно обновленны';
		}

		$user = $userClass->getUserToId($_GET['id']);

		if ($user_id == $_GET['id']) {
			$myTheme = $themeClass->getThemeToUserId($user_id);
		}

	} catch (Exception $e) {
		
		if($e->getCode() == '1') {
			$message = true;	
		} elseif ($e->getCode() == '2') {
			$message['edit'] = $e->getMessage(); 
		} else {
			exit($e->getMessage());
		}
	
	}
?>