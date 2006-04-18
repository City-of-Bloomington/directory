<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
?>
<div id="mainContent">
	<div><div class="titleBar">Departments</div>
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>
	<table>
	<tr><td><ul><li><a href="viewLocation.php?category=Bloomington%20Transit;department=Bloomington%20Transit;location=Bloomington%20Transit">Bloomington Transit</a></li>
				<li><a href="viewCategory.php?category=City%20Hall">City Hall</a>
					<ul><li><a href="viewDepartment.php?category=City%20Hall;department=City%20Clerk">City Clerk</a></li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=Community%20and%20Family%20Resources">Community and Family Resources (CFRD)</a></li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=Controller">Controller</a></li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=Council%20Office">Council Office</a></li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=Employee%20Services">Employee Services</a></li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=HAND">Housing and Neighborhood Development (HAND)</a></li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=ITS">Information &amp; Technology Services (ITS)</a></li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=Legal">Legal</a>
							<ul><li><a href="viewDepartment.php?category=City%20Hall;department=Risk%20Management">Risk Management</a></li></ul>
						</li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=Office%20of%20the%20Mayor">Office of the Mayor (OOTM)</a></li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=Parks and Recreation">Parks and Recreation</a>
							<ul><li><a href="viewLocation.php?category=City%20Hall;department=Parks%20and%20Recreation;location=Adams%20Street">Adams Street</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Parks%20and%20Recreation;location=BACC">Bloomington Adult Community Center (BACC)</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Parks%20and%20Recreation;location=Banneker%20Center">Banneker Center</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Parks%20and%20Recreation;location=Cascades%20Golf%20Course">Cascades Golf Course</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Parks%20and%20Recreation;location=Frank%20Southern%20Center">Frank Southern Center</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Parks%20and%20Recreation;location=Juke%20Box%20Community%20Building">Juke Box Community Building</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Parks%20and%20Recreation;location=Rosehill">Rosehill</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Parks%20and%20Recreation;location=Showers">Showers</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Parks%20and%20Recreation;location=Twin%20Lakes">Twin Lakes</a></li>
							</ul>
						</li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=Planning">Planning</a></li>
						<li><a href="viewDepartment.php?category=City%20Hall;department=Public%20Works">Public Works</a>
							<ul><li><a href="viewLocation.php?category=City%20Hall;department=Public%20Works;location=Animal%20Shelter">Animal Shelter</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Public%20Works;location=Engineering">Engineering</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Public%20Works;location=Facilities">Facilities</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Public%20Works;location=Fleet%20Maintenance">Fleet Maintenance</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Public%20Works;location=Parking%20Enforcement">Parking Enforcement</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Public%20Works;location=Sanitation">Sanitation</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Public%20Works;location=Showers">Showers</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Public%20Works;location=Street">Street</a></li>
								<li><a href="viewLocation.php?category=City%20Hall;department=Public%20Works;location=Traffic">Traffic</a></li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
		</td>
		<td><ul>
				<li><a href="viewCategory.php?category=Fire">Fire</a>
					<ul><li><a href="viewLocation.php?category=Fire;department=Fire;location=Fire%20Administration">Fire Administration</a></li>
						<li><a href="viewLocation.php?category=Fire;department=Fire;location=Fire%20HQ">Fire HQ</a></li>
					</ul>
				</li>
				<li><a href="viewCategory.php?category=Police">Police</a>
					<ul><li><a href="viewLocation.php?category=Police;department=Administration;location=Police%20HQ">Administration</a></li>
						<li><a href="viewLocation.php?category=Police;department=Detectives;location=Police%20HQ">Detectives</a></li>
						<li><a href="viewLocation.php?category=Police;department=Dispatch;location=Police%20HQ">Dispatch</a></li>
						<li><a href="viewLocation.php?category=Police;department=Records;location=Police%20HQ">Records</a></li>
						<li><a href="viewLocation.php?category=Police;department=Uniformed%20Officers;location=Police%20HQ">Uniformed Officers</a></li>
					</ul>
				</li>

				<li><a href="viewCategory.php?category=Utilities">Utilities</a>
					<ul><li><a href="viewDepartment.php?category=Utilities;department=Accounting">Accounting</a></li>
						<li><a href="viewDepartment.php?category=Utilities;department=Administration">Administration</a></li>
						<li><a href="viewDepartment.php?category=Utilities;department=Blucher%20Poole">Blucher Poole</a></li>
						<li><a href="viewDepartment.php?category=Utilities;department=Communication">Communication</a></li>
						<li><a href="viewDepartment.php?category=Utilities;department=Customer%20Services">Customer Services</a></li>
						<li><a href="viewDepartment.php?category=Utilities;department=Dillman">Dillman</a></li>
						<li><a href="viewDepartment.php?category=Utilities;department=Engineering">Engineering</a></li>
						<li><a href="viewDepartment.php?category=Utilities;department=Monroe">Monroe</a></li>
						<li><a href="viewDepartment.php?category=Utilities;department=Purchasing">Purchasing</a></li>
						<li><a href="viewDepartment.php?category=Utilities;department=T%26D">Transmission &amp; Distribution (T&amp;D)</a></li>
					</ul>
				</li>
			</ul>
	</td></tr>
	</table>
	</div>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>
