<?php require_once 'vendor/controller/register_controller.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Регистрация</title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<!-- Подключение стилей, для этой страница -->
	<link rel="stylesheet" type="text/css" href="style/login.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="style/font-awesome.min.css">
	<!-- Подключение стилей push-увдомлений -->
	<link rel="stylesheet" type="text/css" href="../style/push.css">
</head>
<body>
	<div class="container">
		<h3>Регистрация</h3>
		<div class="forms">
			<form method="post">
				<div>
					<input type="login" name="login" placeholder="Ваш логин" value="<?php echo $userClass->getSaveData("login"); ?>">
				</div>
				<div>
					<input type="email" name="email" autocomplete="no" placeholder="Ваш почтовый ящик" value="<?php echo $userClass->getSaveData("email"); ?>">
				</div>
				<div>
					<input type="password" name="password" placeholder="Ваш пароль" >
				</div>
				<div>
					<input type="password" name="sur-password" placeholder="Подтвердите ваш пароль">
				</div>
				<div>
					<input class="last" type="submit" name="register-new-user" value="Зарегистрироваться">
				</div>
				<div class="info">
					<p>Уже есть <a href="login.php">аккаунт</a>?</p>
				</div>
			</form>
		</div>
	</div>

	<?php require 'vendor/push.php'; ?>

	<?php $userClass->delSaveData(); ?>

	<script type="text/javascript" src="../js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="../js/push.js"></script>

</body>
</html>