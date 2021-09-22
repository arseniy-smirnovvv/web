<?php require_once 'vendor/controller/edit_controller.php';?>
<!DOCTYPE html>
<html>
<head>
	<title>Редактирование темы</title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="style/add.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="style/font-awesome.min.css">
	<!-- Подключение push-уведомлений -->
	<link rel="stylesheet" type="text/css" href="style/push.css">
</head>
<body>

	<?php require_once 'header.php'; ?>

	<section class="container">
		<form method="POST">
			<h3>Редактирование темы</h3>
			<div>
				<p>Раздел: <?php echo $sectionClass->getNameToGetSection($editTheme['get-section']); ?></p>
			</div>		
			<div>
				<p>Категория: <?php echo $categoryClass->getNameToGetCategory($editTheme['get-category']); ?></p>
			</div>
			<div>
				<input type="text" minlength="1" maxlength="255" name="title" placeholder="Название темы" value="<?php echo $editTheme['title'] ?>"> 
			</div>
			<div>
				<textarea name="text" minlength="1" maxlength="255" placeholder="Текст темы"><?php echo $editTheme['text']; ?></textarea>
			</div>
			<div>
				<input type="submit" name="edit-theme" value="Изменить">
			</div>
		</form>
	</section>

	<?php require_once 'vendor/push.php'; ?>

</body>
</html>