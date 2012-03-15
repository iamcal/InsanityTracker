<?
	$title = 'Page not found';
	include('head.txt');
?>

<header class="jumbotron">
	<h1>Page not found</h1>
	<p class="lead"><?=$error?></p>
</header>

<?
	include('foot.txt');

	exit;
?>
