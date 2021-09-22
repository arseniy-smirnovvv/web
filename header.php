<header>
	<div class="container-head">
		<div class="logo">
			<h1>Форум ни о чем</h1>
		</div>
		<nav>
			<ul>
				<?php $countPage = count($page);  ?>
				<?php for ($i=0; $i < $countPage; $i++) { ?>
					<li><a href="<?php echo $page[$i]['url'] ?>"><?php echo $page[$i]['name'];?></a></li>
				<?php } ?>
			</ul>
		</nav>
	</div>
</header>