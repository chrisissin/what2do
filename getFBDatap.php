<?php

$accesstoken =
"AAAHB8L7JGeIBAGw4MyC2VyQdLly3hWeSuxqkkMjOdEjTtITx46WCnvooEvbdAOunloqUw1cgTD6ZC1sePdSQA10r6bgYZD";

$wht = "photos";
$frnlistfile = "574924679.friends.20121011.json";
$fh = fopen($frnlistfile, 'r') or die("can't open file");
$handle = fopen($frnlistfile, 'r');
$data = fread($handle,filesize($frnlistfile));
$listobj = json_decode($data);
//echo print_r($listobj);exit;
foreach($listobj->friends->data as $valu)
{ //print_r($valu);exit;
	$fbid = $valu->id;
	$myFile = $fbid."_".$wht."_20121011.json";
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = json_encode(getFriendCheckins($fbid, $accesstoken,$wht));
	fwrite($fh, $stringData);
	fclose($fh);
}

//echo json_encode(getFriendCheckins("835336", $accesstoken));

function getFriendCheckins($fbid,$accesstoken,$what="checkin")
{
	
   	$url = "https://graph.facebook.com/".$fbid."/".$what."/?fields=created_time,id,name,place,likes&access_token=".$accesstoken."&limit=200";
	//?fields=photos.fields(place,id,created_time,likes,tags)
	echo $url;
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

?> 
