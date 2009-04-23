<?php
/*
Plugin Name: my Picasaweb Album
Plugin URI: http://aan.dudut.com/
Description: my very first wordpress plugin for adding the content of your Picasaweb Album with a simple line of code on your posts. this plugin use simplePie (already included)
Version: 1.1
Author: aankun
Author URI: http://aan.dudut.com/
*/

if (!class_exists('SimplePie')){
	include_once('inc/create.php');
	include_once('inc/idn/idna_convert.class.php');
}


function getAlbumContent($usrname,$albumName,$tb,$maxSize,$conO,$conE,$op,$ed,$lBox){	
	$result = findAlbum($usrname,$albumName);
	if($result !== 0){
		$url = str_replace('/entry/','/feed/',$result);
		$feed = new SimplePie();
		$feed->set_feed_url($url);
		$feed->enable_cache(false);
		$feed->enable_order_by_date(false);
		$feed->init();
		$feed->handle_content_type();
		
		$lTag = '';
		if($lBox) { $lTag = ' rel="lightbox[' . $albumName . ']" ';}
		
		echo $conO;
		foreach ($feed->get_items() as $item) {
			$media_group   = $item->get_item_tags('http://search.yahoo.com/mrss/', 'group');
			$media_content = $media_group[0]['child']['http://search.yahoo.com/mrss/']['thumbnail'];
			$tbUrl  = $media_content[$tb]['attribs']['']['url'];
			
			$media_content = $media_group[0]['child']['http://search.yahoo.com/mrss/']['content'];
			$imgUrl = $media_content[0]['attribs']['']['url'] . "?imgmax=" . $maxSize ;
			
			echo $op . '<a href="' . $imgUrl . '"' . $lTag . '><img src="' . $tbUrl . '" /></a>' . $ed;	
		}
		echo $conE;
		
		$feed->__destruct();
		unset($feed); 
	}else{
		echo 'album not found or username wrong';
	}
	
}

function findAlbum($usrname,$albumName){
	$url = 'http://picasaweb.google.com/data/feed/api/user/' . $usrname;
	$feed = new SimplePie();
	$feed->set_feed_url($url);
	$feed->enable_cache(false);
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
}

function myPicasa($atts){
	extract(shortcode_atts(array('usrname' => '', 'album' => '', 'thumb' => '1', 'maxsize' => '640', 'lbox' => '0', 'tagop' => '<ul class="myPicasawebAlbum">', 'edtag' => '</ul>', 'op' => '<li>', 'ed' => '</li>',), $atts));
	if($lbox=='1'){
		$lightbox = true;
	}else{
		$lightbox = false;
	}
	
	getAlbumContent($usrname,$album,$thumb,$maxsize,$tagop,$edtag,$op,$ed,$lightbox);
}

if (!is_admin()) {	
	add_shortcode('myPicasaAlbum', 'myPicasa');
}

?>