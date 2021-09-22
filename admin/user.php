<?php require_once '../vendor/controller/admin/user_controller.php'; ?>
<!DOCTYPE html>
<html>
<head lang="ru">
	<meta charset="utf-8">
	<title>Пользователь </title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="../style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="../style/admin/user.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="../style/font-awesome.min.css">
	<!-- Подключение push уведомлений -->
	<link rel="stylesheet" type="text/css" href="../style/push.css">
</head>
</head>
<body>

	<?php require_once '../header.php'; ?>

	<?php if(!$notFound){ ?>
	<section class="container">
		<div class="info-bar">
			<div class="pictures">
				<img src="../img/profil-photo/<?php $img = $user['img']; if($img == 0 || $img == '') echo 'no-user-image.png'; else echo $img; ?>" alt="Фотка юзера">
			</div>
			<div class="info">
				<p>Имя пользователя <span><?php echo $user['login']; ?></span></p>
				<p>Подпись <span><?php echo $user['status']; ?></span></p>
				<p>Почта пользователя <span><?php echo $user['email']; ?></span></p>
				<p>Дата регистрации пользователя <span><?php echo date('d.m.y в H:i:s', $user['date']); ?></span></p>
				<p>Количество созданных тем пользователя <span><?php echo $themeClass->countThemeToUserId($user['id']); ?></span></p>
				<p>Количество ответов пользователя <span><?php echo $questClass->countQuestToUserId($user['id']); ?></span></p>
				<p>Ip-адрес при регистрации: <span><?php echo $user['ip_reg']; ?></span></p>
				<?php if($user['banned']){ ?>
					<p class="ban-text">Забанен</p>
					<?php $info_ban = $userClass->getInfoBan($user['id']); ?>
					<p>Время бана <span><?php echo date('d.m.y', $info_ban['date']) ?></span> в <span><?php echo date('H:i', $info_ban['date']); ?></span></p>
					<p>Забанил <span><?php echo $userClass->getLoginToId($info_ban['user-id']); ?></span></p>
				<?php } ?>
				<?php if($user['moder']){ ?>
					<p class="moder-text">Модератор</p>
				<?php } ?>
				<?php if ($user['admin']) {?>
					<p class="admin-text">Администратор</p>
				<?php } ?>
			</div>
		</div>
		<div class="panel-bar">
			<form method="POST">
				<?php if($user['banned']) {?>
					<input type="submit" name="unban" value="Разбанить">
				<?php } else { ?>
					<input type="submit" name="ban" value="Забанить">
				<?php } ?>
				<?php if(!$user['moder']){ ?>
					<input type="submit" name="moder" value="Сделать Модератором">
				<?php } else { ?>
					<input type="submit" name="unmoder" value="Разжаловать Модератора">
				<?php } ?>
				<input type="submit" name="del-account" value="Удалить аккаунт">
				<input type="submit" name="del-theme" value="Удалить все темы">
			</form>
		</div>
		<div class="info-auth">
			<h3>История входов. Последние 10 входов.</h3>
			<div class="panel-info">
				<?php $maxCDKInfo = count($cdk_info); for ($i=0; $i < $maxCDKInfo; $i++) { ?>
					<div class="info">
						<p>Пользователь: <?php echo $user['login']; ?></p>
						<p>Дата входа: <span><?php echo date('d.m.y в H:i:s', $cdk_info[$i]['date_login']);?></span></p>
						<p>Ip-Входа: <span><?php echo $cdk_info[$i]['ip']; ?></span></p>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php if($ban_info) {?>
		<div class="info-ban">
			<h3>История Банов</h3>
			<div class="panel-info">
				<?php $maxBanInfo = count($ban_info); for ($i=0; $i < $maxBanInfo; $i++) { ?>
					<div class="info">
						<p>Пользователь: <?php echo $user['login']; ?></p>
						<p>Забанил: <span><?php echo $userClass->getLoginToId($ban_info[$i]['user-id-banned']);?></span></p>
						<p>Дата бана: <span><?php echo date('d.m.y H:i:s', $ban_info[$i]['date']); ?></span></p>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</section>
	<?php } else { ?>
		<div class="error-section">
			<p><?php echo $notFound ?></p>
		</div>
	<?php } ?>

	<?php require_once '../vendor/push.php'; ?>

</body>
</html>