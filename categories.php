<?php    
    include "initialize.php";  
?>

    <div class="container">
        <!-- <h1 class="text-center"><?php echo str_replace('-', ' ', $_GET["name"]); ?></h1> -->
        <h1 class="text-center">Category Items</h1>
        <div class="row">
            <?php

                if(isset($_GET["id"]) && is_numeric($_GET["id"])) {

                    $ctegid = intval($_GET["id"]);

                        // new global getall function
                        
                    $itms = getall("*", "items", "WHERE Category_ID = {$ctegid}", "AND approving = 1", "itemID");

                        // old function
                    // $itms = getItems("Category_ID", $_GET["id"]);

                    foreach($itms as $itm) {
                        // echo $itm["Name"] . "<br>";
                        echo '<div class="col-sm-6 col-md-3 categories">';
                            echo '<div class="thumbnail box-item">';
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
                }
                else {

                    echo '<div class="alert alert-danger alert-dismissible">';
                        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                        echo '<strong>Error! </strong>' . "not available";
                    echo '</div>';
                }
            ?>
        </div>
    </div>
    
<?php   include $temp_file . "footer.php";  ?>
