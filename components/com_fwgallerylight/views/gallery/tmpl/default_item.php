<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

$link = 'index.php?option=com_fwgallerylight&view=image&id='.$this->row->id.':'.JFilterOutput :: stringURLSafe($this->row->name);

$title = JFHelper :: escape($this->row->name);
$img_src = JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$this->row->id));
$img_mid_src = JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$this->row->id.'&w=370&h=256'));
?>
<a href="<?php echo JFHelper::checkLink(JRoute::_($link)); ?>">
   <figure>
	  <img src="<?php echo $img_mid_src; ?>" alt="<?php echo $title; ?>">
	  <figcaption>
		 <div class="middle">
		 	<div class="middle-inner">
				<p class="fwg-item-type"><i class="fa fa-<?php if ($this->row->_type_name == 'Image') { ?>image<?php } else { ?>play-circle-o<?php } ?>"></i></p>
<?php
if ($this->params->get('display_name_image')) {
?>
				<p class="fwg-item-title"><?php echo $title; ?></p>
<?php
}
if (!$this->item_class) {
	if ($this->params->get('display_descr_image') and $this->row->descr) {
?>
				<p class="fwg-item-description"><?php echo $this->row->descr; ?></p>
<?php
	}
} else {
	if ($this->params->get('display_owner_image') and $this->row->_user_name) {
?>
	        	<p class="fwg-item-author"><?php echo $this->row->_user_name; ?></p>
<?php
	}
}
?>
			</div>
		 </div>
	  </figcaption>
   </figure>
</a>
