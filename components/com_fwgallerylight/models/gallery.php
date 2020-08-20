<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightModelGallery extends JModelLegacy {
    function getUserState($name, $def='', $type='cmd') {
        $app = JFactory :: getApplication();
        $context = 'com_fwgallerylight.gallery.';
        return $app->getUserStateFromRequest($context.$name, $name, $def, $type);
    }
    function getGalleryId() {
    	$result = array();
		$app = JFactory :: getApplication();
		$params = new JFParams;
		$input = $app->input;
    	if ($id = (int)$input->getInt('id')) $result[] = $id;
    	else $result = (array)$params->get('id');

    	if (false and $result and $input->getCmd('format') == 'json' and $params->get('display_galleries_lightbox')) {
    		$db = JFactory :: getDBO();
    		$db->setQuery('SELECT id, parent FROM #__fwg_projects WHERE published = 1');
    		$gls = $db->loadObjectList();
    		do {
    			$id_found = false;
    			foreach ($gls as $gl) {
					if (in_array($gl->parent, $result) and !in_array($gl->id, $result)) {
						$result[] = $gl->id;
						$id_found = true;
					}
    			}
    		} while ($id_found);
    	}
		if (!$result) $result[] = 0;
    	return $result;
    }
	function loadAllSubCategories() {
		$model = JModel :: getInstance('Galleries', 'fwGallerylightModel');
		return $model->getAllList($this->getGalleryId());
    }
    function loadSubCategories() {
        $db = JFactory :: getDBO();
        $user = JFactory :: getUser();
        $params = new JFParams;
		$input = JFactory::getApplication()->input;

        $ids = $this->getGalleryId();
        JArrayHelper :: toInteger($ids, '0');

        $db->setQuery('
SELECT
    p.*,
	p.user_id AS _user_id,
    u.name AS _user_name,
    (SELECT f.id FROM #__fwg_files AS f WHERE f.project_id = p.id ORDER BY selected DESC, ordering LIMIT 1) AS _file_id,
    (SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id AND f.published = 1 AND f.type_id = 1) AS _photos,
	(SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id AND f.published = 1 AND f.type_id = 2) AS _videos,
	(SELECT COUNT(*) FROM #__fwg_projects AS pp WHERE pp.parent = p.id AND pp.published = 1) AS _subfolders
FROM
    #__fwg_projects AS p
    LEFT JOIN #__users AS u ON (u.id = p.user_id)
    LEFT JOIN #__usergroups AS pg ON (pg.id = p.gid)
WHERE
	p.parent IN ('.implode(',', $ids).')
	AND
	p.published = 1
'.($user->authorise('core.login.admin')?'':('
	AND
	(
		p.gid = 0
		OR
		p.gid IS NULL
'.($user->id?('
		OR
		EXISTS(SELECT * FROM #__usergroups AS ug WHERE pg.lft=ug.lft AND ug.id IN ('.implode(',',$user->getAuthorisedGroups()).') ORDER BY ug.lft DESC LIMIT 1)'):''
).'
	)
')).'
'.$this->_getCategoryOrderClause(),
        	$input->getInt('glimitstart'),
        	$params->get('galleries_a_row', 3)
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

	function _getCategoryOrderClause() {
		$params = new JFParams;
		switch ($this->getUserState('subcategory_order', $params->get('ordering_galleries'))) {
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

    function getGQty() {
        $db = JFactory :: getDBO();
        $user = JFactory :: getUser();
        $params = new JFParams;

        $ids = $this->getGalleryId();
        JArrayHelper :: toInteger($ids, '0');

        $db->setQuery('
SELECT
	COUNT(*)
FROM
    #__fwg_projects AS p
    LEFT JOIN #__users AS u ON (u.id = p.user_id)
    LEFT JOIN #__usergroups AS pg ON (pg.id = p.gid)
WHERE
	p.parent IN ('.implode(',', $ids).')
	AND
	p.published = 1
'.($user->authorise('core.login.admin')?'':('
	AND
	(
		p.gid = 0
		OR
		p.gid IS NULL
'.($user->id?('
		OR
		EXISTS(SELECT * FROM #__usergroups AS ug WHERE pg.lft=ug.lft AND ug.id IN ('.implode(',',$user->getAuthorisedGroups()).') ORDER BY ug.lft DESC LIMIT 1)'):'').'
	)
')));
        return $db->loadResult();
    }

    function getGPagination() {
        $params = new JFParams;
		$input = JFactory::getApplication()->input;
        jimport('joomla.html.pagination');
        include_once(JPATH_COMPONENT.'/helpers/gpagination.php');
        $pagination = new fwGPagination(
        	$this->getGQty(),
        	$input->getInt('glimitstart'),
        	$this->getUserState('glimit', $params->get('galleries_a_row', 3))
    	);
        return $pagination;
    }

/** ------ **/
	function _getWhereClause($published_only = true) {
        $ids = $this->getGalleryId();
        JArrayHelper :: toInteger($ids, '0');

        $user = JFactory :: getUser();

		$where = array(
			'f.project_id IN ('.implode(',', $ids).')'
		);
		if ($published_only) {
			$where[] = 'f.published = 1';
			$where[] = 'p.published = 1';
		}
		if (!$user->authorise('core.create', 'com_content')) {
			if ($user->id) {
				$where[] = '(p.gid = 0 OR p.gid IS NULL OR EXISTS(SELECT * FROM #__usergroups AS ug WHERE pg.lft=ug.lft AND ug.id IN ('.implode(',',$user->getAuthorisedGroups()).')))';
			} else $where[] = '(p.gid = 0 OR p.gid IS NULL)';
		}
		return $where?('WHERE '.implode(' AND ', $where)):'';
	}

	function _getOrderClause() {
		$params = new JFParams;
		switch ($this->getUserState('order', $params->get('ordering_images'))) {
			case 'new' : $order = 'f.created DESC';
			break;
			case 'old' : $order = 'f.created';
			break;
			case 'name' : $order = 'f.name';
			break;
			default : $order = 'f.ordering';
		}
		return 'ORDER BY '.$order;
	}

    function getObj() {
        $project = $this->getTable('project');
        if ($id = $this->getGalleryId() and !empty($id[0])) $project->load($id[0]);
        return $project;
    }

    function getQty() {
        $db = JFactory :: getDBO();
        $db->setQuery('
SELECT
	COUNT(*)
FROM
	#__fwg_files AS f
	LEFT JOIN #__fwg_projects AS p ON f.project_id = p.id
    LEFT JOIN #__usergroups AS pg ON (pg.id = p.gid)
'.$this->_getWhereClause()
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
        	$input->getInt('limit', $params->get('images_rows', 4) * $params->get('images_a_row', 3))
    	);
        return $pagination;
    }

    function getList($published_only = true) {
        $db = JFactory :: getDBO();
        $user = JFactory :: getUser();
        $params = new JFParams;
		$input = JFactory::getApplication()->input;

        $db->setQuery('
SELECT
    f.*,
    p.user_id AS _user_id,
	p.color AS _color,
    u.name AS _user_name,
    p.name AS _project_name,
	t.name AS _type_name,
	t.plugin AS _plugin_name
FROM
    #__fwg_files AS f
	LEFT JOIN #__fwg_types AS t ON t.id = f.type_id
    LEFT JOIN #__fwg_projects AS p ON f.project_id = p.id
    LEFT JOIN #__users AS u ON u.id = f.user_id
    LEFT JOIN #__usergroups AS pg ON (pg.id = p.gid)
'.$this->_getWhereClause($published_only).'
'.$this->_getOrderClause(),
        	$input->getInt('limitstart'),
        	$params->get('images_rows', 4) * $params->get('images_a_row', 3)
		);
		return $db->loadObjectList('id');
    }

    function getImage() {
    	$table = $this->getTable('file');
		$input = JFactory::getApplication()->input;
    	if ($id = $input->getInt('id')) $table->load($id);
    	return $table;
    }

    function loadModuleData($params) {
    	$db = JFactory :: getDBO();
/* collect filtering clause */
		$where = array(
			'f.published = 1',
			'p.published = 1'
		);
    	if ($gallery_id = (int)$params->get('gallery_id')) {
			$gids = array($gallery_id);
			do {
				$continue = false;
				$db->setQuery('SELECT id FROM #__fwg_projects WHERE parent IN ('.implode(',', $gids).')');
				if ($buff = $db->loadColumn()) foreach ($buff as $gid) {
					if (!in_array($gid, $gids)) {
						$gids[] = $gid;
						$continue = true;
					}
				}
			} while($continue);
			$where[] = 'f.project_id IN ('.implode(',', $gids).')';
		}

		$user = JFactory :: getUser();
		if (!$user->authorise('core.login.admin')) {
			if ($user->id) {
				$where[] = '(p.gid = 0 OR p.gid IS NULL OR EXISTS(SELECT * FROM #__usergroups AS ug WHERE pg.lft=ug.lft AND ug.id IN ('.implode(',',$user->getAuthorisedGroups()).')))';
			} else $where[] = '(p.gid = 0 OR p.gid IS NULL)';
		}
/* collect ordering clause */
    	switch ($params->get('order')) {
    		case 'alpha' : $order = 'f.name';
    		break;
    		case 'popular' : $order = 'f.hits DESC';
    		break;
    		case 'rand' : $order = 'RAND()';
    		break;
    		case 'order' : $order = 'f.ordering';
    		break;
    		default : $order = 'f.created DESC';
    	}
/* load data */
    	$db->setQuery('
SELECT
    f.id,
	f.name,
	f.descr,
	f.filename,
	f.project_id,
	f.created,
	p.name AS _project_name,
    p.user_id AS _user_id
FROM
    #__fwg_files AS f
	LEFT JOIN #__fwg_projects AS p ON f.project_id = p.id
    LEFT JOIN #__usergroups AS pg ON (pg.id = p.gid)
WHERE
	'.implode(' AND ', $where).'
ORDER BY
	'.$order,
			0,
			$params->get('limit', 10)
		);
		return $db->loadObjectList();
    }
    function loadCategoriesPath($category_id = 0) {
		static $categories_above;
		if (!$categories_above) {
			$categories_above = array();
			if ($category_id) {
				$db = JFactory :: getDBO();
				$parent = new stdclass;
				$parent->parent = $category_id;
				do {
					$db->setQuery('SELECT id, parent, name FROM #__fwg_projects WHERE id = '.(int)$parent->parent);
					if ($parent = $db->loadObject()) $categories_above[] = $parent;
				} while ($parent);

				$categories_above = array_reverse($categories_above);
			}
		}
		return $categories_above;
    }
	function save() {
		$project = $this->getTable('project');
        $user = JFactory :: getUser();
        if ($user->authorise('core.create', 'com_content')) {
			$input = JFactory::getApplication()->input;
            $id = $input->getInt('id', 0, 'post');
            if ($id and !$project->load($id)) $input->set('id', 0);

            if ($project->bind($input->getArray(array(), null, 'RAW'), array('user_id', 'created', 'ordering', 'color')) and $project->check() and $project->store()) {
    			$this->setError(JText :: sprintf('FWG_GALLERY_SUCCESSFULLY_ADDED', $project->name));
    			return true;
    		} else $this->setError($project->getError());
        } else $this->setError('FWG_CONTENT_ADMINS_ONLY_ACCESS');
	}
    function update() {
		$project = $this->getTable('project');
		$input = JFactory::getApplication()->input;
		if ($id = $input->getInt('id', 0, 'post')) {
			if ($project->load($id)) {
				if ($project->bind($input->getArray(array(), null, 'RAW'), array('user_id', 'created', 'parent', 'ordering', 'color')) and $project->check() and $project->store()) {
					$this->setError('GALLERY SUCCESSFULLY UPDATED');
					return true;
				} else $this->setError('GALLERY UPDATE ERROR');
			} else $this->setError('GALLERY NOT FOUND');
		} else $this->setError('NO GALLERY ID');
	}
	function publish() {
		$project = $this->getTable('project');
		$input = JFactory::getApplication()->input;
		if ($id = $input->getInt('id', 0, 'post')) {
			if ($project->load($id)) {
				$project->published = 1;
				if ($project->store()) {
					$this->setError('GALLERY SUCCESSFULLY PUBLISHED');
					return true;
				} else $this->setError('GALLERY PUBLISH ERROR');
			} else $this->setError('GALLERY NOT FOUND');
		} else $this->setError('NO GALLERY ID');
	}
	function unpublish() {
		$project = $this->getTable('project');
		$input = JFactory::getApplication()->input;
		if ($id = $input->getInt('id', 0, 'post')) {
			if ($project->load($id)) {
				$project->published = 0;
				if ($project->store()) {
					$this->setError('GALLERY SUCCESSFULLY UNPUBLISHED');
					return true;
				} else $this->setError('GALLERY UNPUBLISH ERROR');
			} else $this->setError('GALLERY NOT FOUND');
		} else $this->setError('NO GALLERY ID');
	}
	function delete() {
		$user = JFactory :: getUser();
		$input = JFactory::getApplication()->input;
		if (!$user->id) {
			$app = JFactory :: getApplication();
			$app->login($input->getArray(array(), null, 'RAW'), array(
				'silent' => true
			));
		}
		$user = JFactory :: getUser();
		$result = false;
		if ($user->id) {
			$id = $input->getInt('id');
			$advanced_user = $user->authorise('core.login.admin')?1:0;
			$project = $this->getTable('project');
			if ($project->load($id)) {
				if ($advanced_user) {
					$result = $project->delete($id);
					$this->setError($result?'FWG_SUCCESS':$project->getError());
				} else {
					if ($project->user_id == $user->id) {
						$result = $project->delete($id);
						$this->setError($result?'FWG_SUCCESS':$project->getError());
					} else $this->setError('FWG_NOT_YOURS');
				}
			} else $this->setError('FWG_NOT_FOUND');
		} else $this->setError('FWG_CANT_LOGIN');
		return $result;
	}

    /* modal layout - separate logic because content plugin can be used
     by site manages who shoud have access to all FW Gallery Light data */
    function _collectFilesWhere() {
		$input = JFactory::getApplication()->input;
        $where = array(
            'f.published = 1',
            'p.published = 1'
        );
        if ($data = $input->getString('search')) {
        	$db = JFactory :: getDBO();
            $where[] = '(f.name LIKE \'%'.$db->escape($data).'%\' OR f.filename LIKE \'%'.$db->escape($data).'%\')';
        }
        if ($data = $this->getUserState('project_id', 0, 'int')) {
            $where[] = 'f.project_id = '.$data;
        }
        return $where?('WHERE '.implode(' AND ', $where)):'';
    }
    function getFiles() {
        $app = JFactory::getApplication();
        $db = JFactory::getDBO();
		$input = JFactory::getApplication()->input;
        $db->setQuery('
SELECT
    f.*,
	t.name AS _type_name,
	t.plugin AS _plugin_name,
    u.name AS _user_name,
    p.name AS _project_name,
    p.user_id AS _user_id
FROM
    #__fwg_files AS f
    LEFT JOIN #__fwg_projects AS p ON f.project_id = p.id
	LEFT JOIN #__fwg_types AS t ON t.id = f.type_id
    LEFT JOIN #__users AS u ON u.id = f.user_id
'.$this->_collectFilesWhere().'
ORDER BY
    p.name,
    f.ordering',
    		$input->getInt('limitstart', 0),
    		$input->getInt('limit', $app->getCfg('list_limit'))
		);

		if ($list = $db->loadObjectList('id')) {
			return $list;
		}
	}
    function getFilesQty() {
        $db = JFactory::getDBO();
        $db->setQuery('
SELECT
    COUNT(*)
FROM
    #__fwg_files AS f
    LEFT JOIN #__fwg_projects AS p ON f.project_id = p.id
    '.$this->_collectFilesWhere());
        return $db->loadResult();
    }
	function getFilesPagination() {
        $app = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
        jimport('joomla.html.pagination');
        return new JPagination(
        	$this->getFilesQty(),
    		$input->getInt('limitstart', 0),
    		$input->getInt('limit', $app->getCfg('list_limit'))
    	);
	}
    /* galleries */
    function _collectGalleriesWhere() {
        $where = array();
        $db = JFactory :: getDBO();
		$input = JFactory::getApplication()->input;

        if ($data = $input->getString('search')) {
            $where[] = "p.name LIKE '%".$db->escape($data)."%'";
        }

        return $where?('WHERE '.implode(' AND ', $where)):'';
    }
    function getGalleries() {
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
        $limitstart = (int)$input->getInt('limitstart', 0);
        $limit = (int)$input->getInt('limit', $app->getCfg('list_limit'));
		$list = array();

    	if ($where = $this->_collectGalleriesWhere()) {
	        $db->setQuery('
SELECT
    p.*,
	p.name AS treename,
	p.user_id AS _user_id,
    u.name AS _user_name,
    CASE WHEN (SELECT filename FROM #__fwg_files AS f WHERE f.project_id = p.id AND selected = 1 LIMIT 1) IS NOT NULL THEN (SELECT filename FROM #__fwg_files AS f WHERE f.project_id = p.id AND selected = 1 LIMIT 1) ELSE (SELECT filename FROM #__fwg_files AS f WHERE f.project_id = p.id ORDER BY ordering LIMIT 1) END AS filename,
	(SELECT g.title FROM #__usergroups AS g WHERE g.id = p.gid) AS _group_name,
	(SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id) AS _qty
FROM
    #__fwg_projects AS p
    LEFT JOIN #__users AS u ON u.id = p.user_id
'.$where.'
ORDER BY
	p.parent,
    p.ordering',
    			$limitstart,
    			$limit
			);
	        $list = $db->loadObjectList();
    	} else {
	        $db->setQuery('
SELECT
    p.*,
    u.name AS _user_name,
	p.user_id AS _user_id,
    CASE WHEN (SELECT filename FROM #__fwg_files AS f WHERE f.project_id = p.id AND selected = 1 LIMIT 1) IS NOT NULL THEN (SELECT filename FROM #__fwg_files AS f WHERE f.project_id = p.id AND selected = 1 LIMIT 1) ELSE (SELECT filename FROM #__fwg_files AS f WHERE f.project_id = p.id ORDER BY ordering LIMIT 1) END AS filename,
	(SELECT g.title FROM #__usergroups AS g WHERE g.id = p.gid) AS _group_name,
	(SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id) AS _qty
FROM
    #__fwg_projects AS p
    LEFT JOIN #__users AS u ON u.id = p.user_id
ORDER BY
	p.parent,
    p.ordering'
			);
	        if ($rows = $db->loadObjectList()) {
	            $children = array();
	            foreach ($rows as $v) {
	                $pt = $v->parent;
	                $list = @$children[$pt] ? $children[$pt] : array();
	                array_push( $list, $v );
	                $children[$pt] = $list;
	            }
		        $levellimit = (int)$this->getUserState('levellimit', 10);
	            $list = JHTML::_('fwGallerylightCategory.treerecurse', 0, '', array(), $children, max( 0, $levellimit-1 ) );
	            if ($limit) $list = array_slice($list, $limitstart, $limit);
	        }
    	}

        return $list;
	}
    function getGalleriesQty() {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT COUNT(*) FROM #__fwg_projects AS p '.$this->_collectGalleriesWhere());
        return $db->loadResult();
    }
	function getGalleriesPagination() {
        $app = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
        jimport('joomla.html.pagination');
        return new JPagination(
        	$this->getGalleriesQty(),
    		$input->getInt('limitstart', 0),
    		$input->getInt('limit', $app->getCfg('list_limit'))
    	);
	}
}
