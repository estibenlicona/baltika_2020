<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class JHTMLfwgGrid {
    static function selected(&$row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='') {
        $img    = $row->selected ? $imgY : $imgX;
        $task   = $row->selected ? 'unselect' : 'select';
        $alt    = JText::_($row->selected ?'FWG_GALLERY_DEFAULT':'FWG_NOT_GALLERY_DEFAULT');
        $action = JText::_($row->selected ?'FWG_UNSET_GALLERY_DEFAULT':'FWG_SET_AS_GALLERY_DEFAULT');

        $href = '
        <a class="btn btn-micro'.($row->selected?' active':'').'" href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
        <i class="icon-star'.($row->selected ?'':'-empty').'"></i></a>'
        ;

        return $href;
    }
	static function published( &$row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='' )
	{
		$img 	= $row->published ? $imgY : $imgX;
		$task 	= $row->published ? 'unpublish' : 'publish';
		$alt 	= $row->published ? JText::_( 'FWG_Published' ) : JText::_( 'FWG_Unpublished' );
		$action = $row->published ? JText::_( 'FWG_Unpublish_Item' ) : JText::_( 'FWG_Publish_item' );

		$href = '
		<a class="btn btn-micro'.($row->published?' active':'').'" href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
		<i class="icon-'.($row->published?'':'un').'publish"></i></a>'
		;

		return $href;
	}
	static function defaultTemplate($item, $selected, $num) {
		$action = JText::_($selected ?'':'Make this tempate default');
		$href = '
        <a class="btn btn-micro'.($selected?' active':'').'" href="javascript:void(0);" onclick="return listItemTask(\'cb'. $num .'\',\'save\')" title="'. $action .'">
        <i class="icon-star'.($selected ?'':'-empty').'"></i></a>'
		;

		return $href;
	}
}
