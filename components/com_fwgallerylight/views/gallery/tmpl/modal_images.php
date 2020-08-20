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
<form action="<?php echo JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=gallery&layout=modal&sublayout=images&tmpl=component&function=' . $this->function . '&' . JSession::getFormToken() . '=1')); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
    <div class="row-fluid">
        <div class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label class="element-invisible" for="search"><?php echo JText :: _('FWG_SEARCH_BY_IMAGE_NAME') ?></label>
                <input id="search" type="text" name="search" placeholder="<?php echo $this->escape(JText :: _('FWG_SEARCH_BY_IMAGE_NAME')); ?>" value="<?php echo $input->getString('search'); ?>" />
            </div>
            <div class="btn-group pull-left hidden-phone">
                <button type="submit" class="btn" onclick="with(this.form){task.value='';project_id.value=0;limitstart.value=0;submit();}" title="<?php echo $this->escape(JText :: _('FWG_SEARCH')); ?>"><i class="icon-search"></i></button>
                <button type="button" class="btn" onclick="with(this.form){search.value='';task.value='';project_id.value='';limitstart.value=0;submit();}" title="<?php echo $this->escape(JText :: _('FWG_CLEAR')); ?>"><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <a class="btn btn-primary" href="<?php echo JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=gallery&layout=modal_image_add&tmpl=component&function=' . $this->function.($this->project_id?('&project_id='.$this->project_id):''))); ?>"><?php echo JText :: _('FWG_ADD_NEW_IMAGE'); ?></a>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <?php echo JHTML :: _('fwGallerylightCategory.getCategories', 'project_id', $this->project_id, 'onchange="with(this.form){limitstart.value=0;submit();}"', false, JText :: _('FWG_SELECT_GALLERY')); ?>
            </div>
        </div>
    </div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="hidden-phone"><?php echo JText :: _('FWG_ID'); ?></th>
                <th><?php echo JText :: _('FWG_IMAGE_PREVIEW'); ?></th>
                <th class="hidden-phone"><?php echo JText :: _('FWG_DATE'); ?></th>
                <th class="hidden-phone"><?php echo JText :: _('FWG_NAME'); ?></th>
                <th><?php echo JText :: _('FWG_PUBLISHED'); ?></th>
            </tr>
        </thead>
        <tbody>
<?php
if ($this->files) {
    $num = 0;
    foreach ($this->files as $file) {
?>
            <tr>
                <td class="center hidden-phone"><?php echo $file->id; ?></td>
                <td>
                    <div class="row center">
                        <a href="javascript:" onclick="if (window.parent) window.parent.<?php echo $this->escape($this->function); ?>('<?php echo $file->id; ?>');">
							<?php echo JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$file->id.'&w=80&h=60')); ?>
                        </a>
                    </div>
                </td>
                <td class="center hidden-phone">
                    <?php echo substr($file->created, 0, 10); ?>
                </td>
                <td>
                    <a href="javascript:" onclick="if (window.parent) window.parent.<?php echo $this->escape($this->function); ?>('<?php echo $file->id; ?>');">
                        <?php echo $file->name; ?>
                    </a>
                </td>
                <td style="text-align:center;"><img src="<?php echo JURI :: root(true); ?>/components/com_fwgallerylight/assets/images/<?php if ($file->published) { ?>accept<?php } else { ?>cancel<?php } ?>.png" alt="" /></td>
            </tr>
<?php
        $num++;
    }
} else {
?>
            <tr>
                <td colspan="5"><?php echo JText :: _('FWG_NO_IMAGES'); ?></td>
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
