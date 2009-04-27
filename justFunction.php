<?php
if (!class_exists('SimplePie')){
	include_once('inc/create.php');
	include_once('inc/idn/idna_convert.class.php');
}

function getAlbumContent($usrname,$albumName,$tb,$maxSize,$conO,$conE,$op,$ed,$lBox,$cLoc,$isCroped){	
	$result = findAlbum($usrname,$albumName,$cLoc);
	if($result !== 0){
		$url = str_replace('/entry/','/feed/',$result);
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->set_cache_location($cLoc);
		$feed->enable_order_by_date(false);
		$feed->init();
		$feed->handle_content_type();
		
		$lTag = '';
		if($lBox) { $lTag = ' rel="lightbox[' . $albumName . ']" ';}
		
		$whole = $conO;
		foreach ($feed->get_items() as $item) {
			$media_group   = $item->get_item_tags('http://search.yahoo.com/mrss/', 'group');
			
			$media_content = $media_group[0]['child']['http://search.yahoo.com/mrss/']['content'];
			$imgUrl = $media_content[0]['attribs']['']['url'];
			$tbUrl  = getTheUrl($imgUrl,$tb,$isCroped);
			
			$whole = $whole . $op . '<a href="' . $imgUrl . "?imgmax=" . $maxSize . '"' . $lTag . '><img src="' . $tbUrl . '" /></a>' . $ed;	
		}
		$whole = $whole . $conE;
		return $whole;
		
		$feed->__destruct();
		unset($feed);
		unset($item); 
	}else{
		echo 'album not found or username wrong';
	}
}

function listAlbumContent($usrname,$albumName,$cLoc){
	$result = findAlbum($usrname,$albumName,$cLoc);
	if($result !== 0){
		$url = str_replace('/entry/','/feed/',$result);
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->set_cache_location($cLoc);
		$feed->enable_order_by_date(false);
		$feed->init();
		$feed->handle_content_type();
		
		$imgs = '';
		$ii = 0;
		foreach ($feed->get_items() as $item){
			$media_group   = $item->get_item_tags('http://search.yahoo.com/mrss/', 'group');
			$media_content = $media_group[0]['child']['http://search.yahoo.com/mrss/']['content'];
			$imgUrl = $media_content[0]['attribs']['']['url'];
			
			$imgs[$ii][] = $ii+1;
			$imgs[$ii][] = $imgUrl;
			
			$ii++;
		}
		return $imgs;
		$feed->__destruct();
		unset($feed); 
		unset($item); 
	}
}

function findAlbum($usrname,$albumName,$cLoc){
	$url = 'http://picasaweb.google.com/data/feed/api/user/' . $usrname;
	$feed = new SimplePie();
	$feed->set_feed_url($url);
	$feed->set_cache_location($cLoc);
	$feed->enable_order_by_date(false);
	$feed->init();
	$feed->handle_content_type();
	$found  = 0;
	$result = 0;
	
	foreach ($feed->get_items() as $item) {
		$nUrl  = $item->get_id(false);
		$title = $item->get_title();
		
		if(strtoupper($title)==strtoupper($albumName)){
			$found = 1;
			$result = $nUrl;
		}
	}
	
	return $result;
	$feed->__destruct();
	unset($feed);
	unset($item); 
}

function listAlbum($usrname,$cLoc){
	$url = 'http://picasaweb.google.com/data/feed/api/user/' . $usrname;
	$feed = new SimplePie();
	$feed->set_feed_url($url);
	$feed->set_cache_location($cLoc);
	$feed->enable_order_by_date(false);
	$feed->init();
	$feed->handle_content_type();
	$found  = 0;
	$result = 0;
	
	$album = '';
	$ii    = 0;
	foreach ($feed->get_items() as $item) {
		$media_group   = $item->get_item_tags('http://search.yahoo.com/mrss/', 'group');
		$media_content = $media_group[0]['child']['http://search.yahoo.com/mrss/']['content'];
		$imgUrl = $media_content[0]['attribs']['']['url'];
		
		$loc = $item->get_item_tags('http://schemas.google.com/photos/2007', 'location');
		$num = $item->get_item_tags('http://schemas.google.com/photos/2007', 'numphotos');
		
		$album[$ii][0] = $item->get_id(false);
		$album[$ii][1] = $item->get_title();
		$album[$ii][2] = $imgUrl;
		
		$thaDate = $item->get_date();
		
		
		$jj = 0;
		foreach($num[0] as $tmp){ if($jj==o){ $fNumber = ' | ' . $tmp . ' photos';} $jj++; }
		$jj = 0;
		foreach($loc[0] as $tmp){ if($jj==o and !empty($tmp)){ $location = ' | location : ' . $tmp;} $jj++; }
		
		$album[$ii][3] = $thaDate . $location . $fNumber ;
		$location ='';
		
		$ii++;
	}
	
	return $album;
	$feed->__destruct();
	unset($feed);
	unset($item); 
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
?>