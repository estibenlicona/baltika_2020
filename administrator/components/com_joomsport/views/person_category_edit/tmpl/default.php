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

?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
        submitbutton(task);
}
function submitbutton(pressbutton) {
        var form = document.adminForm;
         if(pressbutton == 'person_category_apply' || pressbutton == 'person_category_save' || pressbutton == 'person_category_save_new'){
                var reg=/^\s+$/;
                if(form.name.value && !reg.test(form.name.value)){
                        submitform( pressbutton );
                        return;
                }else{
                        getObj('trname').style.border = "1px solid red";
                        alert("<?php echo JText::_('BLBE_JSMDNOT1');?>");


                }
        }else{
                submitform( pressbutton );
                        return;
        }			
}	
function delete_logo(){
        getObj("logoiddiv").innerHTML = '';
}

</script>
<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
<div class="jsrespdiv12">
    <div class="jsBepanel">
        <div class="jsBEheader">
            <?php echo JText::_('BLBE_GENERAL'); ?>
        </div>
        <div class="jsBEsettings">
            <table class="jsTableEqual">
                    <tr>
                            <td width="160">
                                    <?php echo JText::_('BLBE_PERSON_CATEGORY_NAME'); ?>
                                
                            </td>
                            <td>
                                    <input type="text" maxlength="255" size="60" name="name" id="trname" value="<?php echo htmlspecialchars($this->row->name);?>" onKeyPress="return disableEnterKey(event);" />
                                    
                            </td>
                    </tr>


            </table>
        </div>
    </div>          
</div>
  

<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $this->row->id?>" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_('form.token'); ?>
</form>

                
