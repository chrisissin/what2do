<?php
/* Last updated with phpFlickr 1.3.2
 *
 * This example file shows you how to call the 100 most recent public
 * photos.  It parses through them and prints out a link to each of them
 * along with the owner's name.
 *
 * Most of the processing time in this file comes from the 100 calls to
 * flickr.people.getInfo.  Enabling caching will help a whole lot with
 * this as there are many people who post multiple photos at once.
 *
 * Obviously, you'll want to replace the "<api key>" with one provided 
 * by Flickr: http://www.flickr.com/services/api/key.gne
 */

$myfbid = $_GET['fbid'];
$mylon = $_GET['lon'];//"121.616905"
$mylat = $_GET['lat'];//"25.056776"
$mytime = $_GET['tm'];
$myswitch = @$_GET['sw'];
//$unixtime = strtotime( $mytime );
//"574924679";
if(!is_numeric($myfbid)||!is_numeric($mylon)||!is_numeric($mylat)||!is_numeric($myswitch)||!is_numeric($mytime))  //poor man's paronoid :P
{
	echo "please just give me number!".$myfbid.$mylon.$mylat.$myswitch;
	exit;
}

require_once("phpFlickr/phpFlickr.php");
$f = new phpFlickr("c1c02364ba1fe8444ddddafba1bbdfa3", "c77c302476249f10");

//$recent = $f->photos_getRecent();
$photos = $f->photos_search(array(
"per_page"=>50,
"lon"=>$mylon,
 "lat"=>$mylat,
//"sort"=>"date-taken-desc",
"accuracy"=>16));//interestingness-desc
//echo(print_r($fotos, 1).'111');
foreach ($photos['photo'] as &$photo) {

    //$photo['owner'] = $f->people_getInfo($photo['owner']);
    /*
    echo "<a href='http://www.flickr.com/photos/" . $photo['owner'] . "/" . $photo['id'] . "/'>";
    echo $photo['title']."<img src='"$f->buildPhotoURL($photo,"thumbnail")."'>" ;
    echo "</a> Owner: ";
    echo "<a href='http://www.flickr.com/people/" . $photo['owner'] . "/'>";
    echo $owner['username'];
    echo "</a><br>";
    */
     $photo['url']  = $f->buildPhotoURL($photo,"thumbnail");
     $photo =$photo;
}
echo json_encode($photos);
?>
