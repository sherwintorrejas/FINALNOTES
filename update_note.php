<?php
// Include the database configuration file
include_once 'connection/config.php';

// Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the note ID, updated title, and updated text
    $note_id = $_POST['id'];
    $title = $_POST['title'];
    $text = $_POST['text'];

    // Prepare update query
    $sql = "UPDATE notes SET title=?, text=? WHERE note_id=?";

    // Prepare and bind parameters
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $title, $text, $note_id);

    // Execute the update query
    if (mysqli_stmt_execute($stmt)) {
        // If update successful, send success response
        echo "Note updated successfully";
    } else {
        // If update failed, send error response
        echo "Error updating note: " . mysqli_error($link);
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close database connection
    mysqli_close($link);
}
?>
