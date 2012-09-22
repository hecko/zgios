<!DOCTYPE html>
<html lang="en">
<head>
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- <meta http-equiv="refresh" content="5"> -->
<style>
.btn {
	font-size: .9em;
	width: 7em;
	height: 7em;
	margin-bottom: .3em;
	margin-right: .3em;
}
</style>
</head>
<body>
<div class=container id=content>
	<script src="jquery/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<div class=row>
		<h4><a href=""><i class="icon-refresh"></i></a> <?php echo date("H:i:s"); ?></h4>
		<hr>
	</div>
	<div class=row>
<?php

error_reporting(0);

$s = array(
	'PROD' => array(
		'ATM' => 'Starame sa o vase peniazky',
		'ATM',
		'Pergola',
		'My secret service',
		'ATM',
                'My secret service',
		'Pinger',
		'Selenium'
		),
	'UAT2' => array(
		'Selenium',
		'Pinger'
		));

require("PhpZabbixApi_Library/ZabbixApi.class.php");

$api = new ZabbixApi('http://localhost/zabbix/api_jsonrpc.php', 'admin', 'zabbix');

$group=0;
$i=0;
foreach ($s as $gname=>$g) {
	$group++;
	foreach ($g as $name=>$comment) {
		$i++;
		if (is_numeric($name)) { $name = $comment; $comment = 'no comment'; };
		$stat = $api->serviceGet(array(
			//'selectAlarms'=>TRUE,
			//'selectTrigger'=>TRUE,
			//'selectDependencies'=>TRUE,
			'output'=>'extend',
			'filter'=>array('name'=>"$name")));
		if ($stat[0]->status!="") {
			$groups[$gname][$name.$i] = $stat[0];
			$groups[$gname][$name.$i]->comment = $comment;
			$groups[$gname][$name.$i]->group_name = $gname;
			$comment = '';
		}
	}
}

//echo '<pre>';
//print_r($groups);

//print everything out
$i = 0;
foreach ($groups as $gname=>$stats) {
	echo '<h5>'.$gname.'</h5>';
	foreach ($stats as $service) {
		//status value is linked to trigger severity
		$i++;
		//echo '<div class="span2">';
		if ($service->status >= 4) {
			echo '<button class="btn btn-large btn-danger" type=button '.
				'data-trigger=hover rel="popover" data-placement=right '.
				'data-content="'.$service->comment.'">'.
				$service->name.
				'</button>';
	
		} elseif ($service->status <= 3 & $service->status != 0) {
			echo '<button class="btn btn-large btn-warning" type=button '.
				'data-trigger=hover rel="popover" data-placement=right '.
				'data-content="'.$service->comment.'">'.
				$service->name.
				'</button>';
		} else {
			echo '<button class="btn btn-large btn-success" type=button '.
				'data-trigger=hover rel="popover" data-placement=right '.
				'data-content="'.$service->comment.'">'.
				$service->name.
				'</button>';
		}
		//echo '</div>'."\n";
		//7 6 items per line - reflected in span2, as 2*6=12 and that is how it should be for Twitter Bootstrap
		if ($i >= 10) {
			echo '</div><div class=row>';
			$i = 0;
		}
	}
	$i=0;
	//starting a new group
	echo '</div><div class=row>'."\n";
}
?>
	</div>

	<div class=container><hr>
		<small>Build by Marcel Hecko, 2012</small>
	</div>
	<script type="text/javascript">  
		$('.btn').live("mouseover", function() { $(this).popover('show'); });
	</script>
</div>

<!-- refresh part of the page -->
<script>
setInterval(function(){
	$('.btn').popover('hide').popover('disable');
	$("#content").load("index.php #content");
}, 10000);
</script>

</body>
</html>
