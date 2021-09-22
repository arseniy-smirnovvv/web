<?php require_once 'vendor/controller/index_controller.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Главная страница</title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="style/main.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="style/font-awesome.min.css">
</head>
<body>
	
	<?php require 'header.php'; ?>

	<section class="container-themes">
		<h3>Разделы</h3>

		<?php $countSectionList = count($sectionList); ?>
		<?php for ($i=0; $i < $countSectionList; $i++) { ?>
		<div class="container-category">
			<div class="container-title">
				<div class="title">
					<p><?php echo $sectionList[$i]['name']; ?></p>
				</div>
				<div class="info-bar">
					<p>Кол-во тем</p>
					<p>Просмотры</p>
				</div>
			</div>

			<?php $countCategoryList = count($categoryList); ?>
			<?php for ($j=0; $j < $countCategoryList; $j++) { ?>
			<?php if($sectionList[$i]['get-section'] == $categoryList[$j]['get-section']){ ?>
			<div class="themes">
				<div class="title">
					<span class="icon"><i class="fa fa-comments-o"></i></span>
					<p><a href="<?php echo 'category.php?category='.$categoryList[$j]['get-category']; ?>"><?php echo $categoryList[$j]['name']; ?></a></p>
				</div>
				<div class="info-bar">
					<p><?php echo $themeClass->countThemeToGetCategory($categoryList[$j]['get-category']); ?></p>
					<p><?php echo $themeClass->getViewsToGetCategory($categoryList[$j]['get-category']); ?></p>
				</div>
			</div>
			<?php } ?>
			<?php } ?>

		</div>
		<?php } ?>

	</section>
</body>
</html>