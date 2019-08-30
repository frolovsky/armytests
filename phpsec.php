<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Recepts PHP</title>
</head>
<body>
	<h1>Рецепты PHP</h1>
	<br>
	<br>
	<div>
		<?php 

		$info = array('кофе', 'коричневый', 'кофеин');
		$desc = array('desc', 'test', 'Миша');

		array_multisort($info, $desc);

		print_r($info);
		echo '<br>';
		print_r($desc);

		?>
	</div>
	
</body>
</html>

