<?php
/*
Plugin Name: my Picasaweb Album
Plugin URI: http://aan.dudut.com/
Description: my very first wordpress plugin for adding the content of your Picasaweb Album with a simple line of code on your posts. this plugin use simplePie (already included)
Version: 1.3
Author: aankun
Author URI: http://aan.dudut.com/
*/

add_option('usrName'    , 'andi.darmika');
add_option('useLightbox', '0');
add_option('thumbSize'  , '144');
add_option('isCropped'  , true);
add_option('maxSize'    , '640');
add_option('tagFirst'   , '<ul class="myPicasawebAlbum">');
add_option('tagLast'    , '</ul>');
add_option('tagBegin'   , '<li>');
add_option('tagEnd'     , '</li>');


/* options page */
$options_page = get_option('siteurl') . '/wp-admin/options-general.php?page=my-picasaweb-album/options.php';
/* Adds our admin options under "Options" */
function myOptionPage() {
	add_options_page('my Picasaweb Album Options', 'my Picasaweb Album', 10, 'my-picasaweb-album/options.php');
}


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
		$feed->set_cache_location(dirname(__FILE__) . '/cache');
		$feed->enable_order_by_date(false);
		$feed->init();
		$feed->handle_content_type();
		
		$lTag = '';
		if($lBox) { $lTag = ' rel="lightbox[' . $albumName . ']" ';}
		
		echo $conO;
		foreach ($feed->get_items() as $item) {
			$media_group   = $item->get_item_tags('http://search.yahoo.com/mrss/', 'group');
			
			$media_content = $media_group[0]['child']['http://search.yahoo.com/mrss/']['content'];
			$imgUrl = $media_content[0]['attribs']['']['url'];
			$tbUrl  = getTheUrl($imgUrl,get_option('thumbSize'),get_option('isCropped'));
			
			echo $op . '<a href="' . $imgUrl . "?imgmax=" . $maxSize . '"' . $lTag . '><img src="' . $tbUrl . '" /></a>' . $ed;	
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
	$feed->set_cache_location(dirname(__FILE__) . '/cache');
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
	extract(shortcode_atts(array('usrname' => '', 'album' => '', 'thumb' => '', 'maxsize' => '', 'lbox' => '', 'tagop' => '', 'edtag' => '', 'op' => '', 'ed' => '',), $atts));
	
	if($usrname=='') {$usrname=get_option('usrName');}
	if($thumb=='')   {$thumb=get_option('thumbSize');}
	if($isCroped==''){$thumb=get_option('isCropped');}
	if($maxsize=='') {$maxsize=get_option('maxSize');}
	if($lbox=='')    {$lbox=get_option('useLightbox');}
	if($tagop=='')   {$tagop=get_option('tagFirst');}
	if($edtag=='')   {$edtag=get_option('tagLast');}
	if($op=='')      {$op=get_option('tagBegin');}
	if($ed=='')      {$ed=get_option('tagEnd');}

	if($lbox=='1'){	$lightbox = true; }else if($lbox=='0'){ $lightbox = false;}
	
	getAlbumContent($usrname,$album,$thumb,$maxsize,$tagop,$edtag,$op,$ed,$lightbox);
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

if (!is_admin()) {	
	add_shortcode('myPicasaAlbum', 'myPicasa');
}

add_action('admin_menu', 'myOptionPage');
?>