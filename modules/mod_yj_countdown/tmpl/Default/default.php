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
defined('_JEXEC') or die('Restricted access');?>
<!-- http://www.Youjoomla.com  Youjoomla YJ Countdown for Joomla! 2.5  and UP starts here -->
<div class="yjCounter yjCounterid<?php echo $yjcdID?>">
	<?php if (!empty($counterPreText) ) { ?>
	<div class="yjCounterPretext"><?php echo $counterPreText ?></div>
	<?php } ?>
	<div class="yjcountdown_holder yjcid<?php echo $yjcdID?>"></div>
	<?php if (!empty($counterPostText) ) { ?>
	<div class="yjCounterPosttext"><?php echo $counterPostText ?></div>
	<?php } ?>
</div>
