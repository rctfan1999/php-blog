<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
//if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Add User</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
</head>
<body>

<div id="wrapper">

	<?php include('menu.php');?>
	<p><a href="users.php">User Admin Index</a></p>

	<h2>Add User</h2>

	<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);

		//very basic validation
		if($Username ==''){
			$error[] = 'Please enter the Username.';
		}

		if($Password ==''){
			$error[] = 'Please enter the Password.';
		}

		if($PasswordConfirm ==''){
			$error[] = 'Please confirm the Password.';
		}

		if($Password != $PasswordConfirm){
			$error[] = 'Passwords do not match.';
		}

		if($Email ==''){
			$error[] = 'Please enter the Email address.';
		}

		if(!isset($error)){

			$hashedPassword = $user->Password_hash($Password, Password_BCRYPT);

			try {

				//insert into database
				$stmt = $db->prepare('INSERT INTO Users (Username, Password, Email) VALUES (:Username, :Password, :Email)') ;
				$stmt->execute(array(
					':Username' => $Username,
					':Password' => $hashedPassword,
					':Email' => $Email
				));

				//redirect to index page
				header('Location: users.php?action=added');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}

	}

	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo '<p class="error">'.$error.'</p>';
		}
	}
	?>

	<form action='' method='post'>

		<p><label>Username</label><br />
		<input type='text' name='Username' value='<?php if(isset($error)){ echo $_POST['Username'];}?>'></p>

		<p><label>Password</label><br />
		<input type='Password' name='Password' value='<?php if(isset($error)){ echo $_POST['Password'];}?>'></p>

		<p><label>Confirm Password</label><br />
		<input type='Password' name='PasswordConfirm' value='<?php if(isset($error)){ echo $_POST['PasswordConfirm'];}?>'></p>

		<p><label>Email</label><br />
		<input type='text' name='Email' value='<?php if(isset($error)){ echo $_POST['Email'];}?>'></p>
		
		<p><input type='submit' name='submit' value='Add User'></p>

	</form>

</div>
