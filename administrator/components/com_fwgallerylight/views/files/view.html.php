<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewFiles extends fwgView {
    function display($tmpl=null) {
        $model = $this->getModel();

		$this->getMenu('files');
		$input = JFactory::getApplication()->input;
        $this->types = JFHelper::loadTypes($published_only = true);
        $this->projects = $model->getProjects();
        $this->files = $model->getFiles();
        $this->pagination = $model->getPagination();
        $this->order = $input->getString('order');
        if ($this->order) {
            $this->pagination->setAdditionalUrlParam('order', $this->order);
        }
        $this->search = $input->getString('search');
        if ($this->search) {
            $this->pagination->setAdditionalUrlParam('search', $this->search);
        }
        $this->project_id = $input->getInt('project_id');
        if ($this->project_id) {
            $this->pagination->setAdditionalUrlParam('project_id', $this->project_id);
        }
        parent::display($tmpl);
    }

    function batch($tmpl=null) {
        $model = $this->getModel();
        $this->projects = $model->getProjects();
        if (!$this->projects) {
			$app = JFactory::getApplication();
			$app->redirect(JRoute::_('index.php?option=com_fwgallerylight&view=fwgallery', JText::_('FWG_CREATE_A_GALLERY_FIRST')));
        }

		$this->getMenu('files');
        $this->user = JFactory::getUser();
        $this->clients = $model->getClients();
        $this->params = JComponentHelper::getParams('com_fwgallerylight');
        parent::display($tmpl);
    }
}
