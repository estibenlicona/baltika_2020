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

JHtml::_('behavior.framework',true);
$document =  JFactory::getDocument();


class modYJCountdownHelper
{
	static function getYJCounter(&$params,$yjcdID)
	{


			$counterBorderSize   			= 		$params->get('counterBorderSize');
			$counterBorderColor   			= 		$params->get('counterBorderColor');
			$counterBackgroundColor   		= 		$params->get('counterBackgroundColor');
			$counterBigFontColor			= 		$params->get('counterBigFontColor');
			$counterSmallFontColor			= 		$params->get('counterSmallFontColor');
			$yjcountdownDate				= 		$params->get('yjcountdownDate');
			$loadjq							= 		$params->get('loadjq');
			$jqversion						= 		$params->get('jqversion'); 
			$loadLang						= 		$params->get('loadLang'); 
			
			
			$document =  JFactory::getDocument();
			$document->addStyleSheet(JURI::base() . 'modules/mod_yj_countdown/css/stylesheet.css');
			if($loadjq == 1){
				$document->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/'.$jqversion.'/jquery-ui.min.js');
			}
			$document->addScript(JURI::base() . 'modules/mod_yj_countdown/src/yjcountdown.js');
			if($loadLang !='selectlang'){
				$document->addScript(JURI::base() . 'modules/mod_yj_countdown/src/languages/jquery.countdown-'.$loadLang.'.js');
			}
			

			
			
			$splitDate 	= explode('-', $yjcountdownDate);
			$month 		= $splitDate[0];
			$day 		= $splitDate[1];
			$year 		= $splitDate[2];
			
			  
			$document->addScriptDeclaration("
					var \$YJCD = jQuery.noConflict();
					\$YJCD(function () {
						\$YJCD('.yjcid".$yjcdID."').countdown({until: new Date(".$year.",".($month)."-1,".$day.")});
					});
			");


			$document->addStyleDeclaration(".yjCounterid".$yjcdID." .countdown_section{background:".$counterBackgroundColor.";border:".$counterBorderSize." solid ".$counterBorderColor.";color:".$counterSmallFontColor.";}.yjCounterid".$yjcdID." .countdown_amount{color:".$counterBigFontColor.";}");

	}
}

?>