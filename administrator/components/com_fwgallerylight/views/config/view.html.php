<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewConfig extends fwgView {
    function display($tmpl=null) {
        $model = $this->getModel();
		$this->getMenu('config');
        $this->assign('obj', $model->loadObj());
        parent::display($tmpl);
    }
    function edit($tmpl=null) {
        $model = $this->getModel();
		$this->getMenu('config');
        $this->assign('obj', $model->loadObj());
        $this->columns = array(
        	JHTML::_('select.option', '3', '3', 'id', 'name'),
        	JHTML::_('select.option', '4', '4', 'id', 'name'),
        	JHTML::_('select.option', '6', '6', 'id', 'name')
        );
        parent::display($tmpl);
    }
}
