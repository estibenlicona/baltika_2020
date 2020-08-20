<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/
defined('_JEXEC') or die('Restricted access');

$link = JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=gallery&id='.$this->row->id.':'.JFilterOutput::stringURLSafe($this->row->name)));

?>
<div class="col-sm-6 col-md-<?php echo $this->columns; ?> galleries-item isotope-item">
	<div class="fwg-loading galleries-item-image<?php if ($this->row->_videos) { ?> galleries-item-video<?php } ?>">
		<a href="<?php echo $link; ?>" class="img-eye-hover">
			<img src="<?php echo JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$this->row->_file_id.'&w=370&h=256')); ?>" alt="<?php echo JFHelper :: escape($this->row->name); ?>"/>
			<figcaption>
<?php
if ($this->row->_photos or $this->row->_videos or $this->row->_subfolders) {
	$data = array();
	if ($this->row->_subfolders) $data[] = $this->row->_subfolders.' '.JText :: _('FWG_GALLERIES');
	if ($this->row->_photos) $data[] = $this->row->_photos.' '.JText :: _('FWG_IMAGES');
	if ($this->row->_videos) $data[] = $this->row->_videos.' '.JText :: _('FWG_VIDEOS');
?>
				<div><?php echo implode(', ', $data); ?></div>
<?php
}
?>
			</figcaption>
		</a>
	</div>
	<div class="galleries-item-body">
<?php
if ($this->params->get('display_name_gallery')) {
?>
		<h3 class="galleries-item-title"><a href="<?php echo $link; ?>"><?php echo $this->row->name; ?></a></h3>
<?php
}
if ($this->params->get('display_owner_gallery') or $this->params->get('display_date_gallery')) {
?>
		<p class="galleries-item-info">
<?php
	if ($this->params->get('display_owner_gallery') and $this->row->_user_name) {
?>
			<?php echo JText::_('FWG_BY')." ".$this->row->_user_name; ?>
<?php
	}
	if ($this->params->get('display_date_gallery') and $date = JFHelper::encodeDate($this->row->created)) {
		if ($this->params->get('display_owner_gallery') and $this->row->_user_name) {
?>
			<?php echo JText::_('FWG_ON') ?>
<?php
		}
?>
			<time datetime="<?php echo $this->row->created; ?>"><?php echo $date; ?></time>
<?php
	}
?>
		</p>
<?php
}
if ($this->params->get('display_descr_gallery') and $this->row->descr) {
?>
		<p><?php echo $this->row->descr; ?></p>
<?php
}
?>
	</div>
</div>
