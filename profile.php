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
    <style>
        /* Preserve the existing CSS styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding-top: 75px;
            padding-left: 130px;
            justify-content: center;
            align-items: center;
            /* min-height: 100vh; */
            position: relative; /* Set position relative for absolute positioning */
            background-image: radial-gradient(circle, #1a7d9f, #006574, #004c4c, #023329, #021c0c);
        }

        .content {
            max-width: 800px;
            height: 380px;
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow:  5px 5px 10px #bebebe,
                -5px -5px 10px #bebebe;
        }

        .profile-container {
            text-align: center;
            padding: 20px;
            display: flex;
            justify-content: space-evenly;
        }

        .profile-img {
            width: 250px;
            height: 250px;
            float: left;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            cursor: pointer; /* Add cursor pointer to indicate clickable */
        }
        
        .profile-details p {
            margin: 10px 0;
            text-align: justify;
            font-weight: bold;
            color: #333;
            font-size: 18px;
        } 

        .edit-profile-form {
            margin-top: 20px;
        }

        .edit-profile-form input {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

       

        .modal {
            display: none; 
            position: fixed; 
            align-items: center;
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 5px;
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        
        .btn {
            width: 100%;
            padding: 14px 40px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            border-radius: 10px;
            border: 2px dashed #00BFA6;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
            transition: .4s;
            background-color: #00BFA6;
        }
        .btn span:last-child {
            display: none;
        }

        .btn:hover {
            transition: .4s;
            border: 2px dashed #00BFA6;
            background-color: #fff;
            color: #00BFA6;
        }

        .btn:active {
            background-color: #87dbd0;
        }
        .btn-up{
            width: 10%;
            padding: 14px 10px;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            border-radius: 10px;
            border: 2px dashed #00BFA6;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
            transition: .4s;
            background-color: #00BFA6;
        }

        .btn-up span:last-child {
            display: none;
        }

        .btn-up:hover {
            transition: .4s;
            border: 2px dashed #00BFA6;
            background-color: #fff;
            color: #00BFA6;
        }

        .btn-up:active {
            background-color: #87dbd0;
        }
    </style>
</head>
<body>
    <?php include 'bars/sidebar.php'?>
    <div class="content">
    <h1>Profile</h1>
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
  <div class="modal-content">
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

<!-- The Image Upload Modal -->
<div id="imageUploadModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close" onclick="closeImageUploadModal()">&times;</span>
    <form class="edit-profile-form" method="POST" action="upload_profile_image.php" enctype="multipart/form-data">
        <input type="file" name="profile_image" accept="image/*"><br>
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
</script>

</body>
</html>