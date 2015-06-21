<?php

$accesstoken =
"AAAHB8L7JGeIBAGw4MyC2VyQdLly3hWeSuxqkkMjOdEjTtITx46WCnvooEvbdAOunloqUw1cgTD6ZC1sePdSQA10r6bgYZD";
$whosfriend = "574924679";
//$fbid = "835336";

$frnlistfile = "/tmp/".$whosfriend."-info.json";
//$fh = fopen($frnlistfile, 'r') or die("can't open file");
$handle = fopen($frnlistfile, 'r');
$data = fread($handle,filesize($frnlistfile));
$listobj = json_decode($data);
$qry =
"CREATE TABLE IF NOT EXISTS `fb".$whosfriend."` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `data` text CHARACTER SET utf8 NOT NULL,
  `lon` float NOT NULL,
  `lat` float NOT NULL,
  `time` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `placer` bigint(20) DEFAULT NULL,
  `placeid` bigint(20) DEFAULT NULL,
  `whosfriend` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
quertmysql($qry);
//echo print_r($listobj);exit;
foreach($listobj->friends->data as $valu)
{ //print_r($valu);exit;
//$fbid = "835336";
	$fbid = $valu->id;
	$myFile = "/tmp/".$fbid."-checkins.json";
	$fh = fopen($myFile, 'r') or die("can't open file");
	//$handle = fopen($fh, 'r');
	$data = fread($fh,filesize($myFile));
	$listobj = json_decode($data);
	writetodb($listobj,$fbid,$whosfriend);

        $myFile = "/tmp/".$fbid."-photos.json";
        $fh = fopen($myFile, 'r') or die("can't open file");
        //$handle = fopen($fh, 'r');
        $data = fread($fh,filesize($myFile));
        $listobj = json_decode($data);
        writetodb($listobj,$fbid,$whosfriend);
//exit();
}

function quertmysql($query)
{
	$username="root";
	$password="what2do";
	$database="what2do";
	mysql_connect("localhost",$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	mysql_query("SET NAMES utf8");
	mysql_query($query);
	mysql_close();
}


function writetodb($dadada,$fbid,$whosfriend)
{
$username="root";
$password="what2do";
$database="what2do";
foreach($dadada->data as $adada)
{
	if(isset($adada->place)){
	$ctime = $adada->created_time;
	$lon = $adada->place->location->longitude;
	$lat = $adada->place->location->latitude;
	$name  ='';
	$name = @$adada->place->name;
	$placeid = $adada->place->id;
 	mysql_connect("localhost",$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	$rdata = json_encode($adada);
	$query = 
		"INSERT INTO `what2do`.`fb".$whosfriend."` (`id`, `name`, `time`, `lon`, `lat`, 
		`data` ,
		 `placer`, `placeid`, `whosfriend`) VALUES ('$adada->id', '$name', '$ctime', '$lon', '$lat', 
		'$rdata',
		'$fbid','$placeid','$whosfriend')";
	mysql_query("SET NAMES utf8");
	mysql_query($query);

	mysql_close();
	}
}
}
?>

