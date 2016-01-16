<?php //include config
require_once('../includes/config.php');

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin - Edit User</title>
  <link rel="stylesheet" href="../style/normalize.css">
  <link rel="stylesheet" href="../style/main.css">
</head>
<body>

<div id="wrapper">

	<?php include('menu.php');?>
	<p><a href="users.php">User Admin Index</a></p>

	<h2>Edit User</h2>


	<?php

	//if form has been submitted process it
	if(isset($_POST['submit'])){

		//collect form data
		extract($_POST);

		//very basic validation
		if($Username ==''){
			$error[] = 'Please enter the Username.';
		}

		if( strlen($Password) > 0){

			if($Password ==''){
				$error[] = 'Please enter the Password.';
			}

			if($PasswordConfirm ==''){
				$error[] = 'Please confirm the Password.';
			}

			if($Password != $PasswordConfirm){
				$error[] = 'Passwords do not match.';
			}

		}
		

		if($Email ==''){
			$error[] = 'Please enter the Email address.';
		}

		if(!isset($error)){

			try {

				if(isset($Password)){

					$hashedPassword = $user->Password_hash($Password, Password_BCRYPT);

					//update into database
					$stmt = $db->prepare('UPDATE Users SET Username = :Username, Password = :Password, Email = :Email WHERE ID = :ID') ;
					$stmt->execute(array(
						':Username' => $Username,
						':Password' => $hashedPassword,
						':Email' => $Email,
						':ID' => $ID
					));


				} else {

					//update database
					$stmt = $db->prepare('UPDATE Users SET Username = :Username, Email = :Email WHERE ID = :ID') ;
					$stmt->execute(array(
						':Username' => $Username,
						':Email' => $Email,
						':ID' => $ID
					));

				}
				

				//redirect to index page
				header('Location: users.php?action=updated');
				exit;

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}

		}

	}

	?>


	<?php
	//check for any errors
	if(isset($error)){
		foreach($error as $error){
			echo $error.'<br />';
		}
	}

		try {

			$stmt = $db->prepare('SELECT ID, Username, Email FROM Users WHERE ID = :ID') ;
			$stmt->execute(array(':ID' => $_GET['id']));
			$row = $stmt->fetch(); 

		} catch(PDOException $e) {
		    echo $e->getMessage();
		}

	?>

	<form action='' method='post'>
		<input type='hidden' name='ID' value='<?php echo $row['ID'];?>'>

		<p><label>Username</label><br />
		<input type='text' name='Username' value='<?php echo $row['Username'];?>'></p>

		<p><label>Password (only to change)</label><br />
		<input type='Password' name='Password' value=''></p>

		<p><label>Confirm Password</label><br />
		<input type='Password' name='PasswordConfirm' value=''></p>

		<p><label>Email</label><br />
		<input type='text' name='Email' value='<?php echo $row['Email'];?>'></p>

		<p><input type='submit' name='submit' value='Update User'></p>

	</form>

</div>

</body>
</html>	
