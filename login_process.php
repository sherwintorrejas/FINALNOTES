<?php
// Include the database connection
include 'connection/config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Check if fields are empty
    if(empty($username) || empty($password)) {
        // Fields are empty, set error message for display on login.php
        session_start();
        $_SESSION['error_message'] = "Please enter username and password.";
        header("location: login.php");
        exit();
    } else {
        // Query to retrieve encrypted password for the given username
        $sql = "SELECT user_id, password FROM users WHERE username = ?";
        
        // Prepare and execute the statement
        if ($stmt = $link->prepare($sql)) {
            // Bind parameters to the prepared statement
            $stmt->bind_param("s", $username);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store the result
                $stmt->store_result();
                
                // Check if a row was returned
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($user_id, $hashed_password);
                    
                    // Fetch the result
                    $stmt->fetch();
                    
                    // Verify password
                    if (password_verify($password, $hashed_password)) {
                        // Password is correct, start session and redirect to home.php
                        session_start();
                        $_SESSION['user_id'] = $user_id;
                        header("location: home.php");
                        exit(); // Make sure to exit after redirecting
                    } else {
                        // Password is incorrect, set error message for display on login.php
                        session_start();
                        $_SESSION['error_message'] = "Invalid username or password.";
                        header("location: login.php");
                        exit();
                    }
                } else {
                    // No user found with the given username, set error message for display on login.php
                    session_start();
                    $_SESSION['error_message'] = "Invalid username or password.";
                    header("location: login.php");
                    exit();
                }
            } else {
                // Error handling
                echo "Error executing the statement: " . $stmt->error;
            }
        } else {
            // Error handling
            echo "Error preparing statement: " . $link->error;
        }

        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $link->close();
}
?>
