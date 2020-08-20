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

// BEGIN: Loading plugin language file
$lang = JFactory::getLanguage();
$current_lang_tag = $lang->getTag();
$lang = JFactory::getLanguage();
$extension = 'plg_system_web357framework';
$base_dir = JPATH_ADMINISTRATOR;
$language_tag = (!empty($current_lang_tag)) ? $current_lang_tag : 'en-GB';
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);
// END: Loading plugin language file

 // Check if extension=php_curl.dll is enabled in PHP
function isCurl(){
	if (function_exists('curl_version')):
		return true;
	else:
		return false;
	endif;
}

// Check if allow_url_fopen is enabled in PHP
function allowUrlFopen(){
	if(ini_get('allow_url_fopen')):
		return true;
	else:
		return false;
	endif;
}