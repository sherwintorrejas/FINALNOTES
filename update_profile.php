<?php
include_once 'connection/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];

    // You might want to perform validation and sanitization of the data here

    // Update user's profile in the database
    $query = "UPDATE users SET email = ?, first_name = ?, last_name = ?, gender = ?, birthdate = ? WHERE user_id = ?";
    $stmt = $link->prepare($query);
    $stmt->bind_param("sssssi", $email, $first_name, $last_name, $gender, $birthdate, $user_id);
    
    // Assuming $first_name and $last_name are derived from $name
    list($first_name, $last_name) = explode(' ', $name);

    if ($stmt->execute()) {
        // Profile updated successfully
        header("Location: profile.php");
        exit();
    } else {
        // Error occurred while updating profile
        echo "Error updating profile: " . $stmt->error;
    }
} else {
    // If the request method is not POST, redirect to the profile page
    header("Location: profile.php");
    exit();
}
?>
