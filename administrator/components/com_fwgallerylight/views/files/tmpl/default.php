<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('bootstrap.tooltip');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

JToolBarHelper::title(JText::_('FWG_IMAGES'));

JToolBarHelper::addNew();
JToolBarHelper::publish();
JToolBarHelper::unpublish();
JToolBarHelper::custom('batch', 'new', 'new', 'FWG_BATCH_UPLOAD', false);
JToolBarHelper::editList();
JToolBarHelper::deleteList(JText::_('FWG_ARE_YOU_SURE'));

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
$input = JFactory::getApplication()->input;
?>
<form action="index.php?option=com_fwgallerylight&amp;view=files" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label class="element-invisible" for="search"><?php echo JText::_('FWG_SEARCH_BY_IMAGE_NAME') ?></label>
				<input id="search" type="text" name="search" placeholder="<?php echo $this->escape(JText::_('FWG_SEARCH_BY_IMAGE_NAME')); ?>" value="<?php echo $input->getString('search'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn tip hasTooltip" onclick="with(this.form){task.value='';project_id.value=0;limitstart.value=0;submit();}" title="<?php echo $this->escape(JText::_('FWG_SEARCH')); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn tip hasTooltip" onclick="with(this.form){search.value='';task.value='';project_id.value='';limitstart.value=0;submit();}" title="<?php echo $this->escape(JText::_('FWG_CLEAR')); ?>"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right">
				<?php echo JHTML::_('select.genericlist', array(
					JHTML::_('select.option', 5, 5, 'id', 'name'),
					JHTML::_('select.option', 10, 10, 'id', 'name'),
					JHTML::_('select.option', 15, 15, 'id', 'name'),
					JHTML::_('select.option', 20, 20, 'id', 'name'),
					JHTML::_('select.option', 25, 25, 'id', 'name'),
					JHTML::_('select.option', 30, 30, 'id', 'name'),
					JHTML::_('select.option', 50, 50, 'id', 'name'),
					JHTML::_('select.option', 100, 100, 'id', 'name'),
					JHTML::_('select.option', 0, JText::_('FWG_ALL'), 'id', 'name')
				), 'limit', 'class="fwg-ordering" onchange="this.form.submit();"', 'id', 'name', $input->get('limit', $this->pagination->limit)); ?>
			</div>
			<div class="btn-group pull-right">
				<?php echo JHTML::_('fwGallerylightCategory.getCategories', 'project_id', $this->project_id, 'onchange="with(this.form){limitstart.value=0;submit();}"', false, JText::_('FWG_SELECT_GALLERY')); ?>
			</div>
		</div>
	</div>
	<table class="table table-striped">
	    <thead>
	        <tr>
	            <th style="width:20px;"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
	            <th><?php echo JText::_('FWG_ID'); ?></th>
	            <th><?php echo JText::_('FWG_IMAGE_PREVIEW'); ?></th>
	            <th>
<?php
if ($this->order == 'name') {
?>
					<?php echo JText::_('FWG_NAME'); ?>
<?php
} else {
?>
					<a href="index.php?option=com_fwgallerylight&amp;view=files&amp;order=name">
						<?php echo JText::_('FWG_NAME'); ?>
					</a>
<?php
}
?>
				</th>
	            <th><?php echo JText::_('FWG_TYPE'); ?></th>
				<th><?php echo JText::_('FWG_DESCRIPTION'); ?></th>
				<th><?php echo JText::_('FWG_AUTHOR'); ?></th>
	            <th>
<?php
if ($this->order == 'created') {
?>
					<?php echo JText::_('FWG_DATE'); ?>
<?php
} else {
?>
					<a href="index.php?option=com_fwgallerylight&amp;view=files&amp;order=created">
						<?php echo JText::_('FWG_DATE'); ?>
					</a>
<?php
}
?>
				</th>
	            <th><?php echo JText::_('FWG_GALLERY'); ?></th>
	            <th style="width:110px !important;">
<?php
if (!in_array($this->order, array('name', 'created'))) {
?>
					<?php echo JText::_('FWG_ORDER') ?>
					<?php echo JHTML::_('grid.order', $this->files); ?>
<?php
} else {
?>
					<a href="index.php?option=com_fwgallerylight&amp;view=files&amp;order=ordering">
						<?php echo JText::_('FWG_ORDER') ?>
					</a>
<?php
}
?>
				</th>
	        </tr>
	    </thead>
	    <tbody>
<?php
if ($this->files) {
	$num = 0;
    foreach ($this->files as $file) {
?>
	        <tr>
	            <td><?php echo JHTML::_('grid.id', $num, $file->id); ?></td>
	            <td class="center"><?php echo $file->id; ?></td>
	            <td>
	            	<div class="row center">
		                <a href="index.php?option=com_fwgallerylight&amp;view=file&amp;cid[]=<?php echo $file->id; ?>">
							<img src="<?php echo JURI::root(true); ?>/index.php?option=com_fwgallerylight&amp;view=image&amp;layout=image&amp;format=raw&amp;id=<?php echo $file->id; ?>&amp;w=80&amp;h=60&amp;rand=<?php echo rand(); ?>" alt="" />
		                </a>
		            </div>
	            	<div class="row center">
		            	<div class="btn-group">
			                <?php echo JHTML::_('fwgGrid.selected', $file, $num); ?>
			                <?php echo JHTML::_('fwgGrid.published', $file, $num); ?>
<?php
		if ($file->type_id == 1 and JFHelper::isFileExists($file)) {
?>
							<a class="btn btn-micro hasTooltip" href="javascript:" onclick="return listItemTask('cb<?php echo $num; ?>','clockwise')" title="<?php echo JText::_('FWG_ROTATE_CLOCKWISE'); ?>"><img src="<?php echo JURI::root(true); ?>/administrator/components/com_fwgallerylight/assets/images/arrow_rotate_clockwise.png" /></a>
							<a class="btn btn-micro hasTooltip" href="javascript:" onclick="return listItemTask('cb<?php echo $num; ?>','counterclockwise')" title="<?php echo JText::_('FWG_ROTATE_COUNTERCLOCKWISE'); ?>"><img src="<?php echo JURI::root(true); ?>/administrator/components/com_fwgallerylight/assets/images/arrow_rotate_anticlockwise.png" /></a>
<?php
		}
?>
			            </div>
		            </div>
	            </td>
	            <td>
	                <a href="index.php?option=com_fwgallerylight&amp;view=file&amp;cid[]=<?php echo $file->id; ?>">
	                    <?php echo $file->name; ?>
	                </a>
	            </td>
	            <td class="center"><?php echo $file->_type_name; ?></td>
<?php
		$descr = strip_tags($file->descr);
		if (strlen($descr) > 120) {
			$descr = substr(0, 120, $descr).'&hellip;';
		}
?>
				<td><?php echo $descr; ?></td>
				<td><?php echo $file->_user_name; ?></td>
	            <td class="center">
	                <?php echo substr($file->created, 0, 10); ?>
	            </td>
	            <td>
	                <a href="index.php?option=com_fwgallerylight&amp;view=fwgallery&amp;task=edit&amp;cid[]=<?php echo $file->project_id; ?>"><?php echo $file->_project_name; ?></a>
	            </td>
	            <td class="order">
<?php
		if (!in_array($this->order, array('name', 'created'))) {
?>
	                <span><?php echo $this->pagination->orderUpIcon($num, $num?true:false, 'orderup', 'Move Up'); ?></span>
	                <span><?php echo $this->pagination->orderDownIcon($num, count($this->files), true, 'orderdown', 'Move Down'); ?></span>
					<input type="text" name="order[]" size="5" value="<?php echo $file->ordering; ?>" class="inputbox text-area-order" style="text-align: center" />
<?php
		} else {
			echo $file->ordering;
		}
?>
	            </td>
	        </tr>
<?php
		$num++;
    }
} else {
?>
			<tr>
				<td colspan="10"><?php echo JText::_('FWG_NO_IMAGES'); ?></td>
			</tr>
<?php
}
?>
	    </tbody>
	</table>
	<?php echo $this->pagination->getListFooter(); ?>
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="files" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
<script type="text/javascript">
Joomla.submitbutton = function(pressbutton) {
	with (document.adminForm) {
		if (pressbutton == 'edit' || pressbutton == 'add') {
			view.value = 'file';
			task.value = '';
		} else {
			view.value = 'files';
			task.value = pressbutton;
		}
		submit();
	}
}
</script>
