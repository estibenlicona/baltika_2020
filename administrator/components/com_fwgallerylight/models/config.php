<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwgallerylightModelConfig extends JModelLegacy {
    function loadObj() {
    	$obj = new stdclass;
    	$obj->params = JComponentHelper::getParams('com_fwgallerylight');
        return $obj;
    }

    function save() {
    	$params = JComponentHelper::getParams('com_fwgallerylight');
		$input = JFactory::getApplication()->input;
		$data = (array)$input->getVar('config');

    	$fields = array(
			'display_empty_gallery',
			'display_descr_gallery',
			'display_descr_image',
			'allow_frontend_galleries_management',
			'im_just_shrink',
			'use_watermark',
			'display_total_galleries',
			'display_owner_gallery',
			'display_owner_image',
			'display_date_gallery',
			'display_gallery_sorting',
			'display_name_gallery',
			'display_name_image',
			'display_date_image',
			'allow_print_button',
			'hide_bottom_image',
			'display_user_copyright',
			'display_social_sharing'
		);
		foreach ($fields as $field) $data[$field] = $input->getVar($field);

	   	$params->loadArray($data);
		$cache = JFactory::getCache('_system', 'callback');
    	$cache->clean();

		JFHelper::clearImageCache();

		$wmf = $params->get('watermark_file');
		if ($input->getInt('delete_watermark') and $wmf) {
			if (file_exists(FWG_STORAGE_PATH.$wmf)) @unlink(FWG_STORAGE_PATH.$wmf);
			$params->set('watermark_file', '');
			$wmf = '';
		}

    	if ($file = $input->files->get('watermark_file')
    	 and $name = JArrayHelper::getValue($file, 'name')
    	  and empty($file['error']) and preg_match('/\.png$/i', $name)
    	   and move_uploaded_file(JArrayHelper::getValue($file, 'tmp_name'), FWG_STORAGE_PATH.$name)) {
			if ($wmf and $name != $wmf and file_exists(FWG_STORAGE_PATH.$wmf)) {
				@unlink(FWG_STORAGE_PATH.$wmf);
			}
    		$params->set('watermark_file', $name);
    	}

		return $this->store($params);
    }
	function store($params) {
    	$db = JFactory::getDBO();

		$db->setQuery('UPDATE #__update_sites SET extra_query = '.$db->quote('&code='.$params->get('update_code')).' WHERE name = \'FW Gallery Light\'');
		$db->execute();

		$db->setQuery('TRUNCATE TABLE #__updates');
		$db->execute();

    	$db->setQuery('UPDATE #__extensions SET params = '.$db->quote($params->toString()).' WHERE `element` = \'com_fwgallerylight\' AND `type` = \'component\'');
    	return $db->query();
	}
	function loadImages() {
		$db = JFactory::getDBO();
		$db->setQuery('SELECT f.id, p.user_id, f.filename, p.name FROM #__fwg_projects AS p, #__fwg_files AS f WHERE f.project_id = p.id AND filename <> \'\'');
		return $db->loadObjectList();
	}
	function getDescription() {
		$buff = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_fwgallerylight/fwgallerylight.xml');
		if (preg_match('#<description><\!\[CDATA\[(.*?)\]\]></description>#msi', $buff, $match)) {
			return $match[1];
		}
	}
	function getVersion() {
		$buff = file_get_contents(JPATH_ADMINISTRATOR.'/components/com_fwgallerylight/fwgallerylight.xml');
		if (preg_match('#<version>(.*?)</version>#msi', $buff, $match)) {
			return $match[1];
		}
	}
	function checkUpdate() {
		$data = new stdclass;
		$data->loc_version = $this->getVersion();
		if ($buff = JFHelper::request('http://fastw3b.net/index.php?option=com_fwsales&view=updates&layout=package&format=raw&package=Gallery+Light&dummy=extension.xml')) {
			if (preg_match('#<extension.*version="([^"]*)"#', $buff, $match)) {
				$data->rem_version = $match[1];
			}
		} else $this->setError(JText::_('FWG_NO_RESPONSE_FROM_REMOTE_SERVER'));
		return $data;
	}
	function verifyCode() {
		$data = new stdclass;
		$input = JFactory::getApplication()->input;
		if ($code = $input->getString('code')) {
			if ($buff = JFHelper::request('http://fastw3b.net/index.php?option=com_fwsales&view=updates&layout=verify_code&format=raw&package=Gallery+Light&code='.urlencode($code).'&host='.urlencode(JURI::root(false)))) {
				$tmp = json_decode($buff);
				if ($tmp) {
					if (!empty($tmp->msg)) $this->setError($tmp->msg);
					if (!empty($tmp->user_name)) $data->user_name = $tmp->user_name;
					if (!empty($tmp->membership)) $data->membership = JText::_('FWG_'.$tmp->membership);
					if (!empty($tmp->status)) $data->status = JText::_('FWG_'.$tmp->status);
					if (!empty($tmp->expires)) $data->expires = JHTML::date($tmp->expires);
					if (isset($tmp->expired)) $data->expired = $tmp->expired;
					if (!empty($tmp->suggestion_link)) $data->suggestion_link = $tmp->suggestion_link;
					if (!empty($tmp->bug_link)) $data->bug_link = $tmp->bug_link;
					if (!empty($tmp->is_version)) $data->is_version = $tmp->is_version;
					if (!empty($tmp->discount)) $data->discount = $tmp->discount;
					if (!empty($tmp->version)) {
						$data->rem_version = $tmp->version;
						$data->loc_version = $this->getVersion();
					}
					if (!empty($tmp->purchased_version)) {
						$data->purchased_version = $tmp->purchased_version;
						$buff = explode('.', $tmp->purchased_version);
						if (count($buff) > 1) {
							$buff[1]++;
							$data->next_version = $buff[0].'.'.$buff[1].'.0';
						}
					}
					if (!empty($tmp->verified)) {
						$data->verified = 1;
						$params = JComponentHelper::getParams('com_fwgallerylight');
						$params->set('update_code', $code);
						$params->set('verified_code', $code);
						$this->store($params);
					}
				}
			} else $this->setError(JText::_('FWG_NO_RESPONSE_FROM_REMOTE_SERVER'));
		} else $this->setError(JText::_('FWG_NO_CODE_TO_VERIFY'));
		return $data;
	}
	function revokeCode() {
		$data = new stdclass;
		$params = JComponentHelper::getParams('com_fwgallerylight');
		$params->set('verified_code', '');
		$data->result = $this->store($params);
		return $data;
	}
	function updatePackage() {
		$params = JComponentHelper::getParams('com_fwgallerylight');
		$code = $params->get('update_code');
		$result = false;
		if ($code) {
			if ($code == $params->get('verified_code')) {
				if ($buff = JFHelper::request('http://fastw3b.net/index.php?option=com_fwsales&view=updates&layout=package&format=raw&package=Gallery+Light&dummy=extension.xml')) {
					if (preg_match('#detailsurl="([^"]*)"#', $buff, $match)) {
						$url = str_replace('&amp;', '&', $match[1]);
						if ($buff = JFHelper::request($url)) {
							if (preg_match('#<downloadurl[^>]*>([^<]+)</downloadurl>#', $buff, $match)) {
								$url = str_replace('&amp;', '&', $match[1]).'&code='.urlencode($code);
								if ($buff = JFHelper::request($url)) {
									jimport('joomla.filesystem.file');
									jimport('joomla.filesystem.folder');
									$path = JPATH_SITE.'/tmp/';

									$filename = '';
									do {
										$filename = 'inst'.rand().'.zip';
									} while (file_exists($path.$filename));

									if (JFile::write($path.$filename, $buff)) {
										$package = JInstallerHelper::unpack($path.$filename, true);
										if (!empty($package['dir'])) {
											$installer = JInstaller::getInstance();
											if ($result = $installer->install($package['dir'])) {
												$this->setError(JText::_('FWG_UPDATED_SUCCESFULLY'));
											} else {
												$this->setError(JText::_('FWG_NOT_UPDATED'));
											}
										}
										if (file_exists($path.$filename)) JFile::delete($path.$filename);
										if (file_exists($package['dir'])) JFolder::delete($package['dir']);
									} else $this->setError(JText::_('FWG_CANT_WRITE_FILE'));
								} else $this->setError(JText::_('FWG_SERVER_REFUSES_DOWNLOAD'));
							} else $this->setError(JText::_('FWG_WRONG_RESPONSE_FORMAT_FROM_REMOTE_SERVER'));
						} else $this->setError(JText::_('FWG_NO_RESPONSE_FROM_REMOTE_SERVER'));
					} else $this->setError(JText::_('FWG_WRONG_RESPONSE_FORMAT_FROM_REMOTE_SERVER'));
				} else $this->setError(JText::_('FWG_NO_RESPONSE_FROM_REMOTE_SERVER'));
			} else $this->setError(JText::_('FWG_CODE_NOT_VERIFIED'));
		} else $this->setError(JText::_('FWG_NO_UPDATE_CODE'));
		return $result;
	}
}
