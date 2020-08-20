<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

$input = JFactory::getApplication()->input;
?>
<div class="container-popup">
	<h4><?php echo JText::_('FWG_ADD_NEW_GALLERY'); ?></h4>
	<form action="<?php echo JRoute::_('index.php?option=com_fwgallerylight&view=gallery&layout=modal&tmpl=component&function=' . $this->function . '&' . JSession::getFormToken() . '=1'); ?>" method="post" name="adminForm" id="adminForm" class="form-inline form-validate">
        <table class="table">
            <tr>
                <td class="key">
                    <label for="fwg-gallery-name"><?php echo JText::_('FWG_GALLERY_NAME'); ?></label><span class="required">*</span> :
                </td>
                <td>
                    <input class="inputbox required" id="fwg-gallery-name" type="text" name="name" size="50" maxlength="100" value="" />
                </td>
            </tr>
	        <tr>
	            <td class="key">
	                <?php echo JText::_('FWG_PARENT_GALLERY'); ?>:
	            </td>
	            <td>
	                <?php echo JHTML::_('fwGallerylightCategory.parent'); ?>
	            </td>
	        </tr>
        </table>
        <button class="btn btn-primary" type="submit"><?php echo JText::_('FWG_SAVE'); ?></button>
        <a class="btn" href="<?php echo JRoute::_('index.php?option=com_fwgallerylight&view=gallery&layout=modal&tmpl=component&project_id='.$input->getInt('project_id').'&function=' . $this->function . '&' . JSession::getFormToken() . '=1'); ?>"><?php echo JText::_('FWG_CANCEL'); ?></a>
        <input type="hidden" name="task" value="" />
    	<input type="hidden" name="boxchecked" value="0" />
    </form>
</div>
<script>
jQuery(function($) {
    $('#adminForm').submit(function(event) {
        event.preventDefault();
        var form = this;
        var $img = $('<img/>', {
            'src': '<?php echo JURI::root(true); ?>/components/com_fwgallerylight/assets/images/ajax-loader.gif',
            'style': 'margin: 0 10px;'
        });
        $('button[type="submit"]', $(form)).append($img);
        $.ajax({
            url: '',
            method: 'post',
            data: {
                name: form.name.value,
                parent: form.parent.value,
                layout: 'save',
                format: 'json'
            }
        }).done(function(html) {
            var data = $.parseJSON(html);
            if (data) {
                if (data.msg) alert(data.msg);
                if (data.result > 0) {
                    location = $('a.btn', $(form)).attr('href');
                }
            }
        }).always(function() {
            $img.remove();
        });
    });
});
</script>
