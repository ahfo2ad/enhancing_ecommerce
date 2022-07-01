<?php

    $do = (isset($_GET["do"]))? $_GET["do"] : "manage";
    // $do = "";

    // if(isset($_GET["do"])) {

    //     $do = $_GET["do"];
    // }
    // else {

    //     $do = "manage";
    // }

    if($do == "manage") {

        echo "you are in manage category page";
        echo '<a href="?do=add"> add a category </a>';
    }
    elseif($do == "add") {

        echo "you are in add category page";
    }
    elseif($do == "insert") {

        echo "you are in insert category page";
    }
    else {

        echo "Error page not found";
    }