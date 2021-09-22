<?php  
	require_once '../vendor/autoload.php';

	$id = $_SESSION['id'];

	$userClass = new User($mysql, TABLE_USER, TABLE_CDK);	
	$userClass->loginUser();
	$userClass->issetAdminToId($_SESSION['id']);

	if($_SESSION['admin']) header("Location: index.php"); 

	try {
		
		if ($_POST['auth-admin']) {
			$userClass->loginAdmin($id, $_POST["admin-password"]);
			$message = 'Правильный админ пароль!';
			header("Location: index.php");
		}

	} catch (Exception $e) {
		
		if ($e->getCode() == 1) 
			$message = $e->getMessage();
		else 
			exit($e->getMessage());
	}

?>