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

</head>
<body>
<?php include 'bars/sidebar.php'; ?>


<div class="content">
    <?php include 'bars/search.php'?>

    <h1>DASHBOARD</h1>
    <?php
    $sql = "SELECT * FROM notes 
            WHERE user_id = $user_id 
            AND note_id NOT IN (SELECT note_id FROM archive) 
            AND note_id NOT IN (SELECT note_id FROM deletednotes)";
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
            echo "<div class='dropdown-menu-item' onclick='showPopup(" . $row['note_id'] . ", \"" . $row['title'] . "\", \"" . $row['text'] . "\")'>Edit</div>";
            echo "<div class='dropdown-menu-item' onclick='archiveNote(" . $row['note_id'] . ")'>Archive</div>";
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

    <div class="add-note-icon">
        <img src="icons/add.png" alt="Add Note" id="add-note-button" onclick="showAddNotePopup()">
    </div>

    <div class="popup" id="add-note-popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2>Add Note</h2>
            <form id="add-note-form">
                <input type="text" name="title" placeholder="Title" required oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)">
                <textarea name="text" placeholder="Enter your note here" required oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)" ></textarea>
                <button type="submit" class= "btn">SAVE</button>
            </form>
        </div>
    </div>

    <div class="popup" id="note-popup">
        <div class="popup-content">
            <span class="close" onclick="closePopup()">&times;</span>
            <h2 id="popup-title"></h2>
            <input type="text" id="popup-title-input" placeholder="Enter updated title" oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)">
            <textarea id="popup-text" oninput="this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1)"></textarea>
            <button onclick="updateNote()" class= "btn">SAVE</button>
        </div>
    </div>
    <div class="popup" id="view-popup">
        <div class="popup-content">
            <span class="close" onclick="closeViewPopup()">&times;</span>
            <h2 id="view-popup-title"></h2>
            <p id="view-popup-text"></p>
        </div>
    </div>
<script>
    document.getElementById("add-note-form").addEventListener("submit", function(event) {
    event.preventDefault();
    var form = this;
    var formData = new FormData(form);
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            closePopup();
            location.reload();
        }
    };
    xhr.open("POST", "add_note.php", true);
    xhr.send(formData);
});


var activeDropdown = null;

function closeDropdowns() {
    var dropdowns = document.querySelectorAll(".dropdown-menu.active");
    dropdowns.forEach(function(dropdown) {
        dropdown.classList.remove('active');
    });
}

function toggleDropdown(element) {
    var dropdownMenu = element.nextElementSibling;
    if (dropdownMenu !== activeDropdown) {
        closeDropdowns();
        dropdownMenu.classList.add('active');
        activeDropdown = dropdownMenu;
    } else {
        dropdownMenu.classList.remove('active');
        activeDropdown = null;
    }
}

// Close dropdowns when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.dropdown-toggle img')) {
        closeDropdowns();
        activeDropdown = null;
    }
}
function showPopup(id, title, text) {
    document.getElementById("popup-title").textContent = title;
    document.getElementById("popup-text").value = text;
    document.getElementById("popup-title-input").value = title;
    document.getElementById("popup-text").setAttribute('data-id', id);
    document.getElementById("note-popup").style.display = "block";
}

function showAddNotePopup() {
    document.getElementById("add-note-popup").style.display = "block";
}

function closePopup() {
    document.getElementById("add-note-popup").style.display = "none";
    document.getElementById("note-popup").style.display = "none";
    closeDropdowns(); // Close dropdowns when closing the popup
}


function updateNote() {
    var noteId = document.getElementById("popup-text").getAttribute('data-id');
    var updatedTitle = document.getElementById("popup-title-input").value;
    var updatedText = document.getElementById("popup-text").value;

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Optionally, you can handle the response here if needed
            closePopup(); // Close the popup after successful update
            location.reload(); // Reload the page to reflect changes
        }
    };
    xhr.open("POST", "update_note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("note_id=" + noteId + "&title=" + encodeURIComponent(updatedTitle) + "&text=" + encodeURIComponent(updatedText));
}


function archiveNote(noteId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            closePopup();
            location.reload();
        }
    };
    xhr.open("POST", "archive_note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("note_id=" + noteId);
}

// Function to adjust the number of cards per row dynamically
function adjustCardsPerRow() {
    var screenWidth = window.innerWidth;
    var cardRow = document.getElementById('cardRow');
    var cardWidth = 220; // Adjust this value based on your card width
    var numCardsPerRow = Math.floor(screenWidth / cardWidth);
    if (numCardsPerRow < 1) {
        numCardsPerRow = 1; // Ensure at least one card per row
    }
    cardRow.style.justifyContent = 'space-evenly'; // Or any other preferred alignment
    cardRow.style.flexWrap = 'wrap';
    cardRow.style.marginBottom = '20px'; // Adjust margin as needed
    cardRow.innerHTML += '<div class="spacer" style="flex-basis: ' + (screenWidth - (numCardsPerRow * cardWidth)) / (numCardsPerRow * 2) + 'px"></div>'.repeat(numCardsPerRow - 1);
}

// Call the function initially and on window resize
adjustCardsPerRow();
window.addEventListener('resize', adjustCardsPerRow);

function viewNote(noteId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var data = JSON.parse(xhr.responseText);
            showViewPopup(data.title, data.text);
        }
    };
    xhr.open("POST", "view_note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("note_id=" + noteId);
}

function showViewPopup(title, text) {
    document.getElementById("view-popup-title").textContent = title;
    document.getElementById("view-popup-text").textContent = text;
    document.getElementById("view-popup").style.display = "block";
}

function closeViewPopup() {
    document.getElementById("view-popup").style.display = "none";
}

function trashNote(noteId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            closePopup();
            location.reload();
        }
    };
    xhr.open("POST", "trash_note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("note_id=" + noteId);
}

</script>
</div>
</body>
</html>
<!-- change-->
