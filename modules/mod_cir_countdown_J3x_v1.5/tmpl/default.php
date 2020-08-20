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
    defined( '_JEXEC' ) or die( 'Restricted access' );


	$document 			= JFactory::getDocument();
	JHTML::_('behavior.framework', true);
	JHtml::_('jquery.framework');
	
	
	
  $document->addScript(JURI::base(true) . '/modules/mod_cir_countdown/assets/css/init.js');
  $document->addScript(JURI::base(true) . '/modules/mod_cir_countdown/assets/css/jquery.ccountdown.js'); 
  $document->addScript(JURI::base(true) . '/modules/mod_cir_countdown/assets/css/jquery.knob.js'); 
    
	
		$style      = '';
			
	
		$document->addStyleSheet(JURI::base(true) . '/modules/mod_cir_countdown/assets/css/bootstrap.min.css'); 
		
		$document->addStyleDeclaration($style);
	
	

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
$headcolor                  = $params->get('headcolor', '#f0a911');
$show_button                = $params->get('show_button','0');
$button_link                = $params->get('button_link','');
$button_text                = $params->get('button_text','More Here');
$backcolor                  = $params->get('backcolor','#000000');
$htsize                     = $params->get('htsize','30');
$talign                     = $params->get('talign','center');
$secshow                    = $params->get('secshow','1');
$cdownpos                   = $params->get('cdownpos','80');
$cirthickness               = $params->get('cirthickness','.08');
$cirthickness2              = $params->get('cirthickness2','.08');
$bualign                    = $params->get('bualign','center');
?>



              

       
				
			
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:<?php echo $backcolor ?>;
				padding:0px 40px 30px 40px; text-align:<?php echo $talign; ?>; color:<?php echo $headcolor; ?>;">
					<h1 style="font-size:<?php echo $htsize; ?>px;"> <?php echo $ourevent; ?> </h1>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-color:<?php echo $backcolor; ?>; padding:20px 0px 35px 40px;">
					<div class="col-md-3 col-sm-3" style="//background-color:#fff; padding:0px !important;">
						<div class="col-lg-12" style="//background-color:#ccc; height:170px; padding:0px !important;">
							  <div class="ccounter"  >
									   <input class="knob days" data-width="160" data-min="0" data-max="365" data-displayPrevious=true data-fgColor="<?php echo $daycolor;?>" data-readOnly="true" value="1" data-thickness="<?php echo $cirthickness; ?>">
							   </div>
						
						</div>
						<div class="col-lg-12" style="//background-color:#999;">
							<h3 style="margin-left:40px; color:<?php echo $daycolor?>;"> Days </h3> 						
						</div>
					
					</div>
					
					<div class="col-md-3 col-sm-3" style="//background-color:#666; padding:0px !important;">
						<div class="col-lg-12" style="//background-color:#ccc; height:170px; padding:0px !important;">
							  <div class="ccounter"  >
									   <input class="knob hour" data-width="160" data-min="0" data-max="24" data-displayPrevious=true data-fgColor="<?php echo $hourcolor;?>" data-readOnly="true" value="1" data-thickness="<?php echo $cirthickness; ?>">
							   </div>
						
						</div>
						<div class="col-lg-12" style="//background-color:#999;">
							<h3 style="margin-left:33px; color:<?php echo $hourcolor?>;"> Hours </h3> 						
						</div>
					
					</div>
					
					<div class="col-md-3 col-sm-3" style="//background-color:#fff; padding:0px !important;">
						<div class="col-lg-12" style="//background-color:#ccc; height:170px; padding:0px !important;">
							  <div class="ccounter"  >
													   
								  <input class="knob minute" data-width="160" data-min="0" data-max="60" data-displayPrevious=true data-fgColor="<?php echo $mincolor;?>" data-readOnly="true" value="1" data-thickness="<?php echo $cirthickness; ?>">
				  
							   </div>
						
						</div>
						<div class="col-lg-12" style="//background-color:#999;">
							<h3 style="margin-left:23px; color:<?php echo $mincolor?>;"> Minutes </h3> 						
						</div>
					
					</div>
					 <?php if ($secshow==1){?>
					<div class="col-md-3 col-sm-3" style="//background-color:#fff; padding:0px !important;">
						<div class="col-lg-12" style="//background-color:#ccc; height:170px; padding:0px !important;">
							  <div class="ccounter"  >
									 
									  <input class="knob second" data-width="160" data-min="0" data-max="60" data-displayPrevious=true data-fgColor="<?php echo $seccolor;?>" data-readOnly="true" value="0" data-thickness="<?php echo $cirthickness; ?>">
									  
							   </div>
						
						</div>
						<div class="col-lg-12" style="//background-color:#999;">
							<h3 style="margin-left:22px; color:<?php echo $seccolor?>;"> Seconds </h3> 						
						</div>
					
					</div>
					<?php }?>
					
				
				
				</div>
                <br />
         

<script type="text/javascript">
      jQuery(".ccounter").ccountdown(<?php echo $cdowntime;?>);
</script>

