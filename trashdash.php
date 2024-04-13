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
    <title>Home Page</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/tooltip.css">
    <script src="js/functions.js"></script>
</head>
<body>
<?php include 'bars/sidebar.php'; ?>

<div class="content">
    <?php include 'bars/search.php'?>

    <h1>TRASH</h1>
    <?php
    $sql = "SELECT dn.deleted_note_id, dn.note_id, dn.deleted_at, dn.scheduled_permanent_deletion,
                n.title, n.text, n.created_at, n.updated_at
            FROM deletednotes dn
            JOIN notes n ON dn.note_id = n.note_id
            WHERE user_id = $user_id";
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
            $scheduled_deletion = new DateTime($row['scheduled_permanent_deletion']);
            $current_date = new DateTime();
            $difference = $current_date->diff($scheduled_deletion)->days;

            if ($i % 4 == 0 && $i != 0) {
                echo '</div><div class="row">';
            }

            echo "<div class='card' id='note_" . $row['note_id'] . "'>";
echo "<h2>" . $row['title'] . "</h2>";
echo "<div class='card-content'>" . (strlen($row['text']) > 60 ? substr($row['text'], 0, 60) . "..." : $row['text']) . "</div>";
echo "<div class='card-actions'>";
echo "<div class='tooltip-container'>";
echo "<img src='icons/mark.png' alt='Tooltip' class='tooltip-icon'>";
echo "<span class='tooltip'>" . $difference . " days until permanent deletion</span>";
echo "</div>";
echo "<div class='dropdown'>";
echo "<div class='dropdown-toggle' onclick='toggleDropdown(this)'><img src='icons/more.png' alt='Dropdown'></div>";
echo "<div class='dropdown-menu'>";
echo "<div class='dropdown-menu-item' onclick='confirmDelete(" . $row['note_id'] . ")'>Delete</div>";
echo "<div class='dropdown-menu-item' onclick='restoreNote(". $row['note_id'] . ")'>Restore</div>";
echo "<div class='dropdown-menu-item' onclick='viewNote(" . $row['note_id'] . ")'>View</div>";
echo "</div>";
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
