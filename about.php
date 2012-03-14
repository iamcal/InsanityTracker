<?
	include('init.php');

	$current = 'about';
	include('head.txt');
?>

<h1>About</h1>

<h2>Method</h2>

<p>
	To get the initial set of data, I scanned all of the guilds that were ranked in tier 8 to 13 progression on <a href="http://www.wowprogress.com/">wowprogress</a>.
	This included <i>most</i> guilds that had ever downed a raid boss, although not including amalgamated guilds,
	guilds who pug many of their raid spots or guilds who somehow slipped under the wowprogress radar.
	Because I went back to tier 8, quite a few of these guilds no longer exist.
	After generating the guild list, I fetched a roster from the Battle.net API and recorded every character who had hit 85.
	While it's possible to get the achievement at level 60, it's unlikely that somebody would have gotten the achievement
	and still be an active player without having reached 85 (and they need to be active to be on the armory).
</p>

<p>
	For each player, I then fetched their achievements and checked for Insane in the Membrane
	 This page contains some generalized statistics from this data set.
</p>



<?
	include('foot.txt');
?>
