<?php
/*
Plugin Name: my Picasaweb Album
Plugin URI: http://www.aankun.com/my-picasaweb-album/
Description: my very first wordpress plugin for adding the content of your Picasaweb Album to your posts.
Version: 2.0.4
Author: aankun
Author URI: http://www.aankun.com/
*/

add_option('usrName'    , 'aankun.ganteng');
add_option('thumbSize'  , '144');
add_option('isCropped'  , true);
add_option('maxSize'    , '640');
add_option('tagSel'     , 'none');
add_option('defCss'     , false);

$pluginURI = get_option('siteurl').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
$my_picasa_options_page = get_option('siteurl') . '/wp-admin/options-general.php?page=my-picasaweb-album/options.php';

function myOptionPage() {
	add_options_page('my Picasaweb Album Options', 'my Picasaweb Album', 10, 'my-picasaweb-album/options.php');
}

$cacheL = '';

include_once('justFunction.php');
	
	extract(shortcode_atts(array('usrname' => '', 'album' => '', 'thumb' => '', 'maxsize' => '', 'lbox' => '', 'tagop' => '', 'edtag' => '', 'op' => '', 'ed' => '',), $atts));
	
	if($usrname=='') {$usrname=get_option('usrName');}
	if($thumb=='')   {$thumb=get_option('thumbSize');}
	if($isCroped==''){$isCroped=get_option('isCropped');}
	if($maxsize=='') {$maxsize=get_option('maxSize');}
	if($lbox=='')    {$lbox=get_option('useLightbox');}
	if($tagSel=='')  {$tagSel=get_option('tagSel');}

add_action('media_buttons', 'my_add_media_button', 20);
add_action('admin_menu', 'myOptionPage');

function my_add_media_button(){
	global $pluginURI;
	
	global $usrname;
	global $thumb;
	global $maxsize;
	global $lbox;
	global $isCroped;
	global $tagSel;
	
	$siteUrl   = get_option('siteurl');
	$iFrameAdd = "$pluginURI/viewer.php?yourUrl=$pluginURI&siteUrl=$siteUrl";
	$addParam  = "&usrname=$usrname&thumb=$thumb&maxsize=$maxsize&lbox=$lbox&isCroped=$isCroped&tagSel=$tagSel";

	echo "<a href=\"#\"  onclick=\"tb_show('myPicasawebAlbum', '$iFrameAdd$addParam', false)\" ><img src=\"$pluginURI/thaIco.jpg\" /></a>";
}

add_action('wp_head', 'my_picasaweb_album_css_head');
function my_picasaweb_album_css_head() {
	if(get_option('defCss')){
?>
<style type="text/css" media="screen">
div.myPicasawebAlbum { }
div.myPicasawebAlbum .wrapper { float:left;}
div.myPicasawebAlbum .wrapper a { text-decoration:none; border:none; margin:5px; display:block; }
div.myPicasawebAlbum .wrapper a:hover { margin:1px; border:solid 4px #09F; }
div.myPicasawebAlbum .justClear { clear:both; }


ul.myPicasawebAlbum { padding:0px; }
ul.myPicasawebAlbum li { list-style-image:none; list-style-type:none; display:inline; margin:0px; padding:0px; }
ul.myPicasawebAlbum li a { margin:0px; padding:0px; }
ul.myPicasawebAlbum li a:hover {}

div.myPicasawebAlbum .wrapper a img, ul.myPicasawebAlbum li a img { border:none; }
</style>
<?php
	}
}
?>