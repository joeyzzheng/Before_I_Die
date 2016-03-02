var share = function() {
	console.log("share");
}

var add = function() {
	console.log("add");
}

var like = function(item) {
	console.log("like ",item);
}

var completed = function(item) {
	$(".item-img-section[data-item='" + item + "']").css({opacity:0.5});
	var done = '<img class="done" src="../resource/pic/done.png" alt="completed item">'
	$(".item-img-section[data-item='" + item + "']").append(done);
	$(".dropbtn[data-item='" + item + "']").css({display:"none"});
	$(".dropdown-content[data-item='" + item + "']").css({display:"none"});

}
