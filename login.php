<?php 
    require_once "connection/config.php";
    include 'connection/validation-process.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - NoteMatrix</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
<div class="tittle">
<h1><span style="color: #333;  font-size: 5rem; color: gray; padding-top: 50px;"><span style="color: #ff4500;">N</span>ote<span style="color: #ff4500;">M</span>atrix</span></h1>
</div>
<div class="content">

    <div class="login-form">
        <h1>Welcome</h1>
        <h5 class="pl">Please Login</h5>
        <form id="loginForm" action="login_process.php" method="post">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <span class="error-message" id="usernameError" style="display: none;">This field is required</span>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <span class="error-message" id="passwordError" style="display: none;">This field is required</span>
            <button type="submit" id="loginButton" class="btn">Login</button>
        </form>
        <div class="registration-link">
            <p>Don't have an account? <a href="register.php" style=" text-decoration: none; color:
            #ffff;">Register here</a></p>
        </div>
    </div>
</div>

<script>
    // Function to validate form fields and display error messages
    function validateForm(event) {
        var usernameField = document.getElementById("username");
        var passwordField = document.getElementById("password");
        var usernameErrorMessage = document.getElementById("usernameError");
        var passwordErrorMessage = document.getElementById("passwordError");

        // Hide error messages initially
        usernameErrorMessage.style.display = "none";
        passwordErrorMessage.style.display = "none";

        // Check if the form is valid
        if (!usernameField.validity.valid || !passwordField.validity.valid) {
            event.preventDefault(); // Prevent form submission if there are errors

            // Show error messages for invalid fields
            if (usernameField.validity.valueMissing) {
                usernameErrorMessage.style.display = "block";
            }
            if (passwordField.validity.valueMissing) {
                passwordErrorMessage.style.display = "block";
            }
        }
    }

    // Add event listener to the login form for form validation
    document.getElementById("loginForm").addEventListener("submit", validateForm);
</script>

</body>
</html>
