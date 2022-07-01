<?php   

    session_start();

    $pageTitle = "Profile";

    include "initialize.php";

    if(isset($_SESSION["user"])) {

        $userstmt = $db->prepare("SELECT * FROM users WHERE Username = ?");
        $userstmt->execute(array($userSession));
        $userinfo = $userstmt->fetch();


    // echo $userSession;
?>

    <h1 class="text-center">my profile</h1>
    <div class="info">
        <div class="container">
            <div class="card cardprofile">
                <div class="card-header bg-secondary">my information</div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-unlock-alt fa-fw"></i>
                            <span>Login Name</span> : <?php echo $userinfo["Username"] ?>
                        </li>
                        <li>
                            <i class="fa-solid fa-envelope fa-fw"></i>
                            <span>Email</span> : <?php echo $userinfo["Email"] ?>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>Fullname</span> : <?php echo $userinfo["Fullname"] ?>
                        </li>
                        <li>
                            <i class="fa-solid fa-calendar-days fa-fw"></i>
                            <span>Date</span> : <?php echo $userinfo["Date"] ?>
                        </li>
                        <li>
                            <i class="fa fa-tag fa-fw"></i>
                            <span>Favourite Categories</span> :
                        </li>
                    </ul>
                    <a href="#" class="btn btn-outline-info infbtn">Edit Info</a>
                </div>
            </div>
        </div>
    </div>
    <div class="ads" id="my-itms">
        <div class="container">
            <div class="card cardprofile">
                <div class="card-header bg-secondary">my ads</div>
                <div class="card-body">
                        <?php

                            // new global getall function
                
                            $itms = getall("*", "items", "WHERE User_ID = {$userinfo['UserID']}", "", "itemID");

                                //old function
                            // $itms = getItems("User_ID", $userinfo["UserID"], 1);    // show all items ads

                            if(! empty($itms)) {
                                echo '<div class="row">';
                                    foreach($itms as $itm) {
                                        // echo $itm["Name"] . "<br>";
                                        echo '<div class="col-sm-6 col-md-3">';
                                            echo '<div class="thumbnail box-item">';
                                                if($itm["approving"] == 0) {

                                                    echo '<span class="notapprove">Not Approved</span>';
                                                }
                                                echo '<span class="price">' . "$" . $itm["Price"] . '</span>';
                                                // echo '<img class="img-responsive" src="1.png">';
                                                if (empty($itm['Image'])) {

                                                    echo "<img src='1.png'>";
                                                } else {

                                                    echo "<img src='admin-control/uploads/items/" . $itm['Image'] . "'>";
                                                }
                                                echo '<div class="caption">';
                                                    echo '<h3><a href="items-users.php?itemid=' . $itm["itemID"] . '">' . $itm["Name"] . '</a></h3>';
                                                    echo '<p>' . $itm["Description"] . '</p>';
                                                    echo '<p class="date">' . $itm["Date"] . '</p>';
                                                echo '</div>';
                                            echo '</div>';
                                        echo '</div>';  
                                        
                                    }
                                echo '</div>';
                            }
                            else {

                                echo "there's no Ads, create <a href='newads.php'> new ads </a>";
                            }
                        ?>
                </div>
            </div>
        </div>
    </div>
    <div class="comments">
        <div class="container">
            <div class="card cardprofile">
                <div class="card-header bg-secondary">comments</div>
                <div class="card-body">
                    <?php

                        // new global getall function
                
                        $comntsrows = getall("comment", "comments", "WHERE User_ID = {$userinfo['UserID']}", "", "comment_ID");
                        
                            // old 
                        // $getcomnts = $db->prepare("SELECT comment FROM comments WHERE User_ID = ?");
                        // $getcomnts->execute(array($userinfo["UserID"]));
                        // $comntsrows = $getcomnts->fetchAll();
                        if(! empty($comntsrows)) {

                            foreach($comntsrows as $cmnt) {

                                echo '<p>' . $cmnt["comment"] . '</p>';
                            }                            
                        }
                        else {

                            echo "there's no comments";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    

<?php  

    }
    else {

        header("location: login.php");

        exit();
    }
    
    include $temp_file . "footer.php";
?>