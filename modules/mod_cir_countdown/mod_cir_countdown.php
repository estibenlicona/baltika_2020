<?php
	 /*
	# Circular Countdown Module by JoomlaKave.com
	# --------------------------------------------
	# Author - Ahsan Ahmed Shovon for Joomlakave http://www.joomlakave.com
	# Copyright (C)2015 JoomlaKave.com. All Rights Reserved.
	# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	# Website: http://www.joomlakave.com
	*/
    // no direct access
	
    defined('_JEXEC') or die('Restricted access');

    $document 			= JFactory::getDocument();
	 

$cdowntime                  = $params->get('cdowntime','2017,04,10,"18:00"'); 
$daycolor                   = $params->get('daycolor','#f2a918');
$hourcolor                  = $params->get('hourcolor','#e02878');
$mincolor                   = $params->get('mincolor','#0bcf5c');
$seccolor                   = $params->get('seccolor','#ff8052');
$daycolor2                  = $params->get('daycolor2','#a7e8f9');
$hourcolor2                 = $params->get('hourcolor2','#a7e8f9');
$mincolor2                  = $params->get('mincolor2','#a7e8f9');
$seccolor2                  = $params->get('seccolor2','#a7e8f9');
$ourevent                   = $params->get('ourevent','');
$show_button                = $params->get('show_button','0');
$button_link                = $params->get('button_link','');
$button_text                = $params->get('button_text','More Here');
$headcolor                  = $params->get('headcolor', '#f0a911');
$backcolor                  = $params->get('backcolor','#000000');
$htsize                     = $params->get('htsize','30');
$talign                     = $params->get('talign','center');
$secshow                    = $params->get('secshow','1');
$cdownpos                   = $params->get('cdownpos','80');
$cirthickness               = $params->get('cirthickness','.08');
$cirthickness2              = $params->get('cirthickness2','.08');
$bualign                    = $params->get('bualign','center');


	
    $document->addScript(JURI::base(true) . '/modules/mod_cir_countdown/assets/css/jquery-1.11.1.min.js');
    $document->addScript(JURI::base(true) . '/modules/mod_cir_countdown/assets/css/init.js');
    $document->addScript(JURI::base(true) . '/modules/mod_cir_countdown/assets/css/jquery.ccountdown.js'); 
    $document->addScript(JURI::base(true) . '/modules/mod_cir_countdown/assets/css/jquery.knob.js'); 
    
    JHTML::_('behavior.framework', true);
    


require(JModuleHelper::getLayoutPath('mod_cir_countdown', $params->get('layout', 'default')));

?>

