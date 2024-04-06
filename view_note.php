<?php
include_once 'connection/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note_id'])) {
    $note_id = $_POST['note_id'];

    $sql = "SELECT title, text FROM notes WHERE note_id = $note_id";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        echo json_encode(array('error' => 'Note not found'));
    }

    mysqli_close($link);
} else {
    echo json_encode(array('error' => 'Invalid request'));
}
?>
