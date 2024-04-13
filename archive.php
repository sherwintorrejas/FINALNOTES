<?php
include_once 'connection/config.php';
include 'bars/time.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/functions.js"></script>
</head>
<body>
<?php include 'bars/sidebar.php'; ?>

<div class="content">
    <?php include 'bars/search.php'?>

    <h1>ARCHIVE</h1>
    <?php
    // Modified SQL query to fetch archived notes of the logged-in user
    $sql = "SELECT archive.archive_id, archive.note_id, notes.user_id, notes.title, notes.text, notes.updated_at 
    FROM archive
    INNER JOIN notes ON archive.note_id = notes.note_id
    WHERE user_id = $user_id
    AND NOT EXISTS (
        SELECT 1
        FROM deletednotes
        WHERE deletednotes.note_id = archive.note_id
    )"; 


    $result = mysqli_query($link, $sql);

    // Error handling
    if (!$result) {
        echo "Error: " . mysqli_error($link);
        exit(); // Stop script execution if there's an error
    }

    $i = 0;
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="row">';
        while ($row = mysqli_fetch_assoc($result)) {
            if ($i % 4 == 0 && $i != 0) {
                echo '</div><div class="row">';
            }

            echo "<div class='card' id='note_" . $row['note_id'] . "'>";
            echo "<h2>" . $row['title'] . "</h2>";
            echo "<div class='card-content'>" . (strlen($row['text']) > 60 ? substr($row['text'], 0, 60) . "..." : $row['text']) . "</div>";
            echo "<p class='update-time'>" . formatUpdateTime($row['updated_at']) . "</p>";
            echo "<div class='dropdown'>";
            echo "<div class='dropdown-toggle' onclick='toggleDropdown(this)'><img src='icons/more.png' alt='Dropdown'></div>";
            echo "<div class='dropdown-menu'>";
            echo "<div class='dropdown-menu-item' onclick='showPopup(" . $row['note_id'] . ", \"" . addslashes($row['title']) . "\", \"" . addslashes($row['text']) . "\")'>Edit</div>";
 
            echo "<div class='dropdown-menu-item' onclick='unarchiveNote(" . $row['note_id'] . ")'>unarchive</div>";
            echo "<div class='dropdown-menu-item' onclick='trashNote(". $row['note_id'].")'>Trash</div>";
            echo "<div class='dropdown-menu-item' onclick='viewNote(" . $row['note_id'] . ")'>View</div>";

            echo "</div>";
            echo "</div>";
            echo "</div>";

            $i++;
        }
        echo '</div>';
    } else {
        echo "No notes found.";
    } 
    mysqli_close($link);
    ?>

    <div class="popup" id="note-popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2 id="popup-title"></h2>
            <input type="text" id="popup-title-input" placeholder="Enter updated title">
            <textarea id="popup-text"></textarea>
            <button onclick="updateNote()" class="btn">Save</button>
        </div>
    </div>

    <div class="popup" id="view-popup">
        <div class="popup-content">
            <span class="close" onclick="closeViewPopup()">&times;</span>
            <h2 id="view-popup-title"></h2>
            <div id="view-popup-text"></div>
        </div>
    </div>
</body>
</html>


