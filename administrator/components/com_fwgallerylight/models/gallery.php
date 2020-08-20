<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightModelGallery extends JModelAdmin {
	function getForm($data = array(), $loadData = true) {
	}
    function getUserState($name, $def='', $type='cmd') {
        $app = JFactory::getApplication();
        $context = 'com_fwgallerylight.projects.';
        return $app->getUserStateFromRequest($context.$name, $name, $def, $type);
    }

    function getProject() {
        $project = $this->getTable('project');
		$input = JFactory::getApplication()->input;
        if (($ids = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($ids, 0)) or $id = $input->getInt('id', 0)) {
            $project->load($id);
        }
        return $project;
    }

    function save() {
        $project = $this->getTable('project');
		$input = JFactory::getApplication()->input;
        if ($id = $input->getInt('id') and !$project->load($id)) $input->set('id', 0);

		$is_new = !$project->id;
        if ($project->bind($input->getArray(array(), null, 'RAW')) and $project->check() and $project->store()) {
            $this->setError(JText::sprintf($is_new?'FWG_GALLERY_SUCCESSFULLY_ADDED':'FWG_GALLERY_SUCCESSFULLY_UPDATED', $project->name));
            return $project->id;
        } else
            $this->setError(JText::_('FWG_THE_GALLERY_DATA').' '.JText::_('FWG_STORING_ERROR').':'.$project->getError());
    }

    function getClients() {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT u.id, u.name FROM #__users AS u ORDER BY u.name');
        return $db->loadObjectList();
    }

    function getGroups() {
		$acl = JFactory::getACL();
		return array_merge(
			array(JHTML::_('select.option', '0', JText::_('FWG_PUBLIC'))),
			(array)$acl->get_group_children_tree(null, 'Public Frontend', false)
		);
    }
	function getGalleries() {
		$model = JModelLegacy::getInstance('fwGallerylight', 'fwGallerylightModel');
		return $model->getItems();
	}
	function getGalleriesPagination() {
		$model = JModelLegacy::getInstance('fwGallerylight', 'fwGallerylightModel');
		return $model->getPagination();
	}
	function getFiles() {
		$model = JModelLegacy::getInstance('files', 'fwGallerylightModel');
		return $model->getFiles();
	}
	function getFilesPagination() {
		$model = JModelLegacy::getInstance('files', 'fwGallerylightModel');
		return $model->getPagination();
	}
}
