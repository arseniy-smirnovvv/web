<?php require_once 'vendor/controller/login_controller.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Вход</title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="style/login.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="style/font-awesome.min.css">
	<!-- Подключение стилей push-увдомлений -->
	<link rel="stylesheet" type="text/css" href="../style/push.css">
</head>
<body>
	<section class="container">
		<h3>Вход</h3>
		<div class="forms">
			<form method="POST" >
				<div>
					<input type="login" name="login" placeholder="Ваш логин">
				</div>
				<div>
					<input type="password" name="password" placeholder="Ваш пароль" >
				</div>
				<div>
					<input class="last" name="login-submit" type="submit">
				</div>
				<div class="info">
					<p>Еще не <a href="register.php">зарегистрированны</a>?</p>
					<p>Забыли <a href="recovery.php">пароль?</a></p>
				</div>
			</form>
		</div>
	</section>

	<?php if($message){ ?>
		<div id="error-controller">
			<p id="error-text"><?php echo $message; ?></p>
		</div>
	<?php } ?>

	<div id='error_box'>
		<div class="error_container">		
			<p id='error_message' ></p>
		</div>
	</div>

	<script type="text/javascript" src="../js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="../js/push.js"></script>
</body>
</html>