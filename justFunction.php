<?php

function getAlbumContent($usrname,$albumName,$tb,$maxSize,$conO,$conE,$op,$ed,$lBox,$cLoc,$isCroped){	
	$result = findAlbum($usrname,$albumName,$cLoc);
	if($result !== 0){
		$url = str_replace('/entry/','/feed/',$result);
		$feed = getXml($url);

		$lTag = '';
		if($lBox) { $lTag = ' rel="lightbox[' . $albumName . ']" ';}
		
		$whole = $conO;
		foreach ($feed->entry as $item) {
			$imgUrl = $item->media_group->media_content[url];
			$tbUrl  = getTheUrl($imgUrl,$tb,$isCroped);
			
			$whole = $whole . $op . '<a href="' . $imgUrl . "?imgmax=" . $maxSize . '"' . $lTag . '><img src="' . $tbUrl . '" /></a>' . $ed;	
		}
		$whole = $whole . $conE;
		return $whole;

	}else{
		echo 'album not found or username wrong';
	}
}

function listAlbumContent($usrname,$albumName,$cLoc){
	$result = findAlbum($usrname,$albumName,$cLoc);
	if($result !== 0){
		$url = str_replace('/entry/','/feed/',$result);
		$feed = getXml($url);
		
		$imgs = '';
		$ii = 0;
		foreach ($feed->entry as $item){
			$imgUrl = $item->media_group->media_content[url];
			
			$imgs[$ii][] = $ii+1;
			$imgs[$ii][] = $imgUrl;
			$imgs[$ii][] = trim($item->summary);
			
			$ii++;
		}
		return $imgs;
	}
}

function findAlbum($usrname,$albumName,$cLoc){
	$url = 'http://picasaweb.google.com/data/feed/api/user/' . $usrname;
	$feed = getXml($url);
	$found  = 0;
	$result = 0;
	
	$album = '';
	$ii    = 0;
	
	foreach ($feed->entry as $item) {
		$nUrl  = $item->id;
		$title = $item->title;
		
		if(strtoupper($title)==strtoupper($albumName)){
			$found = 1;
			$result = $nUrl;
		}
	}
	
	return $result;
}

function listAlbum($usrname,$cLoc){
	$url = 'http://picasaweb.google.com/data/feed/api/user/' . $usrname;
	$feed = getXml($url);
	$found  = 0;
	$result = 0;
	
	$album = '';
	$ii    = 0;
	
	foreach ($feed->entry as $item) {		
		$imgUrl = $item->media_group->media_content[url];
		$loc    = trim($item->gphoto_location);
		$num    = trim($item->gphoto_numphotos);
		
		$album[$ii][0] = $item->id;
		$album[$ii][1] = $item->title;
		$album[$ii][2] = $imgUrl;
		
		$thaDate = explode('T',$item->updated);
		
		if(!empty($loc)){$location=' | location : ' .$loc;}
		if(!empty($num)){$fNumber=' | ' . $num . ' photos';}
		
		$album[$ii][3] = $thaDate[0] . $location . $fNumber ;
		$location ='';
		
		$ii++;
	}
	return $album;
}


function getTheUrl($url,$size,$cropped){
	$prt = explode('/',$url);
	$nP  = count($prt);
	if($cropped){ $cc = '-c'; }
	$addParam = 's' . $size . $cc . '/'; 
	
	$nUrl = '';
	$nn   = 1; 
	foreach($prt as $temp){	
		if($nn==$nP){ $temp = $addParam . $temp; }
		$nUrl = $nUrl . $slash . $temp;
		$slash = '/';
		$nn++;
	}
	
	return $nUrl;
}

function getXml($url) {
	$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$content = curl_exec($ch);
	curl_close($ch);
	
		if ($content) {
		if (function_exists('simplexml_load_file')) {
			$content = str_replace('media:group'     ,'media_group',$content);
			$content = str_replace('media:content'   ,'media_content',$content); 
			$content = str_replace('media:title'     ,'media_title',$content);
			$content = str_replace('gphoto:location' ,'gphoto_location',$content);
			$content = str_replace('gphoto:numphotos','gphoto_numphotos',$content);
			
			$xml = new SimpleXMLElement($content);
			return $xml;
		} else {
			$error = true;
	}
	} else {
		$error = true;
	}
}

?>