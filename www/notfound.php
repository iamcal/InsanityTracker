<?php
	$title = 'Page not found';
	include('../templates/head.php');
?>

<header class="jumbotron">
	<h1>Page not found</h1>
	<p class="lead"><?=$error ?? ''?></p>
</header>

<?php
	include('../templates/foot.php');

	exit;
?>
