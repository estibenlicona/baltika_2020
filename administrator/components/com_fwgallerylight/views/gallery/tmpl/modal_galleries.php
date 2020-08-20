<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

$input = JFactory::getApplication()->input;
?>
<form action="index.php?option=com_fwgallerylight&amp;view=gallery&amp;layout=modal&amp;sublayout=galleries&amp;tmpl=component&amp;function=<?php echo $this->function; ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
    <div class="row-fluid">
        <div class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label class="element-invisible" for="search"><?php echo JText::_('FWG_SEARCH_BY_GALLERY_NAME') ?></label>
                <input id="search" type="text" name="search" placeholder="<?php echo $this->escape(JText::_('FWG_SEARCH_BY_GALLERY_NAME')); ?>" value="<?php echo $input->getString('search'); ?>" />
            </div>
            <div class="btn-group pull-left hidden-phone">
                <button type="submit" class="btn tip hasTooltip" onclick="with(this.form){task.value='';project_id.value=0;limitstart.value=0;submit();}" title="<?php echo $this->escape(JText::_('FWG_SEARCH')); ?>"><i class="icon-search"></i></button>
                <button type="button" class="btn tip hasTooltip" onclick="with(this.form){search.value='';task.value='';project_id.value='';limitstart.value=0;submit();}" title="<?php echo $this->escape(JText::_('FWG_CLEAR')); ?>"><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_fwgallerylight&view=gallery&layout=modal_image_add&tmpl=component&function=' . $this->function.($this->project_id?('&project_id='.$this->project_id):'')); ?>"><?php echo JText::_('FWG_ADD_NEW_IMAGE'); ?></a>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_fwgallerylight&view=gallery&layout=modal_add&tmpl=component&function=' . $this->function); ?>"><?php echo JText::_('FWG_ADD_NEW_GALLERY'); ?></a>
            </div>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="hidden-phone"><?php echo JText::_('FWG_ID'); ?></th>
                <th><?php echo JText::_('FWG_NAME'); ?></th>
                <th><?php echo JText::_('FWG_VIEW_ACCESS'); ?></th>
                <th class="hidden-phone" style="width:5%;"><?php echo JText::_('FWG_IMAGES_QTY'); ?></th>
                <th><?php echo JText::_('FWG_PUBLISHED'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
if ($this->galleries) {
    foreach ($this->galleries as $gallery) {
?>
            <tr>
                <td class="center hidden-phone"><?php echo $gallery->id; ?></td>
                <td>
                    <a href="javascript:" onclick="if (window.parent) window.parent.<?php echo $this->escape($this->function); ?>('<?php echo $gallery->id; ?>');">
                        <?php echo $gallery->treename; ?>
                    </a>
                </td>
                <td>
                    <?php echo $gallery->_group_name?$gallery->_group_name:JText::_('FWG_PUBLIC'); ?>
                </td>
                <td class="hidden-phone" style="text-align:center;"><?php echo $gallery->_qty; ?></td>
                <td style="text-align:center;"><img src="<?php echo JURI::root(true); ?>/components/com_fwgallerylight/assets/images/<?php if ($gallery->published) { ?>accept<?php } else { ?>cancel<?php } ?>.png" alt="" /></td>
            </tr>
<?php
    }
} else {
?>
            <tr>
                <td colspan="5"><?php echo JText::_('FWG_NO_GALLERIES'); ?></td>
            </tr>
<?php
}
?>
        </tbody>
    </table>
    <?php echo $this->pagination->getListFooter(); ?>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
</form>
