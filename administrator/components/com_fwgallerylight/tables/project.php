<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class TableProject extends JTable {
    /** @var int Primary key */
    var $id = null,
    	$parent = null,
    	$user_id = null,
    	$gid = null,
    	$name = null,
    	$descr = null,
    	$ordering = null,
		$is_public = null,
    	$published = 1,
    	$created = null,
    	$color = null;

    var $_user_name = null,
    	$_group_name = null,
        $_videos = null,
        $_photos = null;

    function __construct(&$db) {
        parent::__construct('#__fwg_projects', 'id', $db);
    }

    function load($oid = null, $reset = true) {
        if ($oid) {
        	$db = JFactory::getDBO();
        	$user = JFactory::getUser();
			$app = JFactory::getApplication();

        	$db->setQuery('
SELECT
	p.*,
	(SELECT u.name FROM #__users AS u WHERE u.id = p.user_id) AS _user_name,
    (SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id AND f.published = 1 AND f.type_id = 1) AS _photos,
	(SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id AND f.published = 1 AND f.type_id = 2) AS _videos,
	pg.title AS _group_name
FROM
	#__fwg_projects AS p
    LEFT JOIN #__usergroups AS pg ON pg.id = p.gid
WHERE
	p.id = '.(int)$oid
			);
        	if ($obj = $db->loadObject()) {
        		foreach ($obj as $key=>$val) $this->$key = $val;
	            return true;
        	}
        } else $this->id = 0;
    }

    function check() {
		if (!$this->id) {
			if (!$this->user_id) {
				$user = JFactory::getUser();
				if (!$user->id) {
					$this->setError(JText::_('FWG_LOGIN_FIRST'));
					return false;
				}
				$this->user_id = $user->id;
			}
			if (!$this->ordering) {
				$db = JFactory::getDBO();
				$db->setQuery('SELECT MAX(ordering) FROM #__fwg_projects WHERE parent = '.(int)$this->parent);
				$this->ordering = (int)$db->loadResult() + 1;
			}
		}
		$input = JFactory::getApplication()->input;
		if ($created = JFHelper::decodeDate($input->getString('created'))) $this->created = $created;
		if ($this->color) $this->color = trim($this->color, '#');
		return true;
    }

    function store($upd=null) {
		$db = JFactory::getDBO();
		$old_user_id = 0;
    	if ($this->id) {
/* find out previous user_id */
    		$db->setQuery('SELECT id, user_id FROM #__fwg_projects WHERE id = '.(int)$this->id);
    		if ($obj = $db->loadObject()) {
    			if ($obj->user_id != $this->user_id) $old_user_id = $obj->user_id;
    		} else $this->id = 0;
    	}
    	if (parent::store($upd)) {
/* sync images */
    		if ($old_user_id) {
    			$path_from = FWG_STORAGE_PATH.'files'.'/'.$old_user_id.'/';
    			$path_to = FWG_STORAGE_PATH.'files'.'/'.$this->user_id.'/';
    			jimport('joomla.filesystem.file');
    			if (!file_exists($path_to)) JFile::write($path_to.'index.html', $body='<html><body></body></html>');
    			$db->setQuery('SELECT original_filename FROM #__fwg_files WHERE project_id = '.(int)$this->id);
    			if ($files = $db->loadObjectList()) foreach ($files as $file) {
    				if ($file->original_filename and file_exists($path_from.$file->original_filename)) JFile::move($path_from.$file, $path_to.$file->original_filename);
    			}
    		}

    		return  true;
    	}
    }

    function delete($oid=null) {
    	$db = JFactory::getDBO();
		$db->setQuery('SELECT id FROM #__fwg_files WHERE project_id = '.(int)$oid);
		if ($ids = $db->loadColumn()) foreach ($ids as $id) {
			$file = JTable::getInstance('file', 'Table');
			$file->delete($id);
		}
		return parent::delete($oid);
    }
}
