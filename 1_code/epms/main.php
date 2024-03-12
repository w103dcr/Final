<?php
	/**
	*	Employee Management System
	*
	*	Known Issues:
	*	- EID has a small chance of already existing in DB upon user creation. Unknown results if this happens.
	*	- No server side validation on inputs. Unknown results if user attempts to put a string as the SSN, for example.
	*/
	
	// Dependencies
	require_once('classes/dbhandler.php');
	require_once('classes/epms.php');
	require_once('classes/employee.php');
	require_once('classes/user.php');
	
	// Start session and retrieve user object,
	// redirect to index.php if session is not set (meaning no user is signed in)
	session_start();
	
	if(isset($_SESSION['user'])){
		$user = unserialize($_SESSION['user']);
	}else{
		header('Location: index.php');
	}
	
	// Establish database connection and create Epms object.
	$dbhandler = new DBHandler('epms', 'epms_user', 'kV4mCphx2!F2KWgR', '127.0.0.1');
	$epms = new Epms($dbhandler);
	
	// Load initial results, if search URL parameter is present, use it instead.
	if(isset($_GET['search']) && !empty($_GET['search'])){
		$results = $epms->searchEmployees($_GET['search']);
	}else{
		$results = $epms->getEmployees();
	}

	/* Server side form submission handling */
	$errors = [];
	
	// Add-employee button clicked
	if(isset($_POST['add-employee'])){
		if(!isset($_POST['add-eid']) || !isset($_POST['add-ssn'])
		|| !isset($_POST['add-first-name']) || !isset($_POST['add-last-name'])
		|| !isset($_POST['add-sex']) || !isset($_POST['add-birth-date'])
		|| !isset($_POST['add-phone-number']) || !isset($_POST['add-address'])
		|| !isset($_POST['add-email']) || !isset($_POST['add-hire-date'])
		|| !isset($_POST['add-lid']) || !isset($_POST['add-pay-rate']) 
		|| empty($_POST['add-eid']) || empty($_POST['add-ssn'])
		|| empty($_POST['add-first-name']) || empty($_POST['add-last-name'])
		|| empty($_POST['add-sex']) || empty($_POST['add-birth-date'])
		|| empty($_POST['add-phone-number']) || empty($_POST['add-address'])
		|| empty($_POST['add-email']) || empty($_POST['add-hire-date'])
		|| empty($_POST['add-lid']) || empty($_POST['add-pay-rate'])){
			$errors['required'] = "One or more required fields are empty.";
		}
		
		if($user->getGroup() != "admin"){
			$errors['permissions'] = "You do not have permissions to add a new employee.";
		}
		
		if(empty($errors)){
			$employee = new Employee($_POST['add-eid'], $_POST['add-ssn'],  $_POST['add-first-name'], $_POST['add-last-name'], $_POST['add-sex'], $_POST['add-birth-date'], $_POST['add-phone-number'], $_POST['add-address'], $_POST['add-email'], $_POST['add-hire-date'], $_POST['add-lid'], $_POST['add-pay-rate']);
			$epms->addEmployee($employee);
			
			header("Refresh:0");
		}
	}
	
	// Delete-employee button clicked
	if(isset($_POST['delete-employee'])){
		if(isset($_POST['manage-eid']) && !empty($_POST['manage-eid'])){ // If the manage-eid hidden field is empty, a fatal error has occured and the application should die.
			if($user->getGroup() != "admin"){
				$errors['permissions'] = "You do not have permissions to delete an employee.";
			}
			
			if(empty($errors)){
				$epms->deleteEmployee($_POST['manage-eid']);
				
				header("Refresh:0");
			}
		}else{
			die('Oops! A critical error has occured. Please contact the system administrator.');
		}
	}
	
	// Submit-edit-employee button clicked
	if(isset($_POST['submit-edit-employee'])){
		if(isset($_POST['manage-eid']) && !empty($_POST['manage-eid'])){ // If the manage-eid hidden field is empty, a fatal error has occured and the application should die.
			if(!isset($_POST['edit-eid']) || !isset($_POST['edit-ssn'])
			|| !isset($_POST['edit-first-name']) || !isset($_POST['edit-last-name'])
			|| !isset($_POST['edit-sex']) || !isset($_POST['edit-birth-date'])
			|| !isset($_POST['edit-phone-number']) || !isset($_POST['edit-address'])
			|| !isset($_POST['edit-email']) || !isset($_POST['edit-hire-date'])
			|| !isset($_POST['edit-lid']) || !isset($_POST['edit-pay-rate']) 
			|| empty($_POST['edit-eid']) || empty($_POST['edit-ssn'])
			|| empty($_POST['edit-first-name']) || empty($_POST['edit-last-name'])
			|| empty($_POST['edit-sex']) || empty($_POST['edit-birth-date'])
			|| empty($_POST['edit-phone-number']) || empty($_POST['edit-address'])
			|| empty($_POST['edit-email']) || empty($_POST['edit-hire-date'])
			|| empty($_POST['edit-lid']) || empty($_POST['edit-pay-rate'])){
				$errors['required'] = "One or more required fields are empty.";
			}
			
			if($user->getGroup() != "admin"){
				$errors['permissions'] = "You do not have permissions to edit an employee.";
			}
			
			if(empty($errors)){
				$employee = new Employee($_POST['edit-eid'], $_POST['edit-ssn'],  $_POST['edit-first-name'], $_POST['edit-last-name'], $_POST['edit-sex'], $_POST['edit-birth-date'], $_POST['edit-phone-number'], $_POST['edit-address'], $_POST['edit-email'], $_POST['edit-hire-date'], $_POST['edit-lid'], $_POST['edit-pay-rate']);
				$epms->editEmployee($_POST['manage-eid'], $employee);
			
				header("Refresh:0");
			}
		}else{
			die('Oops! A critical error has occured. Please contact the system administrator.');
		}
	}
	
	// Search button clicked
	if(isset($_POST['search'])){
		if(isset($_POST['search-query']) && !empty($_POST['search-query'])){				
			header("Location: ?search=" . urlencode($_POST['search-query']));
		}
	}
	
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width">
		<title>Employee Management System</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer">
		<link rel="stylesheet" type="text/css" href="styles.css">
	</head>
	<body>
		<div class="header py-3 px-5 bg-light border-bottom d-flex justify-content-between align-items-center">
			<div class="title">
				<h2>Employee Management System</h2>
			</div>
			<div class="user">
				<p class="m-0">Welcome, <?php echo $user->getUsername(); ?></p>
				<a href="logout.php">Logout</a>
			</div>
		</div>
		<div class="wrapper py-3 m-auto">
			<?php if(!empty($errors)): ?>
				<div class="errors w-100 my-3">
					<?php foreach($errors as $error): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo $error; ?>
						</div>
					<?php endforeach ?>
				</div>
			<?php endif ?>
			<?php if($results): ?>
				<div class="table-responsive">
					<form id="employee-management" action="main.php" method="post">
						<input class="manage-eid" type="hidden" name="manage-eid" value="">
						<div class="my-2 d-flex justify-content-end align-items-center">
							<input class="form-control w-25 rounded-left" type="search" name="search-query" placeholder="Search" value="<?php echo (isset($_GET['search']) && !empty($_GET['search'])) ? htmlspecialchars($_GET['search']) : "" ?>" aria-label="Search">
							<button type="submit" class="search btn bg-primary text-white" name="search"><i class="fas fa-search" aria-hidden="true"></i></button>
						</div>
						<table class="table table-striped table-hover align-middle">
							<thead class="thead-dark">
								<tr>
									<th>EID</th>
									<th>SSN</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Sex</th>
									<th>Birth Date</th>
									<th>Phone Number</th>
									<th>Address</th>
									<th>Email</th>
									<th>Hire Date</th>
									<th>Location</th>
									<th>Pay Rate</th>
									<th>Edit</th>
									<th>Delete</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($results as $row): ?>
								<tr class="table-row">
									<td class="eid"><?php echo htmlspecialchars($row['EID']); ?></td>
									<td class="ssn"><?php echo htmlspecialchars($epms->formatSSN($row['SSN'])); ?></td>
									<td class="first-name"><?php echo htmlspecialchars($row['first_name']); ?></td>
									<td class="last-name"><?php echo htmlspecialchars($row['last_name']); ?></td>
									<td class="sex"><?php echo htmlspecialchars($row['sex']); ?></td>
									<td class="birth-date"><?php echo htmlspecialchars($row['birth_date']); ?></td>
									<td class="phone-number"><?php echo htmlspecialchars($epms->formatPhoneNumber($row['contact_number'])); ?></td>
									<td class="address"><?php echo htmlspecialchars($row['address']); ?></td>
									<td class="email"><?php echo htmlspecialchars($row['email']); ?></td>
									<td class="hire-date"><?php echo htmlspecialchars($row['hire_date']); ?></td>
									<td class="city"><?php echo htmlspecialchars($row['city']); ?></td>
									<td class="pay-rate"><?php echo htmlspecialchars($row['pay_rate']); ?></td>
									<td class="text-center"><button type="button" class="edit btn" name="edit-employee" data-eid="<?php echo htmlspecialchars($row['EID']); ?>"><i class="fa-solid fa-user-pen"></i></button></td>
									<td class="text-center"><button type="submit" class="delete btn" name="delete-employee" data-eid="<?php echo htmlspecialchars($row['EID']); ?>" data-full-name="<?php echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']); ?>"><i class="fa-solid fa-trash"></i></button></td>				
								</tr>
							<?php endforeach ?>
								<tr class="table-row">
									<td><input class="form-control" type="number" name="add-eid" maxlength="5" value="<?php echo $epms->generateEID(); ?>" readonly></td>
									<td><input class="form-control" type="text" name="add-ssn" maxlength="11" placeholder="555-55-5555"></td>
									<td><input class="form-control" type="text" name="add-first-name" placeholder="First Name"></td>
									<td><input class="form-control" type="text" name="add-last-name" placeholder="Last Name"></td>
									<td>
										<select name="add-sex">
											<option value="M">Male</option>
											<option value="F">Female</option>
										</select>
									</td>
									<td><input class="form-control" type="date" name="add-birth-date" placeholder="YYYY-MM-DD"></td>
									<td><input class="form-control" type="tel" name="add-phone-number" maxlength="12" placeholder="555-555-5555"></td>
									<td><input class="form-control" type="text" name="add-address" placeholder="123 N. Main St."></td>
									<td><input class="form-control" type="email" name="add-email" placeholder="example@example.com"></td>
									<td><input class="form-control" type="date" name="add-hire-date" placeholder="YYYY-MM-DD"></td>
									<td>
										<select name="add-lid">
											<option value="1">Chicago</option>
											<option value="2">Dayton</option>
											<option value="3">New Jersey</option>
										</select>
									</td>
									<td>
										<select name="add-pay-rate">
											<option value="G1">G1</option>
											<option value="G2">G2</option>
											<option value="G3">G3</option>
											<option value="G4">G4</option>
											<option value="G5">G5</option>
											<option value="G6">G6</option>
											<option value="G7">G7</option>
											<option value="G8">G8</option>
										</select>
									</td>
									<td class="text-center" colspan="2"><button type="submit" class="btn btn-success" name="add-employee">Add</button></td>
								</tr>
							</tbody>
						</table>
					</form>
					<?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
						<div class="my-2 d-flex justify-content-center align-items-center">
							<a class="btn btn-primary" href="main.php" role="button">Return Home</a>
						</div>
					<?php endif ?>
				</div>
			<?php else: ?>
				<div class="alert alert-primary" role="alert">
					<h4>No results!</h4>
					<hr>
					<p>Your search returned no results, or an error has occured. <a href="main.php">Click here to return to the homepage</a>.</p>
				</div>
			<?php endif ?>
		</div>
		<script>
			/* Client side form submission handling */
			let currentlyEditing = false;
			let table = document.querySelector(".table");
			let eidHiddenInput = document.querySelector("#employee-management input.manage-eid");
			let originalRowInnerHTML;
			
			/**
			*	Event Delegation. Add event listener to table and handle events
			*	there as opposed to adding an event listener on each button in the table.
			*/
			table.onclick = function(event){
				let target = event.target;
				
				// Delete button clicked
				if(target.closest('.delete')){
					eidHiddenInput.value = target.closest('.delete').dataset.eid; // Set hidden input field to EID of clicked button data attribute

					if(confirm('Are you sure you want to delete employee "' + target.closest('.delete').dataset.fullName + '"?')){
						return true;
					}else{
						return false;
					}
				}
				
				// Edit button clicked
				if(target.closest('.edit')){
					if(!currentlyEditing){
						currentlyEditing = true; // User is now editing
						
						eidHiddenInput.value = target.closest('.edit').dataset.eid; // Set hidden input field to EID of clicked button data attribute
						
						target = target.closest('.table-row'); // Set target to parent row
						originalRowInnerHTML = target.innerHTML; // Save current HTML of parent row
						
						target.innerHTML = '<td><input class="form-control" type="number" name="edit-eid" maxlength="5" value="'+ target.querySelector('.eid').innerHTML + '"></td>' +
											'<td><input class="form-control" type="text" name="edit-ssn" maxlength="11" value="'+ target.querySelector('.ssn').innerHTML + '"></td>' +
											'<td><input class="form-control" type="text" name="edit-first-name" value="'+ target.querySelector('.first-name').innerHTML + '"></td>' +
											'<td><input class="form-control" type="text" name="edit-last-name" value="'+ target.querySelector('.last-name').innerHTML + '"></td>' +
											'<td>' +
												'<select name="edit-sex">' +
													'<option value="M" ' + (target.querySelector('.sex').innerHTML == "M" ? "selected" : "") + '>Male</option>' +
													'<option value="F" ' + (target.querySelector('.sex').innerHTML == "F" ? "selected" : "") + '>Female</option>' +
												'</select>' +
											'</td>' +
											'<td><input class="form-control" type="date" name="edit-birth-date" value="'+ target.querySelector('.birth-date').innerHTML + '"></td>' +
											'<td><input class="form-control" type="tel" name="edit-phone-number" maxlength="12" value="'+ target.querySelector('.phone-number').innerHTML + '"></td>' +
											'<td><input class="form-control" type="text" name="edit-address" value="'+ target.querySelector('.address').innerHTML + '"></td>' +
											'<td><input class="form-control" type="email" name="edit-email" value="'+ target.querySelector('.email').innerHTML + '"></td>' +
											'<td><input class="form-control" type="date" name="edit-hire-date" value="'+ target.querySelector('.hire-date').innerHTML + '"></td>' +
											'<td>' +
												'<select name="edit-lid">' +
													'<option value="1" ' + (target.querySelector('.city').innerHTML == "Chicago" ? "selected" : "") + '>Chicago</option>' +
													'<option value="2" ' + (target.querySelector('.city').innerHTML == "Dayton" ? "selected" : "") + '>Dayton</option>' +
													'<option value="3" ' + (target.querySelector('.city').innerHTML == "New Jersey" ? "selected" : "") + '>New Jersey</option>' +
												'</select>' +
											'</td>' +
											'<td>' +
												'<select name="edit-pay-rate">' +
													'<option value="G1" ' + (target.querySelector('.pay-rate').innerHTML == "G1" ? "selected" : "") + '>G1</option>' +
													'<option value="G2" ' + (target.querySelector('.pay-rate').innerHTML == "G2" ? "selected" : "") + '>G2</option>' +
													'<option value="G3" ' + (target.querySelector('.pay-rate').innerHTML == "G3" ? "selected" : "") + '>G3</option>' +
													'<option value="G4" ' + (target.querySelector('.pay-rate').innerHTML == "G4" ? "selected" : "") + '>G4</option>' +
													'<option value="G5" ' + (target.querySelector('.pay-rate').innerHTML == "G5" ? "selected" : "") + '>G5</option>' +
													'<option value="G6" ' + (target.querySelector('.pay-rate').innerHTML == "G6" ? "selected" : "") + '>G6</option>' +
													'<option value="G7" ' + (target.querySelector('.pay-rate').innerHTML == "G7" ? "selected" : "") + '>G7</option>' +
													'<option value="G8" ' + (target.querySelector('.pay-rate').innerHTML == "G8" ? "selected" : "") + '>G8</option>' +
												'</select>' +
											'</td>' +
											'<td class="text-center"><button type="submit" class="submit-edit btn" name="submit-edit-employee"><i class="fa-solid fa-check"></i></button></td>' +
											'<td class="text-center"><button type="button" class="cancel-edit btn" name="cancel-edit-employee"><i class="fa-solid fa-xmark"></i></button></td>';
					}
				}
				
				// Cancel edit clicked
				if(target.closest('.cancel-edit')){
					currentlyEditing = false; // User is no longer editing
					
					target.closest('.table-row').innerHTML = originalRowInnerHTML; //Restore HTML of original row
				}
				
				// Forms use first occurence of <button> on enter key.
				// Overwrite default action to force submit-edit click on enter keydown event.
				table.addEventListener("keydown", function(event){
					if (event.which == 13 || event.keyCode == 13){
						event.preventDefault();
						
						table.querySelector(".submit-edit").click();
					}
				});
			}
		</script>
	</body>
</html>