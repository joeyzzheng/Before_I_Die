var render_user_info = function() {	
	
	$.ajax({
		type        : 'GET', 
		url         : 'https://apiapache-beforeidie.rhcloud.com/api/users/joeyzheng', 
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
