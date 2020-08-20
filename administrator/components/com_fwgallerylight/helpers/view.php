<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwgView extends JViewLegacy {
    function getMenu($menu) {
		$items = array(
			'FWG_GALLERIES' => 'fwgallerylight',
			'FWG_IMAGES' => 'files',
			'FWG_CONFIGURATION' => 'config'
		);
		foreach ($items as $title=>$option) {
			JSubMenuHelper::addEntry(
				JText::_($title),
				'index.php?option=com_fwgallerylight&view='.$option,
				$option == $menu
			);
		}
    }
}
