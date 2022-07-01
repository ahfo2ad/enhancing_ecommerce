<?php   

    session_start();

    $pageTitle = "Home";

    include "initialize.php";

?>

    <div class="container contmarg">
        <div class="row">
            <?php

                // new global getall function

                $allData = getall("*", "items", "WHERE approving = 1", "", "itemID");

                    //old function
                
                    // $allData = getAllData("items", "itemID");
                    // $allData = getItems("itemID", $itm["itemID"]);
                
                foreach($allData as $itm) {
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
            ?>
        </div>
    </div>

    
<?php

    include $temp_file . "footer.php";
?>