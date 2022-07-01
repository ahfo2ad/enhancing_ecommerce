<?php


    // start front end functions

        //the global getall function used in all site

    function getall($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {

        global $db;

        $grttingdata = $db->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

        $grttingdata->execute();

        return $grttingdata->fetchALL();
    }

    // get categories function from data base

    // function getAllData($tableName, $order, $where) {
    function getAllData($tableName, $order, $approving = NULL) {

        global $db;

        $sqlapprove = $approving == NULL ? "approving = 1" : "";
        $sqlapprove = $approving == NULL ? "approving = 1" : "";

        $getall = $db->prepare("SELECT * FROM $tableName WHERE $sqlapprove ORDER BY $order DESC");

        $getall->execute();

        return $getall->fetchAll();
    }

    /*
    function getAllData($tableName, $order, $where = NULL) {

        global $db;

        $sqlapprove = $where == NULL ? "" : "$where";

        $getall = $db->prepare("SELECT * FROM $tableName $where ORDER BY $order DESC");

        $getall->execute();

        return $getall->fetchAll();
    } */

    // get categories function from data base

    function getcategs() {

        global $db;

        $getcats = $db->prepare("SELECT * FROM categories ORDER BY ID ASC");

        $getcats->execute();

        return $getcats->fetchAll();
    }

    // get items function from data base

    function getItems($categoryfield, $value, $approving = NULL) {

        global $db;

        // if function shortly

        $sqlapprove = $approving == NULL ? "AND approving = 1" : "";

        // if($approving == NULL) {

        //     $sqlapprove = "AND approving = 1";
        // }
        // else {

        //     $sqlapprove = NULL;
        // }

        $getitms = $db->prepare("SELECT * FROM items WHERE $categoryfield = ? $sqlapprove ORDER BY itemID DESC");

        $getitms->execute(array($value));

        return $getitms->fetchAll();
    }

    // checkUserStatus function check if the RegisterStatus of the user is avtivated or not

    /* 
        avtivated = 0 
        not activated = 1

        */

    function checkUserStatus($user) {

        global $db;

        $stmtstatus = $db->prepare("SELECT 
                                  Username, RegisterStatus 
                            FROM
                                  users 
                            WHERE 
                                  Username = ? 
                            AND
                                  RegisterStatus = 0 ");

        $stmtstatus->execute(array($user));

        $statusCount = $stmtstatus->rowCount();

        return $statusCount;
    }

    // check items function 

    function checkItem($select, $from, $value) {

        global $db;
        $statment = $db->prepare("SELECT $select FROM $from WHERE $select = ?");
        $statment->execute(array($value));
        $count = $statment->rowCount();
        return $count;
    }


    // end front end functions



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

