<?php

    include "connect.php";

    $temp_file = "includes/temps/";             // header & footer folder track
    $css_file = "Design/css/";                  // css folder track
    $js_file = "Design/js/";                    // js folder track
    $language_file = "includes/langs/";         // language folder track
    $functions_file = "includes/functions/";    // functions folder track


    // including main files 

    include $functions_file . "function.php";

    include $language_file . "english.php";

    include $temp_file . "header.php";

    if(!isset($noNavbar)) {

        include $temp_file . "navbar.php";

    }
    
