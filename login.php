<html>
	<head>
		<title>Login</title>
		<link rel="stylesheet" type="text/css" href="styles/common.css">
	</head>
	<body>
		<?php
            session_start();
            include("navbar.php"); 
        ?>

		<form class="form" action="controller/login.php" method="post">
			<input type="email" name="email" placeholder="Email" required/>
			<input type="password" name="password" placeholder="Password" required/>
			<button type="submit" name="request" value="Login">Login</button>
		</form>	
	</body>
</html>