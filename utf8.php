<?
	header("Content-type: text/html; charset=UTF-8");

	if ($_POST['text']){
		$text = preg_replace_callback('![^a-zA-Z0-9]!', 'local_char', $_POST['text']);

		echo "OUT: ".HtmlSpecialChars($text)."<hr />";
	}

	function local_char($m){
		$code = sprintf('%02x', ord($m[0]));
		return '\\x'.$code;
	}
?>

<form action="utf8.php" method="post">
<textarea name="text"></textarea><br />
<input type="submit" />
</form>
