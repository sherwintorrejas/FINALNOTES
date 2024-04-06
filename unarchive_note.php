<?php
// Include the database configuration file
include_once 'connection/config.php';

// Check if note_id is set
if(isset($_POST['note_id'])) {
    // Sanitize the input to prevent SQL injection
    $note_id = mysqli_real_escape_string($link, $_POST['note_id']);

    // Perform the unarchiving operation
    $sql = "DELETE FROM archive WHERE note_id = '$note_id'";
    if(mysqli_query($link, $sql)) {
        echo "Note unarchived successfully.";
    } else {
        echo "Error unarchiving note: " . mysqli_error($link);
    }
} else {
    echo "Note ID not received.";
}

// Close database connection
mysqli_close($link);
?>
