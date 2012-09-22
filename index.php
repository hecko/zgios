<!DOCTYPE html>
<html lang="en">
<head>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<meta http-equiv="refresh" content="5">
</head>
<body>
<div class=container>
	<div class=container>
		<h2>Status of Zabbix IT Services</h2><h4>@ <?php echo date("H:i"); ?> <a href=""><i class="icon-refresh"></i></a></h4>
		<hr>
<?php

error_reporting(0);

//define names of services here
$s = array('ATM','My secret service','Selenium','Pinger');

require("ZabbixAPI.class.php");

// This enables debugging, this is rather verbose but can help debug problems
ZabbixAPI::debugEnabled(TRUE);

// This logs into Zabbix, and returns false if it fails
ZabbixAPI::login('http://localhost/zabbix/','admin','zabbix')
	or die('Unable to login: '.print_r(ZabbixAPI::getLastError(),true));

// This gets the version of the zabbix server
#$version = ZabbixAPI::fetch_string('apiinfo','version')
#	or die('Unable to get Zabbix Version: '.print_r(ZabbixAPI::getLastError(),true));
#
#echo "Server running Zabbix API Version: $version\n<br><hr>";
#
//$services = ZabbixAPI::fetch('service','get',array('output'=>'extend','filter'=>array('name'=>'new')))
foreach ($s as $name) {
	$stat = ZabbixAPI::fetch_row('service','get',array('output'=>'extend','filter'=>array('name'=>"$name")))
		or die(print_r(ZabbixAPI::getLastError(),true));
	//print_r($stat);
	$stats[$name] = $stat;
}

//print everything our
foreach ($stats as $service) {
	//status value is linked to trigger severity
	echo '<a href="/zabbix/srv_status.php">';
	if ($service["status"] >= 4) {
		echo '<button class="btn btn-large btn-danger" type=button>'.
			'<p>'.$service['name'].'</p><i class="icon-fire"></i>'.
			'</button>';

	} elseif ($service["status"] <= 3 & $service["status"] != 0) {
		echo '<button class="btn btn-large btn-warning" type=button>'.
                        '<p>'.$service['name'].'</p><i class="icon-exclamation-sign"></i>'.
                        '</button>';
	} else {
		echo '<button class="btn btn-large btn-success" type=button>'.
                        '<p>'.$service['name'].'</p><i class="icon-thumbs-up"></i>'.
                        '</button>';
	}
	echo '</a>'."\n";
}

?>
		<hr>
	</div>
	<div class=container>
		<small>Build by Marcel Hecko, 2012</small>
	</div>
</div>
</body>
</html>
