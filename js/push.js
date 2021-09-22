$(document).ready(function () {

	function throw_message(str) {
        $('#error_message').html(str);
    	$("#error_box").fadeIn(1000).delay(2500).fadeOut(500);
    }

    if ($('div').is('#error-controller')) {
    	var str = $('#error-text');
    	throw_message(str);
    }
})