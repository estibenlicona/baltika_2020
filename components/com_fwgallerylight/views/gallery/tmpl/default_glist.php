<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

foreach ($this->subcategories as $row) {
    $this->row = $row;
    include(JPATH_SITE.'/components/com_fwgallerylight/views/galleries/tmpl/default_item.php');
}
