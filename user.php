<?php require_once 'vendor/controller/user_controller.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Пользователь <?php echo $user['login']; ?></title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="style/user.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="style/font-awesome.min.css">
	<!-- Подключение стилей push-увдомлений -->
	<link rel="stylesheet" type="text/css" href="style/push.css">
</head>
<body>

	<?php require_once 'header.php'; ?>

	<div class="container">

		<?php if($message['global']){ ?>
		<div class="error-container">
			<p><?php echo $message; ?></p>
		</div>
		<?php } else { ?>

		<div class="info-bar">
			<div class="picture">
				<img src="img/profil-photo/<?php $img = $user['img']; if($img == '' || $img == 0) echo "no-user-image.png"; else echo $img; ?>" alt="Фото юзера">
			</div>
			<div class="info">
				<p>Логин: <?php echo $user['login']; ?></p>
				<p>Подпись: <?php $status = $user['status']; if($status == '') echo "Отсутствует"; else echo $status; ?></p>
				<p>Последняя созданная тема: отсутствует</p>
				<p>На форуму <?php echo $time->countDays($user['date']); ?></p>
				<?php if($_SESSION['id'] == $user['id']) { ?>
					<?php if($user['admin'] != 0){ ?>
						<a href="admin/login.php"><button>Войти в админ панель</button></a>
					<?php } ?>
				<?php } ?>
			</div>
		</div>

		<?php if($user_id == $user['id']){ ?>

		<div class="panel">
			<div id="setting">
				<span>Настройки</span>
			</div>
			<div class="active" id="theme">
				<span>Мои темы</span>
			</div>
		</div>

		<div class="setting" style="display: none;" id="setting-block">
			<h3>Настройки</h3>
			<form method="POST" enctype="multipart/form-data">
				<div>
					<input type="name" name="login" placeholder="Ваше имя" minlength="1" maxlength="255" value="<?php echo $user['login'] ?>">
				</div>
				<div>
					<input type="email" name="email" placeholder="Ваш почтовый ящик" minlength="1" maxlength="255" value="<?php echo $user['email']; ?>">
				</div>
				<div>
					<input type="text" name="status" placeholder="Ваша подпись" minlength="1" maxlength="255" value="<?php echo $user['status']; ?>">
				</div>
				<div>
					<input type="password" name="new-password" placeholder="Ваш новый пароль">
				</div>
				<div>
					<input type="password" name="sur-new-password" placeholder="Подтвердите ваш новый пароль">
				</div>
				<div>
					<label for="picture" class="picture-block">
						<input type="file" name="photo" id="picture">
						<span>Изменть аватарку</span>
					</label>
				</div>
				<div>
					<input type="submit" name="edit" value="Изменить">
				</div>
			</form>
		</div>

		<div class="my-theme" id="theme-block">
			<h3>Созданные темы</h3>

			<div class="title-info">
				<p>Дата публикации</p>
				<p>Кол-во просмотров</p>
				<p>Кол-во ответов</p>
			</div>

			<?php for ($i=0; $i < count($myTheme); $i++) { ?>
			<div class="theme">
				<div class="title">
					<p><a href="theme.php?id=<?php echo $myTheme[$i]['id']; ?>"><?php echo $myTheme[$i]['title']; ?></a></p>
				</div>
				<div class="info">
					<div class="date">
						<p><?php echo date('d.m.y в H:i', $myTheme[$i]['date']); ?></p>
					</div>
					<div class="views">
						<p><?php echo $myTheme[$i]['views'];  ?></p>
					</div>
					<div class="answers">
						<p><?php echo $questClass->countQuestToThemeId($myTheme[$i]['id']); ?></p>
					</div>
				</div>
			</div>
			<?php } ?>
			
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	
	<?php if($message['edit']){ ?>
		<div id="error-controller">
			<p id="error-text"><?php echo $message['edit']; ?></p>
		</div>
	<?php } ?>

	<div id='error_box'>
		<div class="error_container">		
			<p id='error_message' ></p>
		</div>
	</div>


	<!-- Подключаем javaScript -->
	<script type="text/javascript" src="js/jquery-3.3.1.js"></script>
	<!-- Подключение скрипта -->
	<script type="text/javascript" src="js/user-script.js"></script>
	<!-- Подключение скрипта, для push уведомлений -->
	<script type="text/javascript" src="../js/push.js"></script>
</body>
</html>