<?php

include '../db/model.php';
include '../db/user.php';

session_start();

if (isset($_POST['request']) && $_POST['request'] == "Register") {
    try {
        $db = new DatabaseAdaptor();

        $result = createUser($db->db, $_POST['email'], $_POST['password'], $_POST['role'], $_POST['firstName'], $_POST['lastName'], $_POST['organization'], $_POST['description']);

        if ($result) {
            $_SESSION['SESSION_INFO'] = "Succesfully registered account";
        } else {
            $_SESSION["SESSION_ERROR"] = "Failed to create new user: email already exists!";
        }

        header("Location: ../register.php");
    } catch (Exception $ex) {
        $_SESSION['SESSION_ERROR'] = "Failed to register user on internal exception.";
    }
}

?>