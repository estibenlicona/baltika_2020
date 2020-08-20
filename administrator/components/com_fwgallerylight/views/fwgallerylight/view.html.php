<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewfwGallerylight extends fwgView {
    function display($tmpl=null) {
        $model = $this->getModel();
		$this->getMenu('fwgallery');

		$input = JFactory::getApplication()->input;
        $this->clients = $model->getClients();
        $this->projects = $model->getItems();
        $this->pagination = $model->getPagination();
        $this->order = $input->getString('order');
        if ($this->order) {
            $this->pagination->setAdditionalUrlParam('order', $this->order);
        }
        $this->client = $input->getInt('client');
        if ($this->client) {
            $this->pagination->setAdditionalUrlParam('client', $this->client);
        }
        $this->search = $input->getString('search');
        if ($this->search) {
            $this->pagination->setAdditionalUrlParam('search', $this->search);
        }
        parent::display($tmpl);
    }
}
