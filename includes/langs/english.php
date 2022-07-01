<?php

    function language($phrase) {

        static $lang = array(

            "main-page"     => "Home",
            "sections"      => "Categories",
            "items"         => "Items",
            "members"       => "Members",
            "comments"      => "Comments",
            "statistics"    => "Statistics",
            "logs"          => "Logs",
            "edit-data"     => "Profile",
            "changes"       => "Settings",
            "exit"          => "Logout"
        );

        return $lang[$phrase];
    }