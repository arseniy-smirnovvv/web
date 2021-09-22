<?php  
	require_once './vendor/autoload.php';

	if(isset($_SESSION['cdk'])) header("Location: index.php");

	$userClass = new User($mysql, TABLE_USER, TABLE_CDK);

	$message = false;

	try {

		if (isset($_POST['register-new-user'])) {
			$userClass->register($_POST['login'], $_POST['email'], $_POST['password'], $_POST['sur-password']);
			$message = 'Вы успешно зарегистрировались!';
			header("Location: login.php");
		}
	} catch (Exception $e) {

		if($e->getCode() == '1') {
			$message = $e->getMessage();	
		} else {
			exit($e->getMessage());
		}
	}
?>