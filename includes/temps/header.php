<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $css_file; ?>all.min.css">
    <link rel="stylesheet" href="<?php echo $css_file; ?>normalize.css">
    <link rel="stylesheet" href="<?php echo $css_file; ?>bootstrap.min.css">
    <!-- online bootstrap  -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="<?php echo $css_file; ?>jquery-ui.css">
    <link rel="stylesheet" href="<?php echo $css_file; ?>jquery.selectBoxIt.css">
    <link rel="stylesheet" href="<?php echo $css_file; ?>bootstrap-tagsinput.css">
    <link rel="stylesheet" href="<?php echo $css_file; ?>frontend.css">
    <title><?php echo getTitle(); ?></title>
</head>

<body>
    <!-- navbar -->

    <div class="upper-bar">
        <div class="container">
            <?php

            if (isset($_SESSION["user"])) { ?>
                <!--// normal user not the admin -->
                <div class="flxcont">
                    <div class="btn-group">
                        <span class="nav-link dropdown-toggle btn btn-outline-dark" href="#" role="button" data-bs-toggle="dropdown">
                            <?php echo $userSession; ?>
                        </span>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">my profile</a></li>
                            <li><a class="dropdown-item" href="newads.php">New Item</a></li>
                            <li><a class="dropdown-item" href="profile.php#my-itms">my items</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                    <a href="profile.php"><img class="userimg" src="user.png" alt="my image"></a>
                </div>

            <?php
                // echo "welcome " . $userSession;   // $userSession = $_SESSION["user"]

                // echo '<a href="profile.php"> my profile</a>';
                // echo " | " . '<a href="newads.php"> New Item</a>';
                // echo " | " . '<a href="profile.php#my-itms"> my items</a>';
                // echo " | " . '<a href="logout.php"> Logout</a>';

                // $userRegstats = checkUserStatus($userSession);

                // if($userRegstats == 1) {

                //     // echo " u r not activated";
                // }
            } else {
            ?>

                <a href="login.php" class="rightlink">
                    <span class="justify-content-end">login/signup</span>
                </a>

            <?php } ?>

        </div>
    </div>

    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
        <div class="container">
            <!-- Brand -->
            <li class="nav-item list-unstyled">
                <a class="navbar-brand nav-link logo" href="index.php">MyStore</a>
            </li>

            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li> -->
                    <?php

                    // new global getall function

                    $categories = getall("*", "categories", "WHERE Parent = 0", "", "ID");

                    // old function

                    // $categories = getcategs();

                    foreach ($categories as $category) {

                        echo '<li class="nav-item">
                                <a class="nav-link" href="categories.php?id=' . $category["ID"] . '">' . $category["Name"] . '</a>
                            </li>';
                    }
                    ?>
                </ul>
            </div>
            <li class="nav-item list-unstyled">
                <!-- <a class="navbar-brand nav-link logo" href="index.php">MyStore</a> -->
                <div id="main" class="nav-link">
                    <label class="switch">
                        <input type="checkbox" onclick="darkLight()" id="checkBox">
                        <span class="slider"></span>
                    </label>
                </div>
            </li>
        </div>
    </nav>