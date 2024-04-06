
    <style>
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            background-image: linear-gradient(to top, #005bea 0%, #005bea 100%);
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
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .profile a {
            text-decoration: none;
            color: white;
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
            display: block;
            text-align: left;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
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
            display: block;
            text-align: left;
            margin-top: 50px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
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

        .nav-link:hover {
            background-position: right center;
            background-size: 200% auto;
            -webkit-animation: pulse 2s infinite;
            animation: pulse512 1.5s infinite;
        }

        .nav-logout:hover {
            background-position: right center;
            background-size: 200% auto;
            -webkit-animation: pulse 2s infinite;
            animation: pulse512 1.5s infinite;
        }

        @keyframes pulse512 {
            0% {
                box-shadow: 0 0 0 0 black;
            }

            70% {
                box-shadow: 0 0 0 10px rgb(218 103 68 / 0%);
            }

            100% {
                box-shadow: 0 0 0 0 rgb(218 103 68 / 0%);
            }
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
?>


<!-- Add Home, and Archive links with icons -->
<a href="home.php" class="nav-link">
    <img src="icons/home.png" alt="Home Icon">
    <span>Home</span>
</a>
<a href="archive.php" class="nav-link">
    <img src="icons/archive.png" alt="Archive Icon">
    <span>Archive</span>
</a>
<a href="trashdash.php" class="nav-link">
    <img src="icons/trash.png" alt="Trash Icon">
    <span>Trash</span>
</a>
<!-- Logout link -->
<a href="login.php" class="nav-logout">
    <img src="icons/logout.png" alt="Logout Icon">
    <span>Logout</span>
</a>
</div>
