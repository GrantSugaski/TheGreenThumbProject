<?php

include '../db/model.php';
include '../db/user.php';

session_start();

if (isset($_POST['request']) && $_POST['request'] == "Update") {
    try {
        $db = new DatabaseAdaptor();

        updateUser($db->db, $_POST['email'], $_POST['password'], $_POST['firstName'], $_POST['lastName'], $_POST['organization'], $_POST['description']);

        $_SESSION['SESSION_INFO'] = "Succesfully updated account";

        header("Location: ../account.php");
    } catch (Exception $ex) {
        $_SESSION['SESSION_ERROR'] = "Failed to update account on internal exception.";
    }
}

if (isset($_GET['request']) && $_GET['request'] == 'GetUser') {
    try {
        $db = new DatabaseAdaptor();

        $user = getUser($db->db, $_SESSION['AUTH_TOKEN'], $_SESSION['AUTH_ROLE']);

        echo json_encode($user);
    } catch (Exception $ex) {
        $_SESSION['SESSION_ERROR'] = "Failed to get user information on internal exception.";
    }
}

?>