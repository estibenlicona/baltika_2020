<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.formvalidation');

JToolBarHelper::title(JText::_('FWG_GALLERY').' <small>['.JText::_($this->obj->id?'FWG_Edit':'FWG_New').']</small>');
JToolBarHelper::apply();
JToolBarHelper::save();
JToolBarHelper::cancel('cancel', JText::_($this->obj->id?'FWG_Close':'FWG_Cancel'));

$editor = JFactory::getEditor();
?>
<form action="index.php?option=com_fwgallerylight&amp;view=gallery" method="post" name="adminForm" id="adminForm" class="form-validate">
    <fieldset class="adminform">
        <legend><?php echo JText::_('FWG_DETAILS'); ?></legend>
        <table class="table">
            <tr>
                <td>
                    <?php echo JText::_('FWG_GALLERY_NAME'); ?><span class="required">*</span> :
                </td>
                <td>
                    <input class="inputbox required" type="text" name="name" size="50" maxlength="100" value="<?php echo $this->escape($this->obj->name);?>" />
                </td>
            </tr>
	        <tr>
	            <td>
	                <?php echo JText::_('FWG_PARENT_GALLERY'); ?>:
	            </td>
	            <td>
	                <?php echo JHTML::_('fwGallerylightCategory.parent', $this->obj); ?>
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
                    <?php echo JText::_('FWG_AUTHOR'); ?>:
                </td>
                <td>
                    <?php echo JHTML::_('select.genericlist', (array)$this->clients, 'user_id', '', 'id', 'name', $this->obj->user_id?$this->obj->user_id:$this->user->id); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo JText::_('FWG_VIEW_ACCESS'); ?>:
                </td>
                <td>
                    <?php echo JHTML::_('select.genericlist', (array)$this->groups, 'gid', 'size="10"', 'id', 'name', $this->obj->gid?$this->obj->gid:($this->groups?$this->groups[0]->id:29)); ?>
                </td>
            </tr>
	        <tr>
	            <td>
                    <?php echo JText::_('FWG_PUBLIC_WRITE_ACCESS'); ?>:
	            </td>
	            <td>
					<fieldset class="radio btn-group">
		            	<?php echo JHTML::_('select.booleanlist', 'is_public', '', @$obj->is_public); ?>
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
                    <?php echo JText::_('FWG_DESCRIPTION'); ?>:
                </td>
                <td>
                    <?php echo $editor->display('descr',  $this->obj->descr, '100%', '350', '75', '20', false); ?>
                </td>
            </tr>
        </table>
    </fieldset>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $this->obj->id; ?>" />
</form>
<span class="required">*</span> - <?php echo JText::_('FWG_REQUIRED_FIELDS'); ?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
	if (task == 'cancel') {
		location = 'index.php?option=com_fwgallerylight&view=fwgallerylight';
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
</script>
