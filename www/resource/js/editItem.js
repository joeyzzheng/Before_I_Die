$(document).ready(function() {
<<<<<<< HEAD

    // process the form
    $('#signup-form').submit(function(event) {
			
			var formData = new FormData(this);
			console.log(formData);
        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
//        var formData = {
//            username: "fwang1",
//            title: $("#item-name").val(),
//            content: $("#item-description").val()
//        };

        // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '/api/bucket_item/insert', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            processData: false,
						encode: true
        })
            // using the done promise callback
            .done(function(data) {

                // log data to the console so we can see
                console.log(data); 

                // here we will handle errors and validation messages
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

=======
    // get itemID and username from URL when user trigger edit item from personal page
    var url = window.location.href;
    /* regulare express for edit item - found[0]: full name, found[1]: user name, found[2]: bucketID */
    var found = url.match(/(\w+)#(\d+)/); 
    /* regulare express for add item - found[0]: full name, found[1]: user name */
    var found2 = url.match(/editItem\/(\w+)/);
    /* determine which api request to use */
    var apiFunc = ""; 
    var username = "";
    
    var title = $("#editItem-form #title");
    var content = $("#editItem-form #content");
    var location = $("#editItem-form #location");

    if (found) {
        username = found[1];
        var urlLink = "/api/bucketlist/" + username;
        console.log("urlLink : " + urlLink);
        apiFunc = "/api/bucket_item/update"; 
        
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
                var dataResLocation = data.responseJSON[indexItem]["location"];
                    
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
                title.val(datResTitle);
                content.val(dataResContent);
                location.val(dataResLocation);
             }
        })
        
        /* if returned header shows none 200 OK */
        .fail(function(data) {
            console.log("Failure Message:\n" + JSON.stringify(data)); 
        })
    /* Determine whether url requests add item page */
    } else if (found2) {
        username = found2[1];
        apiFunc = "/api/bucket_item/insert"; 
        
        $("#editItem-form-link").html("Add Item:");
        title.attr("placeholder", "Item name:");
        content.attr("placeholder", "Item Description:");
        location.attr("placeholder", "Location:");
        
    /* Determine whether url displays wrong bucket item */
    } else {
         errorPageDisplay("Error: Bucket Item Edit URL Wrong");
    }
    
    function errorPageDisplay(errText) {
        console.log(errText); 
        /* Implement error page later */
        $("#editItem-form-link").html(errText);
        title.prop( "disabled", true );
        content.prop( "disabled", true );
        location.prop( "disabled", true );
        $("#fileToUpload").prop( "disabled", true );
        $("#submit").prop( "disabled", true );
    }
    
    // validate form
    function editItemFormValidate() {
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
            alert("Item content must be less than 2000 characters long. Please try again");
            return false; 
        }
        if (location.val().length > 100) {
            alert("Location must be less than 100 characters long. Please try again");
            return false; 
        }
        
        return true; 
    }
    
    // process the form
    $("#editItem-form").submit(function(event) {
        if (editItemFormValidate()) {
            event.preventDefault(); 
            
            var editItemForm = new FormData(this);
            // Execute ajax with API /api/bucket_item/update
            $.ajax({
                type        : 'POST',       // Define the https method that we want to use
                url         : apiFunc,      // api url that we want to call
                data        : editItemForm, // our data object
                contentType : false, 
                processData : false,
                dataType    : 'json',       // what type of data do we expect back from the server
                encode      : true
            })
            
            /* if returned header shows 200 OK */
            .done(function(data) {
                //Debug message
                console.log("Success Message:\n" + JSON.stringify(data)); 
                var reURL = "/personal/" + username; 
                window.location.assign(reURL);
            })
            
            /* if returned header shows none 200 OK */
            .fail(function(data) {
                console.log("Failure Message:\n" + JSON.stringify(data)); 
            })
        }
    })
>>>>>>> 77a7d1652ccce8d9841eb466f99505a5fd08fb9a
});