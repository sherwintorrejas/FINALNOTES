<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - NoteMatrix</title>
    <link rel="stylesheet" href="css/registr.css">
</head>
<body class="loginbody">
<div class="tittle">
<h1><span style="font-size: 5rem; color: white; padding-top: 50px;"><span style="color: #0bffa7;">N</span>ote<span style="color: #0bffa7;">M</span>atrix</span></h1>
</div>
<div class="content">

    <div class="login-form">
        <h1>Welcome</h1>
        <h5 class="pl">Please Login</h5>
        
        <form id="loginForm" action="login_process.php" method="post">
            <input type="text" id="username" name="username" placeholder="Username" >
            <input type="password" id="password" name="password" placeholder="Password" >
            <?php
        session_start();
        if(isset($_SESSION['error_message'])) {
            echo '<div style="color: red;">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>
            <button type="submit" id="loginButton" class="btn">Login</button>

        </form>
        <div class="registration-link">
            <p>Don't have an account? <a href="register.php" style=" text-decoration: none; color:
            #ffff;">Register here</a></p>
        </div>
    </div>
</div>

</body>
</html>
