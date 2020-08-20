<?php
/*-------------------------------------------------------------------------------
# mod_popupaholic - popup model box for Joomla 3.x v1.3.1
# -------------------------------------------------------------------------------
# author    GraphicAholic
# copyright Copyright (C) 2016 GraphicAholic.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.graphicaholic.com
--------------------------------------------------------------------------------*/
// No direct access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$path = $params->get('path');
?>	
<?php if($loadjQuery == "1"):?>	
<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<?php endif ?>	
<?php if($loadScripts == "2"):?>	
<script src="<?php echo $modbase ?>js/jquery.gafancybox.min.js"></script>
<script src="<?php echo $modbase ?>js/jquery.gafancybox-media.min.js"></script>
<script src="<?php echo $modbase ?>js/jquery.popup.js"></script>
<script src="<?php echo $modbase ?>js/popper.js"></script>
<script src="<?php echo $modbase ?>js/jquery-cookie.min.js"></script>
<?php endif ?>	
<style>
.gafancybox-lock{overflow:hidden !important}
#gafancybox-overlay<?php echo $moduleID ?> {
	background: <?php echo $params->get('backgroundOverlay') ?>;
	opacity: <?php echo $params->get('backgroundOpacity') ?>;
}
#gafancybox-skin<?php echo $moduleID ?>{
background: <?php echo $params->get('boxBackground') ?>;/*popup background color*/
color: <?php echo $params->get('boxFont') ?>;/*popup font color*/
border-radius:<?php echo $params->get('boxRadius') ?>px;/*popup border radius*/
<?php if($boxBorder == "1"):?>	
border: <?php echo $params->get('borderSize') ?>px <?php echo $params->get('borderType') ?> <?php echo $params->get('borderColor') ?>;	
<?php endif ?>	
<?php if($boxShadow == "1"):?>	
-webkit-box-shadow: 0px 0px 19px 6px <?php echo $params->get('boxShadowOpacity') ?>;
-moz-box-shadow: 0px 0px 19px 6px <?php echo $params->get('boxShadowOpacity') ?>;
box-shadow: 0px 0px 19px 6px <?php echo $params->get('boxShadowOpacity') ?>;
<?php endif ?>	
}
<?php if($closeButton == "1"):?>	
#gafancybox-close<?php echo $moduleID ?>{
right: -18px;
background:url('<?php echo $modbase ?>images/closeButton.png');
}
<?php endif ?>	
	.close-notify {
		color: #ffffff!important;
	}
    #header_message<?php echo $moduleID ?>{  
		display:none;
		text-align: left;
		font-size: 13px;
		color: #ffffff!important;
		background: none repeat scroll 0 0 rgba(50, 49, 59, 0.7);
		box-shadow: 2px 2px 2px #cccccc;
    }
</style>	
<script type="text/javascript">
var myCookie =  jQuery.cookie('popup_aholic<?php echo $moduleID ?>');    
    <?php if($cookieExpire == "inMinutes"):?>
    var inMinutes = new Date(new Date().getTime() + <?php echo $params->get('cookieTime') ?> * 60 * 1000);
    <?php endif ?>    
    <?php if($cookieExpire == "inHours"):?>
    var inHours = new Date(new Date().getTime() + <?php echo $params->get('cookieTime') ?> * 60 * 60 * 1000);
    <?php endif ?>    
    <?php if($cookieExpire == "inDays"):?>
    var inDays = new Date(new Date().getTime() + <?php echo $params->get('cookieTime') ?> * 60 * 60 * 60 * 1000);
    <?php endif ?>    
    <?php if($cookieExpire == "noCookie"):?>
    var noCookie = new Date(new Date().getTime() + 0 * 60 * 1000);
    <?php endif ?>    
		if (myCookie != 1){
        jQuery(document).ready(function() { setTimeout(function () { jQuery('.popup-aholic<?php echo $moduleID ?>').gafancybox({
		padding : <?php echo $params->get('4cornerMargin') ?>,
		margin  : <?php echo $params->get('4cornerMargin') ?>,
		modal: false,
		autoSize  : <?php echo $params->get('autoResponsive') ?>,    
        openEffect	: '<?php echo $params->get('effectsIn') ?>', //elastic, fade or none
        openSpeed   : <?php echo $params->get('effectSpeed') ?>,    
        closeEffect	: '<?php echo $params->get('effectsOut') ?>', //elastic, fade or none
        closeSpeed   : <?php echo $params->get('effectSpeed') ?>,    
		<?php if($autoResponsive == "false"):?>
		autoHeight : false,
		autoWidth : false,
		width     : <?php echo $params->get('boxWidth') ?>,
		maxHeight : <?php echo $params->get('boxHeight') ?>,	
		closeClick  : false,
		<?php endif ?>	
		<?php if($autoResponsive == "true"):?>
		scrolling: 'no',
		autoHeight : true,
		autoWidth : true,	
		<?php endif ?>	
		tpl: {
				overlay  : '<div class="gafancybox-overlay" id="gafancybox-overlay<?php echo $moduleID ?>"></div>',
				wrap     : '<div class="gafancybox-wrap" tabIndex="-1"><div id="gafancybox-skin<?php echo $moduleID ?>" class="gafancybox-skin"><div class="gafancybox-outer"><div class="gafancybox-inner"></div></div></div></div>',
				closeBtn : '<a title="Close" id="gafancybox-close<?php echo $moduleID ?>" class="gafancybox-item gafancybox-close" href="javascript:;"></a>',
			},
		helpers: {  overlay: { closeClick: <?php echo $params->get('closeOnlyX') ?>, title: false, lbwrap: '<div class="gafancybox-overlay" id="gafancybox-overlay<?php echo $moduleID ?>"></div>',opacity: 0.5}} ,afterShow: function(){setTimeout('parent.jQuery.gafancybox.close ()',<?php echo $params->get('boxTimer')*1000 ?>);}
}).trigger('click');}, <?php echo $setDelayTime *1000 ?>);
            
        jQuery('a.fancy-closeBTN').click(function(e){
            e.preventDefault();
            jQuery.gafancybox.close();
        });
            
    });
    
    jQuery.cookie('popup_aholic<?php echo $moduleID ?>', '1', { expires: <?php echo $params->get('cookieExpire') ?> }); 
}
	</script>
<div id="popup_aholic"></div>	
<script type="text/javascript">
function killOverlay() {	
  refID = document.getElementById('popup_aholic');
	refID.style.display = "none";	
}
function hidestuff(boxid){
   document.getElementById(boxid).style.visibility="hidden";
}
</script>	
	<a class="popup-aholic<?php echo $moduleID ?>" href="#inline-auto<?php echo $moduleID ?>"></a>
		<div style="display:none;">
			<div id="inline-auto<?php echo $moduleID ?>" >
				<?php if($boxSelection == "1"):?>
				<?php echo $module->content; ?>
				<?php endif ?>	
				<?php if($boxSelection == "2"):?>
				<a class="popup-aholic<?php echo $moduleID ?> gafancybox.iframe" href="<?php echo $params->get('boxFile') ?>"></a>	
				<?php endif ?>	
            </div>
        </div>