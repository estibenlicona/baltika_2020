<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewFile extends fwgView {
    function display($tmpl=null) {
        $model = $this->getModel();
		$app = JFactory::getApplication();
        $this->projects = $model->getProjects();
        if (!$this->projects) {
			$app->redirect(JRoute::_('index.php?option=com_fwgallerylight&view=fwgallery', JText::_('FWG_CREATE_A_GALLERY_FIRST')));
        }

		$this->getMenu('files');
        $this->types = JFHelper::loadTypes($published_only = true);
        $this->user = JFactory::getUser();
        $this->clients = $model->getClients();
        $this->obj = $model->getFile();
        $this->media = $model->getMedias();
        $this->params = JComponentHelper::getParams('com_fwgallerylight');
        parent::display($tmpl);
    }
}
