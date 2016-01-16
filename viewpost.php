<?php require('includes/config.php'); 

$stmt = $db->prepare('SELECT ID, Title, Cont, Date FROM Posts WHERE ID = :ID');
$stmt->execute(array(':ID' => $_GET['id']));
$row = $stmt->fetch();

//if post does not exists redirect user.
if($row['ID'] == ''){
	header('Location: ./');
	exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Blog - <?php echo $row['Title'];?></title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

	<div id="wrapper">

		<h1>Blog</h1>
		<hr />
		<p><a href="./">Blog Index</a></p>


		<?php	
			echo '<div>';
				echo '<h1>'.$row['Title'].'</h1>';
				echo '<p>Posted on '.date('jS M Y', strtotime($row['Date'])).'</p>';
				echo '<p>'.$row['Cont'].'</p>';				
			echo '</div>';
		?>

	</div>

</body>
</html>