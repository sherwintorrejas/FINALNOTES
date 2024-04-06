<?php
include_once 'connection/config.php';

// Function to permanently delete note from all related tables
function permanentlyDeleteNote($note_id) {
    global $link;

    // List all tables related to notes that need to be checked for deletion
    $related_tables = array('notes_table1', 'notes_table2', 'notes_table3'); // Add all relevant tables here

    foreach ($related_tables as $table) {
        $sql_delete = "DELETE FROM $table WHERE note_id = ?";
        $stmt_delete = mysqli_prepare($link, $sql_delete);
        mysqli_stmt_bind_param($stmt_delete, "i", $note_id);
        mysqli_stmt_execute($stmt_delete);
        mysqli_stmt_close($stmt_delete);
    }
}

// Check if note_id is provided via POST
if(isset($_POST['note_id'])) {
    $note_id = $_POST['note_id'];

    // Get the current datetime
    $current_datetime = date('Y-m-d H:i:s');

    // Prepare and execute the query to move the note to deletednotes table
    $sql = "INSERT INTO deletednotes (note_id, deleted_at, scheduled_permanent_deletion) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $note_id, $current_datetime, date('Y-m-d H:i:s', strtotime('+30 days')));
    mysqli_stmt_execute($stmt);

    // Check if the query was successful
    if(mysqli_stmt_affected_rows($stmt) > 0) {
        echo "Note moved to trash successfully.";
    } else {
        echo "Error: Unable to move note to trash.";
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);

} else {
    echo "Error: Note ID not provided.";
}

// Now, let's check for notes in deletednotes table that have passed their scheduled permanent deletion time
$sql_check_deleted = "SELECT note_id FROM deletednotes WHERE scheduled_permanent_deletion <= NOW()";
$result_check_deleted = mysqli_query($link, $sql_check_deleted);

if ($result_check_deleted) {
    while ($row = mysqli_fetch_assoc($result_check_deleted)) {
        $note_id_to_delete = $row['note_id'];
        // Perform permanent deletion from all related tables
        permanentlyDeleteNote($note_id_to_delete);
        // Also delete from the deletednotes table
        $sql_delete_deleted = "DELETE FROM deletednotes WHERE note_id = ?";
        $stmt_delete_deleted = mysqli_prepare($link, $sql_delete_deleted);
        mysqli_stmt_bind_param($stmt_delete_deleted, "i", $note_id_to_delete);
        mysqli_stmt_execute($stmt_delete_deleted);
        mysqli_stmt_close($stmt_delete_deleted);
    }
}

?>
