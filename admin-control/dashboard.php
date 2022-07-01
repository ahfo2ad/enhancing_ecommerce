<?php

    ob_start();

    session_start();

    if(isset($_SESSION["Username"])) {

        $pageTitle = "Dashboard";

        // echo "welcome " . $_SESSION["Username"];

        include "initialize.php";

        // getLatest function attributes and its parameters

        $latest_users_No = 5;  // number of users that i want to show   ==> limit = 5

        $LatestUsers = getLatest("*", "users", "UserID", $latest_users_No);   // calling getLatest to show users

        $latest_items_No = 4;  // number of items that i want to show   ==> limit = 4

        $LatestItems = getLatest("*", "items", "itemID", $latest_items_No);   // calling getLatest to show items

        $latest_comments_No = 3;  // number of items that i want to show   ==> limit = 4

        $LatestComments = getLatest("*", "comments", "comment_ID", $latest_items_No);   // calling getLatest to show items

        ?>
         <!-- start page -->

        <div class="home-dash">
            <div class="container text-center">
                <h1 class="text-center"> Dashboard</h1>
                <div class="row">
                    <div class="col-md-3">
                        <div class="stat st1">
                            total members
                            <span><a href="users.php"><?php echo countItems("UserID", "users") ?></a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st2">
                            pending members
                            <span><a href="users.php?do=manage&page=pending">
                                <?php echo checkItem("RegisterStatus", "users", 0) ?>
                            </a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st3">
                            total items
                            <span><a href="items.php"><?php echo countItems("itemID", "items") ?></a></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat st4">
                            total comments
                            <span><a href="comments.php"><?php echo countItems("comment_ID", "comments") ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="last-dash">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
    
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> latest <?php echo $latest_users_No ?> registered users
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">

                                <ul class="list-unstyled latest-users">
                                        <!-- loop to get latest users from data base  -->
                                    <?php

                                        if(! empty($LatestUsers)) {

                                            foreach($LatestUsers as $user) {

                                                echo "<li class='latest-listing'>";
                                                    echo $user["Username"];
                                                    echo '<a href="users.php?do=edit&userid=' . $user["UserID"] . '">';
                                                        echo "<span class='btn btn-success pull-right'>";
                                                            echo "<i class='fa fa-edit'></i> Edit";
                                                            if($user["RegisterStatus"] == 0) {

                                                                echo "<a href='users.php?do=activate&userid=" . $user["UserID"] . "' class='btn btn-info activate pull-right'> <i class='fa fa-check'></i> Activate</a>";
                                                            }
                                                        echo "</span>";
                                                    echo "</a>";
                                                echo "</li>";
                                            }
                                        }
                                        else {

                                            echo "There's no data to show";
                                        }

                                    ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> latest <?php echo $latest_items_No ?> items
                                <span class="toggle-info">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                        <!-- loop to get latest items from data base  -->
                                    <?php

                                        if(! empty($LatestItems)) {

                                            foreach($LatestItems as $item) {

                                                echo "<li class='latest-listing'>";
                                                    echo $item["Name"];
                                                    echo '<a href="items.php?do=edit&itemid=' . $item["itemID"] . '">';
                                                        echo "<span class='btn btn-success pull-right'>";
                                                            echo "<i class='fa fa-edit'></i> Edit";
                                                            if($item["approving"] == 0) {

                                                                echo "<a href='items.php?do=approve&itemid=" . $item["itemID"] . "' class='btn btn-info activate pull-right'> <i class='fa fa-check'></i> Approve</a>";
                                                            }
                                                        echo "</span>";
                                                    echo "</a>";
                                                echo "</li>";
                                            }
                                        }
                                        else {

                                            echo "There's no data to show";
                                        }

                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- start comments panel  -->

                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
    
                            <div class="panel-heading">
                                <i class="fa fa-comments"></i> latest <?php echo $latest_comments_No ?> comments
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">

                                <?php

                                    $stmt = $db->prepare("SELECT comments.*, users.Username AS username, users.profileImg AS imagepro FROM comments

                                                        INNER JOIN users ON users.UserID = comments.User_ID ORDER BY comment_ID DESC LIMIT $latest_comments_No ");

                                    $stmt->execute();
                                    $comments = $stmt->fetchAll();

                                    if(! empty($comments)) {
                                        foreach($comments as $comment) {

                                            echo '<div class="comntdv">';
                                                echo '<div class="comntimg">';
                                                    echo '<a href="users.php?do=edit&userid=' . $comment["User_ID"] . '">';

                                                        if (empty($comment['imagepro'])) {

                                                            echo "<img src='1.png'>";
                                                        } else {

                                                            echo "<img src='uploads/profiles/" . $comment['imagepro'] . "'>";
                                                        }

                                                    echo "</a>";
                                                echo '</div>';
                                                echo '<div class="comntinfo">';
                                                    
                                                    echo '<a href="users.php?do=edit&userid=' . $comment["User_ID"] . '">';
                                                        echo '<h5 class="comspn">' . $comment["username"] . '</h5>';
                                                    echo "</a>";
                                                    // echo '<h5 class="comspn">' . $comment["username"] . '</h5>';
                                                    echo '<p class="comprag">' . $comment["comment"] . '</p>';
                                                echo '</div>';
                                            echo '</div>';
                                        }
                                    }
                                    else {

                                        echo "There's no data to show";
                                    }
                                
                                ?>

                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- end comments panel  -->
            </div>
        </div>
        
     
        
         <!-- start page -->


        <?php

        include $temp_file . "footer.php";
        ob_end_flush();

    }
    else {

        header("location: index.php");
        exit();
    }