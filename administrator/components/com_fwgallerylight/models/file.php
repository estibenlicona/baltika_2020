<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightModelFile extends JModelAdmin {
	function getForm($data = array(), $loadData = true) {
	}
    function getUserState($name, $def='', $type='cmd') {
        $app = JFactory::getApplication();
        return $app->getUserStateFromRequest('com_fwgallerylight.files.'.$name, $name, $def, $type);
    }
    function getProjects() {
		$model = JModelLegacy::getInstance('files', 'fwGallerylightModel');
		return $model->getProjects();
	}
    function getFile() {
        $file = $this->getTable('file');
		$input = JFactory::getApplication()->input;
        if (($ids = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($ids, 0)) or $id = $input->getInt('id', 0)) {
            $file->load($id);
        } else if ($project_id = $input->getInt('project_id')) {
        	$file->project_id = $project_id;
        }
        return $file;
    }

    function save() {
        $image = $this->getTable('file');
		$input = JFactory::getApplication()->input;
        if ($id = $input->getInt('id') and !$image->load($id)) $input->set('id', 0);

        if ($image->bind($input->getArray(array(), null, 'RAW')) and $image->check() and $image->store()) {
            $this->setError(JText::_('FWG_THE_IMAGE_DATA').' '.JText::_('FWG_STORED_SUCCESSFULLY'));
            return $image->id;
        } else {
        	$this->setError(JText::_('FWG_THE_IMAGE_DATA').' '.JText::_('FWG_STORING_ERROR').':'.$image->getError());
		}
    }

    function getClients() {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT u.id, u.name FROM #__users AS u ORDER BY u.name');
        return $db->loadObjectList();
    }
	function getMedias() {
		$medias = array();
		$types = array('youtube'=>'youtube', 'vimeo'=>'vimeo', 'mp4'=>'mp4');
		foreach ($types as $key=>$media) {
			$medias[] = JHTML::_('select.option', $key, $media, 'id', 'name');
		}
		return $medias;
	}

}
