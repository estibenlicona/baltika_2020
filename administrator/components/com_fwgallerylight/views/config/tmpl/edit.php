<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::_('behavior.formvalidation');
JHTML::_('behavior.colorpicker');

JToolBarHelper::title(JText::_('FWG_CONFIG').': <small><small>[ '.JText::_('FWG_EDIT').' ]</small></small>');
JToolBarHelper::apply();
JToolBarHelper::save();
JToolBarHelper::cancel();
$color = $this->obj->params->get('gallery_color');
if (preg_match('/[0-9a-fA-F]{3,6}/', $color)) $color = '#'.$color;
else $color = '';
?>
<div class="row-fluid">
<form action="index.php?option=com_fwgallerylight&amp;view=config" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
	<div class="span6">
	    <fieldset class="adminform">
	        <legend><?php echo JText::_('FWG_LAYOUT_SETTINGS'); ?></legend>
	        <table class="table">
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_GALLERIES_IN_A_ROW'); ?> &nbsp;<span class="badge" title="<?php echo JText::_('FWG_THE_NUMBER_DEPENDS_ON_THE_THUMB_SIZES'); ?>">?</span><span class="badge badge-warning">*</span>
		            </td>
		            <td>
		            	<?php echo JHTML::_('select.genericlist', $this->columns, 'config[galleries_a_row]', '', 'id', 'name', $this->obj->params->get('galleries_a_row', 3)); ?>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_GALLERIES_ROWS_PER_PAGE'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_NUMBER_OF_GALLERIES'); ?>">?</span>
		            </td>
		            <td>
						<input type="text" class="inputbox required validate-numeric" name="config[galleries_rows]" value="<?php echo $this->obj->params->get('galleries_rows', 4); ?>" size="5"/>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DEFAULT_GALLERIES_ORDERING'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_SELECT_GALLERIES_ORDER'); ?>">?</span>
		            </td>
		            <td>
						<?php echo JHTML::_('select.genericlist', array(
							JHTML::_('select.option', 'name', JText::_('FWG_ALPHABETICALLY'), 'id', 'name'),
							JHTML::_('select.option', 'new', JText::_('FWG_NEWEST_FIRST'), 'id', 'name'),
							JHTML::_('select.option', 'old', JText::_('FWG_OLDEST_FIRST'), 'id', 'name'),
							JHTML::_('select.option', 'order', JText::_('FWG_ORDERING'), 'id', 'name')
						), 'config[ordering_galleries]', '', 'id', 'name', $this->obj->params->get('ordering_galleries', 'order')); ?>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_IMAGES_IN_A_ROW'); ?> &nbsp;<span class="badge" title="<?php echo JText::_('FWG_THE_NUMBER_DEPENDS_ON_THE_THUMB_SIZES'); ?>">?</span><span class="badge badge-warning">*</span>
		            </td>
		            <td>
		            	<?php echo JHTML::_('select.genericlist', $this->columns, 'config[images_a_row]', '', 'id', 'name', $this->obj->params->get('images_a_row', 3)); ?>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_IMAGES_ROWS_PER_PAGE'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_NUMBER_OF_IMAGES'); ?>">?</span>
		            </td>
		            <td>
						<input type="text" class="inputbox required validate-numeric" name="config[images_rows]" value="<?php echo $this->obj->params->get('images_rows', 4); ?>" size="5"/>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DEFAULT_IMAGES_ORDERING'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_SELECT_WATERMARK_LOCATION_FRONTEND'); ?>">?</span>
		            </td>
		            <td>
						<?php echo JHTML::_('select.genericlist', array(
							JHTML::_('select.option', 'name', JText::_('FWG_ALPHABETICALLY'), 'id', 'name'),
							JHTML::_('select.option', 'new', JText::_('FWG_NEWEST_FIRST'), 'id', 'name'),
							JHTML::_('select.option', 'old', JText::_('FWG_OLDEST_FIRST'), 'id', 'name'),
							JHTML::_('select.option', 'order', JText::_('FWG_ORDERING'), 'id', 'name')
						), 'config[ordering_images]', '', 'id', 'name', $this->obj->params->get('ordering_images', 'order')); ?>
		            </td>
		        </tr>
		    </table>
	    </fieldset>

	    <fieldset class="adminform">
	        <legend><?php echo JText::_('FWG_WATERMARK'); ?></legend>
	        <table class="table">
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_USE_WATERMARK'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_YES_NO_OPTION'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'use_watermark', '', $this->obj->params->get('use_watermark')); ?>
						</fieldset>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_WATERMARK_POSITION'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_SELECT_WATERMARK_LOCATION'); ?>">?</span>
		            </td>
		            <td>
						<?php echo JHTML::_('select.genericlist', array(
							JHTML::_('select.option', 'center', JText::_('FWG_CENTER'), 'id', 'name'),
							JHTML::_('select.option', 'left top', JText::_('FWG_LEFT_TOP'), 'id', 'name'),
							JHTML::_('select.option', 'right top', JText::_('FWG_RIGHT_TOP'), 'id', 'name'),
							JHTML::_('select.option', 'left bottom', JText::_('FWG_LEFT_BOTTOM'), 'id', 'name'),
							JHTML::_('select.option', 'right bottom', JText::_('FWG_RIGHT_BOTTOM'), 'id', 'name')
						), 'config[watermark_position]', '', 'id', 'name', $this->obj->params->get('watermark_position', 'left bottom')); ?>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_WATERMARK_FILE'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_UPLOAD_WATERMARK'); ?>">?</span>
		            </td>
		            <td>
<?php
$wmf = $this->obj->params->get('watermark_file');
if ($wmf) {
	if ($path = JFHelper::getWatermarkFilename()) {
?>
						<img src="<?php echo JURI::root(true); ?>/<?php echo $path; ?>" /><br/>
						<input type="checkbox" name="delete_watermark" value="1" /> <?php echo JText::_('FWG_REMOVE_WATERMARK'); ?><br/>
<?php
	} else {
?>
						<p style="color:#f00;"><?php echo JText::sprintf('FWG_WATERMARK_FILE_NOT_FOUND_', $wmf); ?></p>
<?php
	}
}
?>
						<input id="watermark_file" type="file" class="inputbox" name="watermark_file" />
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_WATERMARK_TEXT'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_TYPE_A_WATERMARK_TEXT'); ?>">?</span>
		            </td>
		            <td>
						<input type="text" class="inputbox" name="config[watermark_text]" value="<?php echo $this->obj->params->get('watermark_text'); ?>" size="35"/>
		            </td>
		        </tr>
			</table>
		</fieldset>

	</div>

	<div class="span6">
	    <fieldset class="adminform">
	        <legend><?php echo JText::_('FWG_DISPLAYING_SETTINGS'); ?></legend>
	        <table class="table">
		        <tr>
		            <td style="width:40%;">
	                    <?php echo JText::_('FWG_DISPLAY_TOTAL_GALLERIES_COUNTER'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_YES_NO_OPTION_HIDE'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_total_galleries', '', $this->obj->params->get('display_total_galleries')); ?>
						</fieldset>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_EMPTY_GALLERIES'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_SHOW_OR_HIDE_THE_GALLERY_NAME'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_empty_gallery', '', $this->obj->params->get('display_empty_gallery')); ?>
						</fieldset>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_GALLERY_NAME'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_SHOW_OR_HIDE_THE_GALLERY_NAME'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_name_gallery', '', $this->obj->params->get('display_name_gallery')); ?>
						</fieldset>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_GALLERY_OWNER_NAME'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_YES_NO_OPTION_HIDE'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_owner_gallery', '', $this->obj->params->get('display_owner_gallery')); ?>
						</fieldset>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_GALLERY_DATE'); ?>&nbsp;<span class="badge"  title="<?php echo JText::_('FWG_SHOW_OR_HIDE_THE_GALLERY_CREATION_DATE'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_date_gallery', '', $this->obj->params->get('display_date_gallery')); ?>
						</fieldset>
		            </td>
		        </tr>
				<tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_GALLERY_DESCR'); ?>&nbsp;<span class="badge"  title="<?php echo JText::_('FWG_SHOW_OR_HIDE_THE_GALLERY_CREATION_DATE'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_descr_gallery', '', $this->obj->params->get('display_descr_gallery')); ?>
						</fieldset>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_ORDER_BY_OPTION'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_YES_NO_OPTION_HIDE_FRONT_END'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_gallery_sorting', '', $this->obj->params->get('display_gallery_sorting')); ?>
						</fieldset>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_IMAGE_NAME'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_SHOW_OR_HIDE_THE_IMAGE_NAME'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_name_image', '', $this->obj->params->get('display_name_image')); ?>
						</fieldset>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_IMAGE_OWNER_NAME'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_YES_NO_OPTION_HIDE'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_owner_image', '', $this->obj->params->get('display_owner_image')); ?>
						</fieldset>
		            </td>
		        </tr>
				<tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_IMAGE_DESCR'); ?>&nbsp;<span class="badge"  title="<?php echo JText::_('FWG_SHOW_OR_HIDE_THE_GALLERY_CREATION_DATE'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_descr_image', '', $this->obj->params->get('display_descr_image')); ?>
						</fieldset>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_IMAGE_DATE'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_SHOW_OR_HIDE_THE_IMAGE_CREATION_DATE'); ?>">?</span>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_date_image', '', $this->obj->params->get('display_date_image')); ?>
						</fieldset>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DATE_FORMAT'); ?>&nbsp;<span class="badge" title="<?php echo JText::_('FWG_SET_DATE_FORMAT'); ?>">?</span>
		            </td>
		            <td>
						<input type="text" class="inputbox" name="config[date_format]" value="<?php echo $this->obj->params->get('date_format', '%B %d, %Y'); ?>" size="15"/>
						&nbsp;<a href="http://www.php.net/date" target="_blank"><?php echo JText::_('FWG_DATE_OPTIONS'); ?></a>
		            </td>
		        </tr>
		        <tr>
		            <td>
	                    <?php echo JText::_('FWG_DISPLAY_SOCIAL_SHARING'); ?>
		            </td>
		            <td>
						<fieldset class="radio btn-group">
							<?php echo JHTML::_('select.booleanlist', 'display_social_sharing', '', $this->obj->params->get('display_social_sharing')); ?>
						</fieldset>
		            </td>
		        </tr>
			</table>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_('FWG_JOOMLA_UPDATE'); ?></legend>
			<table class="table">
				<tr>
					<td>
						<?php echo JText::_('FWG_UPDATE_ACCESS_CODE'); ?>:
					</td>
					<td>
						<input type="text" class="inputbox" name="config[update_code]" value="<?php echo $this->obj->params->get('update_code'); ?>" size="10"/>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>
	<input type="hidden" name="task" value="" />
</form>
</div>
<script type="text/javascript">

jQuery(function($) {
	$('#adminForm input').each(function (index, el) {
		if (el.name == 'hide_fw_copyright') {
			$(el).click(check_copyright).change(check_copyright);
		}
	});
	$('#fw-copyright-donation').hide();
	check_copyright();
});
function check_copyright() {
	jQuery('#adminForm input').each(function (index, el) {
		if (el.name == 'hide_fw_copyright' && el.checked) {
			if (el.value == '0') jQuery('#fw-copyright-donation').hide(500);
			else jQuery('#fw-copyright-donation').show(500);
		}
	});
}
Joomla.submitbutton = function(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'save') {
		if (!document.formvalidator.isValid(form)) {
			alert('<?php echo JText::_('FWG_NOT_ALL_REQUIRED_FIELDS_FILLED'); ?>');
			return;
		}
		if (document.getElementById('watermark_file') && document.getElementById('watermark_file').value && !document.getElementById('watermark_file').value.match(/\.png$/i)) {
			alert('<?php echo JText::_('FWG_ALLOWED_PNG_WATERMARK'); ?>');
			return;
		}
	}
	with (form) {
		task.value = pressbutton;
		submit();
	}
}
</script>
