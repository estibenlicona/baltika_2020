<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<div class="container-popup">
	<ul class="nav nav-tabs">
<?php
if ($this->sublayout == 'galleries') {
?>
	<li class="active"><a href="javascript:"><?php echo JText::_('FWG_SELECT_GALLERY_TO_INSERT'); ?></a></li>
<?php
} else {
?>
	<li><a href="index.php?option=com_fwgallerylight&amp;view=gallery&amp;layout=modal&amp;sublayout=galleries&amp;tmpl=component"><?php echo JText::_('FWG_SELECT_GALLERY_TO_INSERT'); ?></a></li>
<?php
}
if ($this->sublayout == 'images') {
?>
	<li class="active"><a href="javascript:"><?php echo JText::_('FWG_SELECT_IMAGE_TO_INSERT'); ?></a></li>
<?php
} else {
?>
	<li><a href="index.php?option=com_fwgallerylight&amp;view=gallery&amp;layout=modal&amp;sublayout=images&amp;tmpl=component"><?php echo JText::_('FWG_SELECT_IMAGE_TO_INSERT'); ?></a></li>
<?php
}
?>
	</ul>
	<div class="tab-content">
<?php
include('modal_'.$this->sublayout.'.php');
?>
	</div>
</div>
