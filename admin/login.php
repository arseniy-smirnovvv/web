<?php require_once '../vendor/controller/admin/login_controller.php';?>
<!DOCTYPE html>
<html>
<head lang="ru">
	<meta charset="utf-8">
	<title>Вход в админ-панель.</title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="../style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="../style/admin/login.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="../style/font-awesome.min.css">
	<!-- Подключение всплывающий уведомлений -->
	<link rel="stylesheet" type="text/css" href="../style/push.css">
</head>
<body>
	<section class="container">
		<h3>Введите ваш админ-пароль</h3>
		<div class="forms">
			<form method="POST">
				<div>
					<input type="password" name="admin-password" placeholder="Введите сюда ваш админ-пароль">
				</div>
				<div>
					<input type="submit" name="auth-admin" value="Войти">
				</div>
				<div>
					<p class="restart">Если забыли пароль, обратитесь к главному админу в вк.</p>
					<p class="restart">Обратно на <a href="../index.php">форум</a></p>
				</div>
			</form>
		</div>
	</section>

	<?php require_once '../vendor/push.php'; ?>
</body>
</html>