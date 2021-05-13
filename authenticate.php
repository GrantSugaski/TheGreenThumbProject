<?php

include 'controller/authenticate.php';

if (isset($_SESSION["AUTH_ID"]) && isset($_SESSION["AUTH_TOKEN"])) {
    $result = authenticateUser($_SESSION["AUTH_ID"], $_SESSION["AUTH_TOKEN"]);

    if (!$result) {
        unset($_SESSION["AUTH_ID"]);
        unset($_SESSION['AUTH_TOKEN']);
        unset($_SESSION['AUTH_ROLE']);
        header("Location: index.php");
    }
}

?>