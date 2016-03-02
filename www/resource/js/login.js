$(document).ready( function() {
        // create dropdown list
        function createStateList() {
            var listOfUserState = $("#signup-form #user-state");
            var usaAllStates = ["AK","AL","AR","AZ","CA","CO","CT","DC","DE","FL","GA","GU","HI","IA","ID","IL","IN","KS","KY","LA","MA","MD","ME","MH","MI","MN","MO","MS","MT","NC","ND","NE","NH","NJ","NM","NV","NY", "OH","OK","OR","PA","PR","PW","RI","SC","SD","TN","TX","UT","VA","VI","VT","WA","WI","WV","WY"];
            var len = usaAllStates.length;
            var optionResult = "";
            for (var i = 0; i < len; i++) {
                var optionFormat = "<option value=\"{stateValue}\">{stateValue}</option>";
                var usaState = usaAllStates.shift();
                while (true) {
                    if (optionFormat.indexOf("{stateValue}") > -1) {
                        optionFormat = optionFormat.replace("{stateValue}", usaState); 
                    } else {
                        break; 
                    }
                }
                optionResult += " " + optionFormat; 
            }
            listOfUserState.html(optionResult);
        }
        createStateList(); 

        // swith bewteen login page and signup page
        function switchToLogin() {
            $("#signup-form").delay(100).fadeIn(100);
            $("#login-form").fadeOut(100);
            $("#signup-form-link").removeClass("active");
            $("#login-form-link").addClass("active");
        }

        // signup form validation
        function signupFormValidate() {
            // required fields 
            var username = $("#signup-form #username").val();
            var firstname = $("#signup-form #firstname").val();
            var lastname = $("#signup-form #lastname").val();
            var emailAddress = $("#signup-form #email-address").val();
            var signUpPassword = $("#signup-form #signup-password").val();
            var confirmPassword = $("#signup-form #confirm-password").val();
            // optional fields
            var userTitle = $("#signup-form user-title").val();
            var userDescription = $("#signup-form user-description").val();
            var userCity = $("#signup-form user-city").val();
            var userState = $("#signup-form user-state").val();
        }

        // ajax form submission
        //$("#signup-form").ajaxSubmit({url: 'https://loging-sedernet.c9users.io/api/users/register', type: 'post'})
        $("#signup-form").submit(function(event) {
            alert("clicked");
            if (true) {
                alert("if");
                event.preventDefault();
                var signupURL = "https://loging-sedernet.c9users.io/api/users/register";
                var form = $(this);
                $.ajax({
                  type: 'GET',
                  url: signupURL,
                  data: form.serialize()
                }).done(function(data) {
                  // Optionally alert the user of success here...
                }).fail(function(data) {
                  // Optionally alert the user of an error here...
                });
                // $.ajax({
                //     url: signupURL,
                //     type: "POST",
                //     data: new FormData(this), 
                //     crossDomain: true,
                //     //dataType: 'json',
                //     contentType: false, 
                //     processData: false,
                //     success: function(data, textStatus, jqXHR) {
                //         // do something
                //     },
                //     error: function(jqXHR, textStatus, errorThrown) {
                //         alert("An error occured: " + textStatus + " " + errorThrown);
                //     }
                // })
            }
        })
    
        //var signupURL = "http://apiapache-beforeidie.rhcloud.com/api/users/register";
        //var loginURL = "http://apiapache-beforeidie.rhcloud.com/api/login"; 
    }
)