<?php


    //the global getall function used in all site

    function getall($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {

        global $db;

        $grttingdata = $db->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

        $grttingdata->execute();

        return $grttingdata->fetchALL();
    }

        // function to give title to any page if it set 

    function getTitle() {

        global $pageTitle;

        if(isset($pageTitle)) {

            echo $pageTitle;
        }
        else {
            echo "default";
        }
    }

    // redirct to home page and time before redirect
    
    function redirect($themsg, $url = null, $seconds = 3) {

        if($url === null) {

            $url = "index.php";

            $link = "Home page";
        }
        else {

                    // if shortly

            // $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== "" ? $_SERVER['HTTP_REFERER'] : "index.php";

                    // if in details

            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== "") {

                $url = $_SERVER['HTTP_REFERER'];

                $link = "Previous page";
            }
            else {

                $url = "index.php";

                $link = "Home page";
            }
            
        }

        echo $themsg;

        echo '<div class="alert alert-info">' . "you will be redirected to $link in " . $seconds . " seconds" . '</div>';

        header("refresh:$seconds;url=$url");

        exit();
    }

    // check items function 

    function checkItem($select, $from, $value) {

        global $db;
        $statment = $db->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statment->execute(array($value));
        $count = $statment->rowCount();
        return $count;
    }

    // count items function

    function countItems($item, $table) {

        global $db;

        $statment2 = $db->prepare("SELECT COUNT($item) FROM $table");

        $statment2->execute();

        return $statment2->fetchColumn();
    }

    /*
    // count pending members function  for me 

    function countPending($item, $table) {

        global $db;

        $statment3 = $db->prepare("SELECT COUNT($item) FROM $table WHERE RegisterStatus = 0");

        $statment3->execute();

        return $statment3->fetchColumn();
    }
    */

    // get latest function from data base

    function getLatest($select, $table, $order, $limit = 5) {

        global $db;

        $getstatment = $db->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

        $getstatment->execute();

        return $getstatment->fetchAll();
    }

