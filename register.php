<?php
// Include config file
require_once "connection/config.php";

// Define variables and initialize with empty values
include 'connection/validation-process.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/registr.css">
   
</head>

<body>
<div class="tittle">
<h1><span style="color: #333;  font-size: 5rem; color: white;"><span style="color: #0bffa7;">N</span>ote<span style="color: #0bffa7;">M</span>atrix</span></h1>
</div>
    <div class="container">
        <h2>Register</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" placeholder="Username">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <input type="email" id="email" name="email" value="<?php echo $email; ?>"  placeholder="Email">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">

                <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>"  placeholder="First Name">
                <span class="help-block"><?php echo $first_name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>"  placeholder="Last Name">
                <span class="help-block"><?php echo $last_name_err; ?></span>
            </div>
            <div class="form-group-container">
                <div class="form-group <?php echo (!empty($birthdate_err)) ? 'has-error' : ''; ?>">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" value="<?php echo $birthdate; ?>" >
                    <span class="help-block"><?php echo $birthdate_err; ?></span>
                </div>
                <div class="form-group <?php echo (!empty($gender_err)) ? 'has-error' : ''; ?>">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" >
                        <option value="">Select your gender</option>
                        <option value="Male" <?php if ($gender === "Male") echo "selected"; ?>>Male</option>
                        <option value="Female" <?php if ($gender === "Female") echo "selected"; ?>>Female</option>
                        <option value="Other" <?php if ($gender === "Other") echo "selected"; ?>>Other</option>
                    </select>
                    <span class="help-block"><?php echo $gender_err; ?></span>
                </div>
            </div>

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <input type="password" id="password" name="password" value="<?php echo $password; ?>" placeholder="Password">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirm_password; ?>" placeholder="Confirm Password" >
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($profile_image_err)) ? 'has-error' : ''; ?>">
                <div class="custom-file-upload">
                    <img src="icons/upload.png" alt="Upload icon" class="upload-icon">
                    <span id="file-name" class="file-name">Choose Profile Image</span>
                    <input id="profile_image" type="file" name="profile_image" accept="image/*" onchange="displayFileName(this)" style="display: none;">
                </div>
                <span class="help-block"><?php echo $profile_image_err; ?></span>
            </div>

            <button type="submit" class="btn">Register</button>
        </form>
        <div class="register-link">
            <span>Already have an account? </span><a href="login.php">Login</a>
        </div>
    </div>
</body>
</html>
