<?php
//sql//
$qqr = 
"	SELECT * 
	FROM  `person`";
$userlist = quertmysqlwithres($qqr);
//echo(" LOOOOOG user :::: ".print_r($userlist));

//file//
foreach($userlist as $valua)
{
echo(" LOOOOOG user :::: ".$valua['fbid']);
	$mydFile = "/tmp/".$valua['fbid']."-info.json";
	$userfrndlist = readaFile($mydFile);
	
	//checkin datas

//	foreach($userfrndlist->friends->data as $valub)
//	{
//		$thisfrndname = $valub->name;
		$qqr = 
		"	
		SELECT * 
			FROM  fb".$valua['fbid'];
		$frnchdatalst = quertmysqlwithres($qqr);
		opopop($frnchdatalst,$userfrndlist->friends->data , $valua['fbid']);
		/*
		$mycFile = "/tmp/".$valua['fbid']."-checkins.json";
		$frndcheckins = readaFile($mycFile);
		opopop($frndcheckins,$thisfrndname , "checkins",$valua['fbid']);
		
		$mycFile = "/tmp/".$valua['fbid']."-posts.json";
		$frndcheckins = readaFile($mycFile);
		opopop($frndcheckins, $thisfrndname, "posts",$valua['fbid']);			
		*/	
//	}
}	

function opopop($frndcheckins,$userfrndlist,$whosdata)
{
	foreach($frndcheckins as $valuc)
	{
		//$valuc
		$qry4poi = 
		"
		SELECT * 
		FROM  `poi` 
		WHERE  `placeid` = '".$valuc['placeid']."' "
		;
		$apoi = quertmysqlwithres($qry4poi);
echo(" LOOOOOOOG poi ::: ".$qry4poi);		
		$thisplacecat = "";
		$thisrectime =""; 
		if(count($apoi)==0)
		{
			//get fb
			$url = "https://graph.facebook.com/".$valuc['placeid'].
"?access_token=AAACEdEose0cBANnuMyrY2BODnpYMFXXlyfQ9eJBm2Kj2ME2sjC6KTgSLW63sIhE8f4smiLigJAfNXZA6RDoSYBw5zpFOSmvq85oUybQZDZD"
;
echo(" LOOOOOOOG :::".$url);			
			$place =  json_decode(getHttp($url));
			//writefile
//echo("CCCCCCCCC ".print_r($place,1));
			writeobj2file($place, "/tmp/placeinfo-".$valuc['placeid'].".json");
			//insert to poi
			$insqry =
			"
INSERT INTO `what2do`.`poi` (`i`, `name`, `data`, `lon`, `lat`, `timestamp`, `placeid`, `checkins`, `were_here_count`, `talking_about_count`, `category`, `likes`) VALUES (NULL, '".@$place->name."', NULL, '".@$place->location->longitude."', '".@$place->location->latitude."',  CURRENT_TIMESTAMP, '".@$place->id."', '".@$place->checkins."', '".@$place->were_here_count."', '".@$place->talking_about_count."', '".@$place->category."', '".@$place->likes."');		

			";
echo(" LOOOOOG + place :::: ".$insqry);				
			quertmysql($insqry);
			//fb data to category, 
			$thisplacecat = @$place->category;
		}
		else
		{
			//fb data to category, 
			$thisplacecat = $apoi['category'];
		}
		$thisfrndname='';
		//update friend's row with category, type, friend name
		foreach($userfrndlist as $vv)
		{
			if($vv->id == $valuc['placer'])
			{$thisfrndname = $vv->name;
				break;}
		}
		$updateusertb = 
		"
		UPDATE  `what2do`.`fb".$whosdata."` SET
		`friend_name` =  '".$thisfrndname."',
		`place_category` =  '".$thisplacecat."' WHERE  `fb".$whosdata."`.`placer` ='".$valuc['placer']."' and `fb".$whosdata."`.`i` = '".$valuc['i']."'  ";
echo(" LOOOOOG update place :::: ".$updateusertb);				
		quertmysql($updateusertb);
	}	
}

function writetodb($dadada,$fbid,$whosfriend)
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
 `placer`, `placeid`, `whosfriend`) VALUES (NULL,'$adada->id', '$name', '$ctime', '$lon', '$lat', 
'$fbid','$placeid','$whosfriend')";
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


function quertmysqlwithres($query)
{
	$username="root";
	$password="what2do";
	$database="what2do";
	mysql_connect("localhost",$username,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	mysql_query("SET NAMES utf8");
	//mysql_query($query);
	$result = mysql_query($query);
error_log($query);	
	$resary = array();
	while($row = mysql_fetch_array($result))
		$resary[] = $row;
	mysql_close();
	return $resary;
}



?>

