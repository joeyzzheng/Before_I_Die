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
        $("#login-form-link").on("click", function(e) {
            $("#login-form").delay(100).fadeIn(100);
            $("#signup-form").fadeOut(100);
            $("#signup-form-link").removeClass("active");
            $(this).addClass("active");
            e.preventDefault();
        });
        $("#signup-form-link").on("click", function(e) {
            $("#signup-form").delay(100).fadeIn(100);
            $("#login-form").fadeOut(100);
            $("#login-form-link").removeClass("active");
            $(this).addClass("active");
            e.preventDefault();
        })
        

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
            // check each required fields has a value
            if (username == '' || firstname == '' || lastname == '' || email == '' || signUpPassword == '' || confirmPassword == '') {
                alert('You must provide all the requested details. Please try again');
                return false;
            }
            // check username
            var regExName = /^\w+$/;
            if(!regExName.test(username)) { 
                alert("Username must contain only letters, numbers and underscores. Please try again"); 
                $("#signup-form #username").focus();
                return false; 
            }
            // check firstname
            if(!regExName.test(firstname)) { 
                alert("Firstname must contain only letters, numbers and underscores. Please try again"); 
                $("#signup-form #firstname").focus();
                return false; 
            }
            // check lastname
            if(!regExName.test(lastname)) { 
                alert("Lastname must contain only letters, numbers and underscores. Please try again"); 
                $("#signup-form #lastname").focus();
                return false; 
            }
            // check password
            if (signUpPassword.length < 6) {
                alert('Passwords must be at least 6 characters long.  Please try again');
                $("#signup-form #signup-password").focus();
                return false;
            }
            // At least one number, one lowercase and one uppercase letter 
            // At least six characters 
            var regExPW = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
            if (!regExPW.test(password.value)) {
                alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
                $("#signup-form #signup-password").focus(); 
                return false;
            }
            // Check password and confirmation are the same
            if (signUpPassword != confirmPassword) {
                alert('Your password and confirmation do not match. Please try again');
                form.password.focus();
                $("#signup-form #signup-password").focus();
                $("#signup-form #confirm-password").focus();
                return false;
            }
            // check user title
            if (userTitle.length < 100) {
                alert('User occupation must be less than 100 characters long.  Please try again');
                $("#signup-form user-title").focus();
                return false;
            }
            // check user description
            if (userDescription.length < 500) {
                alert('User description must be less than 500 characters long.  Please try again');
                $("#signup-form user-description").focus();
                return false;
            }
            // check user city 
            if (userCity.length < 100) {
                alert('User city must be less than 100 characters long.  Please try again');
                $("#signup-form user-city").focus();
                return false;
            }
            // check user state
            if (userState.length < 100) {
                alert('User state must be less than 100 characters long.  Please try again');
                $("#signup-form user-state").focus();
                return false;
            }
            return true; 
        }

        // login form validation 
        function loginFormValidation() {
            var username = $("#login-form #username").val();
            var loginPassword = $("#login-form #login-password").val();
            var rememberPassword = $('#login-form #remember-password').is(":checked"); // true or false

            // check username 
            var regExName = /^\w+$/;
            if(!regExName.test(username)) { 
                alert("Username must contain only letters, numbers and underscores. Please try again"); 
                $("#login-form #username").focus();
                return false; 
            }
            // check password
            if (loginPassword.length < 6) {
                alert('Passwords must be at least 6 characters long. Please try again');
                $("#login-form #login-password").focus();
                return false;
            }
            // At least one number, one lowercase and one uppercase letter 
            // At least six characters 
            var regExPW = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
            if (!regExPW.test(loginPassword)) {
                alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
                $("#login-form #login-password").focus(); 
                return false;
            }
            return true; 
        }

        // ajax signup form submission
        $("#signup-form").submit(function(event) {
            alert("signup submit button clicked"); // debug
            if (true) {
                event.preventDefault();
                var signupForm = new FormData(this);
                var signUpPassword = $("#signup-form #signup-password").val();
                var confirmPassword = $("#signup-form #confirm-password").val();

                // Create a new element input, this will be our hashed password field. 
                var p = document.createElement("input");
                // Add the new element to our form. 
                signupForm.appendChild(p);
                p.name = "p";
                p.type = "hidden";
                p.value = hex_sha512(signUpPassword.value);
                // Make sure the plaintext password doesn't get sent. 
                signUpPassword = "";
                confirmPassword = "";

                var signupURL = "https://apiapache-beforeidie.rhcloud.com/api/users/register";
                $.ajax({
                    url: signupURL,
                    type: "POST",
                    data: signupForm, 
                    contentType: false, 
                    processData: false,
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
    }
)