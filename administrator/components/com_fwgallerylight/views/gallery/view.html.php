<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewGallery extends fwgView {
    function display($tmpl=null) {
        $model = $this->getModel();
        JHTML::_('behavior.core');
		$input = JFactory::getApplication()->input;

        switch ($this->getLayout()) {
        case 'modal_add' :
            JHTML::_('behavior.core');
            JHTML::_('behavior.formvalidation');
            $this->project_id = $input->getInt('project_id');
            $this->function = $input->getCmd('function', 'jSelectFwgallery');
            break;
        case 'modal' :
            JHTML::_('behavior.core');
            $this->sublayout = $input->getCmd('sublayout', 'galleries');
            if (!in_array($this->sublayout, array('galleries', 'images'))) $this->sublayout = 'galleries';
            switch ($this->sublayout) {
                case 'images' :
                $this->files = $model->getFiles();
                $this->pagination = $model->getFilesPagination();
                $this->project_id = $model->getUserState('project_id', 0, 'int');
                $this->function  = 'jSelectFwgalleryImage';
                break;
                case 'galleries' :
                $this->galleries = $model->getGalleries();
                $this->pagination = $model->getGalleriesPagination();
                $this->function  = 'jSelectFwgallery';
                break;
            }
            break;
        default :
            $this->getMenu('fwgallery');
            $this->user = JFactory::getUser();
            $this->groups = JFHelper::getGroups();
            $this->clients = $model->getClients();
            $this->obj = $model->getProject();
        }

        parent::display($tmpl);
    }
}
