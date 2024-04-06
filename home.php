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
   
    <style>
        body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    position: relative; /* Set position relative for absolute positioning */
    background-color: #A8CD9F;
}

.content {
    margin-left: 250px;
    padding: 20px;
    position: relative; /* Set position relative for absolute positioning */

}

/* Add Note Popup */
.popup {
    display: none; /* Hide popup by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
}

.popup-content h2 {
    margin-bottom: 10px;
}

.popup-content input[type="text"],
.popup-content textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

.popup-content textarea {
    resize: none;
    height: 150px;
}

.popup-content button {
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 2px;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
    transition: .4s;
    background-color: #00BFA6;
}


.popup-content {
    background-color: #fefefe;
    margin: 10% auto; /* Center popup vertically and horizontally */
    padding: 20px;
    border-radius: 5px;
    width: 50%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* Search area */
.search {
    display: flex;
    align-items: center;
    justify-content: flex-end; /* Align search area to the right */
    margin-bottom: 20px; /* Add some spacing between search and content */
}

.group {
    display: flex;
    align-items: center;
    position: relative;
    margin-right: 20px; /* Add some spacing between search input and plus icon */
}

.input {
    height: 40px;
    line-height: 28px;
    padding: 0 1rem;
    width: 100%;
    padding-left: 2.5rem;
    border: 2px solid transparent;
    border-radius: 8px;
    outline: none;
    background-color: #D9E8D8;
    color: #0d0c22;
    box-shadow: 0 0 5px #C1D9BF, 0 0 0 10px #f5f5f5eb;
    transition: .3s ease;
    border: none; /* Remove border */
    outline: none;
}

.input::placeholder {
    color: #777;
}

.icon {
    position: absolute;
    left: 1rem;
    fill: #777;
    width: 1rem;
    height: 1rem;
}

/* Plus icon */
.add-note-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
}

.add-note-icon img {
    width: 50px; /* Adjust the width as needed */
    height: auto; /* Maintain aspect ratio */
    cursor: pointer;
}

.row {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Adjust minmax values and other properties as needed */
    grid-gap: 20px; /* Adjust the gap between grid items */
    padding-left: 20px;
}


.card {
    width: 200px; /* Adjust width as needed */
    height: 180px; /* Adjust height as needed */
    margin-bottom: 20px; /* Add margin below each row */
    padding: 10px;
    border-radius: 10%;
    overflow: hidden; /* Hide overflow */
    position: relative; /* Add relative positioning */
    transition: box-shadow 0.3s ease; /* Add transition for smooth hover effect */
    background-color: #58A399;
}

.card:hover {
    box-shadow: 0px 0px 10px  rgba(0, 0, 0, 0.2); /* Add box-shadow effect on hover */
}

.card-content {
    max-height: 70px; /* Set maximum height */
    overflow: hidden; /* Hide overflow */
    text-wrap: pretty;
    transition: max-height 0.3s ease; /* Add smooth transition */
}

.card h2 {
    margin-bottom: 5px; /* Add margin below the title */
    font-size: 15px; /* Adjust font size as needed */
}

.card p {
    font-size: smaller;
    opacity: 0.7;
    position: absolute;
    bottom: 5px; /* Adjust as needed */
    right: 5px; /* Adjust as needed */
}

/* Dropdown */
.dropdown {
    position: absolute; /* Change position to absolute */
    top: 10px; /* Adjust as needed */
    right: 10px; /* Adjust as needed */
    cursor: pointer;
    z-index: 1; /* Add z-index to ensure it appears above other elements */
}

.dropdown-toggle img {
    width: 24px; /* Adjust width as needed */
    height: 24px; /* Adjust height as needed */
}

.dropdown-menu {
    display: none;
    position: fixed; /* Change position to fixed */
    background-color: #f9f9f9;
    min-width: 120px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 2; /* Set z-index higher than the cards */
}

.dropdown-menu.active {
    display: block;
}

.dropdown-menu-item {
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    cursor: pointer;
}

.dropdown-menu-item:hover {
    background-color:  #f1f1f1;
}

.btn span:last-child {
    display: none;
}

.btn:hover {
    transition: .3s;
    background-color: #fff;
    color: #00BFA6;
}

.btn:active {
    background-color: #87dbd0;
}
    </style>
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
            echo "<div class='dropdown-toggle' onclick='toggleDropdown(this)'><img src='icons/down.png' alt='Dropdown'></div>";
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
        <img src="icons/plus.png" alt="Add Note" id="add-note-button" onclick="showAddNotePopup()">
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

</div>
<script>
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
    }

    function toggleDropdown(element) {
        var dropdownMenu = element.nextElementSibling;
        dropdownMenu.classList.toggle('active');
    }

    // Close dropdowns when clicking outside
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-toggle img')) {
            var dropdowns = document.getElementsByClassName("dropdown-menu");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('active')) {
                    openDropdown.classList.remove('active');
                }
            }
        }
    }

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

    function updateNote() {
        var id = document.getElementById("popup-text").getAttribute('data-id');
        var text = document.getElementById("popup-text").value;
        var title = document.getElementById("popup-title-input").value;

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                closePopup();
                location.reload();
            }
        };
        xhr.open("POST", "update_note.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("id=" + id + "&title=" + title + "&text=" + text);
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

</body>
</html>


