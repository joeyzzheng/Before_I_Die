var render_user_info = function(userName) {	
	
	$.ajax({
		type        : 'GET', 
		url         : 'https://apiapache-beforeidie.rhcloud.com/api/users/' + userName, 
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
	
	$.ajax({
		type        : 'GET', 
		url         : 'https://apiapache-beforeidie.rhcloud.com/api/recommendation', 
		dataType    : 'json', // what type of data do we expect back from the server
  })
	.done(function(data){
		var recom = data.responseJSON;
		if(recom){
			$(".recommend").css("display","initial");
			$(".user-img.a").attr("src",recom[0].profilePicture);
			$(".user-name.a").text(recom[0].firstName + " " + recom[0].lastName);
			$(".user-loc.a").text(recom[0].city);

			$(".user-img.b").attr("src",recom[1].profilePicture);
			$(".user-name.b").text(recom[1].firstName + " " + recom[1].lastName);
			$(".user-loc.b").text(recom[1].city);			
		} else {
			$(".recommend").css("display","none");
		}


	})
	
}
