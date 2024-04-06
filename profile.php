<?php
include_once 'connection/config.php';
include 'bars/time.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's information from the database
$query = "SELECT first_name, last_name, email, gender, birthdate, profile_image FROM users WHERE user_id = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $full_name = $row["first_name"] . ' ' . $row["last_name"];
    $email = $row["email"];
    $gender = $row["gender"];
    $birthdate = $row["birthdate"];
    $profile_image_filename = $row["profile_image"];

    // Calculate age based on birthdate
    $dob = new DateTime($birthdate);
    $now = new DateTime();
    $age = $now->diff($dob)->y;

    // Construct full file path for profile image
    $profile_image_path = isset($profile_image_filename) ? 'profile/' . $profile_image_filename : 'path_to_default_image/default_profile_image.jpg';
} else {
    // No user found with the given ID or query failed
    $full_name = "Welcome";
    $email = "";
    $gender = "";
    $birthdate = "";
    $age = "";
    $profile_image_path = 'path_to_default_image/default_profile_image.jpg';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/profile.css">
    >
</head>
<body>
    <?php include 'bars/sidebar.php'?>
    <h1>PROFILE</h1>
    <div class="content">

    <div class="profile-container">
        <!-- Display profile image -->
        <img class="profile-img" src="<?php echo $profile_image_path; ?>" alt="Profile Picture" onclick="openImageUploadModal()">
        <!-- Display user information -->
        <div class="profile-details">
            <p>Name:       <?php echo $full_name; ?></p>
            <p>Email:      <?php echo $email; ?></p>
            <p>Gender:     <?php echo $gender; ?></p>
            <p>Birthdate:  <?php echo $birthdate; ?></p>
            <p>      Age:  <?php echo $age; ?></p>
            <button class="btn" onclick="openEditProfileModal()">Edit Profile</button>
        </div>
    </div>
</div>

<!-- The Edit Profile Modal -->
<div id="editProfileModal" class="modal">

  <!-- Modal content -->
  <div class="modal-infocontent">
    <span class="close" onclick="closeEditProfileModal()">&times;</span>
    <form class="edit-profile-form" method="POST" action="update_profile.php">
        <label for="name">Fullname:</label>
        <input type="text" name="name" placeholder="Name" value="<?php echo $full_name; ?>"><br>
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>"><br>
        <label for="gender">Gender:</label>
        <input type="text" name="gender" placeholder="Gender" value="<?php echo $gender; ?>"><br>
        <label for="birthdate">Birthdate:</label>
        <input type="date" name="birthdate" value="<?php echo $birthdate; ?>"><br>
        <label for="age">Age:</label>
        <input type="text" name="age" placeholder="Age" value="<?php echo $age; ?>"><br>
        <button class="btn-up" type="submit">Save </button>
    </form>
  </div>
</div>

<div id="imageUploadModal" class="modal">
  <!-- Modal content -->
  <div class="modal-imgecontent">
    <span class="close" onclick="closeImageUploadModal()">&times;</span>
    <form class="edit-profile-form" method="POST" action="upload_profile_image.php" enctype="multipart/form-data">
        <label for="profile_image" class="custom-file-upload">
            <img src="icons/upload.png" alt="Upload icon" class="upload-icon">
            <span id="file-name" class="file-name">No file chosen</span>
            <input id="profile_image" type="file" name="profile_image" accept="image/*" onchange="displayFileName(this)" style="display: none;">
        </label>
        <br><br>
        <button class="btn-up" type="submit">Upload</button>
    </form>
  </div>
</div>



<script>
    function openEditProfileModal() {
        var modal = document.getElementById("editProfileModal");
        modal.style.display = "block";
    }

    function closeEditProfileModal() {
        var modal = document.getElementById("editProfileModal");
        modal.style.display = "none";
    }

    function openImageUploadModal() {
        var modal = document.getElementById("imageUploadModal");
        modal.style.display = "block";
    }

    function closeImageUploadModal() {
        var modal = document.getElementById("imageUploadModal");
        modal.style.display = "none";

    }

    function displayFileName(input) {
        const fileNameSpan = document.getElementById('file-name');
        if (input.files && input.files.length > 0) {
            fileNameSpan.textContent = input.files[0].name;
        } else {
            fileNameSpan.textContent = '';
        }
    }

</script>

</body>
</html>