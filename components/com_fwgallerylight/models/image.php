<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightModelImage extends JModelLegacy {
    function getUserState($name, $def='', $type='cmd') {
        $mainframe = JFactory::getApplication();
        $context = 'com_fwgallerylight.image.'.(int)$this->getImageId().'.';
        return $mainframe->getUserStateFromRequest($context.$name, $name, $def, $type);
    }

    function getImageId() {
		$input = JFactory::getApplication()->input;
    	$id = (int)$input->getInt('id');
    	if (!$id) {
			$menu = JMenu::getInstance('site');
			if ($item = $menu->getActive()) {
	    		$id = $item->params->get('id');
			}
    	}
    	return $id;
    }

    function getObj($id = null) {
        $obj = $this->getTable('file');
        if (is_null($id)) {
        	$id = $this->getImageId();
        	$is_image_hit = true;
        } else $is_image_hit = false;
        if ($id and $obj->load($id)) {
        	if (!$obj->_is_gallery_published) $obj->id = 0;
        	elseif ($is_image_hit) $obj->hit();
        }
        return $obj;
    }

    function getNextImage($image) {
    	return JArrayHelper::getValue($this->_getGalleryImages($image), 1);
    }
    function getPreviousImage($image) {
    	return JArrayHelper::getValue($this->_getGalleryImages($image), 0);
    }
    function _getGalleryImages($current_image) {
    	static $list = null;
    	if (!$list) {
    		$galleryModel = JModelLegacy::getInstance('gallery', 'fwGallerylightModel');
			$params = new JFParams;
    		$db = JFactory::getDBO();

			$where = array(
				'p.published = 1',
				'f.published = 1',
                'f.project_id = '.(int)$current_image->project_id
			);

			$db->setQuery('
SELECT
	f.id,
	f.name,
    f.filename,
	t.name AS _type_name,
	t.plugin AS _plugin_name,
	(SELECT user_id FROM #__fwg_projects AS p WHERE p.id = f.project_id) AS _user_id
FROM
    #__fwg_files AS f
	LEFT JOIN #__fwg_projects AS p ON p.id = f.project_id
	LEFT JOIN #__fwg_types AS t ON t.id = f.type_id
WHERE
    '.implode(' AND ', $where).'
'.$galleryModel->_getOrderClause());
			$list = array();
    		if ($images = $db->loadObjectList()) {
    			$qty = count($images);
    			if ($qty > 1) {
	    			/* look for current image position */
	    			foreach ($images as $pos => $image) {
	    				if ($image->id == $current_image->id) {
	    					if (!$pos) {
	    						$list[] = null;
	    						$list[] = ($qty > 1)?$images[1]:null;
	    					} elseif ($pos == $qty - 1) {
	    						$list[] = ($qty > 1)?$images[$pos - 1]:null;
	    						$list[] = null;
	    					} else {
	    						$list[] = $images[$pos - 1];
	    						$list[] = $images[$pos + 1];
	    					}
							break;
	    				}
	    			}
    			}
    		}
    	}
    	return $list;
    }
    function loadCategoriesPath($category_id = 0) {
    	$model = JModelLegacy::getInstance('gallery', 'fwGallerylightModel');
    	return $model->loadCategoriesPath($category_id);
    }
	function getPlugin() {
		$input = JFactory::getApplication()->input;
		if ($plugin = $input->getString('plugin'))
			return JPluginHelper::getPlugin('fwgallery', $plugin);
	}
	function processPlugin() {
		if ($plugin = $this->getPlugin() and JPluginHelper::importPlugin('fwgallery', $plugin->name)) {
			$dispatcher = JDispatcher::getInstance();
			$result = $dispatcher->trigger('fwProcess');
		}
	}
	function save() {
		$user = JFactory::getUser();
		$input = JFactory::getApplication()->input;
		if (!$user->id) {
			$app = JFactory::getApplication();
			$app->login($input->getArray(array(), null, 'RAW'), array(
				'silent' => true
			));
		}
		$user = JFactory::getUser();
		if ($user->id) {
	        $image = $this->getTable('file');
	        if ($id = $input->getInt('id') and !$image->load($id)) $input->set('id', 0);

	        if ($image->bind($input->getArray(array(), null, 'RAW')) and $image->check() and $image->store()) {
	            $this->setError('FWG_STORED_SUCCESSFULLY');
	            return $image->id;
	        } else
	        	$this->setError($image->getError());
		} else
			$this->setError('FWG_CANT_LOGIN');
	}
	function delete() {
		$user = JFactory::getUser();
		$input = JFactory::getApplication()->input;
		if (!$user->id) {
			$app = JFactory::getApplication();
			$app->login($input->getArray(array(), null, 'RAW'), array(
				'silent' => true
			));
		}
		$user = JFactory::getUser();
		$result = false;
		if ($user->id) {
			$id = $input->getInt('id');
			$advanced_user = $user->authorise('core.login.admin.admin')?1:0;
			$image = $this->getTable('file');
			if ($image->load($id)) {
				if ($advanced_user) {
					$result = $image->delete($id);
					$this->setError($result?'FWG_SUCCESS':$image->getError());
				} else {
					if ($image->user_id == $user->id) {
						$result = $image->delete($id);
						$this->setError($result?'FWG_SUCCESS':$image->getError());
					} else $this->setError('FWG_NOT_YOURS');
				}
			} else $this->setError('FWG_NOT_FOUND');
		} else $this->setError('FWG_CANT_LOGIN');
		return $result;
	}
	function testGallery() {
		$result = new stdclass;
		$result->post_max_size = JFHelper::getIniSize('post_max_size');
		$result->upload_max_filesize = JFHelper::getIniSize('upload_max_filesize');
		$result->max_file_uploads = ini_get('max_file_uploads');

		$filename = JPATH_ADMINISTRATOR.'/components/com_fwgallerylight/fwgallery.xml';
		if (file_exists($filename) and $buff = file_get_contents($filename)) {
			if (preg_match('#<version>([^<]*)</version>#', $buff, $match)) {
				$result->code = 2;
				$result->version = $match[1];
			} else {
				$result->code = 1;
				$result->msg = JText::_('FW Gallery Light version not found');
			}
		} else {
			$result->code = 0;
			$result->msg = JText::_('FW Gallery Light config file not found');
		}

		return $result;
	}
	function testUser() {
		$result = new stdclass;
		$result->code = 0;
		$result->advanced_user = 0;
		$result->msg = '';
		$input = JFactory::getApplication()->input;
		if ($pass = $input->getString('password')) {
			if ($username = $input->getString('username')) {
				$result->code = 2;
				$db = JFactory::getDBO();
				$db->setQuery('SELECT `id`, `password` FROM #__users WHERE username = '.$db->quote($username));
				if ($obj = $db->loadObject()) {
					jimport('joomla.user.helper');
					if (JUserHelper::verifyPassword($pass, $obj->password)) {
						$result->code = 5;
						$user = JFactory::getUser($obj->id);
						$result->advanced_user = $user->authorise('core.login.admin.admin')?1:0;
						$result->msg = JText::_('User ok');
					} else {
						$result->code = 4;
						$result->msg = JText::_('Password do not match');
					}
				} else {
					$result->code = 3;
					$result->msg = JText::_('User not found');
				}
			} else {
				$result->code = 1;
				$result->msg = JText::_('Username not passed');
			}
		} else {
			$result->code = 0;
			$result->msg = JText::_('Password not passed');
		}
		return $result;
	}
	function getPlugins() {
		$user = JFactory::getUser();
		$input = JFactory::getApplication()->input;
		if (!$user->id) {
			$app = JFactory::getApplication();
			$app->login($input->getArray(array(), null, 'RAW'), array(
				'silent' => true
			));
		}
		$user = JFactory::getUser();
		$result = false;
		if ($user->id) {
			$advanced_user = $user->authorise('core.login.admin.admin')?1:0;
			if ($advanced_user) {
				$db = JFactory::getDBO();
				$db->setQuery('SELECT \'\' AS name, element, folder AS `type`, enabled AS published, \'\' AS `version` FROM #__extensions WHERE `type` = \'plugin\' AND (`folder` = \'fwgallery\' OR `name` LIKE \'%FW Gallery Light%\') ORDER BY ordering');
				if ($plugins = $db->loadObjectList()) foreach ($plugins as $i=>$plugin) {
					$filename = JPATH_PLUGINS.'/'.$plugin->type.'/'.$plugin->element.'/'.$plugin->element.'.xml';
					if (file_exists($filename)) {
						$file = file_get_contents($filename);
						if (preg_match('#<name>(.*?)</name>#i', $file, $m)) $plugins[$i]->name = $m[1];
						if (preg_match('#<version>(.*?)</version>#i', $file, $m)) $plugins[$i]->version = $m[1];
					}
					return $plugins;
				}
			} else $this->setError('FWG_NO_ACCESS');
		} else $this->setError('FWG_CANT_LOGIN');
		return $result;
	}
	function getImage() {
		$input = JFactory::getApplication()->input;
		$id = (int)$input->getInt('id');
		$js = (int)$input->getInt('js');
		$height = (int)$input->getInt('h');
		$width = (int)$input->getInt('w');
		$t_path = JPATH_CACHE.'/fwgallery/images/';
		$t_filename = $id.'_'.md5($id.'h'.$height.'w'.$width.'j'.$js).'.jpg';
		if (!file_exists($t_path.$t_filename)) {
			ini_set('memory_limit', '512M');
			$filename = '';
			$s_path = JPATH_SITE.'/images/com_fwgallery/files/';

			$db = JFactory::getDBO();
			$db->setQuery('
SELECT
    (SELECT p.user_id FROM #__fwg_projects AS p WHERE p.id = f.project_id) AS user_id,
    original_filename AS filename
FROM
    #__fwg_files AS f
WHERE
    id = '.$id);
			if ($obj = $db->loadObject()) {
				$s_path .= $obj->user_id.'/';
				$filename = $obj->filename;
			}

			if (!$filename or ($filename and !file_exists($s_path.$filename))) {
				$s_path = JPATH_SITE.'/components/com_fwgallerylight/assets/images/';
				$filename = 'no_image.jpg';
			}

			if ($filename and file_exists($s_path.$filename)) {
				jimport('joomla.filesystem.file');
				if (!file_exists($t_path)) JFile::write($t_path.'index.html', $buff = '<html><head><title></title></head><body></body></html>');
				$params = new JFParams;
				$use_wm = $params->get('use_watermark');
				$wmfile = $wmtext = $wmpos = '';
				if ($use_wm) {
					$wmfile = JFHelper::getWatermarkFilename();
					$wmtext = $params->get('watermark_text');
					$wmpos = $params->get('watermark_position', 'left_bottom');
				}

				$ext = strtolower(JFile::getExt($filename));
				if (!in_array($ext, array('gif', 'jpeg', 'jpg', 'png'))) {
					return $s_path.$filename;
				} else {
					if (!$height and !$width) {
						if ($use_wm) {
							JFile::copy($s_path.$filename, $t_path.$t_filename);
						} else {
							return $s_path.$filename;
						}
					}
					$image = GPMiniImg::loadImage($s_path, $filename);

					if ($image) {
						GPMiniImg::reduceImage($image, $t_path, $t_filename, $width, $height, $wmfile, $wmtext, $wmpos, $js);
					}
				}
			}
		}
		if (file_exists($t_path.$t_filename)) return $t_path.$t_filename;
	}
	function update() {
		$file = $this->getTable('file');
		$input = JFactory::getApplication()->input;
		if ($id = $input->getInt('id', 0, 'post')) {
			if ($file->load($id)) {
				if ($file->bind($input->getArray(array(), null, 'RAW'), array('type_id', 'user_id', 'ordering', 'hits', 'filename', 'original_filename')) and $file->check() and $file->store()) {
					$this->setError('IMAGE SUCCESSFULLY UPDATED');
					return true;
				} else $this->setError('IMAGE UPDATE ERROR');
			} else $this->setError('IMAGE NOT FOUND');
		} else $this->setError('NO IMAGE ID');
		return false;
	}
	function contact() {
		$input = JFactory::getApplication()->input;
		if ($fid = $input->getString('fid')) {
			if ($description = $input->getString('description')) {
				$mailer = JFactory::getMailer();
				$app = JFactory::getApplication();
				$res = $mailer->sendMail(
					$app->getCfg('mailfrom'),
					$app->getCfg('fromname'),
					'gallery@fastw3b.net',
					'New issue from mobile contact manager',
					'Fid: '.$fid.', message: '.$description
				);
				if ($res and !is_object($res)) {
					return true;
				}
				else $this->setError('ERROR SENDING MESSAGE');
			} else $this->setError('EMPTY DESCRIPTION');
		} else $this->setError('EMPTY FID');
		return false;
	}
	function publish() {
		$file = $this->getTable('file');
		$input = JFactory::getApplication()->input;
		if ($id = $input->getInt('id', 0, 'post')) {
			if ($file->load($id)) {
				$file->published = 1;
				if ($file->store()) {
					$this->setError('IMAGE SUCCESSFULLY PUBLISHED');
					return true;
				} else $this->setError('IMAGE PUBLISH ERROR');
			} else $this->setError('IMAGE NOT FOUND');
		} else $this->setError('NO IMAGE ID');
		return false;
	}
	function unpublish() {
		$file = $this->getTable('file');
		$input = JFactory::getApplication()->input;
		if ($id = $input->getInt('id', 0, 'post')) {
			if ($file->load($id)) {
				$file->published = 0;
				if ($file->store()) {
					$this->setError('IMAGE SUCCESSFULLY UNPUBLISHED');
					return true;
				} else $this->setError('IMAGE UNPUBLISH ERROR');
			} else $this->setError('IMAGE NOT FOUND');
		} else $this->setError('NO IMAGE ID');
		return false;
	}
	function checkFid() {
		$input = JFactory::getApplication()->input;
		$data = (object)array(
			'result' => false,
			'name' => ''
		);
		if ($fid = $input->getString('fid')) {
			$result = JFHelper::request('fastw3b.net/index.php?option=com_fwsupermarket&format=raw&view=user&layout=check_fid&fid='.urlencode($fid));
			if ($result and $buff = json_decode($result)) {
				$data = $buff;
			}
		}
		return $data;
	}
}
