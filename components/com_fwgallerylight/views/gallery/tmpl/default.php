<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::stylesheet('components/com_fwgallerylight/assets/css/chocolat.css');
JHTML::_('jquery.framework');
if (!defined('FWG_ISOTOPE_LOADED')) {
	define('FWG_ISOTOPE_LOADED', true);
	JHTML::script('components/com_fwgallerylight/assets/js/vendor/isotope.pkgd.min.js');
}
JHTML::script('components/com_fwgallerylight/assets/js/vendor/jquery.chocolat.min.js');
JHTML::script('components/com_fwgallerylight/assets/js/gallery.js');
$input = JFactory::getApplication()->input;
if ($this->path) {
	$item = $this->path[count($this->path) - 1];
	$back_link = 'index.php?option=com_fwgallerylight&view=gallery&id='.$item->id.':'.JFilterOutput :: stringURLSafe($item->name).'&Itemid='.JFHelper :: getItemid('gallery', $item->id, $input->getInt('Itemid'));
	$back_title = 'FWG_RETURN_TO_PARENT_GALLERY';
} else {
	$back_link = 'index.php?option=com_fwgallerylight&view=galleries&Itemid='.JFHelper :: getItemid('galleries', 0, $input->getInt('Itemid'));
	$back_title = 'FWG_RETURN_TO_LIST_OF_GALLERIES';
}

?>
<div id="fwgallery">
	<div class="page-header">
		<h2 itemprop="headline"><?php echo $this->title; ?></h2>
	</div>
<?php
if ($this->params->get('display_descr_gallery') and $this->obj->descr) {
	echo $this->obj->descr;
}
if ($this->subcategories) {
?>
	<form method="post">
	    <div class="row fwg-toolbar">
			<div class="col-sm-4 text-left">
<?php
	if ($this->params->get('display_gallery_sorting')) {
?>
				<?php echo JText :: _('FWG_ORDER_BY'); ?>: <?php echo JHTML :: _('select.genericlist', array(
					JHTML :: _('select.option', 'name', JText :: _('FWG_ALPHABETICALLY'), 'id', 'name'),
					JHTML :: _('select.option', 'new', JText :: _('FWG_NEWEST_FIRST'), 'id', 'name'),
					JHTML :: _('select.option', 'old', JText :: _('FWG_OLDEST_FIRST'), 'id', 'name'),
					JHTML :: _('select.option', 'order', JText :: _('FWG_ORDERING'), 'id', 'name')
				), 'subcategory_order', 'onchange="this.form.submit();"', 'id', 'name', $this->subcategory_order); ?>
<?php
	}
?>
			</div>
			<div class="col-sm-4 text-center">
				<a href="<?php echo JFHelper::checkLink(JRoute::_($back_link)); ?>" class="fwg-back2gallery">
					<?php echo JText :: _($back_title); ?>
				</a>
			</div>
			<div class="col-sm-4 text-right">
<?php
	if ($this->params->get('display_total_galleries')) {
?>
				<?php echo JText::_('FWG_TOTAL_SUB_GALLERIES').': '.$this->gpagination->total; ?>
<?php
	}
?>
		    </div>
		</div>
	</form>
    <div class="row galleries-list galleries-masonry isotope-masonry fwg-subgalleries">
<?php
	echo $this->loadTemplate('glist');
?>
	</div>
<?php
}

if ($this->gpagination->total > $this->gpagination->limit) {
?>
	<div class="fwg-more-block">
		<a href="javascript:" class="footer-loadmore" data-layout="glist" data-target=".galleries-list" data-selector=".galleries-item" data-items-per-page="<?php echo $this->gpagination->limit; ?>" data-total="<?php echo $this->gpagination->total; ?>"><?php echo JText::_('FWG_LOAD_MORE'); ?></a>
		<span class="loading-spinner" style="display:none;"></span>
	</div>
<?php
}

if ($this->list) {
?>
	<form action="<?php echo JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=gallery&Itemid='.$input->getInt('Itemid').($this->obj->id?('&id='.$this->obj->id):''))); ?>" method="post">
	    <div class="row fwg-toolbar">
			<div class="col-sm-4 text-left">
<?php
if ($this->params->get('display_gallery_sorting')) {
?>
				<?php echo JText :: _('FWG_ORDER_BY'); ?>: <?php echo JHTML :: _('select.genericlist', array(
					JHTML :: _('select.option', 'name', JText :: _('FWG_ALPHABETICALLY'), 'id', 'name'),
					JHTML :: _('select.option', 'new', JText :: _('FWG_NEWEST_FIRST'), 'id', 'name'),
					JHTML :: _('select.option', 'old', JText :: _('FWG_OLDEST_FIRST'), 'id', 'name'),
					JHTML :: _('select.option', 'order', JText :: _('FWG_ORDERING'), 'id', 'name')
				), 'order', 'onchange="this.form.submit();"', 'id', 'name', $this->order); ?>
<?php
}
?>
			</div>
			<div class="col-sm-4 text-center">
<?php
if (!$this->subcategories) {
?>
				<a href="<?php echo JFHelper::checkLink(JRoute::_($back_link)); ?>" class="fwg-back2gallery">
					<?php echo JText :: _($back_title); ?>
				</a>
<?php
}
?>
			</div>
			<div class="col-sm-4 text-right">
<?php
if ($this->params->get('display_total_galleries')) {
	$data = array();
	if ($this->obj->_photos) $data[] = $this->obj->_photos.' '.JText :: _('FWG_IMAGES');
	if ($this->obj->_videos) $data[] = $this->obj->_videos.' '.JText :: _('FWG_VIDEOS');
?>
				<?php echo JText::_('FWG_TOTAL').': '.implode(', ', $data); ?>
<?php
}
?>
			</div>
	    </div>
	</form>

	<ul id="gallery-w-preview" class="fwg-single-gallery fwg-cols-<?php echo $this->images_a_row; ?> fwg-fixed-items fwg-images" data-id="<?php echo (int)$this->obj->id; ?>">
<?php
	$this->item_class = 'fwg-fixed-ratio-item';
	echo $this->loadTemplate('list');
?>
	</ul>
<?php
	if ($this->pagination->total > $this->pagination->limit) {
?>
	<div class="fwg-more-block">
		<a href="javascript:" class="footer-loadmore" data-ic="<?php echo $this->item_class; ?>" data-layout="list" data-target="#gallery-w-preview" data-selector=".fwg-single-gallery-item" data-items-per-page="<?php echo $this->pagination->limit; ?>" data-total="<?php echo $this->pagination->total; ?>"><?php echo JText::_('FWG_LOAD_MORE'); ?></a>
		<span class="loading-spinner" style="display:none;"></span>
	</div>
<?php
	}
} else {
    echo JText::_('FWG_NO_IMAGES_IN_THIS_GALLERY_');
}
?>
</div>
