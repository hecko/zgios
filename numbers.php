<!DOCTYPE html>
<html lang="en">
<head>
	<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
	<!-- <meta http-equiv="refresh" content="5"> -->
	<script src="jquery/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
<style>
.btn {
	font-size: .9em;
	width: 18em;
	height: 5em;
	margin-bottom: .3em;
	margin-right: .3em;
}
</style>
</head>
<body>
<div class="container" id=content>
 <div class="row">
  <div class="span1"></div>
  <div class="span11">
   <h4><?php echo date("H:i:s"); ?></h4>
<?php

error_reporting(1);

$s = array(
	'TEST' => array(
		'testitem' => '10013'
		),
	'SLSP' => array(
		'Number of IB users' => '23328',
		));

require("PhpZabbixApi_Library/ZabbixApi.class.php");

$api = new ZabbixApi('http://localhost/zabbix/api_jsonrpc.php', 'Admin', 'zabbix');

$group=0;
$i=0;
foreach ($s as $gname=>$g) {
	$group++;
	foreach ($g as $name=>$itemid) {
		$value = $api->itemGet(array(
			'output'=>'extend',
			'itemids'=>$itemid
			)
			);
		$data[$name][$itemid] = $value;
		}
}

#echo '<pre>';
#print_r($data);

//print everything out
foreach ($data as $group=>$items) {
	echo "\n".'<p><h5>'.$group.'</h5>';
	foreach ($items as $item) {
		echo "\n".'<button class="btn btn-large" type=button '.
			'data-trigger=hover rel="popover" data-placement=right '.
			'data-content="last value: '.$item[0]->lastvalue.'">'.
			$item[0]->name."<br>".$item[0]->lastvalue.
			'</button>'."\n";
	}
	$i=0;
	//starting a new group
	echo '</p>'."\n\n";
}
?>
  </div>
  <script type="text/javascript">  
    $('.btn').live("mouseover", function() { $(this).popover('show'); });
  </script>
 </div>
</div>

<!-- refresh part of the page -->
<script>
setInterval(function(){
	$('.btn').popover('hide').popover('disable');
	$("#content").load("numbers.php #content");
}, 5000);
</script>

</body>
</html>
