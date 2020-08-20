<?php
/*-------------------------------------------------------------------------------
# mod_fbphotoalbums - Facebook Photo Albums module for Joomla 3.x v2.0.0-FINAL
# -------------------------------------------------------------------------------
# author    GraphicAholic
# copyright Copyright (C) 2011 GraphicAholic.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.graphicaholic.com
--------------------------------------------------------------------------------*/
// No direct access
defined('_JEXEC') or die('Restricted access');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
JHtml::_('bootstrap.framework');
// Import the file / foldersystem
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
$LiveSite 	= JURI::base();
$document = JFactory::getDocument();
$modbase = JURI::base(true).'/modules/mod_fbphotoalbums/';
//$document->addScript ($modbase.'js/jquery.fb.albumbrowser198.js');
$document->addScript ($modbase.'js/jquery.fb.albumbrowser198.js');

$document->addStyleSheet($modbase.'css/jquery.fb.albumbrowser198.css');
$showAccountInfo	= $params->get('showAccountInfo');
$showImageCount	= $params->get('showImageCount');
$useLightbox	= $params->get('useLightbox');
$uselikeButton	= $params->get('uselikeButton');
$skipemptyAlbums  = $params->get('skipemptyAlbums');
$showImageText	= $params->get('showImageText');
$photoCheck  = $params->get('photoCheck');
$moduleID 	 	= $module->id;
?>  
  <style type="text/css">
    li.fb-album, li.fb-photo {width: <?php echo $params->get('tmbWidth_') ?>px !important; height: <?php echo $params->get('tmbHeight_') ?>px !important;}
    div.fb-album-title {width: <?php echo $params->get('tmbWidth_') ?>px; background-color: <?php echo $params->get('styleColor') ?> !important; position: absolute; height:auto; font-size:<?php echo $params->get('titleSize') ?>; color:<?php echo $params->get('titleColor') ?>; font-weight:<?php echo $params->get('titleWeight') ?>;}
    h3.fb-album-heading, h3.fb-account-heading {color: <?php echo $params->get('styleColor') ?> !important;}
    img.fb-albums-list {background-color: <?php echo $params->get('styleColor') ?> !important; border-radius: 25px; opacity: 0.5;}
    div.fb-album-count {background-color: <?php echo $params->get('styleColor') ?> !important; margin-top:75%;}
    .fb-btn-more {background-color: <?php echo $params->get('styleColor') ?> !important;}
  </style>
  <div class="fb-album-container"></div>
  <script type="text/javascript">
        jQuery(document).ready(function () {
          jQuery(".fb-album-container").FacebookAlbumBrowser({
                account: "<?php echo $params->get('facebookPage') ?>",
                accessToken: "<?php echo $params->get('accessToken') ?>",
                skipAlbums: [<?php echo $params->get('skipAlbums') ?>],
                showAccountInfo: <?php echo $showAccountInfo ?>,
                showImageCount: <?php echo $showImageCount ?>,
                skipEmptyAlbums: <?php echo $skipemptyAlbums ?>,
                showImageText: <?php echo $showImageText ?>,
                showComments: <?php echo $params->get('showComments') ?>,
                commentsLimit: <?php echo $params->get('commentsLimit') ?>,
                showAlbumNameInPreview: <?php echo $params->get('showAlbumNameInPreview') ?>,
                addThis:"ra-52638e915dd79612",
                thumbnailSize: <?php echo $params->get('tmbWidth_') ?>,
				pluginImagesPath: "<?php echo $modbase ?>images/",
                lightbox: <?php echo $useLightbox ?>,
                lightboxOverlay: "<?php echo $params->get('lightboxBackground') ?>",
                likeButton: <?php echo $uselikeButton ?>,
                shareButton: <?php echo $uselikeButton ?>,
                albumsPageSize: <?php echo $params->get('albumsToShow') ?>,
				albumsMoreButtonText: "<?php echo $params->get('albumsToShowText') ?>",
				photosPageSize: <?php echo $params->get('photosToShow') ?>,
				photosMoreButtonText: "<?php echo $params->get('photosToShowText') ?>",
                photosCheckbox: <?php echo $photoCheck ?>,
                hoverEffect: <?php echo $params->get('hoverEffect') ?>,
                <?php if ($photoCheck == "true"): ?>
                photoChecked: function(photo){
                    console.log("PHOTO CHECKED: " + photo.id + " - " + photo.url + " - " + photo.thumb);
                    console.log("CHECKED PHOTOS COUNT: " + this.checkedPhotos.length);

                    var tableRow = jQuery("<a href= " + photo.url + " target='_blank'>");
                    var tableThumbCol = jQuery("<td/>");
                    jQuery(tableThumbCol).append(jQuery("<img/>", { src: photo.thumb }));
                    jQuery(tableRow).append(tableThumbCol);
                    jQuery(tableRow).append(jQuery("<td/>", { text: photo.id }));
                    jQuery(tableRow).append(jQuery("<td/>", { text: photo.url }));
                    jQuery("table.picked-photos-table").append(tableRow);

                },
                photoUnchecked: function (photo) {
                    console.log("PHOTO UNCHECKED: " + photo.id + " - " + photo.url + " - " + photo.thumb);
                    console.log("CHECKED PHOTOS COUNT: " + this.checkedPhotos.length);

                    jQuery("table.picked-photos-table td:contains(" + photo.id + ")").parent().remove();
                },
                albumSelected: function (photo) {
                    console.log("ALBUM CLICK: " + photo.id + " - " + photo.url + " - " + photo.thumb);
                },
                photoSelected: function (photo) {
                    console.log("PHOTO CLICK: " + photo.id + " - " + photo.url + " - " + photo.thumb);
                }
                <?php endif ; ?> 
            });
        });
  </script>
  <?php if ($photoCheck == "true"): ?>
  <div class="picker-result">
        <hr />
        <p>Browse an album and pick photo using checkbox which will appear on hover</p>
        <table class="picked-photos-table" cellpadding="0" cellspacing="0">
            <tr>
                <th>&nbsp;&nbsp;Selection Preview</th>
            </tr>
        </table>
    </div>
  <?php endif ; ?> 