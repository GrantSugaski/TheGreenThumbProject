<?php

session_start();

if (isset($_SESSION['AUTH_TOKEN'])) {
	if ($_SESSION['AUTH_ROLE'] == "Customer") {
		header("Location: customer.php");
	} else if ($_SESSION['AUTH_ROLE'] = "Producer") {
		header("Location: producer.php");
	}
} else {
	header("Location: login.php");
}

?>