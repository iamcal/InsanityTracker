<?
	include('init.php');
	include('include/fetch.php');


	#
	# add someone?
	#

	$show_error = 0;

	if ($_REQUEST['r'] && $_REQUEST['n']){

		list($region, $realm) = explode('-', $_REQUEST['r'], 2);
		$name = $_REQUEST['n'];

		$ret = fetch_character($region, $realm, $name, 1);

		if ($ret['ok']){

			$region_url = urlencode($region);
			$realm_url = urlencode($realm);
			$name_url = urlencode($name);

			header("location: /$region_url/$realm_url/$name_url/?added=1");
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



	$current = 'add';
	$title = 'Add a Character';
	include('head.txt');
?>

<header class="jumbotron subhead">
	<h1>Add a Character</h1>
	<p class="lead">Earned the achievement? Known someone else that has? Update them here.</p>
</header>

<? if ($show_error){ ?>
<div class="alert alert-error">
<? if ($show_error['error'] = 'not_found' && $_GET['auto']){ ?>
	<i class="icon-exclamation-sign"></i>
	Character not found.<br />
	<br />
	<i class="icon-calendar"></i>
	Characters must be active on the armory to be imported - that means they must have logged in during the last 90 days.
<? }elseif ($show_error['error'] = 'not_found'){ ?>
	<i class="icon-exclamation-sign"></i>
	Character not found - perhaps you typed it wrong?<br />
	<br />
	<i class="icon-calendar"></i>
	Characters must be active on the armory to be imported - that means they must have logged in during the last 90 days.
<? }else{ ?>
	<i class="icon-exclamation-sign"></i>
	Unknown error: <?=$show_error['error']?>
<? } ?>
</div>
<? } ?>


<form action="/add/" method="post" class="form-horizontal" style="margin-top: 50px">
<fieldset>

<div class="control-group">
	<label class="control-label" for="sel-realm">Realm</label>
	<div class="controls">
		<select id="sel-region" name="reg" style="display: none" class="input-small">
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
	</div>
</div>

<div class="control-group">
	<label class="control-label" for="in-name">Character Name</label>
	<div class="controls">
		<input type="text" name="n" value="<?=HtmlSpecialChars($_REQUEST['n'])?>" class="span4" />
	</div>
</div>

<div class="form-actions">
	<input type="submit" value="Update Character" class="btn btn-primary" />
</div>

</fieldset>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>

var realms = <?=JSON_encode($realms)?>;
var sel = '<?=HtmlSpecialChars($_REQUEST['r'])?>';

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
