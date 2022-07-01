<?php

    ob_start();
    session_start();
    // print_r($_SESSION);      // used to see all sessions

    $noNavbar = "";
    $pageTitle = "login";

    if(isset($_SESSION["Username"])) {

        header("location: dashboard.php");     // i have to enable it at end
    }

    include "initialize.php";

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = $_POST["user"];
        $password = $_POST["pass"];
        $encryptedpass = sha1($password);

        // echo $username . " - " . $encryptedpass;
        
        $stmt = $db->prepare("SELECT 
                                  UserID, Username, Password 
                            FROM
                                  users 
                            WHERE 
                                  Username = ? 
                            AND
                                  Password = ? 
                            AND 
                                  GroupID = 1 
                            LIMIT
                                  1");
        $stmt->execute(array($username, $encryptedpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        // echo $count;
        if($count > 0) {

            // echo "welcome " . $username;
            $_SESSION["Username"] = $username;
            $_SESSION["ID"] = $row["UserID"];
            header("location: dashboard.php");  // will go to this page if he is true
            exit();
        }

    }
?>

    <form class="login" action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">
        <h3 class="text-center">Admin Form</h3>
        <input class="form-control" type="text" name="user" placeholder="username" autocomplete="off">
        <input class="form-control" type="password" name="pass" placeholder="password" autocomplete="off">
        <input class="btn btn-primary btn-block" type="submit" value="login">
    </form>

    <!-- <?php
        echo language('message') . " " . language('admin'); 
    ?> -->

<?php
    include $temp_file . "footer.php";
    ob_end_flush();
?>