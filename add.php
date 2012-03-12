<?
	include('init.php');
	include('include/fetch.php');


	#
	# add someone?
	#

	$show_error = 0;

	if ($_POST['r'] && $_POST['n']){

		list($region, $realm) = explode('-', $_POST['r'], 2);
		$name = $_POST['n'];

		$ret = fetch_character($region, $realm, $name, 1);

		if ($ret['ok']){

			$region_url = urlencode($region);
			$realm_url = urlencode($realm);
			$name_url = urlencode($name);

			header("location: /insanity/$region_url/$realm_url/$name_url/?added=1");
			exit;
		}

		$show_error = $ret;
	}


	#
	# load realms list
	#

	$realms = array();

	$ret = db_fetch("SELECT * FROM realms");
	foreach ($ret['rows'] as $row){

		$slug = $row['region'].'-'.$row['slug'];

		$realms[$row['region']][$slug] = realm_name($row);
	}

	foreach ($realms as $region => $junk){
		asort($realms[$region]);
	}


	include('head.txt');
?>

<h1>Add a Player</h1>

<? if ($show_error){ ?>
<div style="background-color: pink; border: 2px solid red; margin-bottom: 1em; padding: 1em;">
<? if ($show_error['error'] = 'not_found'){ ?>
	Character not found - perhaps you typed it wrong?
<? }else{ ?>
	Unknown error: <?=$show_error['error']?>
<? } ?>
</div>
<? } ?>


<form action="/insanity/add/" method="post">


<table border="1"><tr><td>

<b>Realm:</b><br />

<select id="sel-region" name="reg" style="display: none">
	<option value="us">US</option>
	<option value="eu">Europe</option>
	<option value="kr">Korea</option>
	<option value="tw">Taiwan</option>
</select>

<select id="sel-realm" name="r">
<? foreach ($realms as $region => $list){ ?>
<? foreach ($list as $k => $v){ ?>
	<option value="<?=$k?>"<? if ($_POST['r'] == $k){ echo " selected"; } ?>><?=HtmlSpecialChars($v)?> (<?=StrToUpper($region)?>)</option>
<? } ?>
<? } ?>
</select>

</td><td>

<b>Character Name:</b><br />

<input type="text" name="n" style="width: 300px" value="<?=HtmlSpecialChars($_POST['n'])?>" />

</td></tr></table>



</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>

var realms = <?=JSON_encode($realms)?>;
var sel = '<?=HtmlSpecialChars($_POST['r'])?>';

$(function(){
	populate_realms();
	$('#sel-region').show().change(populate_realms);

	if (sel){
		var a = sel.split('-', 2);
		$('#sel-region').val(a[0]);
		populate_realms();
		$('#sel-realm').val(sel);
	}
});

function populate_realms(){

	var reg = $('#sel-region').val();
	var html = '';

	for (var i in realms[reg]){
		var slug = escapeXML(i);
		var name = escapeXML(realms[reg][i]);
		html += '<option value="'+slug+'">'+name+'</option>';
	}

	$('#sel-realm').html(html);
}

function escapeXML(x){
	return x;
}

</script>

<?
	include('foot.txt');
?>
