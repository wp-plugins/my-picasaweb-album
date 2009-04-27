<?php
/*
Plugin Name: my Picasaweb Album
Plugin URI: http://aan.dudut.com/
Description: my very first wordpress plugin for adding the content of your Picasaweb Album to your posts. this plugin use simplePie (already included)
Version: 2.0
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

$pluginURI = get_option('siteurl').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
$options_page = get_option('siteurl') . '/wp-admin/options-general.php?page=my-picasaweb-album/options.php';

function myOptionPage() {
	add_options_page('my Picasaweb Album Options', 'my Picasaweb Album', 10, 'my-picasaweb-album/options.php');
}

$cacheL = dirname(__FILE__) . '/cache';

include_once('justFunction.php');

	extract(shortcode_atts(array('usrname' => '', 'album' => '', 'thumb' => '', 'maxsize' => '', 'lbox' => '', 'tagop' => '', 'edtag' => '', 'op' => '', 'ed' => '',), $atts));
	
	if($usrname=='') {$usrname=get_option('usrName');}
	if($thumb=='')   {$thumb=get_option('thumbSize');}
	if($isCroped==''){$isCroped=get_option('isCropped');}
	if($maxsize=='') {$maxsize=get_option('maxSize');}
	if($lbox=='')    {$lbox=get_option('useLightbox');}
	if($tagop=='')   {$tagop=get_option('tagFirst');}
	if($edtag=='')   {$edtag=get_option('tagLast');}
	if($op=='')      {$op=get_option('tagBegin');}
	if($ed=='')      {$ed=get_option('tagEnd');}

function myPicasa($atts){
	global $cacheL;

	if($lbox=='1'){	$lightbox = true; }else if($lbox=='0'){ $lightbox = false;}
	
	echo getAlbumContent($usrname,$album,$thumb,$maxsize,$tagop,$edtag,$op,$ed,$lightbox,$cacheL);
}

if (!is_admin()) {	
	add_shortcode('myPicasaAlbum', 'myPicasa');
}

add_action('media_buttons', 'my_add_media_button', 20);
add_action('admin_menu', 'myOptionPage');

function my_add_media_button(){
	global $pluginURI;
	
	global $usrname;
	global $thumb;
	global $maxsize;
	global $lbox;
	global $isCroped;
	
	$siteUrl   = get_option('siteurl');
	$iFrameAdd = "$pluginURI/viewer.php?yourUrl=$pluginURI&siteUrl=$siteUrl";
	$addParam  = "&usrname=$usrname&thumb=$thumb&maxsize=$maxsize&lbox=$lbox&isCroped=$isCroped&theTags=$theTags";

	echo "<a href=\"#\"  onclick=\"tb_show('myPicasawebAlbum', '$iFrameAdd$addParam', false)\" >Pic</a>";
}
?>