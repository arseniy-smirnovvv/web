<section>
	<?php if($message){ ?>
		<div id="error-controller" style="display: none;">
			<p id="error-text"><?php echo $message; ?></p>
		</div>
	<?php } ?>

	<div id='error_box' style="display: none;">
		<div class="error_container">		
			<p id='error_message' ></p>
		</div>
	</div>


	<?php if (preg_match("#admin#", $_SERVER["SCRIPT_NAME"])) {?>
	<script type="text/javascript" src="../js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="../js/push.js"></script>
	<?php  } else { ?>
		<script type="text/javascript" src="js/jquery-3.3.1.js"></script>
		<script type="text/javascript" src="../js/push.js"></script>
	<?php } ?>
</section>