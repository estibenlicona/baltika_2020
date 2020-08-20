<?php
/* ======================================================
# Web357 Framework for Joomla! - v1.7.6 (Free version)
# -------------------------------------------------------
# For Joomla! CMS
# Author: Web357 (Yiannis Christodoulou)
# Copyright (©) 2009-2019 Web357. All rights reserved.
# License: GPLv2 or later, http://www.gnu.org/licenses/gpl-2.0.html
# Website: https:/www.web357.com/
# Demo: https://demo.web357.com/joomla/web357framework
# Support: support@web357.com
# Last modified: 23 Apr 2019, 11:29:38
========================================================= */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

if ( ! class_exists('PlgSystemWeb357frameworkInstallerScript'))
{
	require_once __DIR__ . '/script.install.helper.php';

	class PlgSystemWeb357frameworkInstallerScript extends PlgSystemWeb357frameworkInstallerScriptHelper
	{
		public $name           = 'Web357 Framework';
		public $alias          = 'web357framework';
		public $extension_type = 'plugin';

		public function onBeforeInstall($route)
		{
			if ( ! $this->isNewer())
			{
				$this->softbreak = true;

				return false;
			}

			return true;
		}

		public function onAfterInstall($route)
		{
			$this->deleteOldFiles();
		}

		private function deleteOldFiles()
		{
			JFile::delete(array(JPATH_SITE . '/plugins/system/web357framework/web357framework.script.php'));
		}
	}
}
