<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightModelGalleries extends JModelLegacy {
    function getUserState($name, $def='') {
        $app = JFactory::getApplication();
        $context = 'com_fwgallerylight.galleries.';
        return $app->getUserStateFromRequest($context.$name, $name, $def, 'cmd');
    }
	function _getWhereClause($parent = null, $published_only = true) {
		$params = new JFParams;
		$where = array();
		if ($published_only) {
			$where[] = 'p.published = 1';
			if (!$params->get('display_empty_gallery')) {
				$where[] = 'EXISTS(SELECT * FROM #__fwg_files AS f WHERE f.published = 1 AND f.project_id = p.id)';
			}
		}
		if (!is_null($parent)) {
			if ($parent == 0) {
				$ids = array();
				if ($buff = (array)$params->get('galleries_id')) {
					foreach ($buff as $row) if ($row and is_numeric($row)) $ids[] = $row;
				}
				if ($ids) $where[] = 'p.id IN ('.implode(',', $ids).')';
				 else $where[] = 'p.parent = '.$parent;
			} else $where[] = 'p.parent = '.$parent;
		}

        $user = JFactory::getUser();
		if (!$user->authorise('core.login.admin')) {
			if ($user->id) {
				$where[] = '(p.gid = 0 OR p.gid IS NULL OR EXISTS(SELECT * FROM #__usergroups AS ug WHERE pg.lft=ug.lft AND ug.id IN ('.implode(',',$user->getAuthorisedGroups()).')))';
			} else $where[] = '(p.gid = 0 OR p.gid IS NULL)';
		}
		return $where?('WHERE '.implode(' AND ', $where)):'';
	}
	function _getOrderClause() {
		$params = new JFParams;
		switch ($this->getUserState('order', $params->get('ordering_galleries'))) {
			case 'new' : $order = 'p.created DESC';
			break;
			case 'old' : $order = 'p.created';
			break;
			case 'name' : $order = 'p.name';
			break;
			default : $order = 'p.ordering';
		}
		return 'ORDER BY '.$order;
	}
    function getAllList($parent_id = null) {
        $db = JFactory :: getDBO();
        $params = new JFParams;
        $db->setQuery('
SELECT
    p.*,
	p.user_id AS _user_id,
    u.name AS _user_name,
    (SELECT f.id FROM #__fwg_files AS f WHERE f.published = 1 AND f.project_id = p.id ORDER BY selected DESC, ordering LIMIT 1) AS _file_id,
	(SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id AND f.published = 1 AND f.type_id = 1) AS _photos,
	(SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id AND f.published = 1 AND f.type_id = 2) AS _videos,
	(SELECT COUNT(*) FROM #__fwg_projects AS pp WHERE pp.parent = p.id AND pp.published = 1'.($params->get('display_empty_gallery')?'':' AND EXISTS(SELECT * FROM #__fwg_files AS f WHERE f.published = 1 AND f.project_id = pp.id)').') AS _subfolders
FROM
    #__fwg_projects AS p
    LEFT JOIN #__users AS u ON (u.id = p.user_id)
    LEFT JOIN #__usergroups AS pg ON (pg.id = p.gid)
'.$this->_getWhereClause(null, false).'
'.$this->_getOrderClause()
		);
		if ($list = $db->loadObjectList('id')) {
			foreach ($list as $i=>$row) {
				if ($row->parent and !empty($list[$row->parent])) {
					$parent = $row->parent;
					do {
						$list[$parent]->_photos += $row->_photos;
						$list[$parent]->_videos += $row->_videos;
						$list[$parent]->_subfolders += $row->_subfolders;
						$parent = $list[$parent]->parent;
					} while (!empty($list[$parent]));
				}
			}

			if (is_null($parent_id)) return $list;
			elseif (!is_array($parent_id)) $parent_id = array($parent_id);
			JArrayHelper :: toInteger($parent_id, '0');

			$result = array();
			foreach ($list as $i=>$row) {
				if (in_array($row->parent, $parent_id)) {
					if (!$row->_file_id and $buff = JFHelper::findGalleryImage($row)) {
						$row->_file_id = $buff->_file_id;
					}
					$result[] = $row;
				}
			}
	        return $result;
		}
    }
    function getList() {
        $db = JFactory :: getDBO();
        $params = new JFParams;
		$input = JFactory::getApplication()->input;

        $db->setQuery('
SELECT
    p.*,
	p.user_id AS _user_id,
    u.name AS _user_name,
    (SELECT f.id FROM #__fwg_files AS f WHERE f.project_id = p.id ORDER BY selected DESC, ordering LIMIT 1) AS _file_id,
    (SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id AND f.published = 1 AND f.type_id = 1) AS _photos,
	(SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id AND f.published = 1 AND f.type_id = 2) AS _videos,
	(SELECT COUNT(*) FROM #__fwg_projects AS pp WHERE pp.parent = p.id AND pp.published = 1'.($params->get('display_empty_gallery')?'':' AND EXISTS(SELECT * FROM #__fwg_files AS f WHERE f.published = 1 AND f.project_id = pp.id)').') AS _subfolders
FROM
    #__fwg_projects AS p
    LEFT JOIN #__users AS u ON (u.id = p.user_id)
    LEFT JOIN #__usergroups AS pg ON (pg.id = p.gid)
'.$this->_getWhereClause($parent = 0).'
'.$this->_getOrderClause(),
    		$input->getInt('limitstart'),
    		$params->get('galleries_rows', 4) * $params->get('galleries_a_row', 3)
		);
        if ($list = $db->loadObjectList()) {
			foreach ($list as $i=>$row) {
				if (!$row->_file_id and $buff = JFHelper::findGalleryImage($row)) {
					$list[$i]->_file_id = $buff->_file_id;
				}
			}
			return $list;
		}
    }
    function getQty() {
        $db = JFactory :: getDBO();
        $db->setQuery('
SELECT
	COUNT(*)
FROM
	#__fwg_projects AS p
	LEFT JOIN #__usergroups AS pg ON (pg.id = p.gid)
'.$this->_getWhereClause($parent = 0)
		);
        return $db->loadResult();
    }
    function getPagination() {
        $params = new JFParams;
		$input = JFactory::getApplication()->input;
        jimport('joomla.html.pagination');
        $pagination = new JPagination(
        	$this->getQty(),
        	$input->getInt('limitstart'),
        	$params->get('galleries_rows', 4) * $params->get('galleries_a_row', 3)
    	);
        return $pagination;
    }
    function getObj() {
		$input = JFactory::getApplication()->input;
        $obj = JFactory :: getUser($input->getInt('id'));
        return $obj;
    }
    function getTitle() {
    	$menu = JMenu :: getInstance('site');
    	$item = $menu->getActive();
    	return (is_object($item) and strpos($item->link, 'com_fwgallerylight') !== false)?$item->title:JText :: _('FWG_GALLERIES');
    }
}
