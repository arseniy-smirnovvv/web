<?php 
	require_once '../vendor/autoload.php';

	$user_id = $_GET['id'];
	$id = $_SESSION['id'];
	$route->defSqlUser($user_id);		

	$userClass = new User($mysql, TABLE_USER, TABLE_CDK, TABLE_LOG_BAN);
	$userClass->loginUser();
	$userClass->issetAdminToId($id);
	$userClass->loginSuperUser();

	$themeClass = new Theme($mysql, TABLE_THEMES);
	$questClass = new Quest($mysql, TABLE_QUEST);

	$notFound = false;

	$page = Page::PageList('Главная форума', '../index.php', 'Главная панели', 'index.php', 'Личный Кабинет', 'user.php?id='.$id);

	try {
		$cdk_info = $userClass->getCDKInfoToId($user_id);
		$ban_info = $userClass->getInfoBanToId($user_id);

		try {
			$userClass->issetUserToId($user_id);		
		} catch (Exception $e) {
			throw new Exception($e->getMessage(), 3);
		}	

		$user = $userClass->getUserToId($user_id);	

		if($_POST['ban'])
		{ 
			$userClass->ban($user_id, $id);
			$message = "Аккаунт успешно забанен!";
			$user = $userClass->getUserToId($user_id);
		}	

		if ($_POST['unban']) {
			$userClass->unban($user_id, $id);
			$message = "Аккаунт успешно разбанен!";
			$user = $userClass->getUserToId($user_id);
		}

		if(isset($_POST['moder'])){
			$userClass->makeModer($user_id, $id);
			$message = "Пользователь теперь модератор!";
			$user = $userClass->getUserToId($user_id);
		}

		if(isset($_POST['unmoder'])){
			$userClass->removeModer($user_id, $id);
			$message = "Пользователь успешно разжалован!";
			$user = $userClass->getUserToId($user_id);
		}

		if(isset($_POST['del-theme'])){
			$delUser =  $userClass->getUserToId($user_id);
			if($delUser['admin'] != 0) throw new Exception("Вы не можете удалить темы администратора!", 1);
			$themes_id = $themeClass->delThemeToUserId($user_id);
			$questClass->delQuestToThemeId($themes_id);
			$message = 'Все темы, и ответы успешно удалены!';
		}

		if(isset($_POST['del-account'])){
			if($user_id == $id) throw new Exception("Вы не можете удалить самого себя!", 1);
			try { $delUser = $userClass->getUserToId($user_id);	} catch (Exception $e) { throw new Exception($e->getMessage(), 3);}
			if($delUser['admin'] != 0) throw new Exception("Аккаунт администратора нельзя удалить!", 1);
			try {$themes_id = $themeClass->delThemeToUserId($user_id);} catch (Exception $e) {}
			$questClass->delQuestToThemeId($themes_id);
			$userClass->del($user_id);
			$message = "Аккаунт, и все что с ним связанно удачно удаленно!";
			try { $user = $userClass->getUserToId($user_id); } catch (Exception $e) { throw new Exception($e->getMessage(), 3);}
		}

	} catch (Exception $e) {
	 	
		if($e->getCode() == '1') {
			$message = $e->getMessage();
		} elseif ($e->getCode() == '3') {
			$notFound = $e->getMessage();
		} else {
			exit('Критическая ошибка: '. $e->getMessage());
		}

	}
?>