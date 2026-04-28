<?
	$counts = array();

	$text = explode("\n", shell_exec("ps ax"));
	foreach ($text as $line){
		if (preg_match('!SCREEN.*fetch_characters.php\s*(\w\w)?!', $line, $m)){

			$key = ($m[1] ?? '') ? $m[1] : 'default';
			$counts[$key] = ($counts[$key] ?? 0) + 1;
		}
	}

	echo "found ".count($counts)." job types:\n";
	foreach ($counts as $k => $v) echo "$k: $v\n";

