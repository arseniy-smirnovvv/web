<?php require_once 'vendor/controller/theme_controller.php';?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title><?php echo $theme['title']; ?></title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="style/theme.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="style/font-awesome.min.css">
	<!-- ПОдключение стилей для push-уведомлений -->
	<link rel="stylesheet" type="text/css" href="style/push.css">
</head>
<body>
	<?php require 'header.php'; ?>
	<div class="container">
		<?php if(!$notFound){ ?>
		<?php $userCreate = $userClass->getUserToId($theme['user-id']); ?>
		<?php if($userModer || $createUser) {?>
		<div class="control">
			<a href="edit.php?id=<?php echo $theme['id'];?>"><button>Редактировать</button></a>
		</div>
		<?php } ?>
		<div class="theme">
			<div class="info-bar">  
				<div class="photo">
					<img src="img/profil-photo/<?php $img = $userCreate['img']; if($img == '') echo 'no-user-image.png'; else echo $img; ?>" alt="Фото юзера">
				</div>
				<div class="text">
					<a href="user.php?id=<?php echo $userCreate['id']; ?>"><p <?php if($userCreate['moder'] != 0 || $userCreate['admin'] != 0) echo 'class="moder"'; ?>><?php echo $userCreate['login']; ?></p></a>
					<p><?php echo $time->countDays($userCreate['date']); ?> с нами</p>
				</div>
			</div>
			<div class="info-text">
				<h3><?php echo $theme['title']; ?></h3>
				<p class="text"><?php echo $theme['text']; ?></p>
				<div class="signature">
					<p><?php echo $userCreate['status']; ?></p>
				</div>
				<div class="date">
					<p><?php echo "Тема была создана " . date("d.m.y", $theme['date']) . ' в ' . date("H:i", $theme['date']);  ; ?></p>
					<?php if($theme['last-edit'] || $theme['last-edit'] != 0)  {?>
					<p>Последнее редактирование было <?php echo date('d.m.y', $theme['last-edit']) . date(' в H:i', $theme['last-edit']); ?></p>
					<?php } ?>
				</div>
			</div>
		</div>

		<?php for ($i=0; $i < count($comment); $i++) { ?>
			<?php $user = $userClass->getUserToId($comment[$i]['user-create']) ?>
		<div class="comment">
			<div class="info-bar">  
				<div class="photo">
					<img src="img/profil-photo/<?php $img = $user['img']; if($img == '') echo "no-user-image"; else echo $img; ?>" alt="Фото юзера">
				</div>
				<div class="text">
					<a href="user.php?id=<?php echo $user['id']; ?>"><p><?php echo $user['login']; ?></p></a>
					<p><?php echo $time->countDays($user['date']); ?> с нами</p>
				</div>
			</div>
			<div class="info-text">
				<p class="text"><?php echo $comment[$i]['text']; ?></p>
				<div class="signature">
					<p><?php echo $user['status']; ?></p>
				</div>
				<div class="date">
					<p>Коментарий был создан <?php echo date('d.m.y в H:i', $comment[$i]['date']); ?></p>
				</div>
			</div>
		</div>
		<?php } ?>

		<div class="add-comment">
			<h3>Что думаете по поводу этого?</h3>
			<form name="form-add-coment" method="POST">
				<textarea placeholder="Введите ваш коментарий..." name="text" minlength="1" maxlength="10000"></textarea>
				<input type="submit" name="add-comment" value="Добавить">
			</form>
		</div>
		<?php } else { ?>

		<div class="error-block">
			<p><?php echo $notFound; ?></p>
		</div>
		<?php  }?>
	</div>

	<?php require_once 'vendor/push.php'; ?>
</body>
</html>