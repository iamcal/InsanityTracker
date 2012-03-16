<?
	include('init.php');

	$current = 'about';
	$title = 'About';
	include('head.txt');
?>

<header class="jumbotron subhead">
	<h1>About</h1>
</header>

<div class="row">
<div class="span8">

<h2>The Challenge</h2>

<p>
	When I started this project, I knew that there were over 11 million active World of Warcraft subscribers.
	To get a good starting pool of data, I knew I'd need to scan a great deal of them.
	I contacted the Blizzard API team to explain what I had planned and to get a high-volume API key - I wasn;t going to get far on 3000 queries per day.
</p>

<p>
	The key issue is where to start; there's no API method for finding all players.
	Blizzard suggested that I spider the aution houses on each realm, finding active players.
	With that list of players, I could then scan their guilds, grab the roster and then scan all players in the guild.
	This approach seemed pretty good - find active guilds, then spider their players.
</p>

<p>
	I decided to only include players that I could prove had gotten the achievement, so they had to be active on the armory.
	That means they have to be at least level 10 (I don't <i>think</i> you could get the achievement without hitting level 10,
	but I guess it's possible) and to have logged in during the last 30 days. This will definately exclude some old players
	who earned the achievement and then stopped playing, but I can't do much about those.
</p>


<h2>Finding Players</h2>

<p>
	I tried a slightly different approach to seed the initial guild list - I figured thaty most people who earn the achievement 
	will be in a guild who has killed a raid boss at some point. This excludes some people, but is probably a good representation
	of active, serious players.
</p>

<p>
	To get this list of guilds, I grabbed this tier 8 to 13 progression rankings from <a href="http://www.wowprogress.com/">wowprogress</a>.
	This included <i>most</i> guilds that had ever downed a raid boss, although not including amalgamated guilds,
	guilds who pug many of their raid spots or guilds who somehow slipped under the wowprogress radar.
	Because I went back to tier 8, quite a few of these guilds no longer exist.
	After generating the guild list, I fetched a roster from the Battle.net API and recorded every character who had hit 85.
	While it's possible to get the achievement at level 60, it's unlikely that somebody would have gotten the achievement
	and still be an active player without having reached 85.
</p>

<p>
	For each player, I then fetched their achievements and checked for Insane in the Membrane. If they had completed the achievement,
	I also recorded the date. I was then able to aggregate the results to figure out the top guilds and realms.
</p>

</div>
<div class="span4">

<div class="well">

	This website was created by Cal Henderson, who can often be found playing <a href="/insanity/us/hyjal/Bees/">Bees</a>
	on <a href="/insanity/us/hyjal/">Hyjal-US</a>.<br />
	<br />
	Cal has also created a number of <a href="http://code.iamcal.com/wow/">WoW addons</a>,
	created <a href="http://www.hunterloot.com">hunterloot.com</a>
	and lead the development of the new <a href="http://www.warcraftpets.com/">warcraftpets.com</a>.
	He sometimes blogs about WoW on <a href="http://world-of-theorycraft.tumblr.com/">World of Theorycraft</a>.<br />
	<br />
	His personal website is <a href="http://www.iamcal.com">iamcal.com</a>

</div>

<div class="well">

	Interested in how this all works? You take browse the source code on github (link to come shortly).
</div>

</div>
</div>


<?
	include('foot.txt');
?>
