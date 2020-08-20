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

defined( '_JEXEC' ) or die( 'Restricted access' ); 

// CSS Style from File
$document->addStyleSheetVersion($host.'modules/mod_countdown/tmpl/default.css');

// CSS Style from File for html template overrides
// $document->addStyleSheet($host.'templates/CURRENT_TEMPLATE_NAME/html/mod_countdown/default.css');

// Add Style Declaration
// Your inline css code goes here. 
// Example: .mod_countdown { border: 2px solid #ccc; padding: 15px; }
$css_styles = ''; 

// add Style Declaration
$css_styles = ''; // your inline css code goes here
$document->addStyleDeclaration($css_styles);
?>

<?php
if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/(?i)msie|trident|edge/",$_SERVER['HTTP_USER_AGENT'])) {
    // Browsers IE 8 and below
} else {
    // All other browsers
    ?>
    <div id='cntdwn<?php echo $module->id?>' class='mod_countdown'></div>
    <?php
}
?>
<script language="JavaScript" type="text/javascript">
TargetDate = "<?php echo $dateformat;?>";
CountActive = true;
CountStepper = -1;
LeadingZero = true; 
DisplayFormat<?php echo $module->id?> = "<?php echo $fronttext; ?> %%D%% <?php echo JText::_('MOD_COUNTDOWN_DAYS')?>, %%H%% <?php echo JText::_('MOD_COUNTDOWN_HOURS')?>, %%M%%:%%S%% <?php echo JText::_('MOD_COUNTDOWN_MINUTES'); ?><?php echo $endtext; ?>";
FinishMessage<?php echo $module->id?> = "<?php echo $finish; ?>";

/*
Author: Robert Hashemian
http://www.hashemian.com/

You can use this code in any manner so long as the author's
name, Web address and this disclaimer is kept intact.
********************************************************
*/

function calcage(secs, num1, num2) {
s = ((Math.floor(secs/num1))%num2).toString();
if (LeadingZero && s.length < 2)
    s = "0" + s;
return "<b>" + s + "</b>";
}

function CountBack<?php echo $module->id?>(secs) {
if (secs < 0) {
    document.getElementById("cntdwn<?php echo $module->id?>").innerHTML = FinishMessage<?php echo $module->id?>;
    return;
}

DisplayStr = DisplayFormat<?php echo $module->id?>.replace(/%%D%%/g, calcage(secs,86400,100000));
DisplayStr = DisplayStr.replace(/%%H%%/g, calcage(secs,3600,24));
DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs,60,60));
DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs,1,60));

document.getElementById("cntdwn<?php echo $module->id?>").innerHTML = DisplayStr;

if (CountActive)
    setTimeout("CountBack<?php echo $module->id?>(" + (secs+CountStepper) + ")", SetTimeOutPeriod);
}

function putspan<?php echo $module->id?>() {
document.write("<div id='cntdwn<?php echo $module->id?>' class='mod_countdown'></div>");
}

if (typeof(TargetDate)=="undefined")
TargetDate = "12/31/2020 5:00 AM";
if (typeof(DisplayFormat)=="undefined")
DisplayFormat = "%%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds.";
if (typeof(CountActive)=="undefined")
CountActive = true;
if (typeof(FinishMessage)=="undefined")
FinishMessage = "";
if (typeof(CountStepper)!="number")
CountStepper = -1;
if (typeof(LeadingZero)=="undefined")
LeadingZero = true;

CountStepper = Math.ceil(CountStepper);
if (CountStepper == 0)
CountActive = false;
var SetTimeOutPeriod = (Math.abs(CountStepper)-1)*1000 + 990;

<?php
if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/(?i)msie|trident|edge/",$_SERVER['HTTP_USER_AGENT'])) { ?>
    // Browsers IE 8 and below
    putspan<?php echo $module->id?>();
<?php
} else {
    // All other browsers
}
?>

var dthen = new Date(TargetDate);
var dnow = new Date();
if(CountStepper>0)
ddiff = new Date(dnow-dthen);
else
ddiff = new Date(dthen-dnow);
gsecs = Math.floor(ddiff.valueOf()/1000);
CountBack<?php echo $module->id?>(gsecs);
</script>