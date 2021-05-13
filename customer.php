<html>
	<head>
		<title>Home</title>
		<link rel="stylesheet" type="text/css" href="styles/common.css">
	</head>
	<body onload="fillSearchTable()">
		<?php 
            session_start();
            include("authenticate.php");
            include("navbar.php");
        ?>

		<div class="container">
			<div id="customerMap"></div>

			<input type="text" id="customerSearchBar" onkeyup="filter()" placeholder="Search for produce ..." />

			<table id="customerSearchTable">
				<tr class="header-row">
					<th style="width: 15%;">Organization</th>
                    <th style="width: 15%;">Event Name</th>
					<th style="width: 25%;">Address</th>
					<th style="width: 15%;">Time</th>
                    <th style="width: 20%;">Produce</th>
					<th style="width: 10%;">Options</th>
				</tr>
			</table>
		</div>

		<script>
			var customerMap;
			var markers = {};

			function initMap() {
				customerMap = new google.maps.Map(document.getElementById("customerMap"), {
					zoom: 7,
					center: { lat: 35.394034, lng: -78.898621 }
        		});
			}

			function fillSearchTable() {
				var ajax = new XMLHttpRequest();				//Ajax request to controller
				ajax.open("GET", "controller/customer.php?request=GetEvents");
				ajax.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						var events = JSON.parse(this.responseText);

						var table = document.getElementById("customerSearchTable");

						for (var i = 0; i < events.length; i++) {
							console.log(events[i]);

							var rowCount = table.rows.length;
							var row = table.insertRow(rowCount);

							var cell1 = row.insertCell(0);
							cell1.innerHTML = events[i]["Organization"];
                            cell1.title = events[i]["OrgDescription"];

                            var cell2 = row.insertCell(1);
                            cell2.innerHTML = events[i]["Name"];
                            cell2.title = events[i]["Description"];

							var cell3 = row.insertCell(2);
							cell3.innerHTML = events[i]["Address"];

                            var cell4 = row.insertCell(3);
                            cell4.innerHTML = events[i]["Time"];

							var cell5 = row.insertCell(4);
							cell5.innerHTML = events[i]["Produce"];

							var cell6 = row.insertCell(5);
						    cell6.innerHTML = "<button type='submit' onclick='displayAddress(\"" + events[i]["Address"] + "\")'>Display</button>";
							cell6.innerHTML += "<button type='submit' onclick='removeAddress(\"" + events[i]["Address"] + "\")'>Remove</button>";
						}
					}
				}
				ajax.send();
			}

			function displayAddress(address) {
				console.log(address);

				const geocoder = new google.maps.Geocoder();
				geocoder.geocode({ address: address }, (results, status) => {
					if (status === "OK") {
						customerMap.setCenter(results[0].geometry.location);
						markers[address] = new google.maps.Marker({
							map: customerMap,
							position: results[0].geometry.location,
						});
					} else {
						alert(
						"Geocode was not successful for the following reason: " + status
						);
					}
				});
			}

			function removeAddress(address) {
				markers[address].setMap(null);
				delete markers[address];
			}

			function filter() {
				// Declare variables
				var input, filter, table, tr, td, i, txtValue;
				input = document.getElementById("customerSearchBar");
				filter = input.value.toUpperCase();
				table = document.getElementById("customerSearchTable");
				tr = table.getElementsByTagName("tr");

				// Loop through all table rows, and hide those who don't match the search query
				for (i = 0; i < tr.length; i++) {
					td = tr[i].getElementsByTagName("td")[4];
					if (td) {
						txtValue = td.textContent || td.innerText;
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
							tr[i].style.display = "";
						} else {
							tr[i].style.display = "none";
						}
					}
				}
			}
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgTxPAlALLkrywOYeHZLOovHSRLCq3a2M&callback=initMap&libraries=&v=weekly" async></script>
	</body>
</html>