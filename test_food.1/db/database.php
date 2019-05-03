<?php
    function create_db_connection(){
        $host = "localhost";
        $database = "good_food";
        $username = "root";
        $password = "";

        $mysqli = new mysqli($host, $username, $password, $database);
        $mysqli->set_charset("utf8");
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        return $mysqli;    
    }
?>