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
    iniFun();
    
    /* Declare Events */
    /* When the user clicks the login link to open the login page */
    linkLoginPage.onclick = function() {
        loginPage.style.display = "block";

        /* Load login/signup page */
        loginPageLoad();
    };
    
    /* When the user clicks the popular link to load popular bucket list */
    $("#tab-popular-link").click(function() {
        if (!$("#tab-popular-link").is(".active")) {
            $("#tab-popular").empty();
            tabPageLoad("api/popular_item", "tab-popular", 6, 0, 11);
        }
        
        /* clean class active first */
        $("#tab-ids").find("a").removeClass("active");
        $(this).addClass("active");
        
        /* reset display behavior to none first */
        tabConDisplayReset();
        $("#tab-popular").css("display","block");
    });
    
    /* When the user clicks the recent link to load recent bucket list */
    $("#tab-recent-link").click(function() {
        if (!$("#tab-recent-link").is(".active")) {
            $("#tab-recent").empty();
            tabPageLoad("api/recent_item", 'tab-recent', 6, 0, 9);
        }
        
        /* clean class active first */
        $("#tab-ids").find("a").removeClass("active");
        $(this).addClass("active");
        
        /* reset display behavior to none first */
        tabConDisplayReset();
        $("#tab-recent").css("display","block");
    });
    
    /* When the user clicks the torch relay link to load torch relay bucket item */
    $("#tab-torch-relay-link").click(function() {
        if (!$("#tab-torch-relay").is(".active")) {
            $("#tab-torch-relay").empty();
            tabPageLoad("api/torch_item", 'tab-torch-relay', 6, 0, 9);
        }
        
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
                loginPage.style.display = "none";
                $("#wrapper-login-page").empty(); 
            }
        })
        
        /* if returned header shows none 200 OK */
        .fail(function(data) {
            console.log("Failure Message:\n" + data); 
        })
    }
           
    /**
     * Load tab page information by Ajax Get method
     */
    function tabPageLoad(apiURLCall, tagEl, num_limit, num_lower_bound, num_higher_bound) {
        // Execute ajax with API /api/register
        $.ajax({
            type        : 'GET', // Define the https method that we want to use
            url         : apiURLCall, // api url that we want to call
            dataType    : 'json', // what type of data do we expect back from the server
        })
        
        /* if returned header shows 200 OK */
        .done(function(data) {
            console.log("Success Message - tabPageLoad " + apiURLCall + " :\n" + JSON.stringify(data)); 
            
            //var objLen = data.responseJSON.length; 
            /* Establish random sequence for extracing Bucket List */
            var ranChoice = [];
            ranChoice = ranSeq(num_limit, num_lower_bound, num_higher_bound);
            var ranChoiceLen = ranChoice.length;
            var imgSrc = "";
            var spanText = "";
            
            for	(var index = 0; index < ranChoiceLen; index++) {
                for (var item in data.responseJSON[ranChoice[index]]) {
                    console.log(item + " : " + data.responseJSON[ranChoice[index]][item]);
                }
                var altText = data.responseJSON[ranChoice[index]].username + "_profilePicture";
                
                var tabPopDiv = document.getElementById(tagEl);
                var divPopCard = document.createElement("div");
                divPopCard.setAttribute("class", "tab-bucket-card");
                
                if (apiURLCall === "api/torch_item") {
                    /* set element id with username#bucketID */
                    divPopCard.setAttribute("id", data.responseJSON[ranChoice[index]].username + "#" + data.responseJSON[ranChoice[index]].bucketItemID);
                    imgSrc = data.responseJSON[ranChoice[index]].image;
                    spanText = data.responseJSON[ranChoice[index]].username + "<br>" + data.responseJSON[ranChoice[index]].bucketItemTitle;
                    
                } else {
                    /* set element id with username */
                    divPopCard.setAttribute("id", data.responseJSON[ranChoice[index]].username);
                    imgSrc = data.responseJSON[ranChoice[index]].profilePicture;
                    spanText = data.responseJSON[ranChoice[index]].username + "<br>" + data.responseJSON[ranChoice[index]].description;
                }

                /* Add click function - redirect to personal user page */
                divPopCard.addEventListener("click", function() {
                    console.log("Debug - verify id: " + this.id);
                    var redirectURL = "";
                    redirectURL = "https://apiapache-beforeidie.rhcloud.com/personal/" + this.id;
                    window.location.assign(redirectURL)
                });

                var imgPop = document.createElement("img");
                imgPop.setAttribute("src", imgSrc);
                imgPop.setAttribute("alt", altText);
                divPopCard.appendChild(imgPop);
                
                var divPopWord = document.createElement("div");
                divPopWord.setAttribute("class", "tab-bucket-card-word");
                var spanWord = document.createElement("span");
                spanWord.setAttribute("class", "spacer");

                spanWord.innerHTML = spanText;
                divPopWord.appendChild(spanWord);
                divPopCard.appendChild(divPopWord);
                
                tabPopDiv.appendChild(divPopCard);
            }
        })
        
        /* if returned header shows none 200 OK */
        .fail(function(data) {
            console.log("Failure Message:\n" + data); 
        })
    }
   
    /**
     * Initialize home page
     */
    function iniFun() {
        tabPageLoad("api/popular_item", "tab-popular", 6, 0, 11);
    }
   
    function tabConDisplayReset() {
         $("#tab-popular").css("display","none");
         $("#tab-recent").css("display","none");
         $("#tab-torch-relay").css("display","none");
    }
    
    /***
     * 
     * Refer http://stackoverflow.com/ to generate random number into array
     * http://stackoverflow.com/questions/8378870/generating-unique-random-numbers-integers-between-0-and-x
     */
    function ranSeq(limit, lower_bound, upper_bound) {
        var ranChoice = [];

        while (ranChoice.length < limit) {
            var random_number = Math.round(Math.random()*(upper_bound - lower_bound) + lower_bound);
            if (ranChoice.indexOf(random_number) == -1) { 
                ranChoice.push(random_number);
            }
        }
        console.log("random sequence: " + ranChoice);
        
        return  ranChoice;
    }    
    
});

