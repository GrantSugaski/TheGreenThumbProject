<?php

include '../db/model.php';
include '../db/events.php';

session_start();

if (isset($_GET['request']) && $_GET['request'] == 'GetEvents') {
    try {
        $db = new DatabaseAdaptor();

        $events = getEvents($db->db);

        echo json_encode($events);
    } catch (Exception $ex) {
        $_SESSION['SESSION_ERROR'] = "Failed to get events on internal exception.";
    }
}

?>