<?php

    session_start();

    $pageTitle = "users";

    if(isset($_SESSION["Username"])) {

        include "initialize.php";

        $do = (isset($_GET["do"]))? $_GET["do"] : "manage";

        if($do == "manage") { // manage page

            $query = "";

            if(isset($_GET["page"]) && $_GET["page"] == "pending") {

                $query = "AND RegisterStatus = 0";
            }
        
            $stmt = $db->prepare("SELECT 
                                    *
                                FROM
                                  users 
                                WHERE 
                                  GroupID != 1 $query ORDER BY UserID DESC");
            $stmt->execute();
            $row = $stmt->fetchAll();

            if(! empty($row)) {
            
                ?>

                <div class="container users-page">
                    <h1 class="text-center"> Manage Users </h1>
                    <div class="table-responsive">
                        <table class="main table table-bordered text-center">
                            <tr>
                                <td>ID</td>
                                <td>Image</td>
                                <td>Username</td>
                                <td>Email</td>
                                <td>Fullname</td>
                                <td>REGISTERED DATE</td>
                                <td>control</td>
                            </tr>
                            <?php
                                    // uploads\profiles\
                                foreach($row as $rw) {

                                    echo "<tr>";
                                        echo "<td>" . $rw["UserID"] . "</td>";
                                        echo "<td>";
                                            if(empty($rw['profileImg'])) {

                                                echo "<img src='1.png'>";;
                                            }
                                            else {

                                                echo "<img src='uploads/profiles/" . $rw['profileImg'] . "'>";
                                            }
                                        echo "</td>";
                                        echo "<td>" . $rw["Username"] . "</td>";
                                        echo "<td>" . $rw["Email"] . "</td>";
                                        echo "<td>" . $rw["Fullname"] . "</td>";
                                        echo "<td>" . $rw["Date"] . "</td>";
                                        echo "<td>
                                                <a href='users.php?do=edit&userid=" . $rw["UserID"] . "' class='btn btn-success'> <i class='fa fa-edit'></i> Edit</a>
                                                <a href='users.php?do=delete&userid=" . $rw["UserID"] . "' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete </a>";
                                                
                                                // check  if member RegisterStatus = 0 or not

                                                if($rw["RegisterStatus"] == 0) {

                                                echo "<a href='users.php?do=activate&userid=" . $rw["UserID"] . "' class='btn btn-info activate'> <i class='fa fa-check'></i> Activate</a>";
                                                }
                                        echo  "</td>";
                                    echo "</tr>";
                                }

                            ?>
                            
                        </table>
                    </div>
                    <a href="users.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member </a>
                </div>

        <?php
            } 
            else {

                echo '<div class="container">';
                    echo '<div class="alert alert-info" role="alert">There\'s no data to show</div>';
                    echo '<a href="users.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Member </a>';
                echo '</div>';
            }
         
        ?>

    <?php }
        elseif($do == "add") { ?>

                 <!-- add new user  -->

            <div class="container users-page">
                    <h1 class="text-center"> add user </h1>
                    <form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data">
                                <!-- start 1st form username input  -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">username</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" class="form-control" name="username" autocomplete="off" required="required" placeholder="username">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">password</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="password" class="password form-control" name="password" autocomplete="new-password" required="required" placeholder="strong password">
                                <i class="show-pass far fa-eye-slash fa-2x"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">email</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="email" class="form-control" name="email" required="required" placeholder="valid mail">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">full name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" class="form-control" name="fullname" required="required" placeholder="full name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">profile image</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="file" class="form-control" name="profileimg" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10 col-md-2">
                                <input type="submit" value="Add" class="btn btn-primary btn-block">
                            </div>
                        </div>
                    </form>
                </div>
        <?php 
        }
        elseif($do == "insert") {          // insert page           

            if($_SERVER["REQUEST_METHOD"] == "POST") {

                echo '<h1 class="text-center"> update profile</h1>';
                echo "<div class='container'>";

                // upload files

                $profileimgName = $_FILES["profileimg"]["name"];
                $profileimgType = $_FILES["profileimg"]["type"];
                $profileimgTmp  = $_FILES["profileimg"]["tmp_name"];
                $profileimgSize = $_FILES["profileimg"]["size"];

                 // allowed imag extensions

                $imgExtensions = array("png", "jpeg", "jpg", "gif");

                $convertname = explode('.', $profileimgName);

                $filteredname = strtolower(end($convertname));


                $name     = $_POST["username"];
                $pass     = $_POST["password"];
                $email    = $_POST["email"];
                $fullname = $_POST["fullname"];

                $shahpass = sha1($_POST["password"]);

                // validate the form

                $formErrors = array();

                if(empty($name)) {

                    $formErrors[] = 'username can\'t be empty';
                }
                if(empty($pass)) {

                    $formErrors[] = 'password can\'t be empty';
                }
                if(empty($email)) {

                    $formErrors[] = 'email can\'t be empty';
                }
                if(empty($fullname)) {

                    $formErrors[] = 'fullname can\'t be empty';
                }
                if(! empty($profileimgName) && ! in_array($filteredname, $imgExtensions)) {
                    
                    $formErrors[] = 'image extension not allowed';
                }
                if(empty($profileimgName)) {
                    
                    $formErrors[] = 'profile image can\'t be empty';
                }
                if($profileimgSize > 4194304) {
                    
                    $formErrors[] = 'profile can\'t be more than 4MB';
                }
                foreach($formErrors as $error) {

                    echo '<div class="alert alert-danger">' . $error . '</div>';
                }
                
                
                    // uppdate database auto

                if(empty($formErrors)) {

                    // check the image uploaded

                    $profe = rand(0, 1000000000000) . "-" . $profileimgName;
                    move_uploaded_file($profileimgTmp, "uploads\profiles\\" . $profe);

                    // check if the user is exists or not
                    
                    $check = checkItem("Username", "users", $name);
                    $check2 = checkItem("Email", "users", $email);
                    // $check = checkItem("Username", "users", $name) || checkItem("Email", "users", $email);
                    if($check == 1) {

                    
                        $themsg = '<div class="alert alert-danger" role="alert">This username is already exists </div>';

                        redirect($themsg, "back", 2);
                    }
                    elseif($check2 == 1) {
                    
                        $themsg = '<div class="alert alert-danger" role="alert">This email is already exists </div>';

                        redirect($themsg, "back", 2);
                    }
                    else {
                    

                        // insert user data in the database

                        $stmt = $db->prepare("INSERT INTO users( Username, Password, Email, Fullname, RegisterStatus, Date, profileImg) 
                                        VALUES(:user, :pass, :mail, :fullname, 1, now(), :profil ) ");
                        $stmt->execute(array(
                            "user"     => $name, 
                            "pass"     => $shahpass,
                            "mail"     => $email, 
                            "fullname" => $fullname,
                            "profil"   => $profe

                            ));

                        $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " record updated</div>";

                        redirect($themsg, "back");              

                        }

                }

            }
            
            else
            {

                echo '<div class="container users-page">';

                // calling redirect function 
                
                $themsg = '<div class="alert alert-danger" role="alert">sorry you aren\'t allowed to be here directly </div>';

                redirect($themsg);

                echo '</div>';
            }

            echo "</div>";            

        }
        elseif($do == "edit") { 
            
            $userid = isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;   // if function shortly in one row

            $stmt = $db->prepare("SELECT 
                                  *
                            FROM
                                  users 
                            WHERE 
                                  UserID = ? 
                            LIMIT
                                  1");
            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if($stmt->rowCount() > 0) {
            
                ?>
                <!-- start html code  -->
            
                <div class="container users-page">
                    <h1 class="text-center"> edit profile</h1>
                    <form class="form-horizontal" action="?do=update" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="userid" value="<?php echo $userid ?>">
                                <!-- start 1st form username input  -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">username</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" class="form-control" name="username" value="<?php echo $row["Username"] ?>" autocomplete="off" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">password</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="hidden" name="oldpassword" value="<?php echo $row["Password"] ?>">
                                <input type="password" class="form-control" name="newpassword" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">email</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="email" class="form-control" name="email" value="<?php echo $row["Email"] ?>" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">full name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" class="form-control" name="fullname" value="<?php echo $row["Fullname"] ?>" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">profile image</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="file" class="form-control" name="profileimg" value="<?php echo $row["profileImg"] ?>" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10 col-md-2">
                                <input type="submit" value="Save" class="btn btn-primary btn-block">
                            </div>
                        </div>
                    </form>
                </div>


        <?php  
        } 
        else {

                //redirect function

            echo '<div class="container users-page">';

            $themsg = '<div class="alert alert-danger" role="alert">sorry no id here like that</div>';

            redirect($themsg);

            echo '</div>';
        }

    }
    elseif($do == "update") {        //ubdate page

        echo '<h1 class="text-center"> update profile</h1>';
        echo "<div class='container'>";

        if($_SERVER["REQUEST_METHOD"] == "POST") {

            // upload files

            $profileimgName = $_FILES["profileimg"]["name"];
            $profileimgType = $_FILES["profileimg"]["type"];
            $profileimgTmp  = $_FILES["profileimg"]["tmp_name"];
            $profileimgSize = $_FILES["profileimg"]["size"];

            //     // allowed imag extensions

            $imgExtensions = array("png", "jpeg", "jpg", "gif");

            $convertname = explode('.', $profileimgName);

            $filteredname = strtolower(end($convertname));


            $id       = $_POST["userid"];
            $name     = $_POST["username"];
            $email    = $_POST["email"];
            $fullname = $_POST["fullname"];
            // $profimag = $_POST["profileimg"];

            // password trick

            // if function shortly => condition ? true : false

            $pass = empty($_POST["newpassword"]) ? $_POST["oldpassword"] : sha1($_POST["newpassword"]);
            // $pass = "";

            // if(empty($_POST["newpassword"])) {

            //     $pass = $_POST["oldpassword"];
            // }
            // else {

            //     $pass = sha1($_POST["newpassword"]);
            // }

            // validate the form

            $formErrors = array();

            if(empty($name)) {

                $formErrors[] = '<div class="alert alert-ganger">username can\'t be empty</div>';
            }
            if(empty($email)) {

                $formErrors[] = '<div class="alert alert-danger" role="alert">email can\'t be empty</div>';
            }
            if(empty($fullname)) {

                $formErrors[] = '<div class="alert alert-danger" role="alert">fullname can\'t be empty</div>';
            }
            if(! empty($profileimgName) && ! in_array($filteredname, $imgExtensions)) {
                    
                $formErrors[] = '<div class="alert alert-danger" role="alert">image extension not allowed</div>';
            }
            if(empty($profileimgName)) {
                    
                $formErrors[] = '<div class="alert alert-danger" role="alert">profile image can\'t be empty</div>';
            }
            if($profileimgSize > 4194304) {
                    
                $formErrors[] = '<div class="alert alert-danger" role="alert">profile can\'t be more than 4MB</div>';
            }
            foreach($formErrors as $error) {

                // echo $error;
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }

                // uppdate database auto

            if(empty($formErrors)) {

                // check the image uploaded

                $profe = rand(0, 1000000000000) . "-" . $profileimgName;
                move_uploaded_file($profileimgTmp, "uploads\profiles\\" . $profe);

                $stmt = $db->prepare("UPDATE users SET Username = ?, Email = ?, Fullname = ?, profileImg = ?, Password = ?  WHERE UserID = ?");
                $stmt->execute(array($name, $email, $fullname, $profe, $pass, $id));

                $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " record updated</div>";

                // calling redirect functon and rhe seconds will be 3s for the default
                redirect($themsg, "back");
            }


        }
        else
        {
            // calling redirect functon and rhe seconds will be 3s for the default

            $themsg = '<div class="alert alert-danger" role="alert">sorry u r not allowed here</div>';

            redirect($themsg);
        }

        echo "</div>";

    }
    elseif($do == "delete") {

        // delete mamber from database page

        echo '<h1 class="text-center"> Delete profile</h1>';
        echo "<div class='container'>";

        $userid = isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;   // if function shortly in one row

            // code before checkitem function

            /*$stmt = $db->prepare("SELECT 
                                  *
                            FROM
                                  users 
                            WHERE 
                                  UserID = ? 
                            LIMIT
                                  1");
            $stmt->execute(array($userid));
            $count = $stmt->rowCount();*/

            $check = checkItem("userid", "users", $userid);

            if($check > 0) {

                $stmt = $db->prepare("DELETE FROM users WHERE UserID = :uid");
                $stmt->bindparam(":uid", $userid);
                $stmt->execute();

                // redirect function
                $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " Record Deleted</div>";
                redirect($themsg, "back");
            }
            else {

                //  redirect function
                $themsg = '<div class="alert alert-danger" role="alert">not exist member</div>';

                redirect($themsg);
            }

            echo "</div>";

    }
    elseif($do == "activate") {

        // delete mamber from database page

        echo '<h1 class="text-center"> Activate profile</h1>';
        echo "<div class='container'>";

            $userid = isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;   // if function shortly in one row

            // code for checkitem function


            $check = checkItem("userid", "users", $userid);

            if($check > 0) {

                $stmt = $db->prepare("UPDATE users SET RegisterStatus = 1 WHERE UserID = ? ");
                $stmt->execute(array($userid));

                // redirect function
                $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " Record Activated</div>";
                redirect($themsg, "", 2);
            }
            else {

                //  redirect function
                $themsg = '<div class="alert alert-danger" role="alert">not exist ID member</div>';

                redirect($themsg);
            }

            echo "</div>";

    }



        include $temp_file . "footer.php";

    }
    else {

        header("location: index.php");
        exit();
    }