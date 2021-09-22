$(document).ready(function () {
	
	$('#setting').click(function () {

		$('#theme-block').fadeOut(300, function () {
			$('#setting-block').fadeIn(200);
		});
		
		$("#theme").removeClass('active');
		$('#setting').addClass('active');
	})

	$('#theme').click(function () {

		$('#setting-block').fadeOut(300, function () {
			$('#theme-block').fadeIn(200);
		});
		
		$("#setting").removeClass('active');
		$('#theme').addClass('active');
	})

})