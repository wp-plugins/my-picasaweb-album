<?php
	$thisUrl = $_GET['thisUrl'];
	include_once('justFunction.php');
	
	$usrname   = $_GET['usrname'];
	
	$album     = $_GET['album'];
	$album     = $_POST['albumName'];
	
	$thumb     = $_GET['thumb'];
	$maxsize   = $_GET['maxsize'];
	$lbox      = $_GET['lbox'];
	$isCroped  = $_GET['isCroped'];
	$tagSel    = $_GET['tagSel'];
	$cacheL    = '';
	
	if($isCroped==1){$isCroped=true;}else{$isCroped=false;}
	$albumImg = listAlbumContent($usrname,$album,$cacheL);
	
	$nImg = count($albumImg);
	
	if($tagSel=='none'){
		$myTag[0] = '';
		$myTag[1] = '';
		$myTag[2] = '';
		$myTag[3] = '';		
	}else if($tagSel=='div'){
		$myTag[0] = '<div class="myPicasawebAlbum">';
		$myTag[1] = '</div>';
		$myTag[2] = '<div class="wrapper">';
		$myTag[3] = '</div>';	
	}else if($tagSel=='ulli'){
		$myTag[0] = '<ul class="myPicasawebAlbum">';
		$myTag[1] = '</ul>';
		$myTag[2] = '<li>';
		$myTag[3] = '</li>';	
	}
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
	
	$wTag = '<a title="'.$temp[2].'" href="' . $tgUrl . '"' . $lAdd . '><img src="' . $tbUrl . '" alt="' . $album . '" /></a>';
	$oTag = '<img alt="'.$temp[2].'" src="'.$temp[1].'" />';
?>     
        <div class="albumContent" id="imgcont-<?php echo $temp[0]; ?>" onclick="selctImage('<?php echo $temp[0]; ?>')" style="position:relative;">
        	<div class="thaImg" style="background-image:url(<?php echo getTheUrl($temp[1],$vTb,true); ?>); width:<?php echo $vTb ?>px; height:<?php echo $vTb ?>px;" >
            	<input type="hidden" id="the-tag-<?php echo $temp[0]; ?>" value='<?php echo $wTag; ?>' />
                <input type="hidden" id="the-other-tag-<?php echo $temp[0]; ?>" value='<?php echo $oTag;?>' />
                <div class="thaBg" ></div>
            </div>
            
            <?php if(!empty($temp[2])){ ?>
        	<div style="background:#000; position:absolute; left:3px; bottom:3px; color:#fff; text-align:center; font-size:10px; line-height:1em; padding:3px 0 4px; width:<?php echo $vTb ?>px;" class="trans" >
            	<?php echo $temp[2]; ?>
            </div>
            <?php } ?>
        </div>
<?php } ?>
	<input type="hidden" id="myPicasawebAlbumTagBegin"  value='<?php echo $myTag[0]; ?>' />
    <input type="hidden" id="myPicasawebAlbumTagEnd"    value='<?php echo $myTag[1]; ?>' />
    <input type="hidden" id="myPicasawebAlbumTagBefore" value='<?php echo $myTag[2]; ?>' />
    <input type="hidden" id="myPicasawebAlbumTagAfter"  value='<?php echo $myTag[3]; ?>' />
    <div class="addButton">
    	<div style="float:left; font-size:10px; margin:2px 0 0 5px;">
        	<input type="checkbox" name="insertFullSize" /> insert image only (full size)
        </div>
        
    	<a href="#" onclick="addSelectedToPost()"> add to post </a>
    </div>
    
    </div>
    <div id="clearAll" ></div>
</div>