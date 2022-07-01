<?php    

    session_start();

    // print_r($_SESSION);      // used to see all sessions

    $pageTitle = "login";

    if(isset($_SESSION["user"])) {  // normal user not the admin

        header("location: index.php");     // i have to enable it at end
    }

    include "initialize.php";

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST["login"])) {

            $normuser = $_POST["usrname"];
            $password = $_POST["password"];
            
            $encryptedpass = sha1($password);

            // echo $username . " - " . $encryptedpass;
            
            $stmt = $db->prepare("SELECT 
                                    UserID, Username, Password 
                                FROM
                                    users 
                                WHERE 
                                    Username = ? 
                                AND
                                    Password = ? ");
            $stmt->execute(array($normuser, $encryptedpass));
            $normrow = $stmt->fetch();
            $count = $stmt->rowCount();
            // echo $count;
            if($count > 0) {

                // echo "welcome " . $username;
                $_SESSION["user"] = $normuser;
                $_SESSION["normid"] = $normrow["UserID"];
                header("location: index.php");  // will go to this page if he is true
                exit();
            }
        }
        else {
                // filtering the form 

            $formErrors = array();

            $usrname            = $_POST["usrname"];
            $firstpassword      = $_POST["password"];
            $secondpassword     = $_POST["second-password"];
            $mail               = $_POST["email"];

            // filtering the username input

            if(isset($usrname)) {

                $filteredUser = filter_var($usrname, FILTER_SANITIZE_STRING);

                if(strlen($filteredUser) < 4 ) {

                    $formErrors[] = "username must be more than 4 chars";

                }
            }

            // encrypting passswords and check if matched or not 

            if(isset($firstpassword) && isset($secondpassword)) {

                // check if password empty before hashing 

                if(empty($firstpassword)) {

                    $formErrors[] = "password can't be empty";
                }

                // check matching after hashing 

                $shahpass1 = sha1($firstpassword);
                $shahpass2 = sha1($secondpassword);

                if($shahpass1 !== $shahpass2) {

                    $formErrors[] = "password not matched";

                }
            }

            // encrypting passswords and check if matched or not 

            if(isset($mail)) {

                $filteredEmail = filter_var($mail, FILTER_SANITIZE_EMAIL);

                if(filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != TRUE) {

                    $formErrors[] = "not valid email ";

                }
            }

            // uppdate database auto

            if(empty($formErrors)) {

                // check if the user is exists or not

                $check = checkItem("Username", "users", $usrname);

                // check if the email is exists or not

                $check2 = checkItem("Email", "users", $mail);
                
                if($check == 1) {

                    $formErrors[] = "this name is already exists";
                }
                
                elseif($check2 == 1) {
                
                    $formErrors[] = "this email is already exists use another mail";
                }
                
                else {
                

                    // insert user data in the database

                    $stmt = $db->prepare("INSERT INTO users( Username, Password, Email, RegisterStatus, Date) 

                                            VALUES(:user, :pass, :mail, 0, now() ) ");

                    $stmt->execute(array(

                        "user"     => $usrname, 
                        "pass"     => $shahpass1,
                        "mail"     => $mail

                        ));

                    $msgsucces = "Sign up done Successfully";              

                    }

            }
            // end update 
        }

    }
?>

    <div class="container login-page">
        <h1 class="text-center">
            <span class="active log" data-class="login">login </span> | <span class="snup" data-class="signup"> signup</span>
        </h1>
        <!-- start login form  -->
        <form class="login" action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">
            <div class="con-input">
                <input class="form-control" type="text" name="usrname" autocomplete="off" placeholder="username" required="required">
            </div>
            <div class="con-input">
                <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="password" required="required">
            </div>
            <input type="submit" class="btn btn-primary btn-block" name="login" value="Login">
        </form>
        <!-- end login form  -->

        <!-- start signup form  -->
        <form class="signup" action="<?php $_SERVER["PHP_SELF"] ?>" method="POST">
            <div class="con-input">
                <input class="form-control" type="text" pattern=".{4,}" title="username must be more than 4 chars" name="usrname" autocomplete="off" placeholder="username" required="required">
            </div>
            <div class="con-input">    
                <input class="form-control" type="password" minlength="8" title="password must be more than 8 chars" name="password" autocomplete="new-password" placeholder="password" required="required">
            </div>
            <div class="con-input">    
                <input class="form-control" type="password" minlength="8" title="password must be matched" name="second-password" autocomplete="new-password" placeholder="repeat password" required="required">
            </div>
            <div class="con-input">    
                <input class="form-control" type="email" name="email" placeholder="valid mail" required="required">
            </div>    
                <input type="submit" class="btn btn-success btn-block" name="signup" value="Signup">
        </form>
            
        <!-- print the errors from the form -->

        <div class="msgerror text-center">
            <?php 
            
                if(! empty($formErrors)) {

                    foreach($formErrors as $error) {

                        // echo $error . "<br>";

                        echo '<div class="alert alert-danger">';
                            echo '<strong>Error! </strong>' . $error;
                        echo '</div>';
                    }
                }

                if(isset($msgsucces)) {

                    echo '<div class="alert alert-success">';
                        echo '<strong>Congratulations! </strong>' . $msgsucces;
                    echo '</div>';

                }
            ?>
        </div>
        <!-- end signup form  -->
    </div>



<?php    
    include $temp_file . "footer.php";
?>