$(document).ready(function(){
	function setError(message) {
		$("#username-div").before("<div id='alert' class='alert alert-danger'><strong>Attention! </strong>" + message + "</div>");
	}
	$('body').on('change', '#username', function(){
		var user = $('#username').val();
		$.get('ajax.php',{ username:user }, function(data, status){
			$("#alert").remove();
			if (data == "Not Found") {
				setError("User not found");
			}
		});
	});
	$('body').on('change', '#new-username', function(){
		var user = $('#new-username').val();
		$.get('ajax.php',{ username:user, action:'new user' }, function(data, status){
			$("#alert").remove();
			if (data == "Found") {
				setError("Duplicate username");
			}
		});
	});
});