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
            var email = $("#signup-form #email").val();
            var signUpPassword = $("#signup-form #signup-password").val();
            var confirmPassword = $("#signup-form #confirm-password").val();
            // optional fields
            var userTitle = $("#signup-form user-title").val();
            var userDescription = $("#signup-form user-description").val();
            var userCity = $("#signup-form user-city").val();
            var userState = $("#signup-form user-state").val();
        }

        // login form validation 
        function loginFormValidation() {
            var username = $("#login-form #username").val();
            var loginPassword = $("#login-form #login-password").val();
            var rememberPassword = $('#login-form #remember-password').is(":checked"); // true or false
        }

        // ajax signup form submission
        $("#signup-form").submit(function(event) {
            alert("signup submit button clicked"); // debug
            if (signupFormValidate()) {
                event.preventDefault();
                var signupURL = "https://apiapache-beforeidie.rhcloud.com/api/users/register";
                $.ajax({
                    url: signupURL,
                    type: "POST",
                    data: new FormData(this), 
                    contentType: false, 
                    processData: false,
                    crossDomain: true,
                    success: function(data, textStatus, jqXHR) {
                        switchToLogin(); // switch to login form
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("An error occured: " + textStatus + " " + errorThrown);
                    }
                })
            }
        })
        
        // ajax login form submission 
        $("#login-form").submit(function(event) {
            alert("login submit button clicked"); // debug
            if (loginFormValidation()) {
                event.preventDefault();
                var loginURL = "https://apiapache-beforeidie.rhcloud.com/api/login";
                var loginData = $(this).serialize();
                $.ajax({
                    url: signupURL,
                    type: "GET",
                    data: loginData, 
                    success: function(data, textStatus, jqXHR) {
                        // switch to public page
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("An error occured: " + textStatus + " " + errorThrown);
                    }
                })
            }
        })

        // hash password
        function formhash(form, password) {
            // Create a new element input, this will be our hashed password field. 
            var p = document.createElement("input");

            // Add the new element to our form. 
            form.appendChild(p);
            p.name = "p";
            p.type = "hidden";
            p.value = hex_sha512(password.value);

            // Make sure the plaintext password doesn't get sent. 
            password.value = "";

            // Finally submit the form. 
            form.submit();
        }
    }
)