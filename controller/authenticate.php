<?php

include 'db/model.php';
include 'db/user.php';

function authenticateUser($id, $token) {
    try {
        $db = new DatabaseAdaptor();

        $result = authenticateUserPermissions($db->db, $id, $token);

        if (!$result) {
            $_SESSION["SESSION_ERROR"] = "Failed to authenticate user.";
        }

        return $result;
    } catch (Exception $ex) {
        $_SESSION["SESSION_ERROR"] = "Failed to authenticate user on exception.";

        return false;
    }
}

?>