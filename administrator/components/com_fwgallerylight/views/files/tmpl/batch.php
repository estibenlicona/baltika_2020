<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

JToolBarHelper::title(JText::_('FWG_BATCH_UPLOAD'));
JToolBarHelper::custom('install', 'save', 'save', JText::_('FWG_UPLOAD'), false);
JToolBarHelper::cancel('files');
JHTML::_('behavior.framework');
?>
<form action="index.php?option=com_fwgallerylight&amp;view=files" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<table class="table">
		<td><?php echo JText::_('FWG_USER'); ?>:</td>
		<td>
			<?php echo JHTML::_('select.genericlist', (array)$this->clients, 'user_id', 'class="required"', 'id', 'name', $this->user->id); ?>
		</td>
		<tr>
			<td><?php echo JText::_('FWG_PUBLISHED'); ?>:</td>
			<td>
				<fieldset class="radio btn-group">
					<?php echo JHTML::_('select.booleanlist', 'published', '', 1); ?>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('FWG_GALLERY'); ?>:</td>
			<td>
				<?php echo JHTML::_('fwGallerylightCategory.getCategories', 'project_id', 0, 'class="required"', $multiple=false, $first_option=JText::_('FWG_SELECT_GALLERY')); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div id="fwg-batch-uploader"></div>
			</td>
		</tr>
	</table>
	<input type="hidden" name="task" value="" />
</form>
<script type="text/javascript">
var fwg_uploaded_files = [];
var fwg_uploading_counter_success = fwg_uploading_counter_error = 0;
Joomla.submitbutton = function(task) {
	var form = document.adminForm;
	if ((task == 'install')) {
		if (form.project_id.value == 0) {
			alert('<?php echo JText::_('FWG_SELECT_GALLERY_FIRST', true); ?>');
			return;
		}
		if (typeof(window.FileReader) == 'undefined') {
			var files_added = false;
			jQuery('input[type=file]', form).each(function(index, input) {
				if (input.value != '') files_added = true;
			});
			if (!files_added) {
				alert('<?php echo JText::_('FWG_NOTHING_TO_UPLOAD', true); ?>');
				return false;
			}
		} else {
			if (fwg_uploaded_files.length > 0) {
				var button = jQuery('#toolbar-save button');
				if (button.length) {
					button.attr('disabled', true);
					var img = jQuery('<img id="fwg-batch-wait-img" src="<?php echo JURI::root(true); ?>/components/com_fwgallerylight/assets/images/ajax-loader-small.gif" />');
					button.after(img);
				}
				var published = 0;
				jQuery('input[name=published]').each(function(index, input) {
					if (input.checked) published = input.value;
				});

				var formdata = new FormData();
				formdata.append('user_id', form.user_id.value);
				formdata.append('published', published);
				formdata.append('project_id', form.project_id.value);

				formdata.append('format', 'json');
				formdata.append('layout', 'batchupload');

				for (var i = 0; i < fwg_uploaded_files.length; i++) {
					formdata.append('images[]', fwg_uploaded_files[i]);
				}

				var ajax = new XMLHttpRequest();
				ajax.addEventListener("load", completeHandler, false);
				ajax.open("POST", form.getProperty('action'));
				ajax.send(formdata);
			} else {
				alert('<?php echo JText::_('FWG_NOTHING_TO_UPLOAD', true); ?>');
			}
			return false;
		}
	}
	form.task.value = task;
	form.submit();
}
function completeHandler(event) {
	jQuery('#fwg-batch-wait-img').remove();
	var data = jQuery.parseJSON(event.target.responseText);
	if (data) {
		if (data.msg) alert(data.msg);
	}
	location = jQuery('#adminForm').attr('action');
}
function handleFileInput(input) {
	var form = document.adminForm;

	var div = jQuery('<div class="row-fluid"></div>');
	jQuery(form).append(div);
	div.append(jQuery('<div class="span10">'+input.val()+'</div>'));
	div.append(input);
	input.css('display', 'none');
	var button = jQuery('<div class="span2"><button type="button" class="btn btn-danger btn-small" onclick="jQuery(this).parent().parent().remove();">-</button></div>');
	div.append(button);

	var input = jQuery('<input type="file" name="images[]" />');
	jQuery(form).append(input);
	input.change(function() {
		handleFileInput(jQuery(this));
	});
}
jQuery(function($) {
	var form = document.adminForm;
	var bu = jQuery('#fwg-batch-uploader');
    if (typeof(window.FileReader) == 'undefined') {
		var input = jQuery('<input type="file" name="images[]" />');
		jQuery(form).append(input);
		input.change(function() {
			handleFileInput(jQuery(this));
		});
	} else {
		var div = jQuery('<div id="fwg-batch-uploader-wrapper"></div>');
		bu.append(div);
		var fr = jQuery('<div id="fwg-batch-uploader-drop-zone"></div>');
		div.append(fr);
		fr.html('<?php echo JText::_('FWG_OR_DRAG_FILES_HERE', true); ?>');

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
					if (fr.html() == '<?php echo JText::_('FWG_OR_DRAG_FILES_HERE', true); ?>') fr.html('');
					fr.css({'background-image':'none', 'line-height':'16px', 'text-align':'left'});
					var row = jQuery('<div class="fwg-file-row">'+file.name+'</div>');
					fr.append(row);
					var remove_button = jQuery('<a href="javascript:" title="<?php echo JText::_('FWG_REMOVE', true); ?>"><img src="<?php echo JURI::root(true) ?>/administrator/components/com_fwgallerylight/assets/images/delete16.png" alt="delete"/></a>');
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
							fr.html('<?php echo JText::_('FWG_OR_DRAG_FILES_HERE', true); ?>');
							fr.css({'background-image':'', 'line-height':'200px', 'text-align':'center'});
						}
					});
					fwg_uploaded_files.push(file);
				} else wrong_files_types = true;
			}
			if (wrong_files_types) {
				alert('<?php echo JText::_('FWG_IMAGES_ONLY_ALLOWED'); ?>');
			}
		}
		var input = jQuery('<input type="file" multiple name="files[]" id="fwg-upload-input" />');
		bu.append(jQuery('<p><?php echo JText::_('FWG_ALLOWED_IMAGES_TYPES'); ?>; <?php echo JText::_('FWG_FILE_UPLOAD_SIZE_LIMIT').' '.ini_get('post_max_size'); ?></p><span><?php echo JText::_('FWG_SELECT_IMAGES', true); ?>&nbsp;</span>')).append(input);

		input[0].onchange = function(event) {
			event.preventDefault();
			var wrong_files_types = false;
			for (var i = 0; i < event.target.files.length; i++) {
				var file = event.target.files[i];
				if (file.name.match(/\.(jpg|jpeg|gif|png)$/i)) {
					if (fr.html() == '<?php echo JText::_('FWG_OR_DRAG_FILES_HERE', true); ?>') fr.html('');
					fr.css({'background-image':'none', 'line-height':'16px', 'text-align':'left'});
					var row = jQuery('<div class="fwg-file-row">'+file.name+'</div>');
					fr.append(row);
					var remove_button = jQuery('<a href="javascript:" title="<?php echo JText::_('FWG_REMOVE', true); ?>"><img src="<?php echo JURI::root(true) ?>/administrator/components/com_fwgallerylight/assets/images/delete16.png" alt="delete"/></a>');
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
							fr.html('<?php echo JText::_('FWG_OR_DRAG_FILES_HERE', true); ?>');
							fr.css({'background-image':'', 'line-height':'200px', 'text-align':'center'});
						}
					});
					fwg_uploaded_files.push(file);
				} else wrong_files_types = true;
			}
			if (wrong_files_types) {
				alert('<?php echo JText::_('FWG_IMAGES_ONLY_ALLOWED'); ?>');
			}
		}

	}
});
function humanFileSize(size) {
    var i = Math.floor( Math.log(size) / Math.log(1024) );
    return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
};
</script>
