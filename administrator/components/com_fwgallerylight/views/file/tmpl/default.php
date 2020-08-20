<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(JText::_('FWG_IMAGE').' <small>['.JText::_($this->obj->id?'FWG_Edit':'FWG_New').']</small>');
JToolBarHelper::apply();
JToolBarHelper::save();
JToolBarHelper::cancel();

JHTML::_('behavior.formvalidation');
$editor = JFactory::getEditor();

$media_is_file = (in_array($this->obj->media, array('flv', 'mov', 'mp4', 'divx', 'avi')))?true:false;
$type_id = JFHelper::getTypeId('video');
?>
<form action="index.php?option=com_fwgallerylight&amp;view=file" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate">
    <fieldset class="adminform">
        <legend><?php echo JText::_('FWG_DETAILS'); ?></legend>
        <table class="table">
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_NAME'); ?> :
	            </td>
	            <td>
	                <input id="name" class="required inputbox" type="text" name="name" size="50" maxlength="100" value="<?php echo $this->escape($this->obj->name);?>" />
	            </td>
	        </tr>
	        <tr>
				<td>
					<?php echo JText::_('FWG_AUTHOR'); ?>:
				</td>
				<td>
					<?php echo JHTML::_('select.genericlist', (array)$this->clients, 'user_id', 'class="required"', 'id', 'name', $this->obj->user_id?$this->obj->user_id:$this->user->id); ?>
				</td>
	        </tr>
	        <tr class="fwgallery_image_field">
	            <td>
	                <?php echo JText::_('FWG_GALLERY_DEFAULT'); ?>:
	            </td>
	            <td>
					<fieldset class="radio btn-group">
	                	<?php echo JHTML::_('select.booleanlist', 'selected', '', $this->obj->selected); ?>
					</fieldset>
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_PUBLISHED'); ?>:
	            </td>
	            <td>
					<fieldset class="radio btn-group">
	                	<?php echo JHTML::_('select.booleanlist', 'published', '', $this->obj->published); ?>
					</fieldset>
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_DATE'); ?>:
	            </td>
	            <td>
	                <?php echo JHTML::_('calendar', substr($this->obj->created?$this->obj->created:date('Y-m-d'), 0, 10), 'created', 'created', '%Y-%m-%d', array('class'=>'inputbox', 'size'=>'25',  'maxlength'=>'19')); ?>
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_COPYRIGHT'); ?>:
	            </td>
	            <td>
	                <input id="copyright" type="text" name="copyright" size="50" maxlength="100" value="<?php echo $this->escape($this->obj->copyright);?>" />
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_GALLERY'); ?>:
	            </td>
	            <td>
	                <?php echo JHTML::_('fwGallerylightCategory.getCategories', 'project_id', $this->obj->project_id, 'class="required"', $multiple=false, $first_option=''); ?>
	            </td>
	        </tr>
<?php
if (count($this->types) > 1) {
?>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_FILE_TYPE'); ?>:
	            </td>
				<td>
					<?php echo JHTML::_('select.genericlist', $this->types, 'type_id', '', 'id', 'name', $this->obj->type_id); ?>
				</td>
	        </tr>
<?php
}
?>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_FILE'); ?>:
	            </td>
	            <td>
                    <p><?php echo JText::_('FWG_FILE_UPLOAD_SIZE_LIMIT').' '.ini_get('post_max_size'); ?></p>
					<div class="fwg-types" id="fwg-type-<?php echo $type_id; ?>"<?php if ($this->obj->_type_name != 'Video') { ?> style="display:none;"<?php } ?>>
						<?php echo JText::_('FWG_VIDEO_TYPE'); ?>: <?php echo JHTML::_('select.genericlist', $this->media, 'media', 'class="fwg-media" style="float:none;"', 'id', 'name', $this->obj->media, 'fwg-media'); ?>
						<div class="local"<?php if (!$media_is_file) { ?> style="display:none;"<?php } ?>>
							<?php echo JText::_('FWG_VIDEO_FILENAME'); ?>: <input<?php if (!$media_is_file) { ?> disabled<?php } ?> style="float:none;" name="file" type="file" /><?php if ($this->obj->media_code and $this->obj->media and $media_is_file) { ?> (<?php echo $this->obj->media_code; ?>)<?php } ?><br/>
							<?php echo JText::_('FWG_PREVIEW_FILENAME'); ?>: <input<?php if (!$media_is_file) { ?> disabled<?php } ?> style="float:none;" name="preview" type="file" /><?php if ($this->obj->filename and $this->obj->media and $media_is_file) { ?> (<?php echo $this->obj->filename; ?>)<?php } ?>
						</div>
						<div class="remote"<?php if ($media_is_file) { ?> style="display:none;"<?php } ?>>
							<?php echo JText::_('FWG_VIDEO_ID'); ?>: <input type="text"<?php if ($media_is_file) { ?> disabled<?php } ?> style="float:none;" name="video" value="<?php if ($this->obj->media_code and $this->obj->media and !$media_is_file) echo $this->obj->media_code; ?>" />
						</div>
					</div>
					<div class="fwg-types" id="fwg-type-1"<?php if ($this->obj->type_id > 1 and $this->obj->_is_type_published) { ?> style="display:none;"<?php } ?>>
		                <img src="<?php echo JURI::root(true); ?>/index.php?option=com_fwgallerylight&amp;view=image&amp;layout=image&amp;format=raw&amp;id=<?php echo $this->obj->id; ?>&amp;w=370&amp;h=256&amp;js=1&amp;rand=<?php echo rand(); ?>"/><br/>
		                <?php echo JText::_('FWG_FILENAME').':'.$this->obj->filename; ?><br/>
		                <input id="filename" type="file" name="filename"/>
					</div>
	            </td>
	        </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_DESCRIPTION'); ?>:
	            </td>
	            <td>
                    <?php echo $editor->display('descr',  $this->obj->descr, '600', '350', '75', '20', false); ?>
	            </td>
	        </tr>
        </table>
    </fieldset>
<?php
if (count($this->types) < 2) {
?>
	<input type="hidden" name="type_id" value="1" />
<?php
}
?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->obj->id; ?>" />
</form>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	if (task == 'cancel') {
		location = 'index.php?option=com_fwgallerylight&view=files';
		return;
	}
	var form = document.adminForm;
	if ((task == 'apply' || task == 'save') && !document.formvalidator.isValid(form)) {
		alert('<?php echo JText::_('FWG_NOT_ALL_REQUIRED_FIELDS_FILLED', true); ?>');
	} else {
		form.task.value = task;
		form.submit();
	}
}
function fwg_check_type() {
	var id = jQuery('#type_id').val();
	jQuery('.fwg-types').each(function(index, el) {
		if (el.id == 'fwg-type-'+id) el.style.display = '';
		else el.style.display = 'none';
	});
}
function fwg_check_media() {
	var media = jQuery('#fwg-media').val();
	if (media == 'flv' || media == 'mov' || media == 'divx' || media == 'avi' || media == 'mp4') {
		jQuery('#fwg-type-<?php echo $type_id; ?> .local').css('display', '');
		jQuery('#fwg-type-<?php echo $type_id; ?> .remote').css('display', 'none');
		jQuery('#fwg-type-<?php echo $type_id; ?> .local input').each(function(index, el) {
			jQuery(el).attr('disabled', false);
		});
		jQuery('#fwg-type-<?php echo $type_id; ?> .remote input').attr('disabled', true);
	} else {
		jQuery('#fwg-type-<?php echo $type_id; ?> .local').css('display', 'none');
		jQuery('#fwg-type-<?php echo $type_id; ?> .remote').css('display', '');
		jQuery('#fwg-type-<?php echo $type_id; ?> .local input').each(function(index, el) {
			jQuery(el).attr('disabled', true);
		});
		jQuery('#fwg-type-<?php echo $type_id; ?> .remote input').attr('disabled', false);
	}
}
jQuery(function($) {
	$('#fwg-media').click(fwg_check_media).change(fwg_check_media);
	fwg_check_media();
	if ($('#type_id').length) {
		$('#type_id').change(fwg_check_type).click(fwg_check_type);
		fwg_check_type();
	}
});
</script>
