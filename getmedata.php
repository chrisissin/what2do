<?php

//$accesstoken = $_GET['accesstoken'];
//"AAAHB8L7JGeIBAGw4MyC2VyQdLly3hWeSuxqkkMjOdEjTtITx46WCnvooEvbdAOunloqUw1cgTD6ZC1sePdSQA10r6bgYZD";
$myfbid = $_GET['fbid'];
$mylon = $_GET['lon'];
$mylat = $_GET['lat'];
$mytime = $_GET['tm'];
$myswitch = @$_GET['sw'];
//$unixtime = strtotime( $mytime );
//"574924679";
if(!is_numeric($myfbid)||!is_numeric($mylon)||!is_numeric($mylat)||!is_numeric($myswitch)||!is_numeric($mytime))  //poor man's paronoid :P
{
	echo "please just give me number!".$myfbid.$mylon.$mylat.$myswitch;
	exit;
}

switch($myswitch){
case 0:
$qury =
'SELECT DISTINCT friend_name, name, id, placeid, data,time,place_category
FROM `fb'.$myfbid.'`
WHERE DAYOFWEEK(NOW()) = DAYOFWEEK(time) 
AND '.$mytime.'+1 > HOUR(time) 
AND '.$mytime.'-1 < HOUR(time)
ORDER BY `placeid` ASC 
';
break;
case 1:
$qury = 
'SELECT DISTINCT friend_name,name,time, id, placeid, data,place_category, ((ACOS(SIN('.$mylat.' * PI() / 180) * SIN(lat * PI() / 180) + COS('.$mylat.' * PI() / 180) * COS(lat * PI() / 180) * COS(('.$mylon.' - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS `distance` FROM `fb'.$myfbid.'`  WHERE 
DAYOFWEEK(NOW()) = DAYOFWEEK(time) 
AND '.$mytime.'+1 > HOUR(time) 
AND  '.$mytime.'-1 < HOUR(time)
HAVING `distance`<=1 
ORDER BY `placeid` ASC 
';
break;
case 2:
$qury =
'SELECT DISTINCT friend_name,name, id, placeid, data,place_category, ((ACOS(SIN('.$mylat.' * PI() / 180) * SIN(lat * PI() / 180) + COS('.$mylat.' * PI() / 180) * COS(lat * PI() / 180) * COS(('.$mylon.' - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS `distance` FROM `fb'.$myfbid.'` 
HAVING  `distance`<=1 
	ORDER BY `placeid` ASC 
';
break;
case 3:
$qury =
'SELECT DISTINCT friend_name,name, id, placeid, data,place_category FROM `fb'.$myfbid.'`  WHERE DAYOFWEEK(NOW()) = DAYOFWEEK(time)
ORDER BY `placeid` ASC 
';
break;
case 4:
$qury = 'SELECT DISTINCT friend_name,name,time, id, placeid, data,place_category FROM `fb'.$myfbid.'`  WHERE '.$mytime.' = HOUR(time)
	ORDER BY `placeid` ASC 
';

break;
case 5:
$qury = 'SELECT DISTINCT friend_name,name,time, id, placeid, data,place_category FROM `fb'.$myfbid.'`  WHERE MONTH(NOW()) = MONTH(time)
	ORDER BY `placeid` ASC 
';
break;
case 11:
$qury =
'SELECT DISTINCT name, placeid,category, ((ACOS(SIN('.$mylat.' * PI() / 180) * SIN(lat * PI() / 180) + COS('.$mylat.' * PI() / 180) * COS(lat * PI() / 180) * COS(('.$mylon.' - lon) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS `distance` FROM `poi` 
HAVING  `distance`<=1 
	ORDER BY `placeid` ASC 
LIMIT 0 , 300
';
break;
} 

echo json_encode(quertmysql($qury));



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
	//mysql_query($query);
	$result = mysql_query($query);
error_log($query);	
	$resary = array();
	while($row = mysql_fetch_assoc($result))
		$resary[] = $row;
	mysql_close();
	return $resary;
}


?>
