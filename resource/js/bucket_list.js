var render_bucket_item = function(i) {
	//render image and menu
	$(".bucket-items").append('<div class="bucket-item" data-item="' + i + '"></div>');
	$(".bucket-item[data-item='" + i + "']").append('<div class="item-img-section" data-item="' + i + '"></div>');
	var img = "<img class='item-img alt='bucket item image' src='" + bucket_list.list[i].image + "'>";
	$(".item-img-section[data-item='" + i + "']").append(img);
	var menu = '<div class="dropdown">   <button class="dropbtn"><img class="menu" alt="menu" src="../resource/pic/menu.png"></button>   <div class="dropdown-content"> 	<a href="#">Edit</a> 	<a href="#">Completed</a> 	<a href="#">Request Relay</a> 	<a href="#">Delete</a> 	  <div class="sub-dropdown"> 	  	<a href="#">Privacy</a> 	  		<div class="dropdown-sub-content"> 		  		<a href="#">Private</a> 			  	<a href="#">Public</a> 		  	</div>   	</div>   </div> </div>';
	
	$(".item-img-section[data-item='" + i + "']").append(menu);
	
	
	//render title des
	$(".bucket-item[data-item='" + i + "']").append('<div class="item-info" data-item="' + i + '"></div>');
	var title = "<h2>" + bucket_list.list[i].title + "</h2>";
	$(".item-info[data-item='" + i + "']").append(title);
	var description = '<div class="item-desc">' + bucket_list.list[i].description + '</div>';
	$(".item-info[data-item='" + i + "']").append(description);
	$(".item-info[data-item='" + i + "']").append("<hr>");
	
	//render response
	$(".item-info[data-item='" + i + "']").append('<div class="response" data-item="' + i + '"></div>');
	$(".response[data-item='" + i + "']").append('<div class="like" data-item="' + i + '"></div>');
	var like = '<img class="icon-like" alt="like button" src="../resource/pic/like.png"><span class="count-of-like">' + bucket_list.list[i].like + '</span><img class="torch" src="../resource/pic/torch.png" alt="torch>">';
	$(".like[data-item='" + i + "']").append(like);
	
	//render comment
	for(var j = 0; j < bucket_list.list[i].comment.length; j++) {
		$(".response[data-item='" + i + "']").append('<p class="comment"><span class="comment"><b>' + bucket_list.list[i].comment[j].name + '</b></span>' + bucket_list.list[i].comment[j].text + '</p>');
	}
	
	
	
}

var render = function() {
	for(var i = 0 ; i < bucket_list.list.length; i++) {
		render_bucket_item(i);
	}
	
}