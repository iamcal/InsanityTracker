<table class="table table-striped table-condensed">
	<tr>
		<th>Character</th>
<?php if (!($hide_guild ?? null)){ ?>
		<th>Guild</th>
<?php } ?>
		<th colspan="2">Class</th>
		<th colspan="2">Race</th>
		<th class="ar">Points</th>
		<th class="ar">Went Insane</th>
	</tr>
<?php foreach ($characters as $row){

	$host = $GLOBALS['cfg']['bnet_region_hosts'][$realm['region']] ?? '';
	$name_url = rawurlencode($row['name']);
	$guild_url = rawurlencode($row['guild']);

	$profile = "http://$host/wow/en/character/{$realm['slug']}/{$name_url}/";
	$guild = "http://$host/wow/en/guild/{$realm['slug']}/{$guild_url}/?character={$name_url}";

	$profile = "/{$realm['region']}/{$realm['slug']}/{$name_url}/";
	$guild = "/guilds/{$realm['region']}/{$realm['slug']}/{$guild_url}/"
?>
	<tr>
		<td><a href="<?=$profile?>"><?=HtmlSpecialChars($row['name'])?></a></td>
<?php if (!($hide_guild ?? null)){ ?>
<?php if ($row['guild']){ ?>
		<td><a href="<?=$guild?>"><?=HtmlSpecialChars($row['guild'])?></a></td>
<?php }else{ ?>
		<td>none</td>
<?php }?>
<?php } ?>
		<td width="14"><img src="http://us.media.blizzard.com/wow/icons/18/class_<?=$row['class_id']?>.jpg" /></td>
		<td><?=$classes[$row['class_id']][0] ?? null?></td>
		<td width="14"><img src="http://us.media.blizzard.com/wow/icons/18/race_<?=$row['race_id']?>_<?=$row['gender_id']?>.jpg" /></td>
		<td><?=$races[$row['race_id']][0] ?? null?></td>
		<td class="ar"><?=number_format($row['achievement_points'])?></td>
		<td class="ar">
<?php if ($row['date_got'] > 10){ ?>
			<?=date('Y-m-d', $row['date_got'])?>
			(<?=$patches[$row['patch'] ?? null][0] ?? ''?>)
<?php }else{ ?>
			(Unknown)
<?php } ?>
		</td>
	</tr>
<?php } ?>
</table>
