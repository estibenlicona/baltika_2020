<?php
/*======================================================================*\
|| #################################################################### ||
|| # Package - YJ Counter										        ||
|| # Copyright (C) since 2007  Youjoomla.com . All Rights Reserved.     ||
|| # license - PHP files are licensed under  GNU/GPL V2                 ||
|| # license - CSS  - JS - IMAGE files  are Copyrighted material        ||
|| # bound by Proprietary License of Youjoomla.com                      ||
|| # for more information visit http://www.youjoomla.com/license.html   ||
|| # Redistribution and  modification of this software                  ||
|| # is bounded by its licenses                                         ||
|| # websites - http://www.youjoomla.com | http://www.yjsimplegrid.com  ||
|| #################################################################### ||
\*======================================================================*/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');


$module_template 		=  		$params->get   ('module_template','Default');
$counterPreText   		= 		$params->get('counterPreText');
$counterPostText     	=  		$params->get('counterPostText');
$yjcdID					= 		$module->id;


$getYJCounter = modYJCountdownHelper::getYJCounter($params,$yjcdID);
require(JModuleHelper::getLayoutPath('mod_yj_countdown',''.$module_template.'/default'));
?>