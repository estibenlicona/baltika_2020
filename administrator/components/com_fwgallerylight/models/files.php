<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightModelFiles extends JModelList {
    function getUserState($name, $def='', $type='cmd') {
        $app = JFactory::getApplication();
        return $app->getUserStateFromRequest('com_fwgallerylight.files.'.$name, $name, $def, $type);
    }
    function getProjects() {
        $db = JFactory::getDBO();
        $db->setQuery('
SELECT
	p.id,
	p.parent,
	p.name
FROM
	#__fwg_projects AS p
	LEFT JOIN #__users AS u ON u.id = p.user_id
ORDER BY
	parent,
	name');
        $children = array ();
        if ($mitems = $db->loadObjectList()) {
            // first pass - collect children
            foreach ($mitems as $v) {
                $pt = $v->parent;
                $list = @ $children[$pt] ? $children[$pt] : array ();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }
        return JHTML::_('fwGallerylightCategory.treerecurse', 0, '', array (), $children, 9999, 0, 0);
    }
    function _collectWhere() {
        $where = array();

		$input = JFactory::getApplication()->input;
        if ($data = $input->getString('search')) {
        	$db = JFactory::getDBO();
            $where[] = '(f.name LIKE \'%'.$db->escape($data).'%\' OR f.filename LIKE \'%'.$db->escape($data).'%\')';
        }
        if ($data = $input->getInt('project_id')) {
            $where[] = 'f.project_id = '.$data;
        }
/*        if ($input->getCmd('layout') == 'modal') {
            $where[] = 'f.published = 1';
            $where[] = 'p.published = 1';
        }*/
        return $where?('WHERE '.implode(' AND ', $where)):'';
    }
    function getFilesQty() {
        $db = JFactory::getDBO();
        $db->setQuery('
SELECT
    COUNT(*)
FROM
    #__fwg_files AS f
    LEFT JOIN #__fwg_projects AS p ON f.project_id = p.id
'.$this->_collectWhere());
        return $db->loadResult();
    }
    function getPagination() {
        $app = JFactory::getApplication();
		$input = $app->input;
        jimport('joomla.html.pagination');
        return new JPagination(
        	$this->getFilesQty(),
    		$input->getInt('limitstart'),
    		$input->getInt('limit', $app->getCfg('list_limit'))
    	);
    }
    function getFiles() {
        $app = JFactory::getApplication();
		$input = $app->input;
        $order = $input->getString('order');
        if (!$order or !in_array($order, array('ordering','name','created'))) $order = 'ordering';
        $db = JFactory::getDBO();
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
'.$this->_collectWhere().'
ORDER BY
    p.name,
    f.'.$order,
    		$input->getInt('limitstart'),
    		$input->getInt('limit', $app->getCfg('list_limit'))
		);

		return $db->loadObjectList('id');
    }
    function remove() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $image = $this->getTable('file');
            foreach ($cid as $id) {
                $image->delete($id);
            }
            $this->setError(JText::_('FWG_IMAGE_S__REMOVED'));
            return true;
        }
        $this->setError(JText::_('FWG_NO_IMAGE_S__ID_PASSED_TO_REMOVE'));
        return false;
    }
    function saveorder() {
		$input = JFactory::getApplication()->input;
        $cid = $input->getVar('cid');
        $order = $input->getVar('order');

		$pids = array();
        if (is_array($cid) and is_array($order) and count($cid) and count($cid) == count($order)) {
            $db = JFactory::getDBO();
            $image = $this->getTable('file');
            foreach ($cid as $num=>$id) {
            	$image->load($id);
            	if (array_search($id, $pids) === false) {
            		$pids[$id] = $image->project_id;
            	}
            	if ($image->ordering != ($odering = (int)JArrayHelper::getValue($order, $num))) {
            		$image->ordering = $odering;
            		$image->store();
            	}
            }
            if ($pids) {
            	foreach ($pids as $image_id=>$project_id) {
            		$image->load($image_id);
		            $image->reorder('project_id = '.$project_id);
            	}
            }
            return true;
        }
        return false;
    }
    function orderdown() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($cid, 0)) {
            $image = $this->getTable('file');
            $image->load($id);
            $image->move(1, 'project_id='.$image->project_id);
            return true;
        }
        return false;
    }
    function orderup() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid') and $id = JArrayHelper::getValue($cid, 0)) {
            $image = $this->getTable('file');
            $image->load($id);
            $image->move(-1, 'project_id='.$image->project_id);
            return true;
        }
        return false;
    }
    function publish() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $image = $this->getTable('file');
            $image->publish($cid, 1);
            $this->setError(JText::_('FWG_IMAGE_S__PUBLISHED'));
            return true;
        }
        $this->setError(JText::_('FWG_NO_IMAGE_S__ID_PASSED_TO_PUBLISH'));
        return false;
    }
    function unpublish() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $image = $this->getTable('file');
            $image->publish($cid, 0);
            $this->setError(JText::_('FWG_IMAGE_S__UNPUBLISHED'));
            return true;
        }
        $this->setError(JText::_('FWG_NO_IMAGE_S__ID_PASSED_TO_UNPUBLISH'));
        return false;
    }
    function select() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $image = $this->getTable('file');
            return $image->select($cid);
        }
        return false;
    }
    function unselect() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $image = $this->getTable('file');
            return $image->unselect($cid);
        }
        return false;
    }
    function getClients() {
        $db = JFactory::getDBO();
        $db->setQuery('SELECT u.id, u.name FROM #__users AS u ORDER BY u.name');
        return $db->loadObjectList();
    }
    function clockwise() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $image = $this->getTable('file');
            return $image->clockwise($cid);
        }
        return false;
    }
    function counterClockwise() {
		$input = JFactory::getApplication()->input;
        if ($cid = (array)$input->getVar('cid')) {
            $image = $this->getTable('file');
            return $image->counterClockwise($cid);
        }
        return false;
    }
	function install() {
		$input = JFactory::getApplication()->input;
		$success = $errors = 0;
        $gallery_name = $gallery_user_id = null;
        $project_id = $input->get('project_id');
        $db = JFactory::getDBO();
        $db->setQuery('SELECT user_id, name FROM #__fwg_projects WHERE id = '.(int)$project_id);
        if ($obj = $db->loadObject()) {
            $gallery_user_id = $obj->user_id;
            $gallery_name = $obj->name;
        }
        if (!$gallery_user_id) {
            $this->setError(JText::_('FWGPF_GALLERY_NOT_FOUND'));
            return;
        }
        if ($files = $input->files->get('images')) {
			jimport('joomla.filesystem.file');
            $path = JPATH_SITE.'/images/com_fwgallery/files/'.$gallery_user_id.'/';
            if (!file_exists($path)) {
                JFile::write($path.'index.html', $buff = '<html><head><title></title></head><body></body></html>');
            }
			$exts = array('gif', 'png', 'jpg', 'jpeg');
			foreach ($files as $file) if (!empty($file['name']) and empty($file['error'])) {
				$ext = strtolower(JFile::getExt($file['name']));
				if (!in_array($ext, $exts)) continue;
				$original_filename = JFHelper::findRandUnicFilename($path, $ext);
				if (JFile::copy($file['tmp_name'], $path.$original_filename)) {
					$image = $this->getTable('file');
					$image->id = 0;
					$image->name = JFile::stripExt($file['name']);
					$image->published = 1;
					$image->project_id = $project_id;
					$image->size = $file['size'];
					$image->filename = $file['name'];
					$image->original_filename = $original_filename;

					if ($image->check() and $image->store()) {
						$success++;
					} else {
						$errors++;
					}
				} else {
					$errors++;
				}
			}
		}
		$this->setError(JText::sprintf('FWG_BATCH_UPLOAD_RESULT', $success, ($success+$errors), $gallery_name));
		return $success;
	}
}
