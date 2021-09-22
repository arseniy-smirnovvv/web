<?php  
	require_once '../vendor/controller/admin/add_controller.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<title><?php echo $title; ?></title>
	<!-- Подключение gogle шрифты -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700,900" rel="stylesheet">
	<!-- Подключение стилей для шапки, и футера -->
	<link rel="stylesheet" type="text/css" href="../style/style.css">
	<!-- Подключение стилей, для главной страницы -->
	<link rel="stylesheet" type="text/css" href="../style/admin/add.css">
	<!-- Подключение шрифты font-aweosome -->
	<link rel="stylesheet" type="text/css" href="../style/font-awesome.min.css">
	<!-- Подключение Push Уведомленйи -->
	<link rel="stylesheet" type="text/css" href="../style/push.css">
</head>
<body>

	<?php require_once '../header.php'; ?>

	<section class="container">	
		<?php switch ($mode) { case 'addsection':?>
		<div class="add">
			<form method="POST">
				<h3>Добавить раздел</h3>
				<div>
					<input type="text" name="add-name-section" placeholder="Название раздел"  maxlength="255" minlength="1">
				</div>
				<div>
					<input type="text" name="add-tag-section" placeholder="Гет-Тег для раздела"  maxlength="255" minlength="1">
				</div>
				<div>
					<textarea placeholder="Описание раздела" name="add-des-section"  maxlength="255" minlength="1"></textarea>
				</div>
				<div>
					<label class="check"><p>Виден пользователям</p><input type="checkbox" name="add-check-user-section"></label>
				</div>
				<div>
					<input type="submit" name="add-section" value="Добавить">
				</div>
			</form>
		</div>
		<?php break; ?>

		<?php case 'addcategory':?>
		<div class="add">
			<form method="POST">
				<h3>Добавить категорию</h3>
				<select name="add-list-category">
					<option disabled>Выберите раздел</option>
					<?php for ($i=0; $i < count($sectionList); $i++) { ?>
						<option value="<?php echo $sectionList[$i]['get-section'] ?>"><?php echo $sectionList[$i]['name']; ?></option>
					<?php } ?>
				</select>
				<div>
					<input type="text" name="add-name-category" placeholder="Название категории"  maxlength="255" minlength="1">
				</div>
				<div>
					<input type="text" name="add-tag-category" placeholder="Гет-Тег для раздела"  maxlength="255" minlength="1">
				</div>
				<div>
					<textarea placeholder="Описание категории" name="add-des-category"  maxlength="255" minlength="1"></textarea>
				</div>
				<div>
					<input type="submit" name="add-category" value="Добавить">
				</div>
			</form>
		</div>
		<?php break; ?>

		<?php case 'editsection': ?>
		<?php $editData = $sectionToId;?>
		<div class="change">
			<form method="POST">
				<h3>Изменить раздел</h3>
				<div>
					<input type="text" name="edit-name-section" placeholder="Название раздела" value="<?php if($editData != '') echo $sectionToId['name'] ?>"  maxlength="255" minlength="1">
				</div>
				<div>
					<input type="text" name="edit-tag-section" placeholder="Гет-Тег раздела" maxlength="255" minlength="1"  value="<?php  if($editData != '') echo $sectionToId['get-section'] ?>">
				</div>
				<div>
					<textarea placeholder="Описание раздела" name="edit-desc-section" maxlength="255" minlength="1"><?php if($editData != '')  echo $sectionToId['des'] ?></textarea>
				</div>
				<?php if($editData != '') { ?>
				<div>
					<p class="info"> Дата создания: <?php  echo date('d.m.Y в H:i', $sectionToId['date']); ?></p>
				</div>
				<div>
					<p class="info">Создал: <?php echo $sectionToId['create_user']; ?></p>
				</div>
				<div>
					<label class="check"><p>Виден пользователям</p><input type="checkbox"  <?php if($editData != ''){ if($sectionToId['hidden_user'] != 0) echo "checked"; } ?>  name="add-check-user-section"></label>
				</div>
				<div>
				<?php } ?>
					<input type="submit" name="edit-section" value="Изменить">
				</div>
			</form>
		</div>
		<?php break; ?>

		<?php case 'editcategory':?>
		<?php $editData = $categoryToId ?>
		<div class="change">
			<form method="POST"> 
				<h3>Изменить категорию</h3>
				<div>
					<input type="text" name="edit-name-category" placeholder="Название категории" value="<?php if($editData != '') echo $categoryToId['name'] ?>"  maxlength="255" minlength="1">
				</div>
				<div>
					<input type="text" name="edit-tag-category" placeholder="Гет-Тег категории" maxlength="255" minlength="1"  value="<?php  if($editData != '') echo $categoryToId['get-category'] ?>">
				</div>
				<div>
					<textarea placeholder="Описание категории" name="edit-desc-category" maxlength="255" minlength="1"><?php if($categoryToId != '') echo $categoryToId['des']; ?></textarea>
				</div>
				<?php if ($editData != '') { ?>
				<div>
					<p class="info">Раздел
						<select name="edit-get-section-category">
							<option disabled="">Раздел</option>
							<?php for ($i=0; $i < count($sectionList); $i++) { ?>
							<option <?php  ?> value="<?php echo $sectionList[$i]['get-section'] ?>"><?php echo $sectionList[$i]['name']; ?></option>
							<?php } ?>
						</select>
					</p>
					<p class="info">Категорию создал: <?php echo $categoryToId['create_user']; ?></p>
					<p class="info"> Дата создания: <?php  echo date('d.m.Y в H:i', $categoryToId['date']); ?></p>
				</div>
				<?php } ?>
				<div>
					<input type="submit" name="edit-category" value="Изменить">
				</div>
			</form>
		</div>
		<?php break; ?>
		<?php } ?>
	</section>
	<?php require_once '../vendor/push.php'; ?>
</body>
</html>