<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    <style>
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            background-color: #00565c;
        }

        .profile {
            text-align: center;
            padding: 20px 20px 20px 20px;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 20px auto;
        }

        .profile img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: solid 2px;
            border-color: white;
        }

        .profile a {
            text-decoration: none;
            color: black;
            
        }

        .profile h2 {
            color: white;
            margin: 5px;
            text-decoration: none;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            text-align: left;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 10px 20px;
        }

        .nav-link img {
            height: 20px;
            width: auto;
            margin-right: 10px;
            margin-left: 50px;
        }

        .nav-link span {
            line-height: 20px;
            margin-left: 4px;
            margin-right: 98px;
        }

        .nav-logout {
            color: white;
            text-decoration: none;
            text-align: left;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 10px 20px;
        }

        .nav-logout img {
            height: 20px;
            width: auto;
            margin-right: 10px;
            margin-left: 50px;
        }

        .nav-logout span {
            line-height: 20px;
            margin-left: 4px;
            margin-right: 98px;
        }
        .nav-logout:hover {
             background-color: #06bac6; /* Change background color on hover */
         }
        /* Hover effect for nav links */
        .nav-link:hover{
            background-color: #06bac6; /* Change background color on hover */
        }

        /* Active link styling */
        .active-link {
            color: black; /* Change active link color */
            background-color:  #06bac6; /* Change active link background color */
        }
    </style>
</head>
<body>
<?php
    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        // Get user ID from session
        $user_id = $_SESSION['user_id'];

        // Fetch user details from database
        $sql = "SELECT first_name, last_name, profile_image FROM users WHERE user_id = $user_id";
        $result = mysqli_query($link, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $full_name = $row["first_name"] . ' ' . $row["last_name"];
            $profile_image_filename = $row["profile_image"];

            // Construct full file path for profile image
            $profile_image_path = $profile_image_filename;

            // Check if file exists
            if (file_exists($profile_image_path)) {
                // Display profile image and name
                echo '<div class="sidenav">';
                echo '<div class="profile">';
                echo '<a href="profile.php">';
                echo '<img src="' . $profile_image_path . '" alt="Profile Picture">';
                echo '<h2>' . $full_name . '</h2>';
                echo '</div>';
            } else {
                // Profile image not found, display default profile image
                echo '<div class="sidenav">';
                echo '<div class="profile">';
                echo '<a href="profile.php">';
                echo '<img src="profile/defaultprofile.png" alt="Default Profile Picture">';
                echo '<h2>' . $full_name . '</h2>';
                echo '</div>';
            }
        } else {
            // No user found with the given ID or query failed
            echo '<div class="sidenav">';
            echo '<div class="profile">';
            echo '<a href="profile.php">';
            echo '<img src="profile/defaultprofile.png" alt="Default Profile Picture">';
            echo '<h2>Welcome</h2>';
            echo '</div>';
        }
    } else {
        // No user logged in, display default profile and "Welcome"
        echo '<div class="sidenav">';
        echo '<div class="profile">';
        echo '<a href="profile.php">';
        echo '<img src="profile/defaultprofile.png" alt="Default Profile Picture">';
        echo '<h2>Welcome</h2>';
        echo '</div>';
    }

    // Get current page filename
    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Add Home, and Archive links with icons -->
<a href="home.php" class="nav-link <?php if($currentPage == 'home.php') echo 'active-link'; ?>">
    <img src="icons/home.png" alt="Home Icon">
    <span>Home</span>
</a>
<a href="archive.php" class="nav-link <?php if($currentPage == 'archive.php') echo 'active-link'; ?>">
    <img src="icons/arch.png" alt="Archive Icon">
    <span>Archive</span>
</a>
<a href="trashdash.php" class="nav-link <?php if($currentPage == 'trashdash.php') echo 'active-link'; ?>">
    <img src="icons/trash.png" alt="Trash Icon">
    <span>Trash</span>
</a>
<!-- Logout link -->
<a href="#" class="nav-logout" onclick="return confirmLogout();">
    <img src="icons/lgout.png" alt="Logout Icon">
    <span>Logout</span>
</a>

<script>
    // JavaScript function to show logout confirmation dialog
    function confirmLogout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "logout.php"; // Redirect to logout.php if user confirms
            return true;
        } else {
            return false; // Do nothing if user cancels
        }
    }
</script>
</div>
</body>
</html>
