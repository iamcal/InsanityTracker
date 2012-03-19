<?
	$region = $_SERVER['argv'][1];
	$match = "SCREEN.*fetch_characters.php";
	$match .= $region ? " $region" : '$';

	$num = 0;
	$text = explode("\n", shell_exec("ps ax"));
	foreach ($text as $line){
		if (preg_match('!'.$match.'!', $line)){
			$num++;
		}
	}

	echo "running: $num\n";

	$max = 10;
	$launch = $max - min($max, $num);
	if (!launch) exit;

	echo "launch $launch more to keep jobs at $max ";

	for ($i=0; $i<$launch; $i++){

		echo shell_exec("screen -d -m /usr/bin/php -q /var/www/insanitytracker.com/scrape/fetch_characters.php $region");

		echo '.';
	}

	echo " done\n";
