<?php  

	require_once './vendor/autoload.php';

	if(isset($_SESSION['cdk'])) header("Location: index.php");
 
	$userClass = new User($mysql, TABLE_USER, TABLE_CDK);

	$message = false;

	try {
		
		if (isset($_POST['login-submit'])) {
			$userClass->login($_POST['login'], $_POST['password']);
			header("Location: index.php");		
		}
	} catch (Exception $e) {
		if($e->getCode() == '1') {
			$message = $e->getMessage();	
		} else {
			exit($e->getMessage());
		}
	}
	
?>