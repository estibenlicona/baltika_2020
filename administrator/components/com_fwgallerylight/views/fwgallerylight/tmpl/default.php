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

JToolBarHelper::title(JText::_('FWG_GALLERIES'));
JToolBarHelper::addNew();
JToolBarHelper::publish();
JToolBarHelper::unpublish();
JToolBarHelper::editList();
JToolBarHelper::deleteList(JText::_('FWG_ARE_YOU_SURE'));
$input = JFactory::getApplication()->input;
?>
<form action="index.php?option=com_fwgallerylight&amp;view=fwgallerylight" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="btn-toolbar">
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
				<select name="client" id="client" class="input-medium" onchange="with(document.adminForm){limitstart.value=0;submit();}">
					<option value=""><?php echo JText::_('FWG_AUTHOR');?></option>
					<?php echo JHtml::_('select.options', $this->clients, 'id', 'name', $this->client); ?>
				</select>
			</div>
		</div>
	</div>
	<table class="table table-striped">
	    <thead>
	        <tr>
	            <th style="width:20px;"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
				<th><?php echo JText::_('FWG_DEFAULT_IMAGE'); ?></th>
	            <th>
<?php
if ($this->order == 'name') {
?>
					<?php echo JText::_('FWG_NAME'); ?>
<?php
} else {
?>
					<a href="index.php?option=com_fwgallerylight&amp;view=fwgallerylight&amp;order=name">
						<?php echo JText::_('FWG_NAME'); ?>
					</a>
<?php
}
?>
				</th>
				<th><?php echo JText::_('FWG_DESCRIPTION'); ?></th>
	            <th style="width:5%;"><?php echo JText::_('FWG_IMAGES_QTY'); ?></th>
	            <th><?php echo JText::_('FWG_AUTHOR'); ?></th>
				<th>
<?php
if ($this->order == 'created') {
?>
					<?php echo JText::_('FWG_DATE'); ?>
<?php
} else {
?>
					<a href="index.php?option=com_fwgallerylight&amp;view=fwgallerylight&amp;order=created">
						<?php echo JText::_('FWG_DATE'); ?>
					</a>
<?php
}
?>
				</th>
	            <th><?php echo JText::_('FWG_VIEW_ACCESS'); ?></th>
				<th style="width:110px !important;">
<?php
if (!in_array($this->order, array('name', 'created'))) {
?>
					<?php echo JText::_('FWG_ORDER') ?>
					<?php echo JHTML::_('grid.order', $this->files); ?>
<?php
} else {
?>
					<a href="index.php?option=com_fwgallerylight&amp;view=fwgallerylight&amp;order=ordering">
						<?php echo JText::_('FWG_ORDER') ?>
					</a>
<?php
}
?>
				</th>
	            <th style="width:5%;"><?php echo JText::_('FWG_PUBLISHED'); ?></th>
	        </tr>
	    </thead>
	    <tbody>
<?php
if ($this->projects) {
    foreach ($this->projects as $num=>$project) {
		$descr = strip_tags($project->descr);
		if (strlen($descr) > 120) {
			$descr = substr(0, 120, $descr).'&hellip;';
		}
?>
	        <tr>
	            <td><?php echo JHTML::_('grid.id', $num, $project->id); ?></td>
				<td>
	                <a href="index.php?option=com_fwgallerylight&amp;view=gallery&amp;cid[]=<?php echo $project->id; ?>">
						<img src="<?php echo JURI::root(true); ?>/index.php?option=com_fwgallerylight&amp;view=image&amp;layout=image&amp;format=raw&amp;id=<?php echo $project->_file_id; ?>&amp;w=80&amp;h=60" alt="" />
					</a>
				</td>
	            <td>
	                <a href="index.php?option=com_fwgallerylight&amp;view=gallery&amp;cid[]=<?php echo $project->id; ?>">
	                    <?php echo $project->treename; ?>
	                </a>
	            </td>
				<td><?php echo $descr; ?></td>
	            <td class="center"><?php echo $project->_qty; ?></td>
	            <td><?php echo $project->_user_name; ?></td>
	            <td class="center">
	                <?php echo substr($project->created, 0, 10); ?>
	            </td>
	            <td><?php echo $project->_group_name?$project->_group_name:JText::_('FWG_PUBLIC'); ?></td>
	            <td class="order">
<?php
		if (!in_array($this->order, array('name', 'created'))) {
?>
	                <span><?php echo $this->pagination->orderUpIcon($num, $num?true:false, 'orderup', 'Move Up'); ?></span>
	                <span><?php echo $this->pagination->orderDownIcon($num, count($this->projects), true, 'orderdown', 'Move Down'); ?></span>
					<input type="text" name="order[]" size="5" value="<?php echo $project->ordering; ?>" class="inputbox text-area-order" style="text-align: center" />
<?php
		} else {
			echo $project->ordering;
		}
?>
	            </td>
	            <td class="center">
	                <?php echo JHTML::_('fwgGrid.published', $project, $num); ?>
	            </td>
	        </tr>
<?php
    }
} else {
?>
			<tr>
				<td colspan="10"><?php echo JText::_('FWG_NO_GALLERIES'); ?></td>
			</tr>
<?php
}
?>
	    </tbody>
	</table>
	<?php echo $this->pagination->getListFooter(); ?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="view" value="fwgallerylight" />
	<input type="hidden" name="boxchecked" value="0" />
</form>
<script type="text/javascript">
Joomla.submitbutton = function(pressbutton) {
	with (document.adminForm) {
		if (pressbutton == 'edit' || pressbutton == 'add') {
			view.value = 'gallery';
			task.value = '';
		} else {
			view.value = 'fwgallerylight';
			task.value = pressbutton;
		}
		submit();
	}
}
jQuery(function($) {
	$('a.saveorder').click(function() {
		$('input[name="cid[]"]').attr('checked', true);
	});
});
</script>
