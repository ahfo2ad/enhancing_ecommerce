<?php

    $sdn = "mysql:host=localhost;dbname=shop";
    $user = "root";
    $pass = "";
    $option = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    );

    try {
        $db = new PDO($sdn, $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "you are connected";
    } 
    catch (PDOException $e) {
        echo "failed " . $e->getMessage();
    }