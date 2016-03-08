$(document).ready(function() {

    // process the form
    $('#signup-form').submit(function(event) {
			event.preventDefault();
			var formData = new FormData();
			console.log(formData);
        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
//        var formData = {
//            username: "fwang1",
//            title: $("#item-name").val(),
//            content: $("#item-description").val()
//        };
//
//        // process the form
//        $.ajax({
//            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
//            url         : 'https://apiapache-beforeidie.rhcloud.com/api/bucket_item/insert', // the url where we want to POST
//            data        : formData, // our data object
//            dataType    : 'json', // what type of data do we expect back from the server
//                        encode          : true
//        })
//            // using the done promise callback
//            .done(function(data) {
//
//                // log data to the console so we can see
//                console.log(data); 
//
//                // here we will handle errors and validation messages
//            });
//
//        // stop the form from submitting the normal way and refreshing the page
        
    });

});