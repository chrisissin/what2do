<?php

$accesstoken = $_GET['accesstoken'];
//"AAAHB8L7JGeIBAGw4MyC2VyQdLly3hWeSuxqkkMjOdEjTtITx46WCnvooEvbdAOunloqUw1cgTD6ZC1sePdSQA10r6bgYZD";
$myfbid = $_GET['fbid'];
//"574924679";
error_log($myfbid );

$url="https://graph.facebook.com/".$myfbid."/?fields=id,name,friends,email,gender,education,address,location,locale,work,birthday,hometown&access_token=".$accesstoken;
error_log($url);
$data = getHttp($url);
//sql//
$qqr = 
"INSERT INTO `what2do`.`person` (`fbid`, `data`, `id`, `ts`) VALUES ('".$myfbid."', NULL, NULL, CURRENT_TIMESTAMP);";
error_log($qqr);
quertmysql($qqr);
//sql
//create table
$qry =
"CREATE TABLE IF NOT EXISTS `fb".$myfbid."` (
`i` BIGINT( 20 ) NOT NULL AUTO_INCREMENT ,
 `id` BIGINT( 20 ) NOT NULL ,
 `name` VARCHAR( 255 ) CHARACTER SET utf8 DEFAULT NULL ,
 `data` TEXT CHARACTER SET utf8,
 `lon` FLOAT NOT NULL ,
 `lat` FLOAT NOT NULL ,
 `time` DATETIME DEFAULT NULL ,
 `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 `placer` BIGINT( 20 ) DEFAULT NULL ,
 `placeid` BIGINT( 20 ) DEFAULT NULL ,
 `whosfriend` BIGINT( 20 ) DEFAULT NULL ,
 `activity_type` VARCHAR( 20 ) COLLATE utf8_unicode_ci DEFAULT NULL ,
 `friend_name` VARCHAR( 256 ) COLLATE utf8_unicode_ci DEFAULT NULL ,
 `place_category` VARCHAR( 128 ) COLLATE utf8_unicode_ci DEFAULT NULL ,
PRIMARY KEY (  `i` )
) ENGINE = INNODB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
";
quertmysql($qry);
error_log($qry);
//file//
$myFile = "/tmp/".$myfbid."-info.json";
$mydata = json_decode($data);
writeobj2file($data, $myFile );
error_log($data);
foreach($mydata->friends->data as $valu)
{ 
	$fbid = $valu->id;
	$friend_name = $valu->name;
	$wht = "photos";
	$myFile = "/tmp/".$fbid."-".$wht.".json";
	$obb = getFriendCheckins($fbid, $accesstoken,$wht);
	//file//	
	writeobj2file($obb, $myFile);
	//sql//
	writetodb($obb,$fbid,$myfbid,$wht,$friend_name);

	$wht = "posts";
	$myFile = "/tmp/".$fbid."-".$wht.".json";
	$obb = getFriendCheckins($fbid, $accesstoken,$wht);
	//file//	
	writeobj2file($obb, $myFile);
	//sql//
	writetodb($obb,$fbid,$myfbid,$wht,$friend_name);
	
	$wht = "checkins";
	$myFile = "/tmp/".$fbid."-".$wht.".json";
	$obb = getFriendCheckins($fbid, $accesstoken,$wht);
	//file//	
	writeobj2file($obb, $myFile);
	//sql//
	writetodb($obb,$fbid,$myfbid,$wht,$friend_name);	
}

function writetodb($dadada,$fbid,$whosfriend,$wht,$friend_name)
{
$username="root";
$password="what2do";
$database="what2do";
error_log("fbid::".$fbid."whosfriend".$whosfriend);
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
"INSERT INTO `what2do`.`fb".$whosfriend."` (`i`, `id`, `name`, `time`, `lon`, `lat`, 
 `placer`, `placeid`, `whosfriend`,`activity_type`, `friend_name` ) VALUES (NULL,'$adada->id', '$name', '$ctime', '$lon', '$lat', 
'$fbid','$placeid','$whosfriend', '".$wht."', '".$friend_name."')";
error_log($query);
	mysql_query("SET NAMES utf8");
	mysql_query($query);

	mysql_close();
	}
}
}


//echo json_encode(getFriendCheckins("835336", $accesstoken));
//574924679?fields=id,name,friends,email,gender,education,address,location,locale,work,birthday,hometown
//posts
//502597622/posts?fields=message,place,name,coordinates,picture,via,comments.fields(like_count,user_likes),likes.fields(name),privacy,promotion_status,status_type,created_time&until=1345903335&limit=1&with=location

function getFriendCheckins($fbid,$accesstoken,$what="posts")
{
	if($what ==="photos")
		$url = "https://graph.facebook.com/".$fbid."/".$what."/?fields=created_time,id,message,place,likes&access_token=".$accesstoken."&limit=200";
	else if ($what === "posts")
   		$url = 
"https://graph.facebook.com/".$fbid."/".$what."/?fields=id,message,place,name,coordinates,picture,via,comments.fields(like_count,user_likes),likes.fields(name),privacy,promotion_status,status_type,created_time&limit=200&with=location&access_token=".$accesstoken;
	else if ($what ==="checkins")
		$url = $url = "https://graph.facebook.com/".$fbid."/".$what."/?fields=created_time,id,message,place,likes&access_token=".$accesstoken."&limit=200";
//echo $url;
	return getFCheck($url);
}

function getFCheck($url){
	$checkinlist =  json_decode(getHttp($url));//print_r($checkinlist);exit;
	if(isset($checkinlist->paging->next))
	{
		//$checkinlist->data->append(json_decode(getFCheck($checkinlist->next))->data);
		$se = (getFCheck($checkinlist->paging->next));
		if(isset($se->data))
		$checkinlist->data=(object)array_merge($checkinlist->data,(array)$se->data);
	}
	return $checkinlist;
}

function getHttp($url){
        $ch = curl_init();

	//$headers[]= "Cookie: ";
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	curl_setopt($ch, CURLOPT_HEADER,0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);

        $output=curl_exec($ch);
        curl_close($ch);

        return $output;
}


function readaFile($filename)
{
	$fh = fopen($filename, 'r') or die("can't open file");
	$data = fread($fh,filesize($filename));
	$listobj = json_decode($data);
	return 	$listobj;
}

function writeobj2file($obj, $filename)
{
	$fh = fopen($filename, 'w') or die("can't open file");
	$stringData = json_encode($obj);
	fwrite($fh, $stringData);
	fclose($fh);	
}

function writestr2file($str, $filename)
{
	$fh = fopen($filename, 'w') or die("can't open file");
	fwrite($fh, $str);
	fclose($fh);	
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


?>

