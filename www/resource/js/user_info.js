var render_user_info = function(userName) {

	$.ajax({
		type        : 'GET',
		url         : '/api/users/' + userName, 
		dataType    : 'json', // what type of data do we expect back from the server
  })
	.done(function(data){
		//console.log("data",data);
		var user = data.responseJSON[0];
		$(".bucket-items").attr("id",user.userName);
		$("h1").text(user.firstName + " " + user.lastName);
		$(".user-name.user").text(user.firstName + " " + user.lastName);
		$(".user-loc.user").text(user.city);
		$("#user-about").text(user.description);
		$(".user-img").attr("src",user.profilePicture);
	})
}
