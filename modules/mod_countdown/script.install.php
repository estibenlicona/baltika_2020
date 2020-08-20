<?php
/* ======================================================
# Countdown for Joomla! - v3.2.1
# -------------------------------------------------------
# For Joomla! CMS
# Author: Web357 (Yiannis Christodoulou)
# Copyright (©) 2009-2019 Web357. All rights reserved.
# License: GNU/GPLv3, http://www.gnu.org/licenses/gpl-3.0.html
# Website: https:/www.web357.com/
# Demo: https://demo.web357.com/?item=countdown
# Support: support@web357.com
# Last modified: 16 Jan 2019, 14:51:55
========================================================= */

defined('_JEXEC') or die;

require_once __DIR__ . '/script.install.helper.php';

class Mod_CountdownInstallerScript extends Mod_CountdownInstallerScriptHelper
{
	public $name           	= 'Countdown';
	public $alias          	= 'countdown';
	public $extension_type 	= 'module';
	public $module_position = 'web357';
}