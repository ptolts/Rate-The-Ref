<?php
date_default_timezone_set('EST');

//twitter info
$consumer_key = "********";
$consumer_secret = "***********";

$user="phil";
$password="*****";
$database="ratetheref";
mysql_connect("localhost",$user,$password);
@mysql_select_db($database) or die( "Unable to select database");

//echo "Connected <br><br>";

?>
