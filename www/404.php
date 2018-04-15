<?
	include('../include/init.php');


	#
	# try adding a slash at the end if:
	# 1) we've not already mapped it through a RewriteRule
	# 2) it doesn't look like a filename
	# 3) it doesn't already have a slash at the end
	#

	$url  = $_SERVER['REQUEST_URI'];
	$orig = $_SERVER['REDIRECT_URL'];

	if ($url == $orig){
		$last_part = array_pop((explode('/', $url)));
		if (preg_match('!^[^\.]+$!', $last_part)){

			header("location: $url/");
			exit;
		}
	}


	$error = "The page you requested could not be found.";
	include('notfound.php');
?>
