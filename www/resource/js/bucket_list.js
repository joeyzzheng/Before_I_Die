var render_bucket_item = function(item) {
	//render image and menu
	$(".bucket-items").append('<div class="bucket-item" data-item="' + item.ID + '"></div>');
	$(".bucket-item[data-item='" + item.ID + "']").append('<div class="item-img-section" data-item="' + item.ID + '"></div>');
	var img = '<img class="item-img" alt="bucket item image" src="' + item.image + '" data-item="'+ item.ID +'">';
	$(".item-img-section[data-item='" + item.ID + "']").append(img);
	
	
	if(item.completeTime){
		$(".item-img-section[data-item='" + item.ID + "']").css({opacity:0.5});
		var done = '<img class="done" src="resource/pic/done.png" alt="completed item">'
		$(".item-img-section[data-item='" + item.ID + "']").append(done);
	} else {
			var menu = '<div class="dropdown">   <button class="dropbtn" data-item="'+item.ID+'"><img class="menu" alt="menu" src="resource/pic/menu.png"></button>   <div class="dropdown-content" data-item="'+item.ID+'">' +
					'<a href="#">Edit</a> ' +
					'<a onClick="completed('+item.ID+')">Completed</a>' +
					'<a onClick="requestRelay('+item.ID+')">Request Relay</a>' +
					'<a onClick="deleteItem('+item.ID+')">Delete</a>' +
					'<div class="sub-dropdown"><a href="#">Privacy</a>' +
					'<div class="dropdown-sub-content">' +
					'<a onClick="private('+item.ID+')">Private</a>' +
					'<a onClick="public('+item.ID+')">Public</a> </div>   	</div>   </div> </div>';

			$(".item-img-section[data-item='" + item.ID + "']").append(menu);	
		
			var lock = '<img class="lock" src="resource/pic/lock.png" alt="lock" data-item="'+item.ID+'" ';
			
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
	var like = '<img onClick="like('+item.ID+')" class="icon-like" alt="like button" data-item="'+item.ID+'" src="resource/pic/like.png"><span class="like-count" data-item="'+item.ID+'">'+countLike+'</span>';
	$(".like[data-item='" + item.ID + "']").append(like);
	
	var torch = '<img class="torch" data-item="'+item.ID+'" alt="torch" src="resource/pic/'
	if(item.openToTorch == 0) torch += 'torch.png">';
	else torch += 'torched.png" onClick="inherit(' + item.ID + ')">';
		
	$(".like[data-item='" + item.ID + "']").append(torch);
	
	if(item.inheritFrom) {
		var parentUser = '<span class="inheritFrom">' + 'Inherited from ' + item.inheritFrom + '</span>';
		$(".like[data-item='" + item.ID + "']").append(parentUser);
	}
	//render comment
	if(item.comment){
		for(var j = item.comment.length-1; j >= 0; j--) {
			var text = item.comment[j].comment.replace(/</g, "&lt;").replace(/>/g, "&gt;");
			//add new line to every 70 char
			if(text != "") text = text.match(/.{1,70}/g).join("<br>");
			
			$(".response[data-item='" + item.ID + "']").append('<p class="comment"><span class="comment"><b>' + item.comment[j].username + '</b></span><span class="comment_content">' + text + '</span></p>');
		}
	}
	//render comeent textarea
	var textarea = '<input onkeydown="leave_comment(event,'+item.ID+')" type="text" data-item="' + item.ID + '" placeholder="leave a comment..." maxlength="500">';
	$(".item-info[data-item='" + item.ID + "']").append(textarea);

}

var render = function() {
	render_user_info();
	
	$.ajax({
		type        : 'GET', 
		url         : 'api/bucketlist/joeyzheng',//joeyzheng 
		dataType    : 'json', // what type of data do we expect back from the server
  })
	
	.done(function(data){
		if(data.success){
			
			if(data.responseJSON){
				$(".recommend").css("display","initial");
				for(var i = 0 ; i < data.responseJSON.length; i++) {
					render_bucket_item(data.responseJSON[i], i);
				}					
			}	
		}
	});
	
//	for(var i = 0 ; i < bucket_list.responseJSON.length; i++) {
//		render_bucket_item(bucket_list.responseJSON[i], i);
//	}	

	
}