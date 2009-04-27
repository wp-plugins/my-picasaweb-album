<?php
	$thisUrl = $_GET['thisUrl'];
	include_once('justFunction.php');
	
	$usrname   = $_GET['usrname'];
	$album     = $_GET['album'];
	$thumb     = $_GET['thumb'];
	$maxsize   = $_GET['maxsize'];
	$lbox      = $_GET['lbox'];
	$isCroped  = $_GET['isCroped'];
	$theTags   = $_GET['theTags'];
	$cacheL = dirname(__FILE__) . '/cache';
	
	if($isCroped==1){$isCroped=true;}else{$isCroped=false;}
	$albumImg = listAlbumContent($usrname,$album,$cacheL);
	
	$nImg = count($albumImg);
?>
<div id="rmvArea">
	<div id="picasaImgCont">
<?php 
	foreach($albumImg as $temp){

	if($nImg<=50){
		$vTb = '144';
	} else {
		$vTb = '64';
	}
	
	$tgUrl = $temp[1] . "?imgmax=" . $maxsize;
	$tbUrl = getTheUrl($temp[1],$thumb,$isCroped); 
	$lAdd = '';
	if($lbox==1){ $lAdd=' rel="lightbox[' . $album . ']" '; }
	
	$wTag = '<a href="' . $tgUrl . '"' . $lAdd . '><img src="' . $tbUrl . '" alt="' . $album . '" /></a>';
?>     
        <div class="albumContent" id="imgcont-<?php echo $temp[0]; ?>" onclick="selctImage('<?php echo $temp[0]; ?>')">
        	<div class="thaImg" style="background-image:url(<?php echo getTheUrl($temp[1],$vTb,true); ?>); width:<?php echo $vTb ?>px; height:<?php echo $vTb ?>px;" >
            	<input type="hidden" id="the-tag-<?php echo $temp[0]; ?>" value='<?php echo $wTag; ?>' />
                <div class="thaBg" ></div>
            </div>
        </div>
<?php } ?>
<?php
	echo "Memory usage: " . number_format(memory_get_usage()); 
	echo "Memory usage: " . number_format(memory_get_usage()); 
	echo "Memory usage: " . number_format(memory_get_usage()); 
	echo "Memory usage: " . number_format(memory_get_usage()); 
?>
	
    <div class="addButton"><a href="#" onclick="addSelectedToPost()"> add to post </a></div>
    </div>
    <div id="clearAll" ></div>
</div>