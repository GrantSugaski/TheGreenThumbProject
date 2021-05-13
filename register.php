<html>
	<head>
		<title>Register</title>
		<link rel="stylesheet" type="text/css" href="styles/common.css">
	</head>
	<body onload="updateFields()">
		<?php 
            session_start();
            include("navbar.php"); 
        ?>

		<form class="form" action="controller/register.php" method="post">
			<input type="email" name="email" placeholder="Email" required/>
			
			<input type="password" name="password" placeholder="Password" required/>
			
			<select name="role" onchange="updateFields()">
				<option value="Customer">Customer</option>
				<option value="Producer">Producer</option>
			</select>

			<input type="text" name="firstName" placeholder="First Name" required/>

			<input type="text" name="lastName" placeholder="Last Name" required/>

			<input type="text" name="organization" placeholder="Organization Name" required/>

			<input type="text" name="description" placeholder="Organization Description" required/>

			<button type="submit" name="request" value="Register">Register</button>
		</form>

        <script>
        
            function updateFields() {
                var role = document.getElementsByName("role")[0];

                if (role.value == "Customer") {
                    document.getElementsByName("firstName")[0].style.display = "inline-block";
                    document.getElementsByName("firstName")[0].required = true;

                    document.getElementsByName("lastName")[0].style.display = "inline-block";
                    document.getElementsByName("lastName")[0].required = true;

                    document.getElementsByName("organization")[0].style.display = "none";
                    document.getElementsByName("organization")[0].required = false;

                    document.getElementsByName("description")[0].style.display = "none";
                    document.getElementsByName("description")[0].required = false;
                } else if (role.value == "Producer") {
                    document.getElementsByName("firstName")[0].style.display = "none";
                    document.getElementsByName("firstName")[0].required = false;

                    document.getElementsByName("lastName")[0].style.display = "none";
                    document.getElementsByName("lastName")[0].required = false;

                    document.getElementsByName("organization")[0].style.display = "inline-block";
                    document.getElementsByName("organization")[0].required = true;
                    
                    document.getElementsByName("description")[0].style.display = "inline-block";
                    document.getElementsByName("description")[0].required = true;
                }
            }

        </script>
	</body>
</html>