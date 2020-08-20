<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

JHTML::addIncludePath(JPATH_COMPONENT_SITE.'/helpers');
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');

require_once(JPATH_COMPONENT.'/helpers/helper.php');
require_once(JPATH_COMPONENT.'/controller.php');

JFHelper :: loadStylesheet();
$controller = JControllerLegacy :: getInstance('fwGallerylight');
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
$controller->redirect();
