<?php
include_once 'connection/config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

// Check if the request method is POST and note_id is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['note_id'])) {
    $note_id = $_POST['note_id'];

    // Perform the deletion query for each relevant table
    $tables = array("deletednotes", "notes", "archive"); // Add more tables if necessary
    $success = true;

    foreach ($tables as $table) {
        $sql_delete = "DELETE FROM $table WHERE note_id = $note_id";
        $result_delete = mysqli_query($link, $sql_delete);

        if (!$result_delete) {
            // Deletion failed for this table
            $success = false;
            $error_message = mysqli_error($link);
            break; // Exit the loop on first failure
        }
    }

    if ($success) {
        // Deletion successful
        echo json_encode(array("success" => true));
        exit();
    } else {
        // Deletion failed
        echo json_encode(array("success" => false, "error" => $error_message));
        exit();
    }
}

// If the request is invalid, return an error
echo json_encode(array("success" => false, "error" => "Invalid request."));
exit();
?>
