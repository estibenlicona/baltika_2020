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
    defined( '_JEXEC' ) or die( 'Restricted access' );
	error_reporting(0);
?>
<div class="row jk_countdown">
    <div class="jk_countdown_pre_text" style="font-size:<?php echo $pre_text_fontssd; ?>px !important; color:<?php echo $pretexts_color;?> !important;"><!-- Pre Text -->
		<?php echo $params->get('pre_text')?>
	</div>
    <div id="jk_countdown_cntdwn<?php echo $module->id; ?>" class="jk_countdown_container"><!-- Dynamically creates timer --></div><!-- Countdown Area -->
    <?php if(trim($params->get('post_text'))!=''): ?><!-- Post Text -->
		<div style="clear:both"></div>	
		<div class="jk_countdown_post_text" style="font-size:<?php echo $post_text_fontssd; ?>px; color:<?php echo $posttext_color;?>!important;">
			<?php echo $params->get('post_text')?>
		</div>
    <?php endif; ?>
    <?php if(trim($params->get('show_button'))==1): ?><!-- Button -->
        <div class="jk_countdown_button">
			<a class="button btn btn-default jk_countdown_button_link" href="<?php echo $params->get('button_link') ?>"><span><?php echo $params->get('button_text') ?></span></a>
		</div>
    <?php endif; ?>
	<div style="clear:both"></div>
</div>