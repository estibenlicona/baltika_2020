<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );
JToolBarHelper::title(JText::_('FWG_CONFIG'));
JToolBarHelper::custom('edit', 'edit', 'edit', 'edit', false);

JHTML::_('bootstrap.popover', '[data-toggle="popover"]');
JHTML::_('bootstrap.tooltip', '[data-toggle="tooltip"]');

$update_code = $this->obj->params->get('update_code');
$verified_code = $this->obj->params->get('verified_code');
?>
<div class="row-fluid">
	<strong><?php echo JText::_('FWG_CONFIGURATION_HINT'); ?></strong>
<?php
if (!file_exists(JPATH_SITE.'/images')) {
?>
	<p style="color:#f00;"><?php echo JText::sprintf('FWG_IMAGES_FOLDER_NOT_EXISTS', JPATH_SITE.'/images') ?></p>
<?php
}
if (!file_exists(FWG_STORAGE_PATH) and !is_writable(JPATH_SITE.'/images')) {
?>
	<p style="color:#f00;"><?php echo JText::sprintf('FWG_IMAGES_FOLDER_NOT_WRITABLE', JPATH_SITE.'/images') ?></p>
<?php
}
if (file_exists(FWG_STORAGE_PATH) and !is_writable(FWG_STORAGE_PATH)) {
?>
	<p style="color:#f00;"><?php echo JText::sprintf('FWG_IMAGES_FOLDER_NOT_WRITABLE', FWG_STORAGE_PATH) ?></p>
<?php
}
if (!function_exists('exif_read_data')) {
?>
	<p style="color:#f00;"><?php echo JText::_('FWG_EXIF_DOES_NOT_ENABLED') ?></p>
<?php
}
if (!function_exists('gd_info')) {
?>
	<p style="color:#f00;"><?php echo JText::_('FWG_GD2_DOES_NOT_INSTALLED') ?></p>
<?php
}
?>
</div>
<div class="row-fluid">
<div class="span4">
	<fieldset class="adminform">
		<h3><?php echo JText::_('FWG_UPDATES_SUPPORT'); ?></h3>
		<p><?php echo JText::_('FWG_UPDATES_SUPPORT_HINT'); ?></p>
		<table class="table">
			<tr>
				<td>
					<?php echo JText::_('FWG_UPDATE_CODE'); ?>
					<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" title="<?php echo $this->escape(JText::_('FWG_UPDATE_CODE_TITLE')); ?>" data-content="<?php echo $this->escape(JText::_('FWG_UPDATE_CODE_DESCR')); ?>"></i>
				</td>
				<td>
					<div id="fwg-verify-code"<?php if ($update_code and $update_code == $verified_code) { ?> style="display:none;"<?php } ?>>
						<input class="float-left form-control w-50" type="password" name="config[update_code]" value="<?php echo $this->escape($update_code); ?>">
						<button type="button" class="float-left w-50 btn btn-success"><?php echo JText::_('FWG_VERIFY_CODE'); ?></button>
					</div>
					<div id="fwg-revoke-code"<?php if (!$update_code or !$verified_code or $update_code != $verified_code) { ?> style="display:none;"<?php } ?>>
						<span></span>
						<button type="button" class="btn btn-warning"><?php echo JText::_('FWG_REVOKE_CODE'); ?></button>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_MEMBERSHIP_TYPE'); ?>
					<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" title="<?php echo $this->escape(JText::_('FWG_MEMBERSHIP_TYPE_TITLE')); ?>" data-content="<?php echo $this->escape(JText::_('FWG_MEMBERSHIP_TYPE_DESCR')); ?>"></i>
				</td>
				<td>
					<div class="font-weight-bold" id="fwg-membership-type"></div>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_LATEST_VERSION'); ?>
				</td>
				<td>
					<div class="font-weight-bold" id="fwg-latest-version"></div>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_SUPPORT'); ?>
					<i class="pull-right fa fa-question-circle" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" title="<?php echo $this->escape(JText::_('FWG_SUPPORT_TITLE')); ?>" data-content="<?php echo $this->escape(JText::_('FWG_SUPPORT_DESCR')); ?>"></i>
				</td>
				<td>
					<div id="fwg-support"></div>
				</td>
			</tr>
		</table>

		<h3><?php echo JText::_('FWG_LAYOUT_SETTINGS'); ?></h3>
		<table class="table">
			<tr>
				<td>
					<?php echo JText::_('FWG_GALLERIES_IN_A_ROW'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('galleries_a_row'); ?>
				</td>
			</tr>

			<tr>
				<td>
					<?php echo JText::_('FWG_GALLERIES_ROWS_PER_PAGE'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('galleries_rows', 4); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DEFAULT_GALLERIES_ORDERING'); ?>:
				</td>
				<td>
					<?php echo JText::_('FWG_ORDERING_'.$this->obj->params->get('ordering_galleries', 'order')); ?>
				</td>
			</tr>

			<tr>
				<td>
					<?php echo JText::_('FWG_IMAGES_IN_A_ROW'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('images_a_row'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_IMAGES_ROWS_PER_PAGE'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('images_rows', 4); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DEFAULT_IMAGES_ORDERING'); ?>:
				</td>
				<td>
					<?php echo JText::_('FWG_ORDERING_'.$this->obj->params->get('ordering_images', 'order')); ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<h3><?php echo JText::_('FWG_WATERMARK'); ?></h3>
		<table class="table">
			<tr>
				<td>
					<?php echo JText::_('FWG_USE_WATERMARK'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('use_watermark')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_WATERMARK_POSITION'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('watermark_position', 'left bottom')); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_WATERMARK_FILE'); ?>:
				</td>
				<td>
<?php
if ($this->obj->params->get('watermark_file')) {
	if ($path = JFHelper::getWatermarkFilename()) {
?>
					<img src="<?php echo JURI::root(true); ?>/<?php echo $path; ?>" /><br/>
<?php
	} else {
?>
					<p style="color:#f00;"><?php echo JText::_('FWG_WATERMARK_FILE_NOT_FOUND_'); ?></p>
<?php
	}
}
?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_WATERMARK_TEXT'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('watermark_text'); ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div class="span4">
	<fieldset class="adminform">
		<h3><?php echo JText::_('FWG_DISPLAYING_SETTINGS'); ?></h3>
		<table class="table">
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_TOTAL_GALLERIES_COUNTER'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_total_galleries')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_EMPTY_GALLERIES'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_empty_gallery')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_GALLERY_NAME'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_name_gallery')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_GALLERY_OWNER_NAME'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_owner_gallery')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_GALLERY_DATE'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_date_gallery')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_GALLERY_DESCR'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_descr_gallery')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_ORDER_BY_OPTION'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_gallery_sorting')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_IMAGE_NAME'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_name_image')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_IMAGE_OWNER_NAME'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_owner_image')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_IMAGE_DESCR'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_descr_image')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_IMAGE_DATE'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_date_image')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_USERS_COPYRIGHT'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_user_copyright')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DATE_FORMAT'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('date_format'); ?>
					&nbsp;<a href="http://docs.joomla.org/How_do_you_change_the_date_format%3F" target="_blank"><?php echo JText::_('FWG_DATE_OPTIONS'); ?></a>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo JText::_('FWG_DISPLAY_SOCIAL_SHARING'); ?>:
				</td>
				<td>
					<?php echo JText::_($this->obj->params->get('display_social_sharing')?'FWG_Yes':'FWG_No'); ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset class="adminform">
		<h3><?php echo JText::_('FWG_JOOMLA_UPDATE'); ?></h3>
		<table class="table">
			<tr>
				<td>
					<?php echo JText::_('FWG_UPDATE_ACCESS_CODE'); ?>:
				</td>
				<td>
					<?php echo $this->obj->params->get('update_code'); ?>
				</td>
			</tr>
		</table>
	</fieldset>
</div>
<div class="span4">
	<fieldset class="adminform">
		<h3><?php echo JText::_('FWG_FW_GALLERY'); ?></h3>
		<img style="margin: 0 30px 15px 0; float: left;" src="components/com_fwgallerylight/assets/images/fw_gallery_light.png" />
		<div><strong>Current component version:</strong> 4.3.2</div>
	    <div><strong>Release date:</strong> 26 Jun 2018</div>
		<div><strong>Tested on</strong>: Joomla 3.7.3</div>
		<div><strong>License</strong>: <a href="https://www.gnu.org/licenses/" target="_blank">GPLv3</a></div>
		<p>&nbsp;</p>
		<p>Beautiful and simple Joomla gallery extension with responsive design and social sharing options.</p>
		<div style="clear:both;"></div>
		<p><strong>What to do next</strong></p>
		<ol>
		<li>Check Configuration section to make sure settings match your desired configuration.</li>
		<li>Create a gallery in Galleries section.</li>
		<li>Add images to a gallery in Images section.</li>
		<li>Add a menu item with one of the gallery layouts to view gallery on front-end.</li>
		<li>Check documentation in <a href="http://fastw3b.net/client-section?layout=extensions" target="_blank">My Extensions section of Fastw3b</a> website if you couldn't get it to work.</li>
		<li><a href="http://fastw3b.net/client-section?layout=support" target="_blank">Suggest your idea here</a> if you feel like plugin usage can be simplified or better explained.</li>
		<li><a href="http://fastw3b.net/client-section?layout=support" target="_blank">Report a bug here</a> if you think something must be working and it doesn't.</li>
		<li><a href="https://extensions.joomla.org/extensions/extension/photos-a-images/galleries/fw-gallery
		" target="_blank">Leave a positive review on JED</a> if your experience was pleasant and you want to share it with others.</li>
		</ol>
		<p><strong>Useful links</strong></p>
		<div><img src="components/com_fwgallerylight/assets/images/bullet-green.png" style="margin-right: 5px; vertical-align: bottom;" /><a href="https://fastw3b.net/joomla-extensions/gallery" title="Joomla Gallery Page" target="_blank">FW Gallery page</a> on fastw3b.net.</div>
		<div><img src="components/com_fwgallerylight/assets/images/bullet-green.png" style="margin-right: 5px; vertical-align: bottom;" />Fastw3b <a href="http://fastw3b.net/client-section" title="Fasw3b Profile Page" target="_blank">Profile page</a> - check your membership status and billing info.</div>
		<div><img src="components/com_fwgallerylight/assets/images/bullet-green.png" style="margin-right: 5px; vertical-align: bottom;" /><a href="http://fastw3b.net/client-section?view=user&layout=transactions&id=8" title="Transactions Page" target="_blank">Transactions page</a> - check current status of your recent transactions.</div>
		<div><img src="components/com_fwgallerylight/assets/images/bullet-green.png" style="margin-right: 5px; vertical-align: bottom;" />Follow us on Twitter and Facebook to know latest news and updates.</div>
	</fieldset>
</div>
</div>
<form action="index.php?option=com_fwgallerylight&amp;view=config" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="task" value="" />
</form>
<script>
jQuery(function($) {
	var $lv = $('#fwg-latest-version');
	function fw_check_version() {
		var $img = $('<img src="<?php echo JURI::root(true); ?>/components/com_fwgallerylight/assets/images/ajax-loader.gif" alt="wait" />');
		$lv.html('').append($img);
		$.ajax({
			'url': '',
			'method': 'post',
			'data': {
				'format': 'json',
				'layout': 'check_update'
			}
		}).done(function(html) {
			$img.remove();
			var data = $.parseJSON(html);
			if (data) {
				if (data.rem_version) {
					if (data.rem_version == data.loc_version) {
						$lv.html('<span class="badge badge-default">'+data.rem_version+'</span><br><div class="alert-success p-2 mb-1"><span class="text-success"><i class="fa fa-check-circle"></i> <?php echo JText::_('FWG_VERSION_UPTODATE', true); ?></span></div>');
					} else {
						$lv.html('<span class="badge badge-default">'+data.rem_version+'</span><br><div class="alert-danger p-2"><span class="text-danger"><i class="fa fa-info-circle"></i> <?php echo JText::_('FWG_VERSION_REQ_UPDATE', true); ?></span>');
					}
				}
				if (data.msg) {
					alert(data.msg);
				}
			}
		});
	}
	fw_check_version();
	$('#fwg-verify-code button').click(function() {
		var $img = $('<img src="<?php echo JURI::root(true); ?>/components/com_fwgallerylight/assets/images/ajax-loader.gif" alt="wait" />');
		var $mt = $('#fwg-membership-type').html('').append($img);
		var $butt = $(this).attr('disabled', true);
		$.ajax({
			'url': '',
			'method': 'post',
			'data': {
				'format': 'json',
				'layout': 'verify_code',
				'code': $('input[name="config[update_code]"]').val()
			}
		}).done(function(html) {
			$img.remove();
			$butt.attr('disabled', false);
			var data = $.parseJSON(html);
			if (data) {
				if (data.user_name) {
					$('#fwg-revoke-code span').html('<span class="text-success font-weight-bold"><?php echo JText::_('FWG_VERIFIED', true); ?></span> <?php echo JText::_('FWG_FOR', true); ?> '+data.user_name);
				}
				if (data.expired) {
					if (data.is_version) {
						$mt.html('<?php echo JText::_('FWG_Version', true); ?> '+data.purchased_version+' - <span class="text-danger"><?php echo JText::_('FWG_EXPIRED_AFTER_VERSION', true); ?> '+data.next_version+'</span><br><a href="https://fastw3b.net/client-section?layout=extensions" target="_blank"><?php echo JText::_('FWG_BUY_LATEST_VERSION', true); ?></a> or <a href="https://fastw3b.net/joomla-extensions/realestate/for-agencies"><?php echo JText::_('FWG_Subscribe', true); ?></a>');
					} else {
						$mt.html('<?php echo JText::_('FWG_Subscription', true); ?> - <span class="text-danger"><?php echo JText::_('FWG_Expired_on', true); ?> '+data.expires+'</span><br><a href="https://fastw3b.net/client-section?layout=extensions" target="_blank"><?php echo JText::_('FWG_Renew_subscription', true); ?></a>'+(data.discount?(' <?php echo JText::_('FWG_with', true); ?> '+data.discount+'% <?php echo JText::_('FWG_discount', true); ?>'):''));
					}
				} else {
					if (data.is_version) {
						$mt.html('<?php echo JText::_('FWG_Version', true); ?> '+data.loc_version+' - <span class="text-success"><?php echo JText::_('FWG_Active', true); ?></span><br><small><?php echo JText::_('FWG_EXPIRES_AFTER_VERSION', true); ?> '+data.next_version+' <?php echo JText::_('FWG_IS_RELEASED', true); ?></small>');
					} else if (data.expires) {
						$mt.html('<?php echo JText::_('FWG_Subscription', true); ?> - <span class="text-success"><?php echo JText::_('FWG_Active', true); ?></span><br><small><?php echo JText::_('FWG_Expires_on', true); ?> '+data.expires+'</small>');
					} else if (data.membership) {
						$mt.html(data.membership + ' - <span class="text-success"><?php echo JText::_('FWG_Active', true); ?></span>');
					}
					if (data.rem_version != data.loc_version) {
						var $el = $('<br><button type="button" class="btn btn-success"><?php echo JText::_('FWG_INSTALL_UPDATE', true); ?></button><br><small><?php echo JText::_('FWG_OR_GO_TO', true); ?> <a href="index.php?option=com_installer&view=update"><?php echo JText::_('FWG_UPDATE_CENTER', true); ?></a> <?php echo JText::_('FWG_INSTALL_MANUALLY', true); ?></small></div>');
						$lv.find('span.text-danger').append($el);
					}
				}
				if (data.bug_link) {
					$('#fwg-support').append($('<a/>', {
						'target': '_blank',
						'class': 'btn btn-danger',
						'href': data.bug_link,
						'text': '<?php echo JText::_('FWG_REPORT_BUG'); ?>'
					}));
				}
				if (data.suggestion_link) {
					$('#fwg-support').append($('<a/>', {
						'target': '_blank',
						'class': 'btn btn-info',
						'href': data.suggestion_link,
						'text': '<?php echo JText::_('FWG_SUGGEST_IDEA'); ?>'
					}));
				}
				if (data.verified) {
					$('#fwg-verify-code').hide(300, function() {
						$('#fwg-revoke-code').show(300);
					});
				}
				if (data.msg) alert(data.msg);
			}
		});
	});
	if ($('#fwg-verify-code').css('display') == 'none') {
		$('#fwg-verify-code button').click();
	}
	$('#fwg-latest-version').on('click', 'button', function() {
		var $img = $('<img src="<?php echo JURI::root(true); ?>/components/com_fwgallerylight/assets/images/ajax-loader.gif" alt="wait" />');
		var $butt = $(this).attr('disabled', true).after($img);
		$.ajax({
			'url': '',
			'method': 'post',
			'data': {
				'format': 'json',
				'layout': 'update_package'
			}
		}).done(function(html) {
			$img.remove();
			$butt.attr('disabled', false);
			var data = $.parseJSON(html);
			if (data) {
				if (data.result) {
					fw_check_version();
				}
				if (data.msg) alert(data.msg);
			}
		});
	});
	$('#fwg-revoke-code button').click(function() {
		var $img = $('<img src="<?php echo JURI::root(true); ?>/components/com_fwgallerylight/assets/images/ajax-loader.gif" alt="wait" />');
		var $butt = $(this).attr('disabled', true).after($img);
		$.ajax({
			'url': '',
			'method': 'post',
			'data': {
				'format': 'json',
				'layout': 'revoke_code'
			}
		}).done(function(html) {
			$img.remove();
			$butt.attr('disabled', false);
			var data = $.parseJSON(html);
			if (data) {
				if (data.result) {
					$('#fwg-membership-type').html('');
					$('#fwg-expires').html('');
					$('#fwg-revoke-code span').html('');
					$butt.parent().hide(300);
					$('#fwg-verify-code').show(300);
					fw_check_version();
				}
				if (data.msg) alert(data.msg);
			}
		});
	});
});
</script>
