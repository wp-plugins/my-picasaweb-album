<?php
	$location = $my_picasa_options_page;
	$pluginURI = get_option('siteurl').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__));
	
	if ('process' == $_POST['stage']) {
		update_option('usrName',     $_POST['usrName']);
		update_option('useLightbox', $_POST['useLightbox']);
		update_option('thumbSize',   $_POST['thumbSize']);
		update_option('isCropped',   $_POST['isCropped']);
		update_option('maxSize',     $_POST['maxSize']);
		update_option('tagSel',      $_POST['tagSel']);
	
	}
?>
<div class="wrap">
	<h2><?php _e('myPicasaweb Album Option', 'myPicasaweb Album') ?></h2>
    <form name="form1" method="post" action="<?php echo $location ?>&amp;updated=true">
    	<input type="hidden" name="stage" value="process" />
    	<table width="100%" cellspacing="2" cellpadding="5" class="form-table">
        	<tr valign="baseline">
                <th scope="row"><?php _e('Picasaweb Username', 'myPicasawebAlbum') ?></th> 
                <td>
                	<?php $userName = get_option('usrName'); ?>
					<input name="usrName" value="<?php _e(get_option('usrName')); ?>" />
                    <p><small><?php _e('your picasaweb username, http://picasaweb.google.com/<strong>yourUsername</strong>/ <-- the bold text is your username.', 'myPicasawebAlbum') ?></small></p>
                </td>
            </tr>
        	<tr valign="baseline">
                <th scope="row"><?php _e('Use Lightbox', 'myPicasawebAlbum') ?></th> 
                <td>
					<?php
                    $useLightbox = get_option('useLightbox');
                     if($useLightbox == '1') {
                        echo("\n<input type=\"checkbox\" name=\"useLightbox\" value=\"1\" checked=\"checked\" />\n");
                    } else {
                        echo("\n<input type=\"checkbox\" name=\"useLightbox\" value=\"1\" />\n");
                    }
                    ?>
                    <small><?php _e(' If you want to use Lightbox (plugin required)', 'myPicasawebAlbum') ?></small>
                </td>
            </tr>
            <tr valign="baseline">
                <th scope="row"><?php _e('Thumbnail Size', 'myPicasawebAlbum') ?></th> 
                <td>
                	<?php $tbSize = get_option('thumbSize'); ?>
					<select name="thumbSize">
                    	<!-- 32, 48, 64, 72, 144, 160 -->
                    	<option <?php if($tbSize=='32') { _e('selected="selected"');} ?> value="32">32px </option>
                        <option <?php if($tbSize=='48') { _e('selected="selected"');} ?> value="48">48px </option>
                        <option <?php if($tbSize=='64') { _e('selected="selected"');} ?> value="64">64px </option>
                        <option <?php if($tbSize=='72') { _e('selected="selected"');} ?> value="72">72px </option>
                        <option <?php if($tbSize=='144'){ _e('selected="selected"');} ?> value="144">144px </option>
                        <option <?php if($tbSize=='160'){ _e('selected="selected"');} ?> value="160">160px </option>
                    </select>
                    <?php
                    $isCropped = get_option('isCropped');
                     if($isCropped) {
                        echo("\n<input type=\"checkbox\" name=\"isCropped\" value=\"1\" checked=\"checked\" />\n");
                    } else {
                        echo("\n<input type=\"checkbox\" name=\"isCropped\" value=\"1\" />\n");
                    }
                    ?>
                    <small><?php _e(' cropped (width:height = 1:1)', 'myPicasawebAlbum') ?></small>
                    <p><small><?php _e(' The size of thumbnail image', 'myPicasawebAlbum') ?></small></p>
                </td>
            </tr>
            <tr valign="baseline">
                <th scope="row"><?php _e('Target Image Size', 'myPicasawebAlbum') ?></th> 
                <td>
                	<?php $maxSize = get_option('maxSize'); ?>
					<select name="maxSize">
                    	<!-- 800, 720, 640, 576, 512, 400, 320, 288, 200 -->
                    	<option <?php if($maxSize=='800'){ _e('selected="selected"');} ?> value="800">800px </option>
                        <option <?php if($maxSize=='720'){ _e('selected="selected"');} ?> value="720">720px </option>
                        <option <?php if($maxSize=='640'){ _e('selected="selected"');} ?> value="640">640px </option>
                        <option <?php if($maxSize=='576'){ _e('selected="selected"');} ?> value="576">576px </option>
                        <option <?php if($maxSize=='512'){ _e('selected="selected"');} ?> value="512">512px </option>
                        <option <?php if($maxSize=='320'){ _e('selected="selected"');} ?> value="400">320px </option>
                    </select>
                    <small><?php _e(' The maximum size of target image', 'myPicasawebAlbum') ?></small> 
                </td>
            </tr>
			<tr valign="baseline">
                <th scope="row"><?php _e('Image Wrapper Tags', 'myPicasawebAlbum') ?></th> 
              <td>
              	<?php $tagSel=get_option('tagSel'); ?>
              	<fieldset>
                	<label title="div">
                        <input type="radio" <?php if($tagSel=='div'){ _e('checked="checked"');} ?> value="div" name="tagSel"/>
                        <img style="vertical-align:middle;" src="<?php echo $pluginURI; ?>/div-wrapper.png" />
                    </label>
                    <br/><br/>
                    <label title="div">
                        <input type="radio" <?php if($tagSel=='ulli'){ _e('checked="checked"');} ?> value="ulli" name="tagSel"/>
                        <img style="vertical-align:middle;" src="<?php echo $pluginURI; ?>/ul-li-wrapper.png" />
                    </label>
                    <br/><br/>
                    <label title="div">
                        <input type="radio" <?php if($tagSel=='none'){ _e('checked="checked"');} ?> value="none" name="tagSel"/>
                        none
                    </label>
                    <br/>
                </fieldset>  	
              </td>
            </tr>

            <tr valign="baseline">
                <th scope="row">
                <p class="submit">
                	<input type="submit" name="Submit" value="<?php _e('Save Changes', 'myPicasawebAlbum') ?>" />
                </p>
                </th>
            </tr>
        </table>
    </form>
</div>