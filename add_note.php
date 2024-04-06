<?php
// Include the database connection
include 'connection/config.php';

// Start or resume session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST['title'];
    $text = $_POST['text'];

    // Retrieve user_id from session or any other authentication mechanism
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session after login

    // Prepare the SQL statement
    $sql = "INSERT INTO notes (user_id, title, text) VALUES (?, ?, ?)";
    
    // Prepare the SQL statement
    if ($stmt = $link->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("iss", $user_id, $title, $text);
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Note added successfully
            // Redirect to home.php
            header("Location: home.php");
            exit(); // Ensure that no further code is executed after redirection
        } else {
            // Error handling
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $link->close();
}
?>
