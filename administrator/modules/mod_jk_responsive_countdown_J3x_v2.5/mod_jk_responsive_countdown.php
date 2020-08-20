<?php
		 /*
		# JK Responsive Countdown Module by JoomlaKave.com
		# --------------------------------------------
		# Author - Emdadul Shikder for Joomlakave http://www.joomlakave.com
		# Copyright (C)2014 JoomlaKave.com. All Rights Reserved.
		# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
		# Website: http://www.joomlakave.com
		*/

    // no direct access
    defined('_JEXEC') or die('Restricted access');
	error_reporting(0);
    //Parameters
    $ID 				= $module->id;
    $document 			= JFactory::getDocument();
	// 
	$daycolor				= $params->get('daycolor', "#fd4239");
	$hrcolor				= $params->get('hrcolor', "#490e6f");
	$mincolor				= $params->get('mincolor', "#00c4df");
	$seccolor				= $params->get('seccolor', "#55D15D");
	$countdown_font		    = $params->get('countdown_font', "18");
	$background_width		= $params->get('background_width', "40");
	$background_height		= $params->get('background_height', "40");
	$pre_text_fontssd		    = $params->get('pre_text_fontssd', "22");
	$pretexts_color		    = $params->get('pretexts_color', "#555");
	$post_text_fontssd		    = $params->get('post_text_fontssd', "20");
	$posttext_color		    = $params->get('posttext_color', "#555");
	$container_width		= $params->get('container_width', "40%");
	$container_color		= $params->get('containercolor', "#dddddd");
	$css 					= ".jk_countdown_days {background:{$daycolor}}";
	$css 					.= ".jk_countdown_hours {background:{$hrcolor}}";
	$css 					.= ".jk_countdown_mins {background:{$mincolor}}";
	$css 					.= ".jk_countdown_secs {background:{$seccolor}}";
	$css 					.= ".jk_countdown_days, .jk_countdown_hours, .jk_countdown_mins, .jk_countdown_secs{width:{$background_width}px !important;height:{$background_height}px !important;font-size:{$countdown_font}px !important;}";
	$css 					.= ".jk_countdown_pre_text {font-size:{$pre_text_font}}";
	$css 					.= ".jk_countdown_post_text {font-size:{$post_text_font}}";
	$css 					.= ".jk_countdown {width:{$container_width} !important;background:{$container_color} !important;}";
	
	$document->addStyledeclaration($css);
    $document->addStylesheet(JURI::base(true) . '/modules/mod_jk_responsive_countdown/assets/css/jk_responsive_countdown.css');
	JHTML::_('behavior.framework', true);
    //require(JModuleHelper::getLayoutPath(basename(dirname(__FILE__)))); 
	require(JModuleHelper::getLayoutPath('mod_jk_responsive_countdown', $params->get('layout', 'default')));
?>

<script type="text/javascript">
//<![CDATA[
    window.addEvent('domready', function() {
            function calcage(secs, num1, num2, starthtml, endhtml, singular, plural) {
                s = ((Math.floor(secs/num1))%num2).toString();
                z = ((Math.floor(secs/num1))%num2);
                if (LeadingZero && s.length < 2)
                    {
                    s = "0" + s;
                }
                return starthtml 
                + '<div class="jk_countdown_int"> '
                + s + '</div>' 
                +  '<div class="jk_countdown_string"> '
                + ((z<=1)?singular:plural) 
                + '</div>' 
                + endhtml;
            }

            function CountBack(secs) {
                if (secs < 0) {
                    document.getElementById("jk_countdown_cntdwn<?php echo $module->id?>").innerHTML = '<div class="jk_countdown_finishtext">'+FinishMessage+'</div>';
                    return;
                }
                DisplayStr = DisplayFormat.replace(/%%D%%/g, calcage(secs,86400,100000, 
                        '<div class="jk_countdown_days">','</div>',' <?php echo $params->get("day_text","Day")?>', ' <?php echo $params->get("day_text","Day")?>s'));
                DisplayStr = DisplayStr.replace(/%%H%%/g, calcage(secs,3600,24, 
                        '<div class="jk_countdown_hours">','</div>',' <?php echo $params->get("hr_text","Hr")?>', ' <?php echo $params->get("hr_text","Hr")?>s'));
                DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs,60,60, 
                        '<div class="jk_countdown_mins">','</div>', ' <?php echo $params->get("min_text","Min")?>', ' <?php echo $params->get("min_text","Min")?>s'));
                DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs,1,60, 
                        '<div class="jk_countdown_secs">','</div>', ' <?php echo $params->get("sec_text","Sec")?>', " <?php echo $params->get("sec_text","Sec")?>s"));

                document.getElementById("jk_countdown_cntdwn<?php echo $module->id?>").innerHTML = DisplayStr;
                if (CountActive)
                    setTimeout(function(){

                        CountBack((secs+CountStepper))  

                    }, SetTimeOutPeriod);
            }

            function putspan(backcolor, forecolor) {
            
            }

            if (typeof(BackColor)=="undefined")
                BackColor = "";
            if (typeof(ForeColor)=="undefined")
                ForeColor= "";
            if (typeof(TargetDate)=="undefined")
                TargetDate = "<?php echo $params->get("date_start","12/31/2020") .' '. $params->get("time","12:00 AM")?>";
            if (typeof(DisplayFormat)=="undefined")
                DisplayFormat = "%%D%%  %%H%%  %%M%%  %%S%% ";
            if (typeof(CountActive)=="undefined")
                CountActive = true;
            if (typeof(FinishMessage)=="undefined")
                FinishMessage = "<?php echo $params->get("finish_text","")?>";
            if (typeof(CountStepper)!="number")
                CountStepper = -1;
            if (typeof(LeadingZero)=="undefined")
                LeadingZero = true;

            CountStepper = Math.ceil(CountStepper);
            if (CountStepper == 0)
                CountActive = false;
            var SetTimeOutPeriod = (Math.abs(CountStepper)-1)*1000 + 990;
            putspan(BackColor, ForeColor);
            var dthen = new Date(TargetDate);
            var dnow = new Date();
            if(CountStepper>0)
                ddiff = new Date(dnow-dthen);
            else
                ddiff = new Date(dthen-dnow);
            gsecs = Math.floor(ddiff.valueOf()/1000);
            CountBack(gsecs);
    });
//]]>	
</script>