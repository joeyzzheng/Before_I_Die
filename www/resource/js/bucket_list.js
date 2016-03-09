var render_bucket_item = function(item, id) {
	//render image and menu
	
	$(".bucket-items").append('<div class="bucket-item" id="'+item.ID+'" data-item="' + item.ID + '" data-torch="'+item.openToTorch+'"></div>');
	$(".bucket-item[data-item='" + item.ID + "']").append('<div class="item-img-section" data-item="' + item.ID + '"></div>');
	var img = '<img class="item-img" alt="bucket item image" src="' + item.image + '" data-item="'+ item.ID +'">';
	$(".item-img-section[data-item='" + item.ID + "']").append(img);
	
	
	if(item.completeTime){
		$(".item-img-section[data-item='" + item.ID + "']").css({opacity:0.5});
		var done = '<img class="done" src="../resource/pic/done.png" alt="completed item">'
		$(".item-img-section[data-item='" + item.ID + "']").append(done);
	} else {
		var menu = '<div class="dropdown">   <button class="dropbtn" data-item="'+item.ID+'"><img class="menu" alt="menu" src="../resource/pic/menu.png"></button>   <div class="dropdown-content" data-item="'+item.ID+'">' +
				'<a class="edit" onClick="edit('+item.ID+')">Edit</a> ' +
				'<a onClick="completed('+item.ID+')">Completed</a>' +
				'<a class="requestRelay" onClick="requestRelay('+item.ID+')">Request Relay</a>' +
				'<a onClick="deleteItem('+item.ID+')">Delete</a>' +
				'<div class="sub-dropdown"><a href="#">Privacy</a>' +
				'<div class="dropdown-sub-content">' +
				'<a onClick="private('+item.ID+')">Private</a>' +
				'<a onClick="public('+item.ID+')">Public</a> </div>   	</div>   </div> </div>';

		$(".item-img-section[data-item='" + item.ID + "']").append(menu);	

		var lock = '<img class="lock" src="../resource/pic/lock.png" alt="lock" data-item="'+item.ID+'" ';

		if(item.private == 0) lock += 'style="display:none;">';
		else lock += ">";

		$(".item-img-section[data-item='" + item.ID + "']").append(lock);

	}

	
	
	//render title des
	$(".bucket-item[data-item='" + item.ID + "']").append('<div class="item-info" data-item="' + item.ID + '"></div>');
	var title = "<h2>" + item.title + "</h2>";
	$(".item-info[data-item='" + item.ID + "']").append(title);
	var description = '<div class="item-desc">' + item.content + '</div>';
	$(".item-info[data-item='" + item.ID + "']").append(description);
	$(".item-info[data-item='" + item.ID + "']").append("<hr>");
	
	//render response
	$(".item-info[data-item='" + item.ID + "']").append('<div class="response" data-item="' + item.ID + '"></div>');
	$(".response[data-item='" + item.ID + "']").append('<div class="like" data-item="' + item.ID + '"></div>');
	var countLike = (item.like)?item.like.length:0;
	var like_status = "like";
	var likeusername = document.cookie.match(/=(.*)/)[1];
	if(item.like){
		for(var i = 0; i<item.like.length; i++){
			if (likeusername == item.like[i]) like_status = "liked";
		}		
	}

	var like = '<img onClick="like('+item.ID+')" class="icon-like" alt="like button" data-item="'+item.ID+'" src="../resource/pic/'+like_status+'.png"><span class="like-count" data-item="'+item.ID+'">'+countLike+'</span>';
	$(".like[data-item='" + item.ID + "']").append(like);
	
	var torch = '<img class="torch" data-item="'+item.ID+'" alt="torch" src="../resource/pic/'
	if(item.openToTorch == 0 || item.inheritFrom) torch += 'torch.png">';
	else torch += 'torched.png" onClick="inherit(' + item.ID + ')">';
		
	$(".like[data-item='" + item.ID + "']").append(torch);
	
	if(item.inheritFrom) {
		var parentUser = '<span class="inheritFrom">' + 'Inherited from <a class="inherit-link" href="https://apiapache-beforeidie.rhcloud.com/personal/'+item.inheritFrom+'#'+item.ID+'">' + item.inheritFrom + '</a></span>';
		$(".like[data-item='" + item.ID + "']").append(parentUser);
		$(".dropdown-content[data-item='" + item.ID + "']>.edit").remove();
		$(".dropdown-content[data-item='" + item.ID + "']>.requestRelay").remove();
	}
	//render comment
	if(item.comment){
		$(".like[data-item='" + item.ID + "']").append("<HR>");
		for(var j = item.comment.length-1; j >= 0; j--) {
			var text = item.comment[j].comment.replace(/</g, "&lt;").replace(/>/g, "&gt;");
			//add new line to every 70 char
			if(text != "") text = text.match(/.{1,70}/g).join("<br>");
			
			$(".response[data-item='" + item.ID + "']").append('<p class="comment-row"><img class="user-comment-img" alt="user image" src="'+item.comment[j].profilePic+'"><span class="comment"><b>' + item.comment[j].username + '</b>&nbsp&nbsp' + text + '</span></p><HR>');
		}
	}
	//render comeent textarea
	var textarea = '<input class="commentBox" onkeydown="leave_comment(event,'+item.ID+')" type="text" data-item="' + item.ID + '" placeholder="leave a comment..." maxlength="500">';
	$(".item-info[data-item='" + item.ID + "']").append(textarea);
	
	
	if( ("#"+item.ID) == id){
		//$(".item-img[data-item='" + id + "']")
		setTimeout(function(){
			var pos_top = $(id).offset().top - 25;
			$("body").animate({scrollTop: pos_top}, 2000);			
		},500);

	} 
}

var render = function() {
	var url = window.location.href.split("/");
	var username = url[url.length-1];

	render_user_info(username);
	var userUrl = 'https://apiapache-beforeidie.rhcloud.com/api/bucketlist/' + username;
	
	$.ajax({
		type        : 'GET', 
		url         : userUrl,//joeyzheng 
		dataType    : 'json', // what type of data do we expect back from the server
  })
	
	.done(function(data){
		if(data.success){
			
			if(data.responseJSON){
				$(".recommend").css("display","initial");
				for(var i = 0 ; i < data.responseJSON.length; i++) {
					var id = username.match(/#(\d+)/)||"0";
					render_bucket_item(data.responseJSON[i], id[0]);
				}					
			}	
		}
	});

	
//	for(var i = 0 ; i < bucket_list.responseJSON.length; i++) {
//		render_bucket_item(bucket_list.responseJSON[i], i);
//	}	

	
}