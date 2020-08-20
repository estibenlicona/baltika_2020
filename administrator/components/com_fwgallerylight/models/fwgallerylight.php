<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightModelfwGallerylight extends JModelList {
    function getUserState($name, $def='', $type='cmd') {
        $app = JFactory::getApplication();
        $context = 'com_fwgallerylight.projects.';
        return $app->getUserStateFromRequest($context.$name, $name, $def, $type);
    }

    function _collectWhere() {
        $where = array();
        $db = JFactory::getDBO();
		$input = JFactory::getApplication()->input;

        if ($data = $input->getInt('client')) {
            $where[] = 'p.user_id = '.$data;
        }
        if ($data = $input->getString('search')) {
            $where[] = "p.name LIKE '%".$db->escape($data)."%'";
        }

        return $where?('WHERE '.implode(' AND ', $where)):'';
    }

    function getProjectsQty() {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT COUNT(*) FROM #__fwg_projects AS p '.$this->_collectWhere());
        return $db->loadResult();
    }

    function getPagination() {
        $app = JFactory::getApplication();
        jimport('joomla.html.pagination');
        $pagination = new JPagination(
        	$this->getProjectsQty(),
        	$this->getUserState('limitstart', 0),
        	$this->getUserState('limit', $app->getCfg('list_limit'))
    	);
        return $pagination;
    }

    function getProject() {
        $project = $this->getTable('project');
		$input = JFactory::getApplication()->input;
        if (($ids = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($ids, 0)) or $id = $input->getInt('id', 0)) {
            $project->load($id);
        }
        return $project;
    }

    function getItems() {
        $db = JFactory::getDBO();
        $app = JFactory::getApplication();
		$input = JFactory::getApplication()->input;

        $order = $input->getString('order');
        if (!$order or !in_array($order, array('ordering','name','created'))) $order = 'ordering';

        $limit = (int)$input->getInt('limit', $app->getCfg('list_limit'));
        $limitstart = (int)$input->getInt('limitstart', 0);

		$list = array();

    	if ($where = $this->_collectWhere()) {
	        $db->setQuery('
SELECT
    p.*,
	p.name AS treename,
	p.user_id AS _user_id,
    u.name AS _user_name,
    (SELECT f.id FROM #__fwg_files AS f WHERE f.project_id = p.id ORDER BY selected DESC, ordering LIMIT 1) AS _file_id,
	(SELECT g.title FROM #__usergroups AS g WHERE g.id = p.gid) AS _group_name,
	(SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id) AS _qty
FROM
    #__fwg_projects AS p
    LEFT JOIN #__users AS u ON u.id = p.user_id
'.$where.'
ORDER BY
	p.parent,
    p.'.$order,
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
    (SELECT f.id FROM #__fwg_files AS f WHERE f.project_id = p.id ORDER BY selected DESC, ordering LIMIT 1) AS _file_id,
	(SELECT g.title FROM #__usergroups AS g WHERE g.id = p.gid) AS _group_name,
	(SELECT COUNT(*) FROM #__fwg_files AS f WHERE f.project_id = p.id) AS _qty
FROM
    #__fwg_projects AS p
    LEFT JOIN #__users AS u ON u.id = p.user_id
ORDER BY
	p.parent,
    p.'.$order
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
		if ($list) {
			foreach ($list as $i=>$row) {
				if (!$row->_file_id and $buff = JFHelper::findGalleryImage($row)) {
					$list[$i]->_file_id = $buff->_file_id;
				}
			}
		}
        return $list;
    }

    function saveorder() {
		$input = JFactory::getApplication()->input;
        $cid = (array)$input->getVar('cid');
        $order = (array)$input->getVar('order');

		JArrayHelper::toInteger($cid, 0);
        if (count($cid) and count($cid) == count($order)) {
            $db = JFactory::getDBO();
            $project = $this->getTable('project');
            foreach ($cid as $num=>$id) {
                $db->setQuery('UPDATE #__fwg_projects SET ordering = '.(int)JArrayHelper::getValue($order, $num).' WHERE id = '.(int)$id);
                $db->query();
            }
            $db->setQuery('SELECT DISTINCT parent FROM  #__fwg_projects WHERE id IN ('.implode(',',$cid).')');
            if ($parents = $db->loadColumn()) foreach ($parents as $parent) $project->reorder('parent='.(int)$parent);
            return true;
        }
        return false;
    }

    function orderdown() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($cid, 0)) {
            $project = $this->getTable('project');
            if ($project->load($id)) $project->move(1, 'parent='.(int)$project->parent);
            return true;
        }
        return false;
    }

    function orderup() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($cid, 0)) {
            $project = $this->getTable('project');
            if ($project->load($id)) $project->move(-1, 'parent='.(int)$project->parent);
            return true;
        }
        return false;
    }

    function save() {
        $project = $this->getTable('project');
		$input = JFactory::getApplication()->input;
        if ($id = $input->getInt('id') and !$project->load($id)) $input->set('id', 0);

        if ($project->bind($input->getArray(array(), null, 'RAW')) and $project->check() and $project->store()) {
            $this->setError(JText::_('FWG_THE_GALLERY_DATA').' '.JText::_('FWG_STORED_SUCCESSFULLY'));
            return $project->id;
        } else
            $this->setError(JText::_('FWG_THE_GALLERY_DATA').' '.JText::_('FWG_STORING_ERROR').':'.$project->getError());
    }

    function getClients() {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT u.id, u.name FROM #__users AS u ORDER BY u.name');
        return $db->loadObjectList();
    }

    function remove() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $project = $this->getTable('project');
            foreach ($cid as $id) $project->delete($id);
            $this->setError(JText::_('FWG_GALLERY_IES_REMOVED'));
            return true;
        } else $this->setError(JText::_('FWG_NO_GALLERY_ID_PASSED_TO_REMOVE'));
        return false;
    }

    function publish() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $image = $this->getTable('project');
            $image->publish($cid, 1);
            $this->setError(JText::_('FWG_GALLERY_IES_PUBLISHED'));
            return true;
        } else $this->setError(JText::_('FWG_NO_GALLERY_ID_PASSED_TO_PUBLISH'));
        return false;
    }

    function unpublish() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $image = $this->getTable('project');
            $image->publish($cid, 0);
            $this->setError(JText::_('FWG_GALLERY_IES_UNPUBLISHED'));
            return true;
        } else $this->setError(JText::_('FWG_NO_GALLERY_ID_PASSED_TO_PUBLISH'));
        return false;
    }
}
