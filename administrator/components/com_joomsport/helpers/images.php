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

defined('_JEXEC') or die;

class ImagesHelper
{
    public static function loaderUI($vals, $default = null, $multi = true, $enbldefault = '1')
    {
        $default_val = '';
        ?>
    
            <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
            <link rel="stylesheet" href="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/css/jquery.fileupload-ui.css">
            <link rel="stylesheet" href="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/css/jquery.fileupload-ui.css">
            <link rel="stylesheet" href="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/css/jquery.fileupload.css">
            <!-- CSS adjustments for browsers with JavaScript disabled -->
            <noscript><link rel="stylesheet" href="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/css/jquery.fileupload-ui-noscript.css"></noscript>
            <link rel="stylesheet" href="<?php echo JUri::base()?>../components/com_joomsport/sportleague/assets/css/lightbox.css">
            <!-- The file upload form used as target for the file upload widget -->
                    <!-- Redirect browsers with JavaScript disabled to the origin page -->
                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                    <div class="row fileupload-buttonbar">
                        <div class="span7">
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="btn btn-success fileinput-button">
                                <i class="icon-plus icon-white"></i>
                                <span><?php echo JText::_('BLBE_ADDIMAGES');
        ?></span>
                                <input type="file" name="files[]" multiple>
                            </span>
                            
                            <button type="button" class="btn btn-danger delete">
                                <i class="icon-trash icon-white"></i>
                                <span><?php echo JText::_('BLBE_DELETE');
        ?></span>
                            </button>
                            
                            <!-- The loading indicator is shown during file processing -->
                            <span class="fileupload-loading"></span>
                        </div>
                        <!-- The global progress information -->
                        <div class="span5 fileupload-progress fade">
                            <!-- The global progress bar -->
                            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                <div class="bar" style="width:0%;"></div>
                            </div>
                            <!-- The extended global progress information -->
                            <div class="progress-extended">&nbsp;</div>
                        </div>
                    </div>
                    <!-- The table listing the files available for upload/download -->
                    <table role="presentation" class="table table-striped">
                        <thead class="jsgallerytablehead" <?php if (!count($vals)) {
    echo 'style="display:none;"';
}
        ?>>
                            <tr>
                                <th class="fileupload-buttonbar"><input type="checkbox" class="toggle"></th>
                                <th></th>
                                <th><?php echo JText::_('BLBE_IMAGE');
        ?></th>
                                <th></th>
                                <th><?php echo JText::_('BLBE_IMAGE_SIZE');
        ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="files">
                             <?php

                             if (count($vals)) {
                                 foreach ($vals as $val) {
                                     if (is_file(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'bearleague'.DIRECTORY_SEPARATOR.$val->filename)) {
                                         ?>
                                     <tr class="template-download fade in">
                                        <td>
                                             <input type="checkbox" name="delete" value="1" class="toggle">
                                        </td>
                                        <td class="jsdefaultimg">
                                            <?php
                                            if ($enbldefault) {
                                                if (count($vals) > 1) {
                                                    if ($val->id == $default) {
                                                        $default_val = $val->filename;
                                                        ?>
                                                        <a class="btn btn-micro hasTooltip" href="javascript:void(0);"  title="" data-original-title="Set default"><span class="icon-featured" style="margin:0 auto;"></span></a>

                                                        <?php

                                                    } else {
                                                        ?>
                                                        <a class="btn btn-micro hasTooltip" href="javascript:void(0);"  title="" data-original-title="Set default"><span class="icon-unfeatured" style="margin:0 auto;"></span></a>

                                                        <?php

                                                    }
                                                    ?>

                                                    <?php

                                                }
                                            }
                                         ?>
                                        </td>
                                        <td>
                                            <span class="preview">
                                                <?php
                                                if (is_file(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'bearleague'.DIRECTORY_SEPARATOR.'thumb'.DIRECTORY_SEPARATOR.$val->filename)) {
                                                    echo '<a href="'.JUri::base().'../media/bearleague/'.$val->filename.'" data-lightbox="jsImg"><img class="img-thumbnailJS img-responsive" width="100" src="'.JUri::base().'../media/bearleague/thumb/'.$val->filename.'"></a>';
                                                } elseif (is_file(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'bearleague'.DIRECTORY_SEPARATOR.$val->filename)) {
                                                    echo '<a href="'.JUri::base().'../media/bearleague/'.$val->filename.'" data-lightbox="jsImg"><img class="img-thumbnailJS img-responsive" width="100" src="'.JUri::base().'../media/bearleague/'.$val->filename.'"></a>';
                                                }
                                         ?>
                                                
                                            </span>
                                        </td>
                                        <td>
                                            <p class="name">
                                                
                                                <input type="hidden" name="filnm[]" value="<?php echo $val->filename?>">   
                                            </p>


                                        </td>
                                        <td>
                                            <span class="size">
                                                <?php echo self::filesize_get(JPATH_ROOT.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'bearleague'.DIRECTORY_SEPARATOR.$val->filename)?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger delete" data-type="DELETE" data-url="index.php?option=com_joomsport&task=uploadGallery&tmpl=component&no_html=1&file=<?php echo $val->filename?>">
                                                <i class="icon-trash icon-white"></i>
                                                <span><?php echo JText::_('BLBE_DELETE');
                                         ?></span>
                                            </button>
                                            
                                        </td>
                                    </tr>
                                     <?php

                                     }
                                 }
                             }
        ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="imgdefault" name="imgdefault" value="<?php echo $default_val;
        ?>" />
            
            <!-- The template to display files available for upload -->
            <script id="template-upload" type="text/x-tmpl">
            {% for (var i=0, file; file=o.files[i]; i++) { %}
                <tr class="template-upload fade">
                               
                    <td>
                        <span class="preview"></span>
                    </td>
                    <td>
                        <p class="name">{%=file.name%}</p>

                        {% if (file.error) { %}
                            <div><span class="label label-important">Error</span> {%=file.error%}</div>
                        {% } %}
                    </td>
                    <td>
                        <p class="size">{%=o.formatFileSize(file.size)%}</p>
                        {% if (!o.files.error) { %}
                            <div class="progress progress-success progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar" style="width:0%;"></div></div>
                        {% } %}
                    </td>
                    <td>
                        {% if (!o.files.error && !i && !o.options.autoUpload) { %}
                            <button class="btn btn-primary start">
                                <i class="icon-upload icon-white"></i>
                                <span>Start</span>
                            </button>
                        {% } %}
                        {% if (!i) { %}
                            <button class="btn btn-warning cancel">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Cancel</span>
                            </button>
                        {% } %}
                    </td>
                </tr>
            {% } %}
            </script>
            <!-- The template to display files available for download -->
            <script id="template-download" type="text/x-tmpl">
            
            {% for (var i=0, file; file=o.files[i]; i++) { %}
                <tr class="template-download fade">
                    <td>
                        <input type="checkbox" name="delete" value="1" class="toggle">
                    </td>
                    <td class="jsdefaultimg">
                    </td>            
                    <td>
                        <span class="preview">
                            {% if (file.thumbnailUrl) { %}
                                <a href="{%=file.url%}" data-lightbox="jsImg"><img class="img-thumbnailJS img-responsive" width="100" src="{%=file.thumbnailUrl%}"></a>
                            {% }else if(file.url){ %}
                                <a href="{%=file.url%}" data-lightbox="jsImg"><img class="img-thumbnailJS img-responsive" width="100" src="{%=file.url%}"></a>
                            
                            {% } %}
                        </span>
                    </td>
                    <td>
                        <p class="name">
                            <input type="hidden" name="filnm[]" value="{%=file.name%}" />   
                        </p>

                        {% if (file.error) { %}
                            <div><span class="label label-important">Error</span> {%=file.error%}</div>
                        {% } %}
                    </td>
                    <td>
                        <span class="size">{%=o.formatFileSize(file.size)%}</span>
                    </td>
                    <td>
                        <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                            <i class="icon-trash icon-white"></i>
                            <span><?php echo JText::_('BLBE_DELETE');
        ?></span>
                        </button>
                        
                    </td>
                </tr>
            {% } %}
            </script>
            <script>
            var enbldefault = '<?php echo $enbldefault;
        ?>';
            </script>
            <script src="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/js/vendor/jquery.ui.widget.js"></script>
            <!-- The Templates plugin is included to render the upload/download listings -->
            <script src="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/js/tmpl.min.js"></script>
            <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
            <script src="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/js/load-image.min.js"></script>
            <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
            <script src="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/js/jquery.iframe-transport.js"></script>
            <!-- The basic File Upload plugin -->
            <script src="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/js/jquery.fileupload.js"></script>
            <!-- The File Upload processing plugin -->
            <script src="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/js/jquery.fileupload-process.js"></script>
            <!-- The File Upload image preview & resize plugin -->
            <script src="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/js/jquery.fileupload-image.js"></script>
            <!-- The File Upload validation plugin -->
            <script src="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/js/jquery.fileupload-validate.js"></script>
            <!-- The File Upload user interface plugin -->
            <script src="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/js/jquery.fileupload-ui.js"></script>
            <!-- The main application script -->
            <script src="<?php echo JUri::base()?>components/com_joomsport/includes/jsupload/js/main.js"></script>
            <script src="<?php echo JUri::base()?>../components/com_joomsport/sportleague/assets/js/lightbox.js"></script>
            
            <?php

    }

    private static function filesize_get($file)
    {
        if (!file_exists($file)) {
            return "File doesn't exist";
        }

        $filesize = filesize($file);

        if ($filesize > 1024) {
            $filesize = ($filesize / 1024);

            if ($filesize > 1024) {
                $filesize = ($filesize / 1024);

                if ($filesize > 1024) {
                    $filesize = ($filesize / 1024);
                    $filesize = round($filesize, 1);

                    return $filesize.' GB';
                } else {
                    $filesize = round($filesize, 1);

                    return $filesize.' MB';
                }
            } else {
                $filesize = round($filesize, 1);

                return $filesize.' KB';
            }
        } else {
            $filesize = round($filesize, 1);

            return $filesize.'';
        }
    }
    public static function saveImgs($json, $rowid, $type)
    {
        $db = JFactory::getDBO();
        $image_default = 0;
        if ($json && intval($rowid)) {
            $query = 'DELETE FROM #__bl_assign_photos WHERE cat_id = '.intval($rowid).' AND cat_type = '.intval($type);
            $db->setQuery($query);
            $db->query();
            $filenames = json_decode($json, true);
            if (count($filenames)) {
                foreach ($filenames as $filename) {
                    $file = $filename['value'];
                    $db->setQuery("SELECT id FROM #__bl_photos WHERE ph_filename = '".addslashes($file)."'");
                    $id = $db->loadResult();
                    if (!$id) {
                        $db->setQuery("INSERT INTO #__bl_photos(ph_filename) VALUES('".addslashes($file)."')");
                        $db->query();
                        $id = $db->insertid();
                    }
                    if ($file == addslashes($_POST['imgdefault'])) {
                        $image_default = $id;
                    }

                    $query = 'INSERT INTO #__bl_assign_photos(photo_id,cat_id,cat_type) VALUES('.$id.','.intval($rowid).','.intval($type).')';
                    $db->setQuery($query);
                    $db->query();
                }
            }
        }

        return $image_default;
    }
}
