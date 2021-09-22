<?php  
	require_once './vendor/autoload.php';


	$page = Page::PageList('Вход', 'login.php', 'Регистрация', 'register.php');
	
	if (isset($_SESSION['cdk'])) header("Location: index.php");

	$recCode = $_SESSION['code'];
	$userClass = new User($mysql, TABLE_USER, TABLE_CDK, false, TABLE_LOG_RECOVERY);
	try {
		
		if(isset($_POST['submit-recovery'])){ 
			if($recCode){
				$userClass->recoveryPassword($_POST['code']);
				$message = 'Пароль успешно изменен! Новый пароль пришел к вам на почту!';
				unset($_SESSION['code']);
			} else {
				$userClass->recoveryCode($_POST['email']);
				$message = 'Письмо с кодом успешно отправленно!';
			}
		}

		$recCode = $_SESSION['code'];

	} catch (Exception $e) {
		if($e->getCode() == '1')
			$message = $e->getMessage();
		else 
			exit($e->getMessage());
	}
?>