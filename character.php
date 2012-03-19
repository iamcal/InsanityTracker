<?php

	include('init.php');

	$realm = check_realm("/character/REGION/REALM/$_GET[name]/");

	$region_enc = AddSlashes($_GET['region']);
	$realm_enc = AddSlashes($_GET['realm']);
	$name_enc = AddSlashes($_GET['name']);

	$character = db_single(db_fetch("SELECT * FROM characters WHERE region='$region_enc' AND realm='$realm_enc' AND name='$name_enc'"));
	if (!$character['region']){

		$add_url = "/add/";
		$add_url .= "?r=".urlencode($_GET['region'])."-".urlencode($_GET['realm']);
		$add_url .= "&n=".urlencode($_GET['name']);
		$add_url .= "&auto=1";

		header("location: $add_url");
		exit;
	}

	$guild_enc = AddSlashes($character['guild']);
	$guild = db_single(db_fetch("SELECT * FROM guilds WHERE region='$character[region]' AND realm='$realm_enc' AND name='$guild_enc'"));
	if ($guild['region']){

		$realm_url = rawurlencode($guild['realm']);
		$name_url = rawurlencode($guild['name']);

		$guild['url'] = "/guilds/$guild[region]/$realm_url/$name_url/";
	}


	$host = $GLOBALS['cfg']['bnet_region_hosts'][$realm['region']];
	$realm_url = rawurlencode($character['realm']);
	$name_url = rawurlencode($character['name']);
	$armory = "http://$host/wow/en/character/{$realm_url}/{$name_url}/";


	$api_url = "http://$host/api/wow/character/{$realm_url}/{$name_url}?fields=achievements";
	$url_base = "http://{$host}/static-render/{$realm['region']}/";


	$add_r = urlencode($_GET['region']).'-'.urlencode($_GET['realm']);
	$add_n = urlencode($_GET['name']);
	$refresh_url = "/add/?r={$add_r}&n={$add_n}";


	$class = $classes[$character['class_id']][0];
	$race = $races[$character['race_id']][0];
	assign_patch($character);

	$title = HtmlSpecialChars($character['name']);
	include('head.txt');
?>

<header class="contextual">
	<a href="/">Insanity</a> /
	<a href="/<?=$realm['region']?>/"><?=format_region($realm['region'])?></a> /
	<a href="/<?=$realm['region']?>/<?=$realm['slug']?>/"><?=HtmlSpecialChars(realm_name($realm))?></a> /

	<div style="margin-top: 10px; margin-bottom: 40px">
		<div style="float: left; width: 84px; height: 84px; background-image: url(/img/placeholder.gif); margin-right: 10px;">
			<img src="" id="thumbnail" width="84" height="84" />
		</div>
	
		<h1 style="margin-bottom: 0"><?=HtmlSpecialChars($character['name'])?></h1>
		Level <?=$character['level']?> <?=$race?> <?=$class?>
	</div>
</header>

<? if ($_GET['added']){ ?>

<div class="alert alert-success">
	<i class="icon-ok"></i>
	This character has been updated from the armory.
</div>

<? } ?>

<script>
$(function(){
	//window.setTimeout(function(){

	$.ajax({
		url: '<?=$api_url?>',
		dataType: 'jsonp',
		jsonp: 'jsonp',
		crossDomain: true,
		success: function(data){
			if (data.thumbnail){
				$('#thumbnail').attr('src', '<?=$url_base?>' + data.thumbnail);
				var achieves = parse_achieves(data);
				//console.log(achieves);

				update_criteria(achieves.criteria[8818], 'bsb', 9000);
				update_criteria(achieves.criteria[8819], 'evr', 42000);
				update_criteria(achieves.criteria[8820], 'rat', 42000);
				update_criteria(achieves.criteria[8821], 'rav', 42000);
				update_criteria(achieves.criteria[8822], 'boo', 42000);
				update_criteria(achieves.criteria[8823], 'gad', 42000);
				update_criteria(achieves.criteria[8824], 'dmf', 42000);
			}
		}
	});

	//}, 2000);
});

function parse_achieves(data){
	var a_map = {};
	var c_map = {};
	var out = {
		'criteria' : {}
	};

	for (var i=0; i<data.achievements.criteria.length; i++){
		c_map[i] = data.achievements.criteria[i];
		out.criteria[c_map[i]] = {};
	}

	for (var i=0; i<data.achievements.criteriaQuantity.length; i++){
		var c_id = c_map[i];
		out.criteria[c_id].qty = data.achievements.criteriaQuantity[i];
	}

	for (var i=0; i<data.achievements.criteriaTimestamp.length; i++){
		var c_id = c_map[i];
		out.criteria[c_id].ts = data.achievements.criteriaTimestamp[i];
	}

	for (var i=0; i<data.achievements.criteriaCreated.length; i++){
		var c_id = c_map[i];
		out.criteria[c_id].cre = data.achievements.criteriaCreated[i];
	}

	return out;
}

function update_criteria(criteria, name, needed){

	var qty = criteria ? criteria.qty : -1;

	var label = qty + ' / 3000 neutral';
	if (qty > 3000 ) label = (qty-3000 ) + ' / 6000 friendly';
	if (qty > 9000 ) label = (qty-9000 ) + ' / 12000 honored';
	if (qty > 21000) label = (qty-21000) + ' / 21000 revered';
	if (qty > 42000) label = (qty-42000) + ' / 999 exalted';
	if (qty == -1) label = 'Not started';

	$('#got-'+name).text(label).css({
		'background-color' : qty >= needed ? '#cfc' : '#fcc'
	});
}

</script>


<div class="row">
<div class="span7">
<div class="well">

<? if ($character['got_it']){ ?>

	<table><tr><td>

	<div style="width: 68px; height: 68px; position: relative;">
		<div style="position: absolute; padding: 6px;"><img src="/img/ability_mage_brainfreeze.jpg" width="56" height="56" /></div>
		<div style="position: absolute; background-image: url(/img/default.png); width: 68px; height: 68px;"></div>
	</div>

	</td><td style="padding-left: 20px">

		<b><?=HtmlSpecialChars($character['name'])?>
<? if ($character['date_got'] > 1){ ?>
		earned the achievement
		on <?=date('F jS, Y', $character['date_got'])?>
		at <?=date('g:i a', $character['date_got'])?>
		</b>
	<? if ($character['patch'] == 2){ ?>
		<br /><br />
		That was when the achievement first came out - hardcore!
	<? } ?>
	<? if ($character['patch'] == 3){ ?>
		<br /><br />
		That was during Wrath of the Lich King, when the achievement was harder.
	<? } ?>
	<? if ($character['patch'] == 4){ ?>
		<br /><br />
		That was earned during the Cataclysm expansion.
	<? } ?>
<? }else{ ?>
		earned the achievement, but we don't know when.</b><br />
		(The battle.net API has some bugs)
<? } ?>
		

	</td></tr></table>

<? }else{ ?>

	<?=HtmlSpecialChars($character['name'])?> is still working on the achievement.

<? } ?>

</div>

<h2>Progress</h2>

<table class="table">
	<tr>
		<th>Faction</th>
		<th>Required</th>
		<th>Achieved</th>
	</tr>
 
	<tr>
		<td>Everlook</td>
		<td>Exalted</td>
		<td id="got-evr">...</td>
	</tr>
 
	<tr>
		<td>Ratchet</td>
		<td>Exalted</td>
		<td id="got-rat">...</td>
	</tr>
  
	<tr>
		<td>Booty Bay</td>
		<td>Exalted</td>
		<td id="got-boo">...</td>
	</tr>
 
	<tr>
		<td>Gadgetzan</td>
		<td>Exalted</td>
		<td id="got-gad">...</td>
	</tr>

	<tr>
		<td>Ravenholdt</td>
		<td>Exalted</td>
		<td id="got-rav">...</td>
	</tr>

	<tr>
		<td>Bloodsail Buccaneers</td>
		<td>Honored</td>
		<td id="got-bsb">...</td>
	</tr>
 
	<tr>
		<td>Darkmoon Faire</td>
		<td>Exalted</td>
		<td id="got-dmf">...</td>
	</tr>
</table>

<p>
	Progress is pulled in real-time from the battle.net API, but is pretty buggy - it doesn't update correctly.
	To see current reputations for this character, <a href="<?=$armory?>reputation/">click here</a>.
</p>

</div>
<div class="span5">

<div class="well">

	Achievement points: <?=number_format($character['achievement_points'])?><br />
	<br />
	<a href="<?=$armory?>">View on Armory</a><br />
</div>

<? if (!$_GET['added']){ ?>
<div class="well ac">
	<a href="<?=$refresh_url?>" class="btn" id="refresh">Refresh from Armory</a><br />
	<span style="color: #999">Last refreshed <?=date('Y-m-d', $character['last_fetched'])?></span>

<script>
$(function(){
	$('#refresh').click(function(){
		$('#refresh').addClass('disabled').text('Refreshing...');
	});
});
</script>

</div>
<? } ?>

<div class="well">

<? if ($character['guild'] && $guild['total_got']){ ?>
	This player is a member of <a href="<?=$guild['url']?>"><?=HtmlSpecialChars($character['guild'])?></a>.<br />
	<br />
	Scanned members: <?=$guild['total_found']?><br />
	Insane members: <?=$guild['total_got']?><br />
<? }elseif ($character['guild']){ ?>
	This player is a member of <a href="<?=$guild['url']?>"><?=HtmlSpecialChars($character['guild'])?></a>.<br />
	This guild is currently unranked.
<? }else{ ?>
	This player is not currently in a guild.
<? } ?>

</div>
</div>
</div>


<?
	include('foot.txt');
?>
