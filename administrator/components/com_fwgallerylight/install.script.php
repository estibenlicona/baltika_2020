<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined('_JEXEC') or die('Restricted access');

class com_fwgallerylightInstallerScript {
	function postflight($type, $adaptor) {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$db = JFactory::getDBO();
		$tables = $db->getTableList();

		$db->setQuery('SHOW FIELDS FROM #__fwg_files');
		$fields = $db->loadColumn();

		$list = array(
			'media'=>"ALTER TABLE `#__fwg_files` ADD COLUMN `media` VARCHAR(20)",
			'media_code'=>"ALTER TABLE `#__fwg_files` ADD COLUMN `media_code` VARCHAR(200)",
			'hits'=>'ALTER TABLE `#__fwg_files` ADD COLUMN `hits` INT(10) UNSIGNED NOT NULL DEFAULT \'0\' AFTER `ordering`;',
			'type_id'=>'ALTER TABLE `#__fwg_files` ADD COLUMN `type_id` INT(10) UNSIGNED NOT NULL DEFAULT \'0\' AFTER `project_id`,  ADD INDEX `type_id` (`type_id`);',
			'user_id'=>'ALTER TABLE `#__fwg_files` ADD COLUMN `user_id` INT(10) UNSIGNED NOT NULL DEFAULT \'0\' AFTER `project_id`,  ADD INDEX `user_id` (`user_id`);',
			'latitude'=>'ALTER TABLE `#__fwg_files` ADD COLUMN `latitude` FLOAT NOT NULL DEFAULT \'0\';',
			'longitude'=>'ALTER TABLE `#__fwg_files` ADD COLUMN `longitude` FLOAT NOT NULL DEFAULT \'0\';',
			'size'=>"ALTER TABLE `#__fwg_files` ADD COLUMN `size` INT NULL DEFAULT NULL, ADD COLUMN `width` INT NULL DEFAULT NULL, ADD COLUMN `height` INT NULL DEFAULT NULL",
			'original_filename'=>'ALTER TABLE `#__fwg_files` ADD COLUMN `original_filename` VARCHAR(250);',
			'copyright'=>'ALTER TABLE `#__fwg_files` ADD COLUMN `copyright` VARCHAR(100);'
		);

		foreach ($list as $name=>$query) {
			if (!in_array($name, $fields)) {
				$db->setQuery($query);
				if (!$db->query()) echo 'Can\'t execute a query: '.$name.' - do it manually: '.$db->getQuery().'<br/>';
				else {
					if ($name == 'media') {
						$db->setQuery('ALTER TABLE `#__fwg_files` CHANGE COLUMN `media` `media` VARCHAR(20)');
						if (!$db->query()) echo 'Can\'t execute a query: '.$name.' - do it manually: '.$db->getQuery().'<br/>';
					} elseif ($name == 'media_code') {
						$db->setQuery('ALTER TABLE `#__fwg_files` CHANGE COLUMN `media_code` `media_code` VARCHAR(200)');
						if (!$db->query()) echo 'Can\'t execute a query: '.$name.' - do it manually: '.$db->getQuery().'<br/>';
					} elseif ($name == 'size') {
						$db->setQuery('SELECT id, filename, (SELECT p.user_id FROM #__fwg_projects AS p WHERE p.id = f.project_id) AS _user_id FROM #__fwg_files AS f WHERE size IS NULL');
						if ($files = $db->loadObjectList()) {
							foreach ($files as $file) if ($file->_user_id and $file->filename) {
								$filename = $path.$file->_user_id.'/'.$file->filename;
								if (file_exists($filename)) {
									$size = filesize($filename);
									$data = (array)getimagesize($filename);
									$width = JArrayHelper::getValue($data, 0);
									$height = JArrayHelper::getValue($data, 1);
									if ($size or $width or $height) {
										$db->setQuery('UPDATE #__fwg_files SET size = '.(int)$size.', width = '.(int)$width.', height = '.(int)$height);
										$db->query();
									}
								}
							}
						}
					}
				}
			}
		}

		$path = JPATH_SITE.'/images/com_fwgallery/files/';
		$db->setQuery('
SELECT
	id,
	filename,
	original_filename,
	(SELECT p.user_id FROM #__fwg_projects AS p WHERE p.id = f.project_id) AS _user_id
FROM
	#__fwg_files AS f
WHERE
	filename IS NOT NULL
	AND
	filename <> \'\'
	AND
	EXISTS(SELECT * FROM #__fwg_projects AS p WHERE p.id = f.project_id)');
		if ($list = $db->loadObjectList()) {
			foreach ($list as $file) {
				$filename = $path.$file->_user_id.'/th_'.$file->filename;
				if (file_exists($filename)) JFile::delete($filename);
				$filename = $path.$file->_user_id.'/mid_'.$file->filename;
				if (file_exists($filename)) JFile::delete($filename);
				$filename = $path.$file->_user_id.'/'.$file->filename;
				if (file_exists($filename)) {
					$o_filename = $path.$file->_user_id.'/'.$file->original_filename;
					if (!$file->original_filename or ($file->original_filename and !file_exists($o_filename))) {
						if (!$file->original_filename) {
							$ext = strtolower(JFile::getExt($file->filename));
							do {
								$file->original_filename = md5($file->filename.microtime()).'.'.$ext;
								$o_filename = $path.$file->_user_id.'/'.$file->original_filename;
							} while (file_exists($o_filename));
							$db->setQuery('UPDATE #__fwg_files SET original_filename = '.$db->quote($file->original_filename).' WHERE id = '.$file->id);
							$db->execute();
						}
						JFile::copy($filename, $o_filename);
					}
					JFile::delete($filename);
				}
			}
		}
		/* remove lost images files */
		if (is_dir($path) and $files = JFolder::files($path, '.', true, true)) {
			foreach ($files as $file) {
				$path = str_replace(JPATH_SITE, '', $file);
				if (preg_match('#(\d+)[\\\/](.+)$#', $path, $match)) {
					$file_found = false;
					$db->setQuery('
SELECT
	f.id,
	(SELECT p.id FROM #__fwg_projects AS p WHERE p.user_id = '.(int)$match[1].' LIMIT 1) AS _project_id,
	p.user_id AS _user_id
FROM
	#__fwg_files AS f
	LEFT JOIN #__fwg_projects AS p ON p.id = f.project_id
WHERE
	original_filename = '.$db->quote($match[2]));
					if ($obj = $db->loadObject()) {
						if ($obj->_user_id == $match[1]) {
							$file_found = true;
						} elseif ($obj->_project_id) {
							$db->setQuery('UPDATE #__fwg_files SET project_id = '.(int)$obj->_project_id.' WHERE id = '.(int)$obj->id);
							if ($db->execute()) {
								$file_found = true;
							}
						}
					}
					if (!$file_found) {
						JFile::delete($file);
					}
				}
			}
		}

		if (!in_array($db->getPrefix().'fwg_files_thumbs_vote', $tables)) {
			$db->setQuery('
CREATE TABLE `#__fwg_files_thumbs_vote` (
	`user_id` INT(10) UNSIGNED NOT NULL,
	`file_id` INT(10) UNSIGNED NOT NULL,
	`value` TINYINT(4) NOT NULL DEFAULT 0,
	`ipaddr` BIGINT(20) NULL DEFAULT NULL,
	INDEX `user_id` (`user_id`),
	INDEX `file_id` (`file_id`),
	INDEX `ipaddr` (`ipaddr`)
)');
			if (!$db->query()) echo $db->getError();
		}
		if (!in_array($db->getPrefix().'fwg_file_prices', $tables)) {
			$db->setQuery('
CREATE TABLE `#__fwg_file_prices` (
	id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	file_id INT UNSIGNED,
	width INT UNSIGNED,
	height INT UNSIGNED,
	price DECIMAL(10, 2),
	PRIMARY KEY(id),
	KEY(file_id),
	KEY(price)
)');
			if (!$db->query()) echo $db->getError();
		}
		if (!in_array($db->getPrefix().'fwg_files_tags', $tables)) {
			$db->setQuery('
CREATE TABLE `#__fwg_files_tags` (
	`tag_id` INT(10) UNSIGNED NOT NULL,
	`file_id` INT(10) UNSIGNED NOT NULL,
	INDEX `tag_id` (`tag_id`),
	INDEX `file_id` (`file_id`)
)');
			if (!$db->query()) echo $db->getError();
		}
		if (!in_array($db->getPrefix().'fwg_tags', $tables)) {
			$db->setQuery('
CREATE TABLE `#__fwg_tags` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `name` (`name`)
)');
			if (!$db->query()) echo $db->getError();
		}

		$db->setQuery('SELECT * FROM #__fwg_types WHERE plugin = \'video\'');
		if ($obj = $db->loadObject()) {
			if (!$obj->published) {
				$db->setQuery('UPDATE #__fwg_types SET published = 1 WHERE id = '.$obj->id);
				$db->query();
			}
		} else {
			$db->setQuery('INSERT INTO #__fwg_types SET published = 1, name = \'Video\', plugin = \'video\'');
			$db->query();
		}

		$db->setQuery('SHOW FIELDS FROM #__fwg_files_vote');
		$fields = $db->loadColumn();
		$list = array(
			'ipaddr'=>'ALTER TABLE `#__fwg_files_vote` ADD COLUMN `ipaddr` BIGINT NULL, ADD INDEX `ipaddr` (`ipaddr`), DROP PRIMARY KEY, ADD INDEX `user_id` (`user_id`), ADD INDEX `file_id` (`file_id`)',
		);

		foreach ($list as $name=>$query) {
			if (!in_array($name, $fields)) {
				$db->setQuery($query);
				if (!$db->query()) echo 'Can\'t execute a query: '.$name.' - do it manually: '.$db->getQuery().'<br/>';
			}
		}
		$db->setQuery('ALTER TABLE `#__fwg_files_vote`  CHANGE COLUMN `ipaddr` `ipaddr` BIGINT NULL DEFAULT NULL');
		$db->query();

		$db->setQuery('SHOW FIELDS FROM #__fwg_projects');
		$fields = $db->loadColumn();
		$list = array(
			'is_public'=>'ALTER TABLE `#__fwg_projects` ADD COLUMN `is_public` TINYINT UNSIGNED NOT NULL DEFAULT 0',
			'parent'=>'ALTER TABLE `#__fwg_projects` ADD COLUMN `parent` INT(10) UNSIGNED NOT NULL DEFAULT 0, ADD INDEX `parent` (`parent`)',
			'gid'=>'ALTER TABLE `#__fwg_projects` ADD COLUMN `gid` TINYINT UNSIGNED NOT NULL DEFAULT 0, ADD INDEX `gid` (`gid`)',
			'color'=>'ALTER TABLE `#__fwg_projects` ADD COLUMN `color` CHAR(6)'
		);

		foreach ($list as $name=>$query) {
			if (!in_array($name, $fields)) {
				$db->setQuery($query);
				if (!$db->query()) echo 'Can\'t execute a query: '.$name.' - do it manually: '.$db->getQuery().'<br/>';
			}
		}
		/* install simple template */
        $installer = method_exists($adaptor, 'getParent') ? $adaptor->getParent() : $adaptor->parent;
		$source_path = $installer->getPath('source').'/';
		if (file_exists($source_path.'com_fwgallerylighttmplsimple.zip') and JArchive::extract($source_path.'com_fwgallerylighttmplsimple.zip', $source_path.'com_fwgallerylighttmplsimple')) {
			$installer->install($source_path.'com_fwgallerylighttmplsimple');
			JFolder::delete($source_path.'com_fwgallerylighttmplsimple');
		}
	}
}
