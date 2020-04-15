<?php

    // host, database names username and password
    $host = 'localhost';
    $dbname = 'test_it_test';
    $username = 'root';
    $password = '';

    // connect to db using PDO and get data
    $connection = new PDO('mysql:host=' . $host . ';dbname=' .  $dbname, $username, $password);
    $sql = 'SELECT `id`, `phone`, `birthday` FROM `mock_data_test_it`';
    $sth = $connection->prepare($sql);
    $sth->execute();
    //fetch data from database
    $data = $sth->fetchAll(PDO::FETCH_ASSOC);

    //modify data and insert it into database
    foreach ($data as $key => $value){
            if($value['phone'] != ''){
                $value['phone'] = preg_replace('/[^0-9]/', '', $value['phone']);
            }
            if($value['birthday'] != ''){
                $value['birthday'] = date('d.m.y', strtotime($value['birthday']));
            }
            $sql = "UPDATE `mock_data_test_it` SET `phone` = :phone, `birthday` = :birthday WHERE `id` = :id;";
            $sth = $connection->prepare($sql);
            $sth->execute(array(':phone' => $value['phone'], ':birthday' => $value['birthday'], ':id' => $value['id']));
    }