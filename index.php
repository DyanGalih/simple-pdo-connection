<?php
    include_once "config.php";

    include_once "database.php";

    $db = new Database($configuration);

    /**
     * sample get data from table users
     */
    $sql = "SELECT email,name,photo FROM users WHERE email = :email";
    $objData = new StdClass;
    $objData->email = "";

    $userData =  $db->open($sql, $objData);

    /**
     * sample insert data to table users
     */

    $sql = "INSERT INTO users (email,name) VALUES(:email, :name)";
    $objInsert = new StdClass;
    $objInsert->email = "faker@email.com";
    $objInsert->name = "name";

    $userData =  $db->execute($sql, $objData);