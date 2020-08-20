<?php
/*-------------------------------------------------------------------------------	
# mod_popupaholic - Popup model box for Joomla 3.x v1.3.1
# -------------------------------------------------------------------------------	
# author    GraphicAholic	
# copyright Copyright (C) 2016 GraphicAholic.com. All Rights Reserved.	
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL	
# Websites: http://www.graphicaholic.com	
--------------------------------------------------------------------------------*/	
// No direct access	
defined('_JEXEC') or die('Restricted access');	
defined('DS') or define('DS', DIRECTORY_SEPARATOR);	
JHtml::_('bootstrap.framework');	
// Import the file / foldersystem	
jimport('joomla.filesystem.file');	
jimport('joomla.filesystem.folder');	
$LiveSite 	= JURI::base();	
$document = JFactory::getDocument();	
$modbase = JURI::base(true).'/modules/mod_popupaholic/';
$loadjQuery = $params->get('loadjQuery');	
$loadScripts = $params->get('loadScripts');	
if($loadScripts == 1) {
$document->addScript ($modbase.'js/jquery.gafancybox.min.js');	
$document->addScript ($modbase.'js/jquery.gafancybox-media.min.js');	
$document->addScript ($modbase.'js/jquery.popup.js');	
$document->addScript ($modbase.'js/popper.js');	
$document->addScript ($modbase.'js/jquery-cookie.min.js');
}
$document->addStyleSheet($modbase.'css/jquery.gafancybox.min.css');	
$moduleID = $module->id;	
JPluginHelper::importPlugin('content');	
$module->content = JHtml::_('content.prepare', $module->content, '', 'mod_popupaholic');	
$closeButton = $params->get('closeButton');
$boxSelection = $params->get('boxSelection');	
$boxBorder = $params->get('boxBorder');	
$boxShadow = $params->get('boxShadow');	
$autoResponsive = $params->get('autoResponsive');	
$setDelay = (int) $params->get('setDelay');	
if($setDelay == 1) {	
$setDelayTime = (int) $params->get('setDelayValue');	
} else {	
$setDelayTime = 0;	
}	
$cookieExpire = $params->get('cookieExpire');
$setDelayValue = (int) $params->get('setDelayValue', 0);	
$gauseCookie = (int) $params->get( 'gauseCookie', 0 );	
require JModuleHelper::getLayoutPath('mod_popupaholic','default',$params);	
?>	