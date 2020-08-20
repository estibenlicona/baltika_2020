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
	<h4><?php echo JText :: _('FWG_ADD_NEW_IMAGE'); ?></h4>
	<form action="<?php echo JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=gallery&layout=modal&sublayout=images&tmpl=component&function=' . $this->function . '&' . JSession::getFormToken() . '=1')); ?>" method="post" name="adminForm" id="adminForm" class="form-inline form-validate">
        <table class="table">
			<tr>
				<td class="key"><?php echo JText::_('FWG_PUBLISHED'); ?>:</td>
				<td>
					<fieldset class="radio btn-group">
						<div class="controls">
							<label for="published0" id="published0-lbl" class="radio btn">
								<input name="published" id="published0" value="0" type="radio"><?php echo JText :: _('JNo'); ?>
							</label>
							<label for="published1" id="published1-lbl" class="radio btn active btn-success">
								<input name="published" id="published1" value="1" checked="checked" type="radio"><?php echo JText :: _('JYes'); ?>
							</label>
						</div>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td class="key"><?php echo JText::_('FWG_GALLERY'); ?>:</td>
				<td>
					<?php echo JHTML :: _('fwGallerylightCategory.getCategories', 'project_id', $input->getInt('project_id'), 'class="required"', $multiple=false, $first_option=''); ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div id="fwg-batch-uploader"></div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<button class="btn btn-primary" type="button" onclick="fwg_store_image(this);"><?php echo JText :: _('FWG_SAVE'); ?></button>
					<a class="btn" href="<?php echo JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=gallery&layout=modal&sublayout=images&tmpl=component&project_id='.$input->getInt('project_id').'&function=' . $this->function . '&' . JSession::getFormToken() . '=1')); ?>"><?php echo JText :: _('FWG_CANCEL'); ?></a>
				</td>
			</tr>
        </table>
        <input type="hidden" name="task" value="" />
    	<input type="hidden" name="boxchecked" value="0" />
    </form>
</div>
<script>
var form;
var fwg_uploaded_files = [];
var fwg_uploading_counter_success = fwg_uploading_counter_error = 0;
function fwg_store_image(butt) {

	if (typeof(window.FileReader) == 'undefined') {
		var files_added = false;
		jQuery('input[type=file]', jQuery(form)).each(function(index, input) {
			if (input.value != '') files_added = true;
		});
		if (!files_added) {
			alert('<?php echo JText :: _('FWG_NOTHING_TO_UPLOAD', true); ?>');
			return false;
		}
	} else {
		if (fwg_uploaded_files.length > 0) {
			var published = 0;
			jQuery('input[name=published]').each(function(index, input) {
				if (input.checked) published = input.value;
			});

			var formdata = new FormData();

			formdata.append('published', published);
			formdata.append('project_id', form.project_id.value);
			formdata.append('format', 'json');
			formdata.append('layout', 'batchupload');

			for (var i = 0; i < fwg_uploaded_files.length; i++) {
				formdata.append('images[]', fwg_uploaded_files[i]);
			}

			var ajax = new XMLHttpRequest();
			ajax.addEventListener("load", completeHandler, false);
			ajax.open("POST", '<?php echo JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=frontendmanager&layout=default_image')); ?>');
			ajax.send(formdata);
		} else {
			alert('<?php echo JText :: _('FWG_NOTHING_TO_UPLOAD', true); ?>');
		}
		return false;
	}
}
function completeHandler(event) {
	jQuery('#fwg-batch-wait-img').remove();
	var data = jQuery.parseJSON(event.target.responseText);
	if (data) {
		if (data.msg) alert(data.msg);
	}
	location = jQuery('a.btn').attr('href');
}
function handleFileInput(input) {
	var div = jQuery('<div class="row-fluid"></div>');
	jQuery('#fwg-batch-uploader').append(div);
	div.append(jQuery('<div class="span10">'+input.val()+'</div>'));
	div.append(input);
	input.css('display', 'none');
	var button = jQuery('<div class="span2"><button type="button" class="btn btn-danger btn-small" onclick="jQuery(this).parent().parent().remove();">-</button></div>');
	div.append(button);

	var input = jQuery('<input type="file" name="images[]" />');
	jQuery('#fwg-batch-uploader').append(input);
	input.change(function() {
		handleFileInput(jQuery(this));
	});
}
jQuery(function($) {
	var bu = $('#fwg-batch-uploader');
	form = $('#adminForm')[0];

    if (typeof(window.FileReader) == 'undefined') {
//		var input = $('<input type="file" name="images[]" />');
		bu.append($('<?php echo JText :: _('FWS_YOUR_BROWSER_TOO_OLD', true); ?>'));
/*		input.change(function() {
			handleFileInput($(this));
		});*/
	} else {
		var div = $('<div id="fwg-batch-uploader-wrapper"></div>');
		bu.append(div);
		var fr = $('<div id="fwg-batch-uploader-drop-zone"></div>');
		div.append(fr);
		fr.html('<?php echo JText :: _('FWG_OR_DRAG_FILES_HERE', true); ?>');

		fr[0].ondragover = function() {
			fr.addClass('fwg-drag-over');
			return false;
		};
		fr[0].ondragleave = function() {
			fr.removeClass('fwg-drag-over');
			return false;
		};
		fr[0].ondrop = function(event) {
			event.preventDefault();
			fr.removeClass('fwg-drag-over');
			fr.addClass('fwg-drag-drop');

			var wrong_files_types = false;
			for (var i = 0; i < event.dataTransfer.files.length; i++) {
				var file = event.dataTransfer.files[i];
				if (file.name.match(/\.(jpg|jpeg|gif|png)$/i)) {
					if (fr.html() == '<?php echo JText :: _('FWG_OR_DRAG_FILES_HERE', true); ?>') fr.html('');
					fr.css({'background-image':'none', 'line-height':'16px', 'text-align':'left'});
					var row = $('<div class="fwg-file-row">'+file.name+'</div>');
					fr.append(row);
					var remove_button = $('<a href="javascript:" title="<?php echo JText :: _('FWG_REMOVE', true); ?>"><img src="<?php echo JURI :: root(true) ?>/administrator/components/com_fwgallerylight/assets/images/delete16.png" alt="delete"/></a>');
					row.append(remove_button);
					var span = $('<span class="fwg-file-size">'+humanFileSize(file.size)+'</span>');
					row.append(span);
					remove_button.click(function() {
						var current_button = this;
						var num = 0;
						var count = 0;
						$('.fwg-file-row a').each(function(index, button) {
							if (button == current_button) num = index;
							count++;
						});
						if (fwg_uploaded_files[num]) {
							fwg_uploaded_files.splice(num, 1);
							$(current_button).parent().remove();
						}
						if (count == 1) {
							fr.html('<?php echo JText :: _('FWG_OR_DRAG_FILES_HERE', true); ?>');
							fr.css({'background-image':'', 'line-height':'200px', 'text-align':'center'});
						}
					});
					fwg_uploaded_files.push(file);
				} else wrong_files_types = true;
			}
			if (wrong_files_types) {
				alert('<?php echo JText :: _('FWG_IMAGES_ONLY_ALLOWED'); ?>');
			}
		}
		var input = $('<input type="file" multiple name="files[]" id="fwg-upload-input" />');
		bu.append($('<span><?php echo JText :: _('FWG_SELECT_IMAGES', true); ?>&nbsp;</span>')).append(input);

		input[0].onchange = function(event) {
			fwg_add_files(event);
		}

	}
	$('label.radio').unbind('click').click(function() {
		var label = $(this);
		var input = $('#' + label.attr('for'));

		if (!input.prop('checked')) {
			label.closest('.btn-group').find('label').removeClass('active btn-success btn-danger btn-primary');
			if (input.val() == '') {
				label.addClass('active btn-primary');
			} else if (input.val() == 0) {
				label.addClass('active btn-danger');
			} else {
				label.addClass('active btn-success');
			}
			input.prop('checked', true);
			input.trigger('change');
		}
	});
});
function fwg_add_files(event) {
	event.preventDefault();
	var wrong_files_types = false;
	var fr = jQuery('#fwg-batch-uploader-drop-zone');
	for (var i = 0; i < event.target.files.length; i++) {
		var file = event.target.files[i];
		if (file.name.match(/\.(jpg|jpeg|gif|png)$/i)) {
			if (fr.html() == '<?php echo JText :: _('FWG_OR_DRAG_FILES_HERE', true); ?>') fr.html('');
			fr.css({'background-image':'none', 'line-height':'16px', 'text-align':'left'});
			var row = jQuery('<div class="fwg-file-row">'+file.name+'</div>');
			fr.append(row);
			var remove_button = jQuery('<a href="javascript:" title="<?php echo JText :: _('FWG_REMOVE', true); ?>"><img src="<?php echo JURI :: root(true) ?>/administrator/components/com_fwgallerylight/assets/images/delete16.png" alt="delete"/></a>');
			row.append(remove_button);
			var span = jQuery('<span class="fwg-file-size">'+humanFileSize(file.size)+'</span>');
			row.append(span);
			remove_button.click(function() {
				var current_button = this;
				var num = 0;
				var count = 0;
				jQuery('.fwg-file-row a').each(function(index, button) {
					if (button == current_button) num = index;
					count++;
				});
				if (fwg_uploaded_files[num]) {
					fwg_uploaded_files.splice(num, 1);
					jQuery(current_button).parent().remove();
				}
				if (count == 1) {
					fr.html('<?php echo JText :: _('FWG_OR_DRAG_FILES_HERE', true); ?>');
					fr.css({'background-image':'', 'line-height':'200px', 'text-align':'center'});
				}
			});
			fwg_uploaded_files.push(file);
		} else wrong_files_types = true;
	}
	if (event.target.files.length > 0) {
		var $input = jQuery(event.target);
		var $span = $input.prev();
		$input.remove();
		var $input = jQuery('<input type="file" multiple name="files[]" id="fwg-upload-input" />');
		$span.after($input);

		$input[0].onchange = function(event) {
			fwg_add_files(event);
		}
	}
	if (wrong_files_types) {
		alert('<?php echo JText :: _('FWG_IMAGES_ONLY_ALLOWED'); ?>');
	}
}
function humanFileSize(size) {
    var i = Math.floor( Math.log(size) / Math.log(1024) );
    return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
};
</script>
