<?php

    session_start();

    $pageTitle = "categories";

    if(isset($_SESSION["Username"])) {

        include "initialize.php";

        $do = (isset($_GET["do"]))? $_GET["do"] : "manage";

        if($do == "manage") {

            echo "welcome categ";
        }
        elseif($do == "add") {

        }
        elseif($do == "insert") {

        }
        elseif($do == "edit") {

        }
        elseif($do == "update") {

        }
        elseif($do == "delete") {

        }
        
        include $temp_file . "footer.php";
    }
    else {

        header("location: index.php");
        exit();
    }

?>
