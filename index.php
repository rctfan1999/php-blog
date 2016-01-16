<?php require('includes/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
</head>
<body>

	<div id="wrapper">

		<h1>Blog</h1>
		<hr />

		<?php
			try {

				$stmt = $db->query('SELECT ID, Title, PostDesc, Date FROM Posts ORDER BY ID DESC');
				while($row = $stmt->fetch()){
					
					echo '<div>';
						echo '<h1><a href="viewpost.php?id='.$row['ID'].'">'.$row['Title'].'</a></h1>';
						echo '<p>Posted on '.date('jS M Y H:i:s', strtotime($row['Date'])).'</p>';
						echo '<p>'.$row['PostDesc'].'</p>';				
						echo '<p><a href="viewpost.php?id='.$row['ID'].'">Read More</a></p>';				
					echo '</div>';

				}

			} catch(PDOException $e) {
			    echo $e->getMessage();
			}
		?>

	</div>


</body>
</html>