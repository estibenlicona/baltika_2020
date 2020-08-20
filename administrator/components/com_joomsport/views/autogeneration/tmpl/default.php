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

$lists = $this->lists;

?>
<script>
jQuery(document).ready(function() {
    jQuery('.numbersOnlyAG').keyup(function () { 
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });
    jQuery('#generatem').on('click', function(){
        document.adminForm.autogeneration.value = 'true';
        document.adminForm.submit();
    });
    
    jQuery('#generateknock').on('click', function(){
        
        var formatnum = document.adminForm.format_post.value;
        
        if(formatnum == '0'){
            alert('Please specify number of participiants');
            return false;
        }
        var teams_knock = document.adminForm['teams_knock[]'];
        if(teams_knock.length == '0'){
            alert('Please select participiants');
            return false;
        }
        
        var srcLen = teams_knock.length;

        for (var i=0; i < srcLen; i++) {
                        teams_knock.options[i].selected = true;
        } 
        
        document.adminForm.autogeneration.value = 'true';
        document.adminForm.submit();
    });
    
    
});  


(function($){
    //Shuffle all rows, while keeping the first column
    //Requires: Shuffle
 $.fn.shuffleRows = function(){
     return this.each(function(){
        var main = $(/table/i.test(this.tagName) ? this.tBodies[0] : this);
        var firstElem = [], counter=0;
        main.children().each(function(){
             firstElem.push(this.firstChild);
        });
        main.shuffle();
        main.children().each(function(){
           this.insertBefore(firstElem[counter++], this.firstChild);
        });
     });
   }
  /* Shuffle is required */
  $.fn.shuffle = function() {
    return this.each(function(){
      var items = $(this).children();
      return (items.length)
        ? $(this).html($.shuffle(items))
        : this;
    });
  }

  $.shuffle = function(arr) {
    for(
      var j, x, i = arr.length; i;
      j = parseInt(Math.random() * i),
      x = arr[--i], arr[i] = arr[j], arr[j] = x
    );
    return arr;
  }
})(jQuery);

function shuffleNumberstm(){
    
    jQuery.each(jQuery("input[name^='team_number_rand']"),function(i,el){
        jQuery(this).val(i+1);
    })
}

function checkQnty(obj){
    var qty = parseInt(obj.value);
    var qty_list = parseInt(jQuery('#teams_knock option').size()); 
    if(qty_list > qty){
        jQuery("#qty_notify").text("You added more participants than chosen knockout format allows. Not all the teams/player will participate Matches");
    }else{
        jQuery("#qty_notify").text('');
    }

}


function ReAnalize_tbl_Rows( tbl_id ) {
			start_index = 0;
			var tbl_elem = getObj(tbl_id);
			if (tbl_elem.rows[start_index]) {
				for (var i=start_index; i<tbl_elem.rows.length; i++) {
					
					
					
					if (i > 0) { 
						tbl_elem.rows[i].cells[0].style.visibility = 'visible';
					} else { tbl_elem.rows[i].cells[0].style.visibility = 'hidden'; }
					if (i < (tbl_elem.rows.length - 1)) {
						tbl_elem.rows[i].cells[1].style.visibility = 'visible';
					} else { tbl_elem.rows[i].cells[1].style.visibility = 'hidden'; }

				}
			}
		}
		
		

		
		

jQuery(document).ready(function(){
    jQuery(".up,.down").click(function(){
        var row = jQuery(this).parents("tr:first");
        if (jQuery(this).is(".up")) {
            row.insertBefore(row.prev());
        } else if (jQuery(this).is(".down")) {
            row.insertAfter(row.next());
        }
        ReAnalize_tbl_Rows('teamsToShuf');
    });
});
jQuery( document ).ready(function() {
            
            jQuery("#teamsToShuf").sortable(
                    
            );
            
            
            
        });
</script>    

<form action="index.php?option=com_joomsport" method="post" name="adminForm" id="adminForm">

    <table>
        <tr>
            <td style="padding: 5px 0px;">
                Season
            </td>
            <td style="padding: 5px 10px;">
                <?php echo $lists['seasons'];?>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px 0px;">
                Matchday type
            </td>
            <td style="padding: 5px 10px;">
                <?php echo $lists['t_type'];?>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px 0px;">
                Matchday name
            </td>
            <td style="padding: 5px 10px;">
                <input style="margin-bottom: 0px;" type="text" name="mday_name" value="Matchday" />
            </td>
        </tr>
        <?php
        if (!$lists['md_type'] && $lists['sid']) {
            ?>
        <tr>
            <td style="padding: 5px 0px;">
                Rounds
            </td>
            <td style="padding: 5px 10px;">
                <input type="text" name="rounds" value="2" style="width:30px;" class="numbersOnlyAG" />
            </td>
        
        </tr>
        <tr>
            <td style="padding: 5px 0px;">
                <input type="button"  class="btn btn-small" value="Randomize" onclick="jQuery('#teamsToShuf').shuffleRows();shuffleNumberstm();" />
          
            </td>
            <td style="padding: 5px 10px;">
                <input type="button" class="btn btn-small"  name="generatem" id="generatem" value="Generate Matches" />
            </td>
        
        </tr>
        <?php 
        } ?>
    </table>
    <?php
    if ($lists['sid']) {
        ?>
    <div style="padding:10px 0px; width:50%;">
        <?php
        if (!$lists['md_type']) {
            ?>
           
        <table>
            <tbody id="teamsToShuf">
            <?php for ($intA = 0; $intA < count($lists['teams']); ++$intA) {
    ?>
            <tr class="ui-state-default">
                <td width="30">
                    <span class="sortable-handler" style="cursor: move;">
                        <span class="icon-menu"></span>
                    </span>
                </td>
                <td>
                    <?php echo $lists['teams'][$intA]->name;
    ?>
                    <input type="hidden" name="team_number_id[]" value="<?php echo $lists['teams'][$intA]->id;
    ?>" />
                </td>
                <td>
                    
                </td>
            </tr>
            
            <?php 
}
            ?>
            </tbody>
        </table>
        
        <?php

        } else {
            ?>
        
        <table>
            <tr>
                <td width="150">
                    <i>Available participants</i><br />
			<?php 
                        echo JHTML::_('select.genericlist',   $this->lists['teams'], 'teams_id', ' size="10" multiple ondblclick="javascript:JS_addSelectedToList(\'adminForm\',\'teams_id\',\'teams_knock\');"', 'id', 'name', 0);
            ?>
                </td>
                <td valign="middle" width="60" align="center">
                    <input class="btn" type="button" style="cursor:pointer;" value=">>" onClick="javascript:JS_addSelectedToList('adminForm','teams_id','teams_knock');" /><br />
                        <input class="btn" type="button" style="cursor:pointer;" value="<<" onClick="javascript:JS_delSelectedFromList('adminForm','teams_knock','teams_id');" />
                </td>
                <td >
                    <i>Chosen participants</i><br />
                        <?php 
                        echo JHTML::_('select.genericlist',   array(), 'teams_knock[]', ' size="10" multiple ondblclick="javascript:JS_delSelectedFromList(\'adminForm\',\'teams_knock\',\'teams_id\');"', 'id', 'name', 0);
            ?>
                </td>
            </tr>
        </table>
        <div>
            <div id="qty_notify" style="color:red;"></div>
            <?php echo $lists['format_kn'];
            ?>
                
            <input type="button" name="generateknock" id="generateknock" value="Generate Knockout" />
    
        </div>
        <?php 
        }
        ?>
    </div>    
        
    <?php

    }
    ?>
<input type="hidden" name="autogeneration" value="false" />
<input type="hidden" name="task" value="autogeneration" />

<?php echo JHTML::_('form.token'); ?>
</form>