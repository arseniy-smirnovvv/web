<?php  
	require_once 'config/db.php';
	require_once 'route.php';
	require_once 'class/TimeController_class.php';

 	function __autoload($classname)
	{
		require_once "class/{$classname}_class.php";
	}

	$route = new Router;

	$time = new TimeController;

	session_start();

	if(isset($_GET['exit']) and $_GET['exit'] == 'account') {
		unset($_SESSION['cdk']);
			if($_SESSION['admin']) 
				unset($_SESSION['admin']);
	}

?>