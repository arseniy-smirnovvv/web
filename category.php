<?php require_once 'vendor/controller/category_controller.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Название категории</title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="style/category.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="style/font-awesome.min.css">
	<!-- Подключение push-уведомлений -->
	<link rel="stylesheet" type="text/css" href="style/push.css">
</head>
<body>

	<?php require_once 'header.php'; ?>
	
	<?php if($errorNotFound){ ?>
	<section class="error-section">
		<p>Запрашиваемая категория не найдена!</p>
	</section>
	<?php } else { ?>
	<section class="container-themes">
		<div class="title-panel">
			<h3><?php echo $category['name']; ?></h3>
			<a href="<?php echo "add.php?section=".$category['get-section'].'&category=' . $category['get-category']; ?>"><button>Создать тему</button></a>
		</div>
		<div class="container-theme">
			<div class="container-title">
				<div class="title">
					<p>Последнии актуальные темы данной категории</p>
				</div>
				<div class="info-bar">
					<div>
						<p>Автор</p>
					</div>
					<div>
						<p>Дата публикации</p>
					</div>
					<div>
						<p>Кол-во ответов</p>
					</div>
					<div>
						<p>Просмотры</p>
					</div>
				</div>
			</div>

			<?php if(!$themeList){ ?>
				<div class="error-themes">
					<p>Тем в данной категории не найдено, но вы можете создать её!</p>
				</div>
			<?php } ?>	
			<?php for ($i=0; $i < count($themeList); $i++) { ?>
			<div class="themes">
				<div class="title">
					<p><a href="theme.php?id=<?php echo $themeList[$i]['id'] ?>"><?php echo $themeList[$i]['title']; ?></a></p>
				</div>
				<div class="info-bar">
					<div>
						<?php $user = $userClass->getUserToId($themeList[$i]['user-id']);?>
						<p><a href="user.php?id=<?php echo $user['id']; ?>"><?php echo $user['login']; ?></a></p>
					</div>
					<div>
						<p><?php echo date("d.m H:i", $themeList[$i]['date']);?></p>
					</div>
					<div>
						<p><?php echo $questClass->countQuestToThemeId($themeList[$i]['id']); ?></p>
					</div>
					<div>
						<p><?php echo $themeList[$i]['views']; ?></p>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>

	</section>
	<?php } ?>

	<?php require_once 'vendor/push.php'; ?>
</body>
</html>