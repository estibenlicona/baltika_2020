<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::_('jquery.framework');
if (!defined('FWG_ISOTOPE_LOADED')) {
	define('FWG_ISOTOPE_LOADED', true);
	JHTML::script('components/com_fwgallerylight/assets/js/vendor/isotope.pkgd.min.js');
}
JHTML::script('components/com_fwgallerylight/assets/js/galleries.js');
?>
<div id="fwgallery">
<?php
if ($this->params->get('show_page_heading')) {
?>
	<div class="page-header">
		<h2 itemprop="headline"><?php echo $this->title; ?></h2>
	</div>
<?php
}
if ($this->list) {
?>
    <form action="<?php echo JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=galleries&Itemid='.JFHelper :: getItemid())); ?>" method="post" name="adminForm" id="adminForm">
<?php
	if ($this->params->get('display_gallery_sorting') || $this->params->get('display_total_galleries')) {
?>
	    <div class="row fwg-toolbar">
<?php
	if ($this->params->get('display_gallery_sorting')) {
?>
			<div class="col-sm-4 text-left">
				<?php echo JText :: _('FWG_ORDER_BY'); ?>: <?php echo JHTML :: _('select.genericlist', array(
					JHTML :: _('select.option', 'name', JText :: _('FWG_ALPHABETICALLY'), 'id', 'name'),
					JHTML :: _('select.option', 'new', JText :: _('FWG_NEWEST_FIRST'), 'id', 'name'),
					JHTML :: _('select.option', 'old', JText :: _('FWG_OLDEST_FIRST'), 'id', 'name'),
					JHTML :: _('select.option', 'order', JText :: _('FWG_ORDERING'), 'id', 'name')
				), 'order', 'onchange="this.form.submit();"', 'id', 'name', $this->order); ?>
			</div>
<?php
	}
?>
			<div class="col-sm-4 text-center">
			</div>
<?php
    if ($this->params->get('display_total_galleries')) {
?>
		    <div class="col-sm-4 text-right"><?php echo JText::_('FWG_TOTAL_GALLERIES'); ?>: <?php echo $this->pagination->total; ?></div>
<?php
    }
?>
	    </div>
<?php
	}
?>
	</form>
    <div class="row galleries-list galleries-masonry isotope-masonry">
<?php
	echo $this->loadTemplate('list');
?>
	</div>
<?php
	if ($this->pagination->total > $this->pagination->limit) {
?>
	<div class="fwg-more-block">
		<a href="javascript:" class="footer-loadmore" data-layout="list" data-items-per-page="<?php echo $this->pagination->limit; ?>" data-total="<?php echo $this->pagination->total; ?>"><?php echo JText::_('FWG_LOAD_MORE'); ?></a>
		<span class="loading-spinner" style="display:none;"></span>
	</div>
<?php
	}
} else {
    echo JText::_('FWG_NO_GALLERIES_AVAILABLE_FOR_PREVIEW_');
}
?>
</div>
