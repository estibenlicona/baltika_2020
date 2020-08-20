<?php
/*------------------------------------------------------------------------
# JoomSport Professional 
# ------------------------------------------------------------------------
# BearDev development company 
# Copyright (C) 2011 JoomSport.com. All Rights Reserved.
# @license - http://joomsport.com/news/license.html GNU/GPL
# Websites: http://www.JoomSport.com 
# Technical Support:  Forum - http://joomsport.com/helpdesk/
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die;
$row = $this->row;
$lists = $this->lists;
JHTML::_('behavior.tooltip');
        require_once JPATH_ROOT.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_joomsport'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'tabs.php';
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');        $etabs = new esTabs();

        ?>
		<script type="text/javascript">
		<!--
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
            var reg = /^\s+$/;
			 if(pressbutton == 'person_apply' || pressbutton == 'person_save' || pressbutton == 'person_save_new'){
                    jQuery('input[name="jsgallery"]').val(JSON.stringify(jQuery('input[name^="filnm"]').serializeArray()));

                        if(form.first_name.value == '' || form.last_name.value == '' ){
			 		alert('<?php echo JText::_('BLBE_JSNOTICEPL'); ?>');
			 	}else{
					
                    
                     if(reg.test(form.first_name.value) || reg.test(form.last_name.value)){
                         alert('<?php echo JText::_('BLBE_JSNOTICEPL'); ?>');
                     }else{
                         submitform( pressbutton );
                         return;
                     }
			 	}
			 }else{
				submitform( pressbutton );
					return;
			 }		
		}	
		
		 
		//-->
		</script>
		<?php
        if (!count($row)) {
            echo "<div id='system-message'>".JText::_('BLBE_NOITEMS').'</div>';
        }
        ?>
<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    
       
    <div id="main_pl_div" class="tabdiv">
        <div class="jsrespdiv12">
            <div class="jsBepanel">
                <div class="jsBEheader">
                    <?php echo JText::_('BLBE_GENERAL'); ?>
                </div>
                <div class="jsBEsettings">
                    <table class="jsbetable">
			
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_FIRSTNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
                                </td>
				<td>
					<input type="text" maxlength="255" size="60" name="first_name" value="<?php echo htmlspecialchars($row->first_name)?>" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['first_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['first_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][first_name]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_LASTNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
					
                                </td>
				<td>
					<input type="text" maxlength="255" size="60" name="last_name" value="<?php echo htmlspecialchars($row->last_name)?>" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['last_name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['last_name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][last_name]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_PERSON_CATEGORY'); ?>
					
                                </td>
				<td>
					<?php echo $lists['category'];?>
				</td>
			</tr>
			<tr>
				<td width="200">
					<?php echo JText::_('BLBE_PERSON_ABOUT'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
                                </td>
				<td>
					<?php echo $editor->display('about',  htmlspecialchars($row->about, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));  ?>
                                    <?php
                                    if(count($lists['languages'])){
                                        echo '<div class="jsTranslationContainer">';
                                        foreach (($lists['languages']) as $value) {
                                            echo '<div class="jsTranslationDiv">';
                                            $translation = '';
                                            echo $value;
                                            if(isset($lists['translation']) && isset($lists['translation'][$value]['about'])){
                                                $translation = $lists['translation'][$value]['about'];
                                            }
                                            echo $editor->display('translation['.$value.'][about]',  htmlspecialchars($translation, ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                            
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                                </td>
			</tr>
		</table>
            </div>
        </div>
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('BLBE_MENAF'); ?>
            </div>
            <div class="jsBEsettings">
                <?php
                if (count($this->lists['ext_fields'])) {
                    ?>
                <table  class='jsTableEqual'>
                    <?php
                    for ($p = 0;$p < count($this->lists['ext_fields']);++$p) {
                        if ($this->lists['ext_fields'][$p]->field_type == '3' && !isset($this->lists['ext_fields'][$p]->selvals)) {
                        } else {
                            ?>
                    <tr>
                            <td width="200">
                                    <?php echo $this->lists['ext_fields'][$p]->name;
                            ?>
                            </td>
                            <td>
                                    <?php

                                            switch ($lists['ext_fields'][$p]->field_type) {

                                                    case '1':    echo $lists['ext_fields'][$p]->selvals;
                                                                            break;
                                                    case '2':    echo $editor->display('extraf['.$lists['ext_fields'][$p]->id.']',  htmlspecialchars(isset($lists['ext_fields'][$p]->fvalue_text) ? ($lists['ext_fields'][$p]->fvalue_text) : '', ENT_QUOTES), '550', '300', '60', '20', array('pagebreak', 'readmore'));
                                                                            break;
                                                    case '5':                    
                                                    case '3':    echo $lists['ext_fields'][$p]->selvals;
                                                                            break;
                                                    case '0':
                                                    default:    echo '<input type="text" maxlength="255" size="60" name="extraf['.$lists['ext_fields'][$p]->id.']" value="'.(isset($lists['ext_fields'][$p]->fvalue) ? htmlspecialchars($lists['ext_fields'][$p]->fvalue) : '').'" />';
                                                                            break;

                                            }
                            ?>
                                    <input type="hidden" name="extra_ftype[<?php echo $lists['ext_fields'][$p]->id;
                            ?>]" value="<?php echo $lists['ext_fields'][$p]->field_type?>" />
                                    <input type="hidden" name="extra_id[<?php echo $lists['ext_fields'][$p]->id;
                            ?>]" value="<?php echo $lists['ext_fields'][$p]->id?>" />
                            </td>
                    </tr>
                    <?php	
                        }
                    }
                    ?>
                </table>
                <?php

                } else {
                    echo JText::_('BLBE_EXTRAFIELDS_NOTIF');
                }
                ?>
            </div>
        </div>
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('BLBE_HEAD_IMAGES'); ?>
            </div>
            <div class="jsBEsettings">

		<?php
                    require_once 'components/com_joomsport/helpers/images.php';
                    echo ImagesHelper::loaderUI($lists['photos'], $row->def_img);
                ?>   
            </div>
        </div>
        </div> 
    </div>    

		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="person_edit" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="cid[]" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="jscurtab" id="jscurtab" value="" />
                <input type="hidden" name="jsgallery" value="" />
		<?php echo JHTML::_('form.token'); ?>
	</form>