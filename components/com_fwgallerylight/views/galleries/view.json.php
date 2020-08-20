<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewGalleries extends JViewLegacy {
    function display($tmpl=null) {
        $model = $this->getModel();
        $app = JFactory::getApplication();
		$input = JFactory::getApplication()->input;

        switch ($this->getLayout()) {
        case 'list' :
            $this->setLayout('default');
            $data = new stdclass;
            $this->params = new JFParams;
            $this->galleries_a_row = $this->params->get('galleries_a_row', 3);
    		if (!$this->galleries_a_row) $this->galleries_a_row = 3;
            $this->columns = (int)(12 /$this->galleries_a_row);

            $this->list = (array)$model->getList();
            $data->msg = $model->getError();
            $data->html = $this->loadTemplate('list');
            die(json_encode($data));
        break;
        default :
            $input->set('limit', '0');
            $input->set('limitstart', '0');
            if ($data = $model->getAllList()) foreach ($data as $i=>$row) {
    			$data[$i]->descr = htmlentities(strip_tags($row->descr));
    			$data[$i]->link = JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$row->_file_id, false, -2);
    			$data[$i]->color = JFHelper :: getGalleryColor($row->id);
            }
            die(json_encode($data));
        }
    }
}
