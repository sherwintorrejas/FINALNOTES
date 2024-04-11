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

function closeDropdowns() {
    var dropdowns = document.getElementsByClassName("dropdown-menu");
    for (var i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('active')) {
            openDropdown.classList.remove('active');
        }
    }
}

function toggleDropdown(element) {
    var dropdownMenu = element.nextElementSibling;
    dropdownMenu.classList.toggle('active');
}

// Close dropdowns when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.dropdown-toggle img')) {
        closeDropdowns();
    }
}

// document.getElementById("add-note-form").addEventListener("submit", function(event) {
//     event.preventDefault();
//     var form = this;
//     var formData = new FormData(form);
//     var xhr = new XMLHttpRequest();
//     xhr.onreadystatechange = function() {
//         if (xhr.readyState == 4 && xhr.status == 200) {
//             closePopup();
//             location.reload();
//         }
//     };
//     xhr.open("POST", "add_note.php", true);
//     xhr.send(formData);
// });

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

function unarchiveNote(noteId) {
    // AJAX request to unarchive note
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "unarchive_note.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Reload page after unarchiving note
            window.location.reload();
        }
    };
    xhr.send("note_id=" + noteId);
}

function deleteNote(noteId) {
    // Send AJAX request to delete the note
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // If deletion is successful, reload the page to reflect changes
                location.reload();
            } else {
                // If deletion fails, display an error message
                alert("Failed to delete note: " + response.error);
            }
        }
    };
    xhr.open("POST", "delete_note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("note_id=" + noteId);
}

function restoreNote(noteId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // If restoration is successful, reload the page to reflect changes
                location.reload();
            } else {
                // If restoration fails, display an error message
                alert("Failed to restore note: " + response.error);
            }
        }
    };
    xhr.open("POST", "restore_note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("note_id=" + noteId);
}