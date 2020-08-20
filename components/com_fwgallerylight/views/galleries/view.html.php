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
        $user = JFactory::getUser();
        $app = JFactory::getApplication();

        $this->obj = $model->getObj();
        $this->list = $model->getList();
        $this->pagination = $model->getPagination();
        $this->title = $model->getTitle();
		$this->params = new JFParams;
        $this->order = $model->getUserState('order', $this->params->get('ordering_galleries'));

		$this->galleries_a_row = $this->params->get('galleries_a_row', 3);
		if (!$this->galleries_a_row) $this->galleries_a_row = 3;
        $this->columns = (int)(12 /$this->galleries_a_row);

        if ($this->obj->id) {
            /* set correct breadcrump */
            $pathway = $app->getPathway();
            $pathway->addItem('Galleries');
        }

        parent::display($tmpl);
    }
}
