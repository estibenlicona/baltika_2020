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

// get helper
require_once(dirname(__FILE__).'/helper.php');
$modWeb357CountDownHelper = new modWeb357CountDownHelper();

// Default Vars
jimport('joomla.environment.uri' );
$host = JURI::root();
$document = JFactory::getDocument();

// Parameters
$layout = $params->get('layout', 'default');
$date = $params->get('date', '14-06-2018');
$time = $params->get('time', '15:00');
$fronttext = $params->get('fronttext', 'The FIFA World Cup 2018 in Russia will start in');
$endtext = $params->get('endtext', '. Stay tunned!');
$finish = $params->get('finish', 'The FIFA World Cup 2018 in Russia has been started!');

$day = substr($date,0,2);
$month = substr($date,3,2);
$year = substr($date,6,4);
$hour = substr($time,0,2);
$minutes = substr($time,3,2);

$dateformat = $month.'/'.$day.'/'.$year.' '.$hour.':'.$minutes;

// Print Layout
require(JModuleHelper::getLayoutPath('mod_countdown', $layout));