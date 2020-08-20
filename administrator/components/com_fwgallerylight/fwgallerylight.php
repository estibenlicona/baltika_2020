<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );
error_reporting(E_ALL);
JHTML::stylesheet('administrator/components/com_fwgallerylight/assets/css/styles.css');
JHTML::addIncludePath(JPATH_COMPONENT_SITE.'/helpers');
JHTML::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');

require_once(JPATH_COMPONENT_SITE.'/helpers/helper.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/view.php');
require_once(JPATH_COMPONENT.'/controller.php');

$input = JFactory::getApplication()->input;
$controller = JControllerLegacy::getInstance('fwGallerylight');
$controller->execute($input->getCmd('task'));
$controller->redirect();
