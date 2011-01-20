<?php
include_once('justFunction.php');

$thisUrl   = $_GET['yourUrl'];
$siteUrl   = $_GET['siteUrl'];

$usrname   = $_GET['usrname'];
$thumb     = $_GET['thumb'];
$maxsize   = $_GET['maxsize'];
$lbox      = $_GET['lbox'];
$isCroped  = $_GET['isCroped'];
$tagSel    = $_GET['tagSel'];

$cacheL = '';

$addParam = "thisUrl=$thisUrl&usrname=$usrname&thumb=$thumb&maxsize=$maxsize&lbox=$lbox&isCroped=$isCroped&tagSel=$tagSel";


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
html,body{ padding:0; margin:0;}
html{ height:99%; }
body{ height:100%; }
#title h3 {color:#5A5A5A; font-family:Georgia,"Times New Roman",Times,serif; font-size:1.6em; font-weight:normal;}

#picasaAlbumList li { list-style:none; list-style-image:none; margin-bottom:0px; }
.odd { background:#eee; padding:5px; }
.even { padding:4px; border-left:1px solid #eee; border-bottom:1px solid #eee; border-right:1px solid #eee; }
	#picasaAlbumList li a { display: block; text-decoration:none; height:32px; }
	#picasaAlbumList li a .albumTb { height:32px; width:32px; background:#333; float:right; }
	#picasaAlbumList li a span { font-family:"Trebuchet MS", Arial, Helvetica, sans-serif; font-size:16px; margin-left:5px; font-weight:bold; line-height:0.95em; }
	#picasaAlbumList li a small { margin-left:5px; font-size:9px; display:block; color:#999; }
	
#rmvArea { border-left:1px solid #eee; border-bottom:4px solid #eee; border-right:1px solid #eee; padding:1px; }

#picasaImgCont { float:left; margin-top:3px; width:100%; }
.albumContent { float:left; padding:3px; margin:0px 2px 5px 2px; }
	.albumContent img { margin:0px; padding:0px; }
	.albumContent .thaImg { background:#eee; }

#TB_ajaxContent { width:auto; height:auto; }
	#TB_ajaxContent p { padding:0px;}
	.albumContent .thaImg span { color:#eee; padding-left:5px; font-family:Georgia, "Times New Roman", Times, serif; font-size:38px; float:left; line-height:normal; position:relative; height:46px; margin-top:-46px; }
	.albumContent .thaImg .thaBg { background:#000; height:40px; width:100%; float:left ; filter:alpha(opacity=70); -moz-opacity: 0.7; opacity: 0.7; display:none; }
#clearAll { clear:both; }

#TB_window .addButton { float:left; width:100%; background:#777; color:#fff; }
	#TB_window .addButton a { color:#fff; text-decoration:none; float:right; padding:2px 10px 5px 10px; background:#555; }
	#TB_window .addButton a:hover { background:#333; }

-->
</style>

</head>
<body>
<div id="title"><h3>Select Images to Insert to Post</h3></div>
<div id="picasaAlbumList">
<?php
	$album = listAlbum($usrname,$cacheL);
	$oddEven = 'odd';
	$ii = 1;
	foreach($album as $temp){
?>
        <li class="<?php echo $oddEven; ?>" >
            <a class="albumLink-<?php echo $ii; ?>" href="#" onclick="mekimeki('#theFrame-<?php echo $ii; ?>', '<?php echo $temp[1]; ?>', '.albumLink-<?php echo $ii; ?>')">
            <div class="albumTb" style="position:relative;">
            	<img src="<?php echo getTheUrl($temp[2],'32',true); ?>" />
            	<img src="<?php echo $thisUrl; ?>/ajax-loader.gif" class="loaderImg" style="position:absolute; left:-20px; top:8px; display:none;" />
            </div>
            <span><?php echo $temp[1]; ?></span>
            <small><?php echo $temp[3]; ?></small>
            </a>
        </li>
        <div id="theFrame-<?php echo $ii; ?>"></div>
<?php
		if($oddEven=='odd'){$oddEven='even';}else{$oddEven='odd';}
		$ii++;
	}
?>
</div>
<div style="display:none;">
<script type="text/javascript">
	var myData;
	var activeDiv;
	var imgSeq;
	
	imgSeq = 0;
	activeDiv = '';
	
	jQuery.post("<?php echo $thisUrl; ?>/get-tag.php?<?php echo $addParam; ?>", function(data){
		myData = data; 
	});
	
	function removeLah(){
		jQuery("#newContent").remove();
	}
	
	function mekimeki(target, album, linkAncor){
		var target;
		var album;
		var linkAncor;
		
		if(activeDiv==target){
			removeDiv(activeDiv);
			activeDiv = '';
			imgSeq = 0;
		}else{
			jQuery(linkAncor).find('.loaderImg').css('display','block');
			if(removeDiv!=''){removeDiv(activeDiv);}
			
			jQuery.post("<?php echo $thisUrl; ?>/get-tag.php?<?php echo $addParam; ?>&album="+album, {
				albumName : album
			}, function(data){
				jQuery(target).append('<div id="newContent">' + data + '</div>');
				jQuery('.albumContent .trans').css('opacity',0.7);
				jQuery(linkAncor).find('.loaderImg').css('display','none');
			});				
			activeDiv = target;
		}
	}
	
	function removeDiv(target){
		var target;
		jQuery(target + ' #rmvArea').remove();
	}
	
	function sendToEditor(htmlContent){
    	parent.send_to_editor(htmlContent);
        tb_remove();
        return false;
    }
	
	var nng = new Array;
	function selctImage(thaId){
		var thaId;
		var theStat;
		theStat = (jQuery('#imgcont-'+thaId+' .thaImg .thaBg').css('display'));
		
		if(theStat=='none'){		
			imgSeq++;
			nng[imgSeq] = thaId;
			//reSort();
			
			jQuery('#imgcont-'+thaId).css({background: "#444"});
			jQuery('#imgcont-'+thaId+' .thaImg .thaBg').css({display :"block"});
			jQuery('#imgcont-'+thaId+' .thaImg').append('<span>'+ imgSeq +'</span>');
		}else{
			reSort(thaId);
			imgSeq--;
			
			jQuery('#imgcont-'+thaId).css({background: "none"});
			jQuery('#imgcont-'+thaId+' .thaImg .thaBg').css({display :"none"});
			jQuery('#imgcont-'+thaId+' .thaImg span').remove();
		}
	}
	
	function reSort(delId){
		var delId;
		
		for(ii=1;ii<=imgSeq;ii++){
			if(nng[ii]==delId){ startId = parseInt(ii+1); }
		}
		
		for(jj=startId;jj<=imgSeq;jj++){
			//alert(nng[jj]);
			tg = parseInt(jj-1);
			nng[tg] = nng[jj];
			
			jQuery('#imgcont-'+nng[jj]+' .thaImg span').remove();
			jQuery('#imgcont-'+nng[jj]+' .thaImg').append('<span>'+ tg +'</span>');
		}
	}
	
	function addSelectedToPost(){
		var tag  = '';
		var oTag = '';
		var insertFormat = jQuery('[name=insertFullSize]').attr('checked');
		
		if(imgSeq==0){
			alert('please select the images you want to put on your post');
		}else{
			tag='';
			for(ii=1;ii<=imgSeq;ii++){
				jj   =  nng[ii];
				tag  = tag + (jQuery('#myPicasawebAlbumTagBefore').attr('value')) + (jQuery('#the-tag-'+jj).attr('value')) + (jQuery('#myPicasawebAlbumTagAfter').attr('value'));
				oTag = oTag + jQuery('#the-other-tag-'+jj).val();
			}
			
			if(insertFormat){
				sendToEditor( oTag );
			}else{
				sendToEditor((jQuery('#myPicasawebAlbumTagBegin').attr('value')) + tag + (jQuery('#myPicasawebAlbumTagEnd').attr('value')));
			}
		}
	}
	
	jQuery('#TB_ajaxContent').css({width:"auto"});	
	jQuery(document).ready(function(){   
		var hh1 = jQuery("#TB_overlay").height();
		jQuery('#TB_ajaxContent').css({height:parseInt(hh1-90)+'px'});
	});
</script>
</div>
</body>
</html>