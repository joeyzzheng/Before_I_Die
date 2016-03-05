/**
 * Main function
 */
$(document).ready(function() {
    /* Add below code to remove issue - 
        Synchronous XMLHttpRequest on the main thread is deprecated because of its 
        detrimental effects to the end user's experience. For more help http://xhr.spec.whatwg.org/
    */
    $.ajaxPrefilter(function( options, originalOptions, jqXHR ) { options.async = true; });
    
    /* Declare variables */
    /* Get the login/signup page */
    var loginPage = document.getElementById('wrapper-login-page');
    /* Get the button that opens the whiteboard */
    var linkLoginPage = document.getElementById("link-open-login-page");

    /* Initialize web page */

    
    /* Declare Events */
    /* When the user clicks the login link to open the login page */
    linkLoginPage.onclick = function() {
        loginPage.style.display = "block";

        /* Load login/signup page */
        loginPageLoad();
    };
    
    /* When the user clicks the popular link to load popular bucket list */
    $("#tab-popular-link").click(function() {
        /* clean class active first */
        $("#tab-ids").find("a").removeClass("active");
        $(this).addClass("active");
        
        /* reset display behavior to none first */
        tabConDisplayReset();
        $("#tab-popular").css("display","block");
    });
    
    /* When the user clicks the recent link to load recent bucket list */
    $("#tab-recent-link").click(function() {
        /* clean class active first */
        $("#tab-ids").find("a").removeClass("active");
        $(this).addClass("active");
        
        /* reset display behavior to none first */
        tabConDisplayReset();
        $("#tab-recent").css("display","block");
    });
    
    /* When the user clicks the torch relay link to load torch relay bucket item */
    $("#tab-torch-relay-link").click(function() {
        /* clean class active first */
        $("#tab-ids").find("a").removeClass("active");
        $(this).addClass("active");
        
        /* reset display behavior to none first */
        tabConDisplayReset();
        $("#tab-torch-relay").css("display","block");
    });

    /* Declare functions */
    /**
     * Load login page information by Ajax Get method
     */
    function loginPageLoad() {
        /* Find the value of target attribute to link */ 
        var urlLink = $("#link-open-login-page").attr('target'); 
        console.log("urlLink:\n" + urlLink);
        
        // Execute ajax with API /api/register
        $.ajax({
            type        : 'GET', // Define the https method that we want to use
            url         : urlLink, // api url that we want to call
            dataType    : 'html', // what type of data do we expect back from the server
        })
        
        /* if returned header shows 200 OK */
        .done(function(data) {
            $("#wrapper-login-page").html(data); 
            
            /* Get the <span> element that closes the modal */
            var btnClose = document.getElementById("btn-close");
            /* When the user clicks on <span> (x), close the modal */
            btnClose.onclick = function() {
              cleanFun();
            }
        })
        
        /* if returned header shows none 200 OK */
        .fail(function(data) {
            console.log("Failure Message:\n" + data); 
        })
    }
           
    /**
     * Clean all function when user close or end the login page
     */
    function cleanFun() {
        loginPage.style.display = "none";
        $("#wrapper-login-page").empty(); 
    }
    
    function tabConDisplayReset() {
         $("#tab-popular").css("display","none");
         $("#tab-recent").css("display","none");
         $("#tab-torch-relay").css("display","none");
    }
    
});

