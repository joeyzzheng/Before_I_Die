var render_title = function() {
	console.log(user_info.name);
	$("h1").text(user_info.name);
}

var render_user_card = function() {
	$(".user-name.user").text(user_info.name);
	$(".user-loc.user").text(user_info.location);
	$("#user-about").text(user_info.about);
	
}