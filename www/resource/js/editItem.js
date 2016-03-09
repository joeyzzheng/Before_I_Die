$(document).ready(function() {
    // get item list
    $("")

    // get item number  
    function getItemNum() {
        var url = window.location.href.split("/");
        var itemNum = url[url.length-1];
        if (itemNum.charAt(0) == "#") {
            return itemNum.slice(1); 
        }
    }

    // get username from cookie
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
        }
        return "";
    }

    // validate form
    function editItemFormValidate() {
        var title = $("#editItem-form #title");
        var content = $("#editItem-form #content");
        if (title.val() == "" || content.val() == "") {
            alert("Please provide both item and content");
            return false;
        }
        alert(title.val()); 
        alert(title.val().length); 
        if (title.val().length > 100) {
            alert("Item title must be less than 100 characters long. Please try again"); 
            return false; 
        }
        if (content.val().length > 2000) {
            alert("Item title must be less than 100 characters long. Please try again");
            return false; 
        }
        return true; 
    }

    // process the form
    $("#editItem-form").submit(function(event) {
        if (editItemFormValidate()) {
            event.preventDefault(); 
            var editItemForm = new FormData(this);
            // editItemForm.append("username", "Sen180"); 
            var editItemURL = "https://apiapache-beforeidie.rhcloud.com/api/bucket_item/update"; 
            $.ajax({
                url: editItemURL,
                type: "POST",
                data: editItemForm, 
                dataType: "json", 
                contentType: false, 
                processData: false,
                crossDomain: true,
                success: function(data, textStatus, jqXHR) {
                    alert("responsed data:" + JSON.stringify(data));
                    alert("textStatus:" + textStatus);
                    alert("jqXHR:" + JSON.stringify(jqXHR));
                    var username = "username";
                    username = getCookie(username); 
                    var url = "https://apiapache-beforeidie.rhcloud.com/personal/" + username; 
                    window.location.assign(url);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("An error occured: " + textStatus + " " + errorThrown);
                }
            })
        }
    })
});