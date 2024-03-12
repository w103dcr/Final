<?php
	
	session_start();

	require_once('classes/dbhandler.php');
	require_once('classes/user.php');

	$dbhandler = new DBHandler('epms', 'epms_user', 'kV4mCphx2!F2KWgR', '127.0.0.1');
	$errors = [];
	
	// if(isset($_SESSION['user'])){
		
	// }
	
	// Login form submitted
	if(isset($_POST['login'])){
		if(!isset($_POST['username']) || empty($_POST['username'])){
			$errors['username'] = "Username is required.";
		}
		
		if(!isset($_POST['password']) || empty($_POST['password'])){
			$errors['password'] = "Password is required.";
		}
		
		if(empty($errors)){
			$user = new User();
			
			if($user->login($_POST['username'], $_POST['password'], $dbhandler)){
				// If credentials are valid, store user object in session variable
				// and redirect to main.php
				$_SESSION['user'] = serialize($user);
				header("Location: main.php");
			}
			else{
				$errors['login'] = "Incorrect username or password.";
			}
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
		<div class="wrapper login h-100-vh py-3 m-auto d-flex flex-column justify-content-center align-items-center">
			<form id="employee-management-login" class="w-100 p-3" action="index.php" method="post">
				<div class="text-center">
					<h2>Welcome to the Employee Management System</h2>
					<p>Please sign in to continue.</p>
				</div>
				<div class="form-group my-3">
					<label for="username">Username</label>
					<input class="form-control" type="text" name="username" aria-describedby="emailHelp" placeholder="Username">
				</div>
				<div class="form-group my-3">
					<label for="password">Password</label>
					<input class="form-control" type="password" name="password" placeholder="Password">
				</div>
				<button class="btn btn-primary" type="submit" name="login">Login</button>
			</form>
			<?php if(!empty($errors)): ?>
				<div class="errors w-100 my-3">
					<?php foreach($errors as $error): ?>
						<div class="alert alert-danger" role="alert">
							<?php echo $error; ?>
						</div>
					<?php endforeach ?>
				</div>
			<?php endif ?>
		</div>
	</body>
</html>