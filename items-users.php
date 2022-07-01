<?php   

    session_start();

    $pageTitle = "items ads";

    include "initialize.php";

    $itemid = isset($_GET["itemid"]) && is_numeric($_GET["itemid"]) ? intval($_GET["itemid"]) : 0;   // if function shortly in one row

    $stmt = $db->prepare("SELECT items.*, categories.Name AS categ_name, users.Username FROM items
                                    INNER JOIN categories ON categories.ID = items.Category_ID
                                    INNER JOIN users ON users.UserID = items.User_ID
                                    WHERE itemID = ? AND approving = 1");
                        
    $stmt->execute(array($itemid));
    $count = $stmt->rowCount();
    if($count > 0) {

        $item = $stmt->fetch();

    

?>

    <h1 class="text-center"><?php echo $item["Name"] ?></h1> 
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="thumbnail box-item">
                    <?php 
                    
                        if (empty($item['Image'])) {

                            echo "<img src='1.png'>";
                        } else {

                            echo "<img src='admin-control/uploads/items/" . $item['Image'] . "'>";
                        }
                    ?>
                </div>
            </div>
            <div class="col-md-9">
                <ul class="list-unstyled">
                    <li><h2><?php echo $item["Name"] ?></h2></li>
                    <li><p><?php echo $item["Description"] ?></p></li>
                    <li><p>price: $<?php echo $item["Price"] ?></p></li>
                    <li><p><?php echo $item["Date"] ?></p></li>
                    <li><p>Made in <?php echo $item["Country_Made"] ?></p></li>
                    <li>
                        <p>category: <a href="categories.php?id=<?php echo $item["Category_ID"] ?>"> <?php echo $item["categ_name"] ?></a></p>
                    </li>
                    <li><p>Added by: <a href="#"> <?php echo $item["Username"] ?></a></p></li>
                    <li>
                        <span>Tags: </span>
                        <?php

                            $alltags = explode(",", $item["tags"]);

                            foreach($alltags as $tag) {

                                $tag = str_replace(" ", "", $tag);
                                $smalltag = strtolower($tag);

                                if(! empty($tag)) {

                                echo "<a class='taglink' href='tags.php?name={$smalltag}'>" . $tag . "</a> ";

                                }
                            }
                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <?php 

            if(isset($_SESSION["user"])) {
        ?>
        <div class="row">
        <div class="container">
            <div class="col-md-offset-3">
                <div class="commenting">
                    <h3>write a comment</h3>
                    <form action="<?php echo $_SERVER["PHP_SELF"] . "?itemid=" . $item["itemID"] ?>" method="POST">
                        <textarea class="form-control" required="required" name="coments" id="" cols="70" rows="4"></textarea>
                        <input class="btn btn-outline-primary" type="submit" value="Add Comment">
                    </form>
                    <?php 
                    
                        if($_SERVER["REQUEST_METHOD"] == "POST") {

                            $coment     = filter_var($_POST["coments"], FILTER_SANITIZE_STRING);
                            $itemmid    = $item["itemID"];
                            $nrmusrid   = $_SESSION["normid"];

                            if(! empty($coment)) {

                                $statmnt = $db->prepare("INSERT INTO 
                                                        comments(comment, status, comment_Date, itemID, User_ID)
                                                        VALUES(:xcmnt, 0, NOW(), :xitmid, :xusrid)");

                                $statmnt->execute(array(

                                    "xcmnt"  => $coment,
                                    "xitmid" => $itemmid,
                                    "xusrid" => $nrmusrid
                                ));

                                if($statmnt) {

                                    echo '<div class="alert alert-success alert-dismissible">';
                                        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                                        echo '<strong>Success! </strong>' . "comment added successfully";
                                    echo '</div>';
                                }
                                
                            }
                            else {

                                    echo '<div class="alert alert-danger alert-dismissible">';
                                        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                                        echo '<strong>Error! </strong>' . "comment can't be empty";
                                    echo '</div>';
                                }
                        }
                    ?>
                </div>
            </div>
        </div>
        </div>
        <?php 
        
            }
            else {
                echo "<a href='login.php'>login</a> or <a a href='login.php'>register</a> to add comments";
            }
        ?>

        <!-- start show comment -->
            <?php
                $stmt = $db->prepare("SELECT comments.*, users.Username AS username FROM comments
                                        INNER JOIN users ON users.UserID = comments.User_ID
                                        WHERE itemID = ? AND status = 1
                                        ORDER BY comment_ID DESC ");

                $stmt->execute(array($item["itemID"]));

                $rowcomnts = $stmt->fetchAll();
            ?>
            
            <?php 

                foreach($rowcomnts as $rwcmt) { ?>
                    <div class="comntcontent">
                        <div class="row">
                            <div class="col-md-2 colimg">
                                <a href="#"><img class="img-fluid" src="user.png" alt=""></a>
                            </div>
                            <div class="colcontinfo">
                                <a href="#"><?php echo $rwcmt["username"] ?></a>
                                <p><?php echo $rwcmt["comment"] ?></p>
                            </div>
                            <!-- echo $rwcmt["comment_Date"] . "<br>"; -->
                            <!-- // echo $rwcmt["User_ID"] .  "<br>"; -->
                        </div>
                    </div>

            <?php  } ?>
            
        <!-- end show comment -->
    </div>   

<?php  

    }
    else {

        echo '<div class="alert alert-danger">';
            echo '<strong>Error! </strong>' . "there's no such id or this item waiting approved";
        echo '</div>';    
    }
    
    include $temp_file . "footer.php";
?>