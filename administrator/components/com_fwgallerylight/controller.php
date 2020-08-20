<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightController extends JControllerLegacy {
    function __construct($config = array())    {
        parent::__construct($config);
        $this->registerTask('add','edit');
    }
    function edit() {
        $viewName = $this->input->getCmd('view', $this->getName());
        $view = $this->getView($viewName, 'html');
        $view->setModel($this->getModel($viewName), true);
        $view->setLayout('edit');
        $view->edit();
    }
    function batch() {
        $viewName = $this->input->getCmd('view', $this->getName());
        $view = $this->getView($viewName, 'html');
        $view->setModel($this->getModel($viewName), true);
        $view->setLayout('batch');
        $view->batch();
    }
    function apply() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'save')) {
            $id = $model->save();
            $msg = $model->getError();
        } else {
            $id = JArrayHelper::getValue($this->input->getVar('cid'), 0);
            $msg = JText::_('FWG_METHOD__APPLY__IS_NOT_EXISTS');
        }
        if ($view_name == 'config') {
            $view_name .= '&task=edit';
        }
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name.'&cid[]='.$id, $msg?$msg:null);
    }
    function install() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'install')) {
            $model->install();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWG_METHOD__INSTALL__IS_NOT_EXISTS');
        }
        if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
    function save() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);

        if (method_exists($model, 'save')) {
            $model->save();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWG_METHOD__SAVE__IS_NOT_EXISTS');
        }
		if ($view_name == 'gallery') $view_name = 'fwgallerylight';
		elseif (!in_array($view_name, array('config', 'templates', 'language'))) $view_name .= 's';
		if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
    function remove() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'remove')) {
            $model->remove();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWG_METHOD__REMOVE__IS_NOT_EXISTS');
        }
        if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
    function saveorder() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'saveorder')) {
            $model->saveorder();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWG_METHOD__SAVEORDER__IS_NOT_EXISTS');
        }
		if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
    function orderup() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'orderup')) {
            $model->orderup();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWG_METHOD__ORDERUP__IS_NOT_EXISTS');
        }
		if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
    function orderdown() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'orderdown')) {
            $model->orderdown();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWG_METHOD__ORDERDOWN__IS_NOT_EXISTS');
        }
		if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
    function publish() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'publish')) {
            $model->publish();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWG_METHOD__PUBLISH__IS_NOT_EXISTS');
        }
		if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }

    function unpublish() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'unpublish')) {
            $model->unpublish();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWG_METHOD__UNPUBLISH__IS_NOT_EXISTS');
        }
		if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
    function select() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'select')) {
            $model->select();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWG_METHOD__SELECT__IS_NOT_EXISTS');
        }
		if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
    function unselect() {
        $view_name = $this->input->getString('view');
        $model = $this->getModel($view_name);
        if (method_exists($model, 'unselect')) {
            $model->unselect();
            $msg = $model->getError();
        } else {
            $msg = JText::_('FWG_METHOD__UNSELECT__IS_NOT_EXISTS');
        }
		if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
    function clockwise() {
        $model = $this->getModel('files');
    	$model->clockwise();
		$msg = $model->getError();
		$view_name = 'files';
		if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
    function counterclockwise() {
        $model = $this->getModel('files');
    	$model->counterClockwise();
		$msg = $model->getError();
		$view_name = 'files';
		if ($pid = $this->input->getInt('project_id')) $view_name .= '&project_id='.$pid;
        $this->setRedirect('index.php?option=com_fwgallerylight&view='.$view_name, $msg?$msg:null);
    }
}
