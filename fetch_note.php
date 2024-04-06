<?php
include_once 'connection/config.php';

if(isset($_POST['note_id'])) {
    $note_id = $_POST['note_id'];

    // Fetch note data from the database based on note_id
    $sql = "SELECT * FROM notes WHERE note_id = $note_id";
    $result = mysqli_query($link, $sql);

    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Return note data as JSON response
        echo json_encode($row);
    } else {
        echo json_encode(array("error" => "Note not found"));
    }
} else {
    echo json_encode(array("error" => "Invalid request"));
}
?>
