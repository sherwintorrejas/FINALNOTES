<?php
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file was uploaded without errors
    if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] == 0) {
        // Include database connection
        include_once 'connection/config.php';

        // Validate user ID
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['upload_error'] = "User not logged in.";
            header("Location: profile.php");
            exit();
        }

        $user_id = $_SESSION['user_id'];
        
        $target_dir = "profile/"; // Directory where the file will be saved
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]); // Path of the file to be saved

        // Check file size (optional)
        if ($_FILES["profile_image"]["size"] > 500000) {
            $_SESSION['upload_error'] = "Sorry, your file is too large.";
            header("Location: profile.php");
            exit();
        }
        
        // Check if file already exists (optional)
        if (file_exists($target_file)) {
            $_SESSION['upload_error'] = "Sorry, file already exists.";
            header("Location: profile.php");
            exit();
        }
        
        // Check if file has an allowed file type (optional)
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_types)) {
            $_SESSION['upload_error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            header("Location: profile.php");
            exit();
        }

        // Get the old profile image path from the database
        $old_profile_image_path = '';
        $sql = "SELECT profile_image FROM users WHERE user_id = ?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_bind_result($stmt, $old_profile_image_path);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
        }

        // If an old profile image exists, delete it
        if (!empty($old_profile_image_path)) {
            if (file_exists($old_profile_image_path)) {
                unlink($old_profile_image_path);
            }
        }
        
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            // Update profile image path in the database
            $profile_image_path = $target_file;
            $sql = "UPDATE users SET profile_image = ? WHERE user_id = ?";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "si", $profile_image_path, $user_id);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['upload_success'] = "Profile image uploaded successfully.";
                header("Location: profile.php");
                exit();
            } else {
                $_SESSION['upload_error'] = "Failed to update profile image in the database: " . mysqli_error($link);
                header("Location: profile.php");
                exit();
            }
        } else {
            $_SESSION['upload_error'] = "Sorry, there was an error uploading your file.";
            header("Location: profile.php");
            exit();
        }
    } else {
        $_SESSION['upload_error'] = "No file uploaded or an error occurred during upload.";
        header("Location: profile.php");
        exit();
    }
} else {
    $_SESSION['upload_error'] = "Access denied.";
    header("Location: profile.php");
    exit();
}
?>
