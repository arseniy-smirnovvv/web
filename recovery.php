<?php require_once 'vendor/controller/recovery_controller.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Восстановление пароля</title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="style/recovery.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="style/font-awesome.min.css">
	<!-- Подключение push-увдомлений -->
	<link rel="stylesheet" type="text/css" href="style/push.css">
</head>
<body>

	<?php require_once 'header.php'; ?>

	<section class="container">
		<h3>Восстановление пароля</h3>
		<div class="recovery-panel">
			<p>Если вы забыли пароль, то введите в поле ниже e-mail, который привязан к аккаунта. После чего на этот пароль придет код подтверждения.</p>
			<form method="POST">
				<?php if($recCode){ ?>
					<div>
						<input type="text" name="code" placeholder="Код, который пришел на почту">
					</div>
				<?php } else {?>
					<div>
						<input type="email" name="email" placeholder="Ваш почтовый ящик" minlength="1" maxlength="255">
					</div>
				<?php } ?>
				<div>
					<input type="submit" name="submit-recovery" value="Изменить">
				</div>
			</form>
		</div>
	</section>

	<?php require_once 'vendor/push.php'; ?>

</body>
</html>