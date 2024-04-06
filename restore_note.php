<?php
include_once 'connection/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect if user is not logged in
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Check if note ID is provided
if (!isset($_POST['note_id'])) {
    echo json_encode(array("success" => false, "error" => "Note ID not provided"));
    exit();
}

$note_id = $_POST['note_id'];

// Start transaction
mysqli_autocommit($link, false); // Disable autocommit

// Check if the note exists in the deletednotes table
$check_note_sql = "SELECT * FROM deletednotes WHERE note_id = $note_id";
$check_note_result = mysqli_query($link, $check_note_sql);

if (!$check_note_result) {
    echo json_encode(array("success" => false, "error" => "Failed to check note in deletednotes table: " . mysqli_error($link)));
    mysqli_rollback($link); // Rollback transaction
    exit();
}

if (mysqli_num_rows($check_note_result) == 0) {
    echo json_encode(array("success" => false, "error" => "Note not found in the trash"));
    mysqli_rollback($link); // Rollback transaction
    exit();
}

// Delete the record from the archive table if present
$delete_archive_sql = "DELETE FROM archive WHERE note_id = $note_id";
if (!mysqli_query($link, $delete_archive_sql)) {
    $error_message = "Failed to delete record from archive table: " . mysqli_error($link);
    mysqli_rollback($link); // Rollback transaction
    echo json_encode(array("success" => false, "error" => $error_message));
    exit();
}

// Delete the record from deletednotes table
$delete_deletednotes_sql = "DELETE FROM deletednotes WHERE note_id = $note_id";
if (!mysqli_query($link, $delete_deletednotes_sql)) {
    $error_message = "Failed to delete record from deletednotes table: " . mysqli_error($link);
    mysqli_rollback($link); // Rollback transaction
    echo json_encode(array("success" => false, "error" => $error_message));
    exit();
}

// Commit transaction
mysqli_commit($link);
echo json_encode(array("success" => true));
exit();
?>
