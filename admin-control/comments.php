<?php

    session_start();

    $pageTitle = "comments";

    if(isset($_SESSION["Username"])) {

        include "initialize.php";

        $do = (isset($_GET["do"]))? $_GET["do"] : "manage";

        if($do == "manage") { // manage page

        
            $stmt = $db->prepare("SELECT comments.*, items.Name AS item_name, users.Username AS username FROM comments
                                    INNER JOIN items ON items.itemID = comments.itemID
                                    INNER JOIN users ON users.UserID = comments.User_ID
                                    ORDER BY comment_ID DESC ");
            $stmt->execute();
            $row = $stmt->fetchAll();

            if(! empty($row)) {
            
            ?>

                <div class="container users-page">
                    <h1 class="text-center"> Manage Comments </h1>
                    <div class="table-responsive">
                        <table class="main table table-bordered text-center">
                            <tr>
                                <td>ID</td>
                                <td>comment</td>
                                <td>Adding date</td>
                                <td>item name</td>
                                <td>username</td>
                                <td>control</td>
                            </tr>
                            <?php

                                foreach($row as $rw) {

                                    echo "<tr>";
                                        echo "<td>" . $rw["comment_ID"] . "</td>";
                                        echo "<td>" . $rw["comment"] . "</td>";
                                        echo "<td>" . $rw["comment_Date"] . "</td>";
                                        echo "<td>" . $rw["item_name"] . "</td>";
                                        echo "<td>" . $rw["username"] . "</td>";
                                        echo "<td>
                                                <a href='comments.php?do=edit&commentid=" . $rw["comment_ID"] . "' class='btn btn-success'> <i class='fa fa-edit'></i> Edit</a>
                                                <a href='comments.php?do=delete&commentid=" . $rw["comment_ID"] . "' class='btn btn-danger confirm'> <i class='fa fa-close'></i> Delete </a>";
                                                
                                                // check  if comment Status = 0 or not

                                                if($rw["status"] == 0) {

                                                echo "<a href='comments.php?do=approve&commentid=" . $rw["comment_ID"] . "' class='btn btn-info activate'> <i class='fa fa-check'></i> Approve</a>";
                                                }
                                        echo  "</td>";
                                    echo "</tr>";
                                }

                            ?>
                            
                        </table>
                    </div>
                </div>

            <?php
            } 
            else {

                echo '<div class="container">';
                    echo '<div class="alert alert-info" role="alert">There\'s no data to show</div>';
                echo '</div>';
            }
         
        ?>
    <?php

        }
        
        elseif($do == "edit") { 
            
            $commentid = isset($_GET["commentid"]) && is_numeric($_GET["commentid"]) ? intval($_GET["commentid"]) : 0;   // if function shortly in one row

            $stmt = $db->prepare("SELECT * FROM comments WHERE comment_ID = ? ");
                                
            $stmt->execute(array($commentid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if($stmt->rowCount() > 0) {
            
            ?>
            <!-- start html code  -->
            
                <div class="container users-page">
                    <h1 class="text-center"> edit comment</h1>
                    <form class="form-horizontal" action="?do=update" method="POST">
                            <!-- hidden input for comment id -->
                        <input type="hidden" name="commentid" value="<?php echo $commentid ?>">
                                <!-- start comment input  -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">comment</label>
                            <div class="col-sm-10 col-md-6">
                                <textarea class="form-control" name="comment"><?php echo $row["comment"] ?></textarea>
                            </div>
                        </div>
                            <!-- end comment input  -->
    
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

            echo '<h1 class="text-center"> update comment</h1>';
            echo "<div class='container'>";

            if($_SERVER["REQUEST_METHOD"] == "POST") {

                $id          = $_POST["commentid"];
                $comment     = $_POST["comment"];
                

                    $stmt = $db->prepare("UPDATE comments SET comment = ?  WHERE comment_ID = ?");
                    $stmt->execute(array($comment, $id));

                    $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " record updated</div>";

                    // calling redirect functon and rhe seconds will be 3s for the default
                    redirect($themsg, "back");
                


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

            echo '<h1 class="text-center"> Delete comment</h1>';
            echo "<div class='container'>";

            $commentid = isset($_GET["commentid"]) && is_numeric($_GET["commentid"]) ? intval($_GET["commentid"]) : 0;   // if function shortly in one row

                // calling checkitem function 

                $check = checkItem("comment_ID", "comments", $commentid);

                if($check > 0) {

                    $stmt = $db->prepare("DELETE FROM comments WHERE comment_ID = :comid");
                    $stmt->bindparam(":comid", $commentid);
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
        elseif($do == "approve") {

            // delete mamber from database page

            echo '<h1 class="text-center"> Approving comments</h1>';
            echo "<div class='container'>";

            $commentid = isset($_GET["commentid"]) && is_numeric($_GET["commentid"]) ? intval($_GET["commentid"]) : 0;   // if function shortly in one row

                // code for checkitem function


                $check = checkItem("comment_ID", "comments", $commentid);

                if($check > 0) {

                    $stmt = $db->prepare("UPDATE comments SET status = 1 WHERE comment_ID = ? ");
                    $stmt->execute(array($commentid));

                    // redirect function
                    $themsg = '<div class="alert alert-success" role="alert">' . $stmt->rowCount() . " Comment Approved</div>";
                    redirect($themsg, "back", 2);
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