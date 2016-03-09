$(document).ready(function() {
    // get itemID and username from URL when user trigger edit item from personal page
    var url = window.location.href;
    /* regulare express - found[0]: full name, found[1]: user name, found[2]: bucketID */
    var found = url.match(/(\w+)#(\d+)/);
    if (found) {
        var urlLink = "/api/bucketlist/" + found[1];
        console.log("urlLink : " + urlLink);
        
        // Execute ajax with API /api/register
        $.ajax({
            type        : 'GET', // Define the https method that we want to use
            url         : urlLink, // api url that we want to call
            dataType    : 'json' // what type of data do we expect back from the server
        })
        
        /* if returned header shows 200 OK */
        .done(function(data) {
             console.log("Success Message - " + urlLink + " :\n" + JSON.stringify(data)); 
             var dataResSize = data.responseJSON.length;
             var indexItem = Number.MAX_SAFE_INTEGER;
             
             for (var index = 0; index < dataResSize; index++) {
                 //console.log(index + " : " + data.responseJSON[index]["ID"]);
                 if (data.responseJSON[index]["ID"] == found[2]) {
                     indexItem = index;
                     break;
                 }
             }
             
             if (indexItem == Number.MAX_SAFE_INTEGER) {
                errorPageDisplay("Error: bucket Item ID doesn't exist!");
             } else {
                console.log("Bucket Item ID exist - " + indexItem);
                var datResTitle = data.responseJSON[indexItem]["title"];
                var dataResContent = data.responseJSON[indexItem]["content"];
                var dataResImg = data.responseJSON[indexItem]["image"];
                var dataResItemID = data.responseJSON[indexItem]["ID"];
                    
                /* Add two iputs with name itemID and image into form */    
                var form = $("#editItem-form");
                var itemID = document.createElement("input");
                form.append(itemID);
                itemID.name = "itemID";
                itemID.id = "itemID";
                itemID.type = "hidden";
                itemID.value = dataResItemID;
                
                var img = document.createElement("input");
                form.append(img);
                img.name = "image";
                img.id   = "image";
                img.type = "hidden";
                img.value = dataResImg;
                
                /* Write information into edit form */
                $("#title").val(datResTitle);
                $("#content").val(dataResContent);
             }
        })
        
        /* if returned header shows none 200 OK */
        .fail(function(data) {
            console.log("Failure Message:\n" + JSON.stringify(data)); 
        })
    } else {
        errorPageDisplay("Error: Bucket Item Edit URL Wrong");
    }
    
    function errorPageDisplay(errText) {
        console.log(errText); 
        /* Implement error page later */
        $("#editItem-form-link").html("Edit item: " + errText);
        $("#title").prop( "disabled", true );
        $("#content").prop( "disabled", true );
        $("#fileToUpload").prop( "disabled", true );
        $("#submit").prop( "disabled", true );
    }
    
    // validate form
    function editItemFormValidate() {
        var title = $("#editItem-form #title");
        var content = $("#editItem-form #content");
        if (title.val() == "" || content.val() == "") {
            alert("Please provide both item and content");
            return false;
        }
        //alert(title.val()); 
        //alert(title.val().length); 
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
            var editItemURL = "/api/bucket_item/update"; 

            // Execute ajax with API /api/bucket_item/update
            $.ajax({
                type        : 'POST', // Define the https method that we want to use
                url         : editItemURL, // api url that we want to call
                data        : editItemForm, // our data object
                contentType : false, 
                processData : false,
                dataType    : 'json', // what type of data do we expect back from the server
                encode      : true
            })
            
            /* if returned header shows 200 OK */
            .done(function(data) {
                //Debug message
                console.log("Success Message:\n" + JSON.stringify(data)); 
                var reURL = "/personal/" + found[1]; 
                window.location.assign(reURL);
            })
            
            /* if returned header shows none 200 OK */
            .fail(function(data) {
                console.log("Failure Message:\n" + JSON.stringify(data)); 
            })
        }
    })
});