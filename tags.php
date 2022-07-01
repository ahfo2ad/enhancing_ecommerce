<?php    include "initialize.php";  ?>

    <div class="container">
        <!-- <div class="row"> -->
            <?php

                if(isset($_GET["name"])) {

                    $tagnm = $_GET["name"];

                    echo "<h1 class='text-center'>" . $tagnm . "</h1>";

                    echo '<div class="row">';
                            // new global getall function
                            
                        $itmstag = getall("*", "items", "WHERE tags LIKE '%$tagnm%'", "AND approving = 1", "itemID");

                            // old function
                        // $itms = getItems("Category_ID", $_GET["id"]);                  

                        foreach($itmstag as $itmtag) {
                            // echo $itm["Name"] . "<br>";
                            echo '<div class="col-sm-6 col-md-3 categories">';
                                echo '<div class="thumbnail box-item">';
                                    echo '<span class="price">' . "$" . $itmtag["Price"] . '</span>';
                                    echo '<img class="img-responsive" src="1.png">';
                                    echo '<div class="caption">';
                                        echo '<h3><a href="items-users.php?itemid=' . $itmtag["itemID"] . '">' . $itmtag["Name"] . '</a></h3>';
                                        echo '<p>' . $itmtag["Description"] . '</p>';
                                        echo '<p class="date">' . $itmtag["Date"] . '</p>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';  
                            
                        }
                    echo '</div>';
                }
                else {

                    echo '<div class="alert alert-danger alert-dismissible">';
                        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
                        echo '<strong>Error! </strong>' . "not available";
                    echo '</div>';
                }
            ?>
        <!-- </div> -->
    </div>
    
<?php   include $temp_file . "footer.php";  ?>
