<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewfwGallerylight extends JViewLegacy {
    function display($tmpl=null) {
        $model = $this->getModel();

		$input = JFactory::getApplication()->input;
        $input->set('limit', '0');
        $input->set('limitstart', '0');
        $list = array();
        if ($data = $model->getItems()) foreach ($data as $row) {
			$buff = new stdclass;
			$buff->id = $row->id;
			$buff->parent = $row->parent;
			$buff->name = $row->name;
			$buff->created = $row->created;
			$buff->link = JURI::root(false).'index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$row->_file_id.'&w=80&h=60';
			$buff->color = JFHelper::getGalleryColor($row->id);
			$buff->_user_name = $row->_user_name;

			$list[] = $buff;
        }
        echo json_encode($list);
        die();
    }
}
