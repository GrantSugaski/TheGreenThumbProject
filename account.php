<html>
	<head>
		<title>Account - The Green Thumb</title>
		<link rel="stylesheet" type="text/css" href="styles/common.css">
	</head>
	<body onload="fillForm()">
		<?php 
            session_start();
            include("navbar.php");
        ?>

		<form class="form" action="controller/account.php" method="post">
			<input type="email" name="email" placeholder="Email" required/>
			
			<input type="password" name="password" placeholder="Password" />
			
			<input type="text" name="firstName" placeholder="First Name" required/>

			<input type="text" name="lastName" placeholder="Last Name" required/>

            <input type="text" name="organization" placeholder="Organization" required/>

            <input type="text" name="description" placeholder="Description" required/>

			<button type="submit" name="request" value="Update">Update Account</button>
		</form>

		<script>
		
		function fillForm() {
			var ajax = new XMLHttpRequest();				//Ajax request to controller
    		ajax.open("GET", "controller/account.php?request=GetUser");
			ajax.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var user = JSON.parse(this.responseText);

					document.getElementsByName("email")[0].value = user[0]["Email"];

                    if (user[0]["Role"] == "Customer") {
                        document.getElementsByName("firstName")[0].value = user[0]["FirstName"];
					    document.getElementsByName("lastName")[0].value = user[0]["LastName"];

                        document.getElementsByName("organization")[0].style.display = "none";
                        document.getElementsByName("organization")[0].required = false;

                        document.getElementsByName("description")[0].style.display = "none";
                        document.getElementsByName("description")[0].required = false;
                    } else if (user[0]["Role"] == "Producer") {
                        document.getElementsByName("organization")[0].value = user[0]["Organization"];
                        document.getElementsByName("description")[0].value = user[0]["Description"];

                        document.getElementsByName("firstName")[0].style.display = "none";
                        document.getElementsByName("firstName")[0].required = false;

                        document.getElementsByName("lastName")[0].style.display = "none";
                        document.getElementsByName("lastName")[0].required = false;
                    }
				}
			}
    		ajax.send();
		}

		</script>
	</body>
</html>