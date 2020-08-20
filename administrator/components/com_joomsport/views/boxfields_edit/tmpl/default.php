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
        ?>
		<script type="text/javascript">
		Joomla.submitbutton = function(task) {
			submitbutton(task);
		}
		function submitbutton(pressbutton) {
			var form = document.adminForm;
                        var reg = /^\s+$/;
			 if(pressbutton == 'boxfields_apply' || pressbutton == 'boxfields_save' || pressbutton == 'boxfields_save_new'){
                                if(form.name.value != '' && !reg.test(form.name.value)){
					
					
						submitform( pressbutton );
						return;
						
				}else{
					getObj('fldname').style.border = "1px solid red";
					alert("<?php echo JText::_('BLBE_JSMDNOT19');?>");
					
					
				}
			}else{
				submitform( pressbutton );
					return;
			}			
		}	
		function boxfield_hide(){
                    if(jQuery('input[name="complex"]:checked').val() == '1'){
                        jQuery('.jshideforcomposite').hide();
                    }else{
                        jQuery('.jshideforcomposite').show();
                    }    
                }
                function boxfield_type_hide(){
                    if(jQuery('input[name="ftype"]:checked').val() == '1'){
                        jQuery('.jshideforboxtype').show();
                    }else{
                        jQuery('.jshideforboxtype').hide();
                    }    
                }
                
                jQuery( document ).ready(function() {
                    boxfield_hide();
                    boxfield_type_hide();
                });    
		</script>
		<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm">
<div class="jsrespdiv8">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo JText::_('BLBE_GENERAL'); ?>
        </div>
        <div class="jsBEsettings">		
		<table>
			<tr>
				<td width="250">
					<?php echo JText::_('BLBE_FIELDNAME'); ?>
                                    <?php
                                    if(count($lists['languages'])){

                                        echo '<img src="'.JUri::base().'components/com_joomsport/img/multilanguage.png" class="jsMultilangIco" />';
                                    }?>
				</td>
				<td>
					<input type="text" maxlength="255" size="60" name="name" id="fldname" value="<?php echo htmlspecialchars($row->name)?>" onKeyPress="return disableEnterKey(event);" />
                                        <?php
                                        if(count($lists['languages'])){

                                            echo '<div class="jsTranslationContainer">';
                                            foreach (($lists['languages']) as $value) {
                                                echo '<div class="jsTranslationDiv">';
                                                $translation = '';

                                                if(isset($lists['translation']) && isset($lists['translation'][$value]['name'])){
                                                    $translation = htmlspecialchars($lists['translation'][$value]['name'], ENT_QUOTES);
                                                }
                                                echo '<input type="text" maxlength="255" size="60" name="translation['.$value.'][name]" value="'.addslashes($translation).'"/>';
                                                echo '  ' . $value;
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                        ?>
                                </td>
			</tr>
			
			<tr>
				<td width="250">
					<?php echo JText::_('BLBE_BOX_COMPOSITE'); ?>
				</td>
				<td>
                                    <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['complex'];?></fieldset></div>
                                </td>
			</tr>
                        <tr class="jshideforcomposite">
				<td width="250">
					<?php echo JText::_('BLBE_BOX_SELECT_PARENT'); ?>
				</td>
				<td>
                                    <?php echo $this->lists['parent'];?>
                                </td>
			</tr>
                        <tr class="jshideforcomposite">
				<td width="250">
					<?php echo JText::_('BLBE_BOX_TYPE'); ?>
				</td>
				
                                <td>
                                    <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['calctype'];?></fieldset></div>
                                </td>
			</tr>
                        <tr class="jshideforcomposite jshideforboxtype">
				<td width="250">
					<?php echo JText::_('BLBE_BOX_DEPEND'); ?>
				</td>
				<td>
                                    <?php echo $this->lists['depend1'];?>
                                    <?php echo $this->lists['calc'];?>
                                    <?php echo $this->lists['depend2'];?>
                                </td>
			</tr>
                        <?php
                        if($this->lists['efs2']){
                        ?>
                        <tr class="jshideforcomposite">
				<td width="250">
					<?php echo JText::_('BLBE_BOX_EFS'); ?>
				</td>
				<td>
                                    <?php echo $this->lists['efs2'];?>
                                </td>
			</tr>
                        <?php
                        }
                        ?>
		</table>
	
            </div>
        </div>
    </div>
    <div class="jsrespdiv4 jsrespmarginleft2">
        <div class="jsBepanel">
            <div class="jsBEheader">
                <?php echo JText::_('JGLOBAL_FIELDSET_PUBLISHING'); ?>
            </div>
            <div class="jsBEsettings">
                <table>
                    <tr>
                        <td width="250">
                                <?php echo JText::_('JSTATUS'); ?>
                        </td>
                        <td>
                            <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['published'];?></fieldset></div>
                        </td>
                    </tr>
                    <tr>
                        <td width="250">
                                <?php echo JText::_('BLBE_BOX_DISPLAYFE'); ?>
                        </td>
                        <td>
                            <div class="controls"><fieldset class="radio btn-group"><?php echo $this->lists['displayonfe'];?></fieldset></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>    
    </div> 
		
		<input type="hidden" name="option" value="com_joomsport" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $row->id?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHTML::_('form.token'); ?>
	</form>