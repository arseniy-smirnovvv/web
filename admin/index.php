<?php require_once '../vendor/controller/admin/index_controller.php';?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Админ-Панель</title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="../style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="../style/admin.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="../style/font-awesome.min.css">
	<!-- Подключение стилей push-увдомлений -->
	<link rel="stylesheet" type="text/css" href="../style/push.css">
	<!-- Подключение всплаывающих подсказок -->
	<link rel="stylesheet" type="text/css" href="../style/tootlip.css">
</head>
<body>

	<?php require_once '../header.php'; ?>

	<section class="container">
		<div class="title">
			<h3>Админ-Панель</h3>
			<a href="?account=exit"><button id="exit">Выйти</button></a>
		</div>
		<div class="info">
			 <h3>Общая информация</h3>
			 <p>Всего зарегистрированно пользователей: <span><?php echo $countUser; ?></span></p>
			 <p>Всего модераторов: <span><?php echo $countModer; ?></span></p>
			 <p>Всего забаненных пользователей: <span><?php echo $countUserBan; ?></span></p>
			 <p>Всего тем: <span><?php echo $countTheme; ?></span></p>
			 <p>Самый просматриваемый раздел: <?php echo $hotSection['name']; ?></p>
			 <p>Самая просматриваемая тема: <a href="../theme.php?id=<?php echo $hotTheme['id']; ?>"><?php echo $hotTheme['title']; ?></a></p>
		</div>

		<div class="section-panel">	
			<h3>Управление разделами и её категориями</h3>
			<?php for ($i=0; $i < count($sectionList); $i++) { ?>
			<div class="section-block">
				<div class="section">
					<h3><?php echo $sectionList[$i]['name'] ?></h3>
					<div class="panel">
						<a target="_blank" href="<?php echo "add.php?mode=edit&type=section&id=".$sectionList[$i]['id'] ?>"><i class="fa fa-cogs"></i></a>
						<a href="?del=section&id=<?php echo $sectionList[$i]['id']; ?>"<?php echo $sectionList[$i]['id']; ?>><i class="fa fa-trash"></i></a>
					</div>
				</div>
				<?php for ($j=0; $j < count($categoryList); $j++) { ?>
					<?php if($sectionList[$i]['get-section'] == $categoryList[$j]['get-section']){ ?>
						<div class="category">
							<p><?php echo $categoryList[$j]['name']; ?></p>
							<div class="panel">
								<span class="count" data-tooltip="Кол-во тем"><?php echo $themeClass->countThemeToGetCategory($categoryList[$j]['get-category']); ?>
									<span id="tooltip"></span>
								</span>
								<a target="_blank" href="<?php echo "add.php?mode=edit&type=category&id=".$categoryList[$j]['id'] ?>"><i class="fa fa-cogs"></i></a>
								<a href="?del=category&id=<?php echo $categoryList[$j]['id']; ?>"><i class="fa fa-trash"></i></a>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
				<div class="add-category">
					<a target="_blank" href="add.php?mode=add&type=category"><p>Добавть Категорию <i class="fa fa-plus"></i></p></a>
				</div>
			</div>
			<?php } ?>
			<div class="add-section">
				<a target="_blank" href="add.php?mode=add&type=section"><p>Добавить раздел <i class="fa fa-plus"></i></p></a>
			</div>	
		</div>

		<div class="baned-user-block">
			<h3>Забаненные пользователи</h3>
			<div class="search-block">
				<div class="title-panel">
					<p>Поиск забаненных пользователей</p>
					<div>
						<form method="POST"> 
							<input type="search" name="search-user-ban" placeholder="Введите логин">
							<label class="search" for="search-user-ban">
								<span><i class="fa fa-search"></i></span>
								<input type="submit" name="submit-search-user-ban" id="search-user-ban">
							</label>
						</form>
					</div>
				</div>
				<div class="table-user">
					<?php if(!$banUser){ ?>
						<div class="ban-user-info">
							<p>Нету забаненных пользователей</p>
						</div>
					<?php } else { ?>
					<?php $maxBanUser = count($banUser); for ($i=0; $i < $maxBanUser; $i++) { ?>
					<?php $log_info = $userClass->getInfoBan($banUser[$i]['id'])?>
					<div class="user"><div class="info-name"><p><a href="user.php?id=<?php echo $banUser[$i]['id']; ?>"><?php echo $banUser[$i]['login']; ?></a></p></div><div class="ban-info"><p>Выдал бан: <?php echo $userClass->getLoginToId($log_info['user-id']); ?></p><p>Дата: <?php echo date('d.m.y', $log_info['date']); ?></p></div><div class="panel"></div></div>
					<?php }} ?>
				</div>
			</div>
		</div>

		<div class="user-list">
			<h3>Поиск пользователя</h3>
			<div class="search-block">
				<div class="title-panel">
					<p>Поиск пользователей</p>
					<div>
						<form method="POST">
							<input type="search" name="search-user" placeholder="Введите логин">
							<label class="search" for="search-user">
								<span><i class="fa fa-search"></i></span>
								<input type="submit" name="submit-search-user" id="search-user">
							</label>
						</form>
					</div>
				</div>
				<?php if(!$user) {?>
					<div class="ban-user-info">
						<p>Пользователей не найдено!</p>
					</div>
				<?php } else { ?>
					<div class="table-user">
						<?php $maxUser = count($user); ?>
						<?php for ($i=0; $i < $maxUser; $i++) {?>
						<div class="user"><div class="info-name"><p <?php 
						if($user[$i]['moder'] != 0 and $user[$i]['admin'] != 0) echo "class='admin-nick'";
						 elseif($user[$i]['moder'] != 0) echo "class='moder-nick'"; 
						 elseif($user[$i]['admin'] != 0) echo "class='moder-nick'"; 
						 ?>><a href="user.php?id=<?php echo $user[$i]['id']; ?>"><?php echo $user[$i]['login']; ?></a></p></div></div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<?php require_once '../vendor/push.php'; ?>

	<script type="text/javascript" src="../js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="../js/push.js"></script>
	<script type="text/javascript" src="../js/tootlip.js"></script>

</body>
</html>