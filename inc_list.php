<table border="0" width="100%" class="listing">
	<tr>
		<th>Character</th>
		<th>Guild</th>
		<th colspan="2">Class</th>
		<th colspan="2">Race</th>
		<th>Points</th>
		<th>Went Insane</th>
	</tr>
<? foreach ($characters as $row){

	$host = $GLOBALS['cfg']['bnet_region_hosts'][$realm['region']];
	$name_url = rawurlencode($row['name']);
	$guild_url = rawurlencode($row['guild']);

	$profile = "http://$host/wow/en/character/{$realm['slug']}/{$name_url}/";
	$guild = "http://$host/wow/en/guild/{$realm['slug']}/{$guild_url}/?character={$name_url}";

	$profile = "/insanity/character/{$realm['region']}/{$realm['slug']}/{$name_url}/";
	$guild = "/insanity/guild/{$realm['region']}/{$realm['slug']}/{$guild_url}/"
?>
	<tr>
		<td><a href="<?=$profile?>"><?=HtmlSpecialChars($row['name'])?></a></td>
<? if ($row['guild']){ ?>
		<td><a href="<?=$guild?>"><?=HtmlSpecialChars($row['guild'])?></a></td>
<? }else{ ?>
		<td>none</td>
<? }?>
		<td width="14"><img src="http://us.media.blizzard.com/wow/icons/18/class_<?=$row['class_id']?>.jpg" /></td>
		<td><?=$classes[$row['class_id']][0]?></td>
		<td width="14"><img src="http://us.media.blizzard.com/wow/icons/18/race_<?=$row['race_id']?>_<?=$row['gender_id']?>.jpg" /></td>
		<td><?=$races[$row['race_id']][0]?></td>
		<td class="ap"><?=number_format($row['achievement_points'])?></td>
		<td class="date">
			<?=date('Y-m-d', $row['date_got'])?>
			(<?=$patches[$row['patch']][0]?>)
		</td>
	</tr>
<? } ?>
</table>
