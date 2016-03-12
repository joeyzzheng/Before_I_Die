$(document).ready( function() {
        // create dropdown list
        function createStateList() {
            var listOfUserState = $("#signup-form #state");
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
            $("#signup-form-link").removeClass("signup-active");
            $(this).addClass("login-active");
            e.preventDefault();
        });
        $("#signup-form-link").on("click", function(e) {
            $("#signup-form").delay(100).fadeIn(100);
            $("#login-form").fadeOut(100);
            $("#login-form-link").removeClass("login-active");
            $(this).addClass("signup-active");
            e.preventDefault();
        })
        

        // signup form validation
        function signupFormValidate() {
            // required fields 
            var username = $("#signup-form #username");
            var firstname = $("#signup-form #firstname");
            var lastname = $("#signup-form #lastname");
            var email = $("#signup-form #email");
            var signUpPassword = $("#signup-form #signup-password");
            var confirmPassword = $("#signup-form #confirm-password");
            // optional fields
            var userTitle = $("#signup-form #title");
            var userDescription = $("#signup-form #description");
            var userCity = $("#signup-form #city");
            var userState = $("#signup-form #state");
            // check each required fields has a value
            if (username.val() == '' || firstname.val() == '' || lastname.val() == '' || email.val() == '' || signUpPassword.val() == '' || confirmPassword.val() == '') {
                alert('Please provide all the requested details. Please try again');
                return false;
            }
            // check username
            var regEx = /[a-zA-Z0-9_]+/;
            if(!regEx.test(username.val())) { 
                alert("Username must contain only letters, numbers and underscores. Please try again"); 
                username.focus();
                return false; 
            }
            // check firstname
            if(!/[a-zA-Z]+/.test(firstname.val())) { 
                alert("Firstname must contain only letters, numbers and underscores. Please try again"); 
                firstname.focus();
                return false; 
            }
            // check lastname
            if(!/[a-zA-Z]+/.test(lastname.val())) { 
                alert("Lastname must contain only letters, numbers and underscores. Please try again"); 
                lastname.focus();
                return false; 
            }
            // check password
            if (signUpPassword.val().length < 6) {
                alert('Passwords must be at least 6 characters long.  Please try again');
                signUpPassword.focus();
                return false;
            }
            // At least one number, one lowercase and one uppercase letter 
            // At least six characters 
            var regExPW = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
            if (!regExPW.test(signUpPassword.val())) {
                alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
                signUpPassword.focus(); 
                return false;
            }
            // Check password and confirmation are the same
            if (signUpPassword.val() != confirmPassword.val()) {
                alert('Your password and confirmation do not match. Please try again');
                confirmPassword.focus();
                signUpPassword.focus();
                return false;
            }
            // check user title
            if (userTitle.val() != '') {
                if (userTitle.val().length > 100){
                    alert('User occupation must be less than 100 characters long. Please try again');
                    userTitle.focus();
                    return false;
                }
            }
            // check user description
            if (userDescription.val() != '') {
                if (userDescription.val().length > 500) {
                    alert('User description must be less than 500 characters long. Please try again');
                    userDescription.focus();
                    return false;
                }
            }
            // check user city 
            if (userCity.val().length > 100 || (!/[a-zA-Z\s]+/.test(userCity))) {
                alert('User city must be less than 100 characters long. Please try again');
                userCity.focus();
                return false;
            }
            // check user state
            if (userState.val().length > 100 || (!/[a-zA-Z\s]+/.test(userState))) {
                alert('User state must be less than 100 characters long. Please try again');
                userState.focus();
                return false;
            }
            return true; 
        }

        // login form validation 
        function loginFormValidation() {
            var username = $("#login-form #username");
            var loginPassword = $("#login-form #login-password");
            var rememberPassword = $("#login-form #remember-password");
            // check username 
            var regEx = /[a-zA-Z0-9_]+/;
            if(!regEx.test(username.val())) { 
                alert("Username must contain only letters, numbers and underscores. Please try again"); 
                username.focus();
                return false; 
            }
            // check password
            if (loginPassword.val().length < 6) {
                alert('Passwords must be at least 6 characters long. Please try again');
                loginPassword.focus();
                return false;
            }
            // At least one number, one lowercase and one uppercase letter 
            // At least six characters 
            var regExPW = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
            if (!regExPW.test(loginPassword.val())) {
                alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
                loginPassword.focus(); 
                return false;
            }
            // check if the remember me is checked
            if (rememberPassword.is(":checked")){
                // do something
            }
            // check if the forget me link is clicked
            $("#forgot-password").click(function() {
                // do something
            });

            return true; 
        }

        // ajax signup form submission
        $("#signup-form").submit(function(event) {
            if (signupFormValidate()) {
                event.preventDefault();
                var form = $("#signup-form"); 
                var signUpPassword = $("#signup-form #signup-password").val();
                var confirmPassword = $("#signup-form #confirm-password").val();
                // Create a new element input, this will be our hashed password field. 
                var p = document.createElement("input");
                // Add the new element to our form. 
                form.append(p); 
                p.name = "p";
                p.type = "hidden";
                p.value = hex_sha512(signUpPassword);
                // Make sure the plaintext password doesn't get sent. 
                signUpPassword = "";
                confirmPassword = "";
                // collect the form data
                var signupForm = new FormData(this);
                var signupURL = "/api/register";
                $.ajax({
                    url: signupURL,
                    type: "POST",
                    data: signupForm, 
                    dataType: "json", 
                    contentType: false, 
                    processData: false,
                    crossDomain: true,
                    success: function(data, textStatus, jqXHR) {
                        // alert("responsed data:" + JSON.stringify(data));
                        // alert("textStatus:" + textStatus);
                        // alert("jqXHR:" + JSON.stringify(jqXHR));
                        // switch to login form
                        $("#login-form").delay(100).fadeIn(100);
                        $("#signup-form").fadeOut(100);
                        $("#signup-form-link").removeClass("signup-active");
                        $("#login-form-link").addClass("login-active");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("An error occured: " + textStatus + " " + errorThrown);
                    }
                })
            }
        })

        // ajax login form submission 
        $("#login-form").submit(function(event) {
            if (loginFormValidation()) {
                // event.preventDefault();
                var form = $("#login-form"); 
                var loginPassword = $("#login-form #login-password").val();
                // Create a new element input, this will be our hashed password field. 
                var p = document.createElement("input");
                // Add the new element to our form. 
                form.append(p); 
                p.name = "p";
                p.type = "hidden";
                p.value = hex_sha512(loginPassword);
                // Make sure the plaintext password doesn't get sent. 
                loginPassword = "";
                // collect the form data
                // var loginForm = new FormData(this);
                var loginURL = "/api/login";
                // var loginData = $(this).serialize();
                var apiData = {
                    "username": $("#login-form #username").val(),
                    "p": p.value 
                }
                $.ajax({
                    url: loginURL,
                    type: "POST",
                    dataType: "json",
                    data: apiData, 
                    encode: true
                    // success: function(data, textStatus, jqXHR) {
                    //     alert("responsed data:" + JSON.stringify(data));
                    //     alert("textStatus:" + textStatus);
                    //     alert("jqXHR:" + JSON.stringify(jqXHR));
                    // },
                    // error: function(jqXHR, textStatus, errorThrown) {
                    //     alert("An error occured: " + textStatus + " " + errorThrown);
                    // }
                })
                .done(function(data) {
                    //Debug message
                    // alert("Success Message:\n" + JSON.stringify(data)); 
                    // change to the home page
                    if (data.success == "true") {
                        document.cookie = "username" + "=" + $("#login-form #username").val();
                        window.location.assign("/");
                    } else {
                        alert("Invalid username or password!");
                        $("#login-form #username").val("");
                        $("#login-form #login-password").val("");
                        document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC";
                    }
                    
                    // console.log("Cookies Set: " + document.cookie);
                })
                .fail(function(data) {
                    // Debug message
                    alert("Failure Message:\n" + JSON.stringify(data)); 
                })
                event.preventDefault();
            }
        })
    }
)