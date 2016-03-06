var private = function(item) {
	var apiData = {
		itemID:item,
		private:1
	};
	$.ajax({
		type        : 'POST', 
		url         : 'https://apiapache-beforeidie.rhcloud.com/api/bucket_item/privacy', 
		data        : apiData, // our data object
		dataType    : 'json' // what type of data do we expect back from the server
  })	
	.done(function(){
		$(".lock[data-item='" + item + "']").css({display:"initial"});
	})
};

var public = function(item) {
	var apiData = {
		itemID:item,
		private:0
	};
	$.ajax({
		type        : 'POST', 
		url         : 'https://apiapache-beforeidie.rhcloud.com/api/bucket_item/privacy', 
		data        : apiData, // our data object
		dataType    : 'json' // what type of data do we expect back from the server
  })	
	.done(function(){
		$(".lock[data-item='" + item + "']").css({display:"none"});
	})
};

var deleteItem = function(item) {
	var apiData = {
		itemID:item
	};
	$.ajax({
		type        : 'POST', 
		url         : 'https://apiapache-beforeidie.rhcloud.com/api/bucket_item/delete', // the url where we want to POST
		data        : apiData, // our data object
		dataType    : 'json', // what type of data do we expect back from the server
  })
	.done(function(data){
		$(".bucket-item[data-item='" + item + "']").remove();
	})

};

var requestRelay = function(item) {
	var apiData = {
		itemID:item,
		openToTorch:1
	};
	$.ajax({
		type        : 'POST', 
		url         : 'https://apiapache-beforeidie.rhcloud.com/api/bucket_item/request_relay', // the url where we want to POST
		data        : apiData, // our data object
		dataType    : 'json', // what type of data do we expect back from the server
  })
	.done(function(data){
		$(".torch[data-item='" + item + "']").attr("src","../resource/pic/torched.png");
		$(".torch[data-item='" + item + "']").attr("onClick","inherit("+item+")");
	})
};

var inherit = function(item) {
	var apiData = {
		itemID:item,
		childUsername:"fwang1"
	};
	$.ajax({
		type        : 'POST', 
		url         : 'api/bucket_item/torch',
		data        : apiData,
		dataType    : 'json' // what type of data do we expect back from the server
  })

}

var share = function() {
	var url = window.location.href;
	console.log(url);
};

var add = function() {
	console.log("add");
};

var like = function(item) {
	//console.log("like ",item);
	var apiDataPOST = {
		itemID:item,
		likeusername:"fwang3",
		liked:"1"
	};
	$.ajax({
		type        : 'GET', 
		url         : 'api/bucket_item/like', // the url where we want to POST
		data        : apiDataPOST, // our data object
		dataType    : 'json', // what type of data do we expect back from the server
	})
	.done(function(likers){
		var likerExist = false;
		if(likers.success == "true"){
			if(likers.responseJSON){
				for(var i = 0; i < likers.responseJSON.length; i++) {
					if(likers.responseJSON[i] == apiDataPOST.likeusername) likerExist = true;
				}				
			}
			if(likerExist) apiDataPOST.liked = "0";
			
			$.ajax({
				type        : 'POST', 
				url         : 'api/bucket_item/like', // the url where we want to POST
				data        : apiDataPOST, // our data object
				dataType    : 'json', // what type of data do we expect back from the server
			})
			.done(function(data){
				if(data[0].success == "true"){
					$.ajax({
						type        : 'GET', 
						url         : 'api/bucket_item/like', // the url where we want to POST
						data        : apiDataPOST, // our data object
						dataType    : 'json', // what type of data do we expect back from the server
					})
					.done(function(dataGet){
						var countLike = dataGet.responseJSON?dataGet.responseJSON.length:0;
						$(".like-count[data-item='" + item + "']").text(countLike);
					})
				}			
			})
		}

	})	




};

var completed = function(item) {

	var apiData = {
		itemID:item,
		complete:1
	};
	
	$.ajax({
		type        : 'POST', 
		url         : 'https://apiapache-beforeidie.rhcloud.com/api/bucket_item/complete', // the url where we want to POST
		data        : apiData, // our data object
		dataType    : 'json', // what type of data do we expect back from the server
  })
	.done(function(data){
		$(".item-img-section[data-item='" + item + "']").css({opacity:0.5});
		var done = '<img class="done" src="../resource/pic/done.png" alt="completed item">'
		$(".item-img-section[data-item='" + item + "']").append(done);
		$(".dropbtn[data-item='" + item + "']").css({display:"none"});
		$(".dropdown-content[data-item='" + item + "']").css({display:"none"});
	})
};

var leave_comment = function(event, item) {
	var text = "";

	if(event.keyCode == 13){
		var apiData = {
			itemID:item,
			commentusername:"fwang1",
			comment:$("input[data-item='" + item + "']").val()
		};
		
		var apiDataGet = {
			itemID:item
		};
		
		$.ajax({
			type        : 'POST', 
			url         : 'https://apiapache-beforeidie.rhcloud.com/api/bucket_item/comment', // the url where we want to POST
			data        : apiData, // our data object
			dataType    : 'json', // what type of data do we expect back from the server
		})		
		
		.done(function(data){
			if(data[0].success == "true"){
				//ajax get
				$.ajax({
					type        : 'GET', 
					url         : 'https://apiapache-beforeidie.rhcloud.com/api/bucket_item/comment', 
					data        : apiDataGet, // our data object
					dataType    : 'json', // what type of data do we expect back from the server
				})	
				.done(function(getdata){
					$("input[data-item='" + item + "']").val("");
					var comment = getdata.responseJSON[0].comment.replace(/</g, "&lt;").replace(/>/g, "&gt;");
					$(".response[data-item='" + item + "']").append('<p class="comment"><span class="comment"><b>' + getdata.responseJSON[0].username + '</b></span><span class="comment_content">' + comment + '</span></p>');
					
				});
			}
		});
		
	
	}
	
	
};

