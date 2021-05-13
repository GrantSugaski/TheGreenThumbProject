<?php

session_start();

unset($_SESSION['AUTH_ID']);
unset($_SESSION['AUTH_TOKEN']);
unset($_SESSION['AUTH_ROLE']);

header("Location: index.php");

?>