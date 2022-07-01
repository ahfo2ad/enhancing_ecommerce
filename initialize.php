<?php

    include "admin-control/connect.php";

    // check if there is session for that user or not 

    $userSession = " ";
    
    if(isset($_SESSION["user"])) {  

        $userSession = $_SESSION["user"];
    }

    $temp_file = "includes/temps/";             // header & footer folder track
    $css_file = "Design/css/";                  // css folder track
    $js_file = "Design/js/";                    // js folder track
    $language_file = "includes/langs/";         // language folder track
    $functions_file = "includes/functions/";    // functions folder track


    // including main files 

    include $functions_file . "function.php";

    include $language_file . "english.php";

    // header here = header + navbar
    
    include $temp_file . "header.php";

    
    
