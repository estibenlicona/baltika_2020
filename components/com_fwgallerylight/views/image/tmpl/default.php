<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

$ret_link = JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=gallery&id='.$this->row->project_id.':'.JFilterOutput :: stringURLSafe($this->row->_gallery_name)));
$next_link = $this->next_image?JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=image&id='.$this->next_image->id.':'.JFilterOutput :: stringURLSafe($this->next_image->name))):'';
$prev_link = $this->previous_image?JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=image&id='.$this->previous_image->id.':'.JFilterOutput :: stringURLSafe($this->previous_image->name))):'';

$show_right_panel = ($this->params->get('display_name_image') or ($this->params->get('display_owner_image') and $this->row->_user_name) or ($this->params->get('display_user_copyright') and $this->row->copyright) or ($this->params->get('display_date_image') and $date = JFHelper::encodeDate($this->row->created)) or ($this->params->get('display_descr_image') and $this->row->descr) or $this->params->get('display_social_sharing'));
?>
<div id="fwgallery" class="fwg-single-item">
	<div class="row fwg-toolbar">
	    <div class="col-sm-4 text-left">
<?php
if ($prev_link) {
?>
			<a href="<?php echo $prev_link; ?>" class="fwg-prev-post"><?php echo JText::_('FWG_PREVIOUS_IMAGE'); ?></a>
<?php
} else {
?>
			<a href="javascript:" class="fwg-prev-post disabled"><?php echo JText::_('FWG_PREVIOUS_IMAGE'); ?></a>
<?php
}
?>
		</div>
	    <div class="col-sm-4 text-center">
                <a href="<?php echo $ret_link; ?>" class="fwg-back2gallery">
                    <?php echo JText :: sprintf('FWG_RETURN_TO_THE_GALLERY', $this->row->_gallery_name); ?></a>
            </div>
	    <div class="col-sm-4 text-right">
<?php
if ($next_link) {
?>
			<a href="<?php echo $next_link; ?>" class="fwg-next-post"><?php echo JText::_('FWG_NEXT_IMAGE'); ?></a>
<?php
} else {
?>
			<a href="javascript:" class="fwg-next-post disabled"><?php echo JText::_('FWG_NEXT_IMAGE'); ?></a>
<?php
}
?>
		</div>
	</div>
	<div class="row fwg-single-item-wrapper">
        <div class="col-sm-<?php if ($show_right_panel) { ?>8<?php } else { ?>12<?php } ?>">
<?php
if ($this->row->_type_name == 'Image') {
	$img_src = JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$this->row->id));
?>
			<figure class="image-fit">
				<img src="<?php echo $img_src; ?>" alt="<?php echo JFHelper :: escape($this->row->name); ?>" />
<?php
		if (!$this->params->get('hide_mignifier')) {
?>
				<a href="<?php echo $img_src; ?>" class="fwg-zoom"></a>
<?php
		}
?>
			</figure>
<?php
} else {
?>
			<div class="fwg-single-item-video fwg-video">
<?php
	switch ($this->row->media) {
		case 'youtube' :
?>
				<iframe src="http://www.youtube.com/embed/<?php echo $this->row->media_code; ?>?rel=0" frameborder="0" allowfullscreen></iframe>
<?php
		break;
		case 'vimeo' :
?>
				<iframe src="http://player.vimeo.com/video/<?php echo $this->row->media_code; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<?php
		break;
		case 'mp4' :
		$path = '/images/com_fwgallery/files/'.$this->row->_user_id.'/';
		if ($this->row->media_code and file_exists(JPATH_SITE.$path.$this->row->media_code)) {
?>
				<video class="video-js vjs-default-skin vjs-mental-skin" width="100%" height="100%" controls preload="none" poster="<?php echo JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$this->row->id)); ?>" data-setup="{}">
				   <source src="<?php echo JURI::root(true).$path.$this->row->media_code; ?>" type="video/mp4" />
				</video>
<?php
		}
		break;
	}
?>
			</div>
<?php
}
?>
		</div>
<?php
if ($show_right_panel) {
?>
    	<div class="col-sm-4 fwg-image-info">
<?php
	if ($this->params->get('display_name_image')) {
?>
        	<h4><?php echo $this->row->name; ?></h4>
<?php
	}
	if ($this->params->get('display_owner_image') and $this->row->_user_name) {
?>
        	<p><?php echo $this->row->_user_name; ?></p>
<?php
	}
	if ($this->params->get('display_user_copyright') and $this->row->copyright) {
?>
        	<p><?php echo $this->row->copyright; ?></p>
<?php
	}
	if ($this->params->get('display_date_image') and $date = JFHelper::encodeDate($this->row->created)) {
?>
        	<p><?php echo $date; ?></p>
<?php
	}
	if ($this->params->get('display_descr_image') and $this->row->descr) {
?>
        	<p><?php echo $this->row->descr; ?></p>
<?php
	}
	if ($this->params->get('display_social_sharing')) {
		$share_name = urlencode(JText::sprintf('FWG_SHARE_IMAGE_NAME', $this->row->name, $this->row->_project_name));
		$share_link = urlencode(JURI::getInstance()->toString());
		$share_img = urlencode(JURI::root(false).JFHelper::getFileFilename($this->row));
?>
	        <div class="fwg-social">
	            <h6><?php echo JText::_('FWG_SHARE'); ?></h6>
                    <a target="_blank" href="https://twitter.com/intent/tweet?url=<?php echo $share_link; ?>&amp;text=<?php echo $share_name; ?>"><i class="fa fa-twitter"></i></a>
                    <a target="_blank" href="https://www.facebook.com/sharer.php?u=<?php echo $share_link; ?>"><i class="fa fa-facebook"></i></a>
                    <a target="_blank" href="https://plus.google.com/share?url=<?php echo $share_link; ?>"><i class="fa fa-google-plus"></i></a>
                    <a target="_blank" href="https://pinterest.com/pin/create/bookmarklet/?media=<?php echo $share_img; ?>&amp;url=<?php echo $share_link; ?>&amp;description=<?php echo $share_name; ?>"><i class="fa fa-pinterest"></i></a>
                    <a target="_blank" href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=<?php echo $share_link; ?>&amp;title=<?php echo $share_link; ?>"><i class="fa fa-tumblr"></i></a>
                </div>
<?php
	}
?>
	    </div>
<?php
}
?>
	</div>

</div>
