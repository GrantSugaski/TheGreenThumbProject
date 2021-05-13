<?php

class DatabaseAdaptor {
    public $db;

    public function __construct() {
        $database = 'mysql:dbname=thegreenthumb;charset=utf8;host=127.0.0.1';
        $user = 'root';
        $pass = '@dukeX1468';

        try{
            $this->db = new PDO($database, $user, $pass);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo ('Error establishing connection to thegreenthumb database.');
            exit();
        }
    }
}

?>