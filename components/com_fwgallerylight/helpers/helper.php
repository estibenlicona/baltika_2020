<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

set_error_handler("fwgErrorHandler");

function fwgErrorHandler($errno, $errstr, $errfile, $errline) {
    if ($errno == E_STRICT) return true;
}

if (!class_exists('GPMiniImg')) {
    class GPMiniImg {
		static function upload($name, $path, $allowed_exts=array()) {
			$input = JFactory::getApplication()->input;
			if ($file = $input->files->get($name) and !empty($file['name']) and empty($file['error'])) {
				jimport('joomla.filesystem.file');
				$ext = strtolower(JFile::getExt($file['name']));
				if (!$allowed_exts or ($allowed_exts and in_array($ext, $allowed_exts))) {
					$filename = JFHelper::findRandUnicFilename($path, $ext);
					if (JFile::copy($file['tmp_name'], $path.$filename)) {
						return $filename;
					}
				}
			}
		}
        static function imageProcessing($name, $dst_path='', $max_x=0, $max_y=0, $watermark='', $watermark_text='', $watermark_pos='') {
            if (!$dst_path) $dst_path = JPATH_SITE.'/images/com_fwgallery/files';

			if ($filename = GPMiniImg::upload($name, $dst_path, array('gif', 'png', 'jpg', 'jpeg'))) {
				if ($image = GPMiniImg::loadImage($dst_path.$filename, '', $ext)) {
					$rotate = 0;
					$mirror = 0;
					if (function_exists('exif_read_data') and $exif = @exif_read_data($dst_path.$filename)) {
						switch (JArrayHelper::getValue($exif, 'Orientation')) {
							case 3 :
								$rotate = 180;
							break;
							case 6 :
								$rotate = 270;
							break;
							case 8 :
								$rotate = 90;
							break;
	/* part with a mirror effect */
							case 2 :
								$mirror = 1;
							break;
							case 4 :
								$mirror = 1;
								$rotate = 180;
							break;
							case 5 :
								$mirror = 1;
								$rotate = 270;
							break;
							case 7 :
								$mirror = 1;
								$rotate = 90;
							break;
						}
					}

					if ($rotate) $image = imagerotate($image, $rotate, 0);
					if ($mirror) {
						$width = imagesx($image);
						$height = imagesy($image);

						$buff_image = imagecreatetruecolor($width, $height);
						$y = 1;

						while ( $y < $height ) {
							for ( $i = 1; $i <= $width; $i++ )
								imagesetpixel($buff_image, $i, $y, imagecolorat($image, ( $i ), ($height - $y)));
							$y = $y + 1;
						}
						imagecopy($image, $buff_image, 0, 0, 0, 0, $width, $height);
						imagedestroy($buff_image);
					}
					if ($max_x and $max_y) {
						GPMiniImg::reduceImage($image, $dst_path, $filename, $max_x, $max_y, $watermark, $watermark_text, $watermark_pos);
					} else {
						GPMiniImg::storeImage($image, $dst_path, $filename);
					}
				}
	            return $filename;
			}
        }

        static function makeThumb($filename, $path, $prefix = 'th_', $max_x=160, $max_y=120, $is_just_shrink=1) {
            if (file_exists($path.'/'.$filename) and $prefix) {
                if ($image = GPMiniImg::loadImage($path, $filename)) {
	                GPMiniImg::reduceImage($image, $path, $prefix.$filename, $max_x, $max_y, '', '', '', $is_just_shrink);
                }
            }
        }

        static function reduceImage(&$image, $path, $filename, $max_x=0, $max_y=0, $watermark='', $watermark_text='', $watermark_pos='', $is_just_shrink=1) {
			jimport('joomla.filesystem.file');

            $org_width = imagesx($image);
            $org_height = imagesy($image);

			if (!$max_x and !$max_y) {
				$max_x = $org_width;
				$max_y = $org_height;
			} elseif (!$max_x) $max_x = $max_y * $org_width / $org_height;
			elseif (!$max_y) $max_y = $max_x * $org_height / $org_width;

			$org_ratio = $org_width/$org_height;

			if ($is_just_shrink) {
				$x_offset = 0;
				$y_offset = 0;
				$s_width = $org_width;
				$s_height = $org_height;

				if ($org_ratio > 1) { /* width larger, so srink by width */
					if ($org_width <= $max_x) {
						$max_x = $org_width;
						$max_y = $org_height;
					} else $max_y = round($max_x / $org_ratio);
				} else { /* height larger or eq */
					if ($org_height <= $max_y) {
						$max_x = $org_width;
						$max_y = $org_height;
					} else $max_x = round($max_y * $org_ratio);
				}
			} else {
				if (!($trg_ratio = $max_x/$max_y)) {
					return false;
				}

				if ($org_ratio < $trg_ratio) { /*cut vertical top & shrink */
					$s_width = $org_width;
					$s_height = (int)($org_width / $trg_ratio);

					$x_offset = 0;
					$y_offset = (int)(($org_height-$s_height)/5);
				} elseif ($org_ratio > $trg_ratio) { /* cut horisontal middle & shrink */
					$s_width = (int)($org_height * $trg_ratio);
					$s_height = $org_height;

					$x_offset = (int)(($org_width-$s_width)/2);
					$y_offset = 0;
				} else { /* images fully proportional - just shrink */
					$s_width = $org_width;
					$s_height = $org_height;

					$x_offset = 0;
					$y_offset = 0;
				}
			}

            $thumb = imagecreatetruecolor($max_x, $max_y);

            $ct = imagecolortransparent($image);
            if ($ct >= 0) {
	            $color_tran = @imagecolorsforindex($image, $ct);
	            $ct2 = imagecolorexact($thumb, $color_tran['red'], $color_tran['green'], $color_tran['blue']);
	            imagefill($thumb, 0, 0, $ct2);
            }

			imagecopyresampled(
				$thumb,
				$image,
				0,
				0,
				$x_offset,
				$y_offset,
				$max_x,
				$max_y,
				$s_width,
				$s_height
			);
	        imagedestroy($image);
	        unset($image);

			if ($watermark and file_exists(JPATH_SITE.'/'.$watermark)) {
				$wmfile = imagecreatefrompng(JPATH_SITE.'/'.$watermark);
			} elseif ($watermark_text) {
				/* calculate text size */
				$font_path = FWG_ASSETS_PATH.'fonts/chesterfield.ttf';

				$bbox = imagettfbbox(36, 0, $font_path, $watermark_text);
				if ($bbox[0] < -1) $box_width = abs($bbox[2]) + abs($bbox[0]) - 1;
				else $box_width = abs($bbox[2] - $bbox[0]);
				if ($bbox[3] > 0) $box_height = abs($bbox[7] - $bbox[1]) - 1;
				else $box_height = abs($bbox[7]) - abs($bbox[1]);

				if ($wmfile = imagecreatetruecolor($box_width, $box_height + 2)) {
					$colorTransparent = imagecolortransparent($wmfile);
					imagefill($wmfile, 0, 0, $colorTransparent);

		            $black = imagecolorallocate($wmfile, 0, 0, 0);
					imagettftext($wmfile, 36, 0, 0, abs($bbox[7]), $black, $font_path, $watermark_text);
				}
			} else $wmfile = false;

			if ($wmfile) {
				$wm_width = imagesx($wmfile);
				$wm_height = imagesy($wmfile);
				$shr_coeff = min($max_x/1024, $max_y/768);
				if ($shrinking_coeff < 1) {
					$new_wm_width = (int)($wm_width*$shr_coeff);
					$new_wm_height = (int)($wm_height*$shr_coeff);
					$buff = imagecreatetruecolor($new_wm_width, $new_wm_height);
					$colorTransparent = imagecolortransparent($buff);
					imagefill($buff, 0, 0, $colorTransparent);

					ImageCopyResampled($buff, $wmfile, 0, 0, 0, 0, $new_wm_width, $new_wm_height, $wm_width, $wm_height);

					$wmfile = $buff;
					$wm_width = $new_wm_width;
					$wm_height = $new_wm_height;
				}

				$indent = 50 * $shr_coeff;
				switch ($watermark_pos) {
					case  'center' :
						$dest_x = round(($max_x - $wm_width)/2);
						$dest_y = round(($max_y - $wm_height)/2);
					break;
					case  'left top' :
						$dest_x = $indent;
						$dest_y = $indent;
					break;
					case  'right top' :
						$dest_x = $max_x - $wm_width - $indent;
						$dest_y = $indent;
					break;
					case  'left bottom' :
						$dest_x = $indent;
						$dest_y = $max_y - $wm_height - $indent;
					break;
					default :
						$dest_x = $max_x - $wm_width - $indent;
						$dest_y = $max_y - $wm_height - $indent;
				}
				imagecopy($thumb, $wmfile, $dest_x, $dest_y, 0, 0, $wm_width, $wm_height);
			}
			GPMiniImg::storeImage($thumb, $path, $filename);
	        imagedestroy($thumb);
	        unset($thumb);
        }
	    static function rotate($path, $filename, $rotate) {
	    	if ($image = GPMiniImg::loadImage($path, $filename)) {
	    		$image = imagerotate($image, $rotate, 0);
	    		GPMiniImg::storeImage($image, $path, $filename);
	    	}
	    }
	    static function loadImage($path, $filename, $ext=null) {
	    	$fullname = $path.($filename?$filename:'');
	    	if (file_exists($fullname) and is_file($fullname)) {
				ini_set('memory_limit', '512M');
		    	if (is_null($ext)) {
					jimport('joomla.filesystem.file');
		    		$ext = JFile::getExt($filename);
		    	}
		    	if (file_exists($fullname)) {
			        switch ($ext) {
			            case 'gif' :
			                $image = @imagecreatefromgif($fullname);
				            break;
			            case 'png' :
			                $image = @imagecreatefrompng($fullname);
				            break;
			            default :
			                $image = @imagecreatefromjpeg($fullname);
			        }
			        return $image;
		    	}
	    	}
	    }
	    static function storeImage($image, $path, $filename) {
	    	if ($filename) {
				if (file_exists($path.$filename) and is_file($path.$filename) and is_writable($path.$filename)) @unlink($path.$filename);
				jimport('joomla.filesystem.file');
				ob_start();
		        switch (JFile::getExt($filename)) {
		            case 'gif' :
		                @imagegif($image);
			            break;
		            case 'png' :
		                @imagepng($image);
			            break;
		            default :
		                @imagejpeg($image);
		        }
		        if ($buff = ob_get_clean()) JFile::write($path.$filename, $buff);
	    	}
	    }
    }
}
if (!class_exists('JFParams')) {
	class JFParams {
		function get($key, $def=null) {
			$app = JFactory::getApplication();
			$input = $app->input;
			$com_params = JComponentHelper::getParams('com_fwgallerylight');
			$val = null;
			if ($app->isSite() and $input->getCmd('option') == 'com_fwgallerylight') {
				$params = JFactory::getApplication()->getParams();
				$val = $params->get($key);
				if ($val == 'def') $val = $com_params->get($key);
			} else {
				$val = $com_params->get($key);
			}
			if (is_null($val) and !is_null($def)) $val = $def;
			return $val;
		}
	}
}
if (!class_exists('JFHelper')) {
	class fwGallerylightView extends JViewLegacy {}

    define('FWG_ASSETS','components/com_fwgallerylight/assets/');
    define('FWG_ASSETS_PATH',JPATH_SITE.'/components/com_fwgallerylight/assets/');
    define('FWG_ASSETS_URI',JURI::base(true).'/components/com_fwgallerylight/assets/');

    define('FWG_STORAGE', 'images/com_fwgallery/');
    define('FWG_STORAGE_PATH', JPATH_SITE.'/images/com_fwgallery/');
    define('FWG_STORAGE_URI', JURI::base(true).'/'.FWG_STORAGE);

    class JFHelper {
		static function checkLink($link) {
			$app = JFactory :: getApplication();
			if ($app->getCfg('sef') and !$app->getCfg('sef_rewrite') and strpos($link, 'index.php') === false) {
				$root = JURI::root(false);
				if (strpos($link, $root) !== false) {
					$link = str_replace($root, $root.'index.php/', $link);
				} else {
					$link = '/index.php'.$link;
				}
			}
			return $link;
		}
		static function getIniSize($name) {
			$val = ini_get($name);
			if (preg_match('/^(\d+)([MK])$/', $val, $matches)) {
				if ($matches[2] == 'M') $val = $matches[1] * 1024 * 1024;
				elseif ($matches[2] == 'K') $val = $matches[1] * 1024;
			}
			return $val;
		}
		static function getInstalledLanguages() {
			static $languages;
			if (!is_array($languages)) {
				$buff = JLanguage::getKnownLanguages(JPATH_BASE);
				foreach ($buff as $row) {
					$code = substr($row['tag'], 0, 2);
					$lang = new stdclass;
					$lang->id = $code;
					$lang->tag = $row['tag'];
					$lang->name = $row['name'];
					$languages[$code] = $lang;
				}
			}
			return $languages;
		}
		static function getLanguage() {
			$app = JFactory::getApplication();
			$lang = JFactory::getLanguage();
			$lang_name = strtolower($app->getUserStateFromRequest('com_fwgallerylight.language', 'lang', substr($lang->getTag(), 0, 2)));
			if (!$lang_name or ($lang_name and !in_array($lang_name, array_keys(JFHelper::getInstalledLanguages())))) {
				$db = JFactory::getDBO();
				$db->setQuery('SELECT `sef` FROM #__languages WHERE `published` = 1 AND `ordering` = 1');
				$lang_name = $db->loadResult();
				if (!$lang_name) $lang_name = 'en';
			}
			return $lang_name;
		}
	    static function getGroups() {
	    	static $groups;
	    	if (!is_array($groups)) {
		    	$db = JFactory::getDBO();
		    	$db->setQuery('
SELECT
	id,
	title AS name
FROM
	#__usergroups
WHERE
	title NOT IN (\'Guest\', \'Public\', \'Public frontend\', \'ROOT\', \'USERS\')
ORDER BY
	lft');
				$groups = array_merge(
					array(JHTML::_('select.option', '0', JText::_('FWG_GUESTS'), 'id', 'name')),
					(array)$db->loadObjectList()
				);
	    	}
	    	return $groups;
	    }
    	static function escape($text) {
    		return str_replace('"', '&quot;', strip_tags($text));
    	}
    	static function loadTypes($published_only = true) {
			static $published, $all;
			if (!is_array($published)) {
				$published = array();
				$db = JFactory::getDBO();
				$db->setQuery('SELECT * FROM #__fwg_types');
				$all = (array)$db->loadObjectList('plugin');
				if ($all) foreach ($all as $row) if ($row->published) $published[$row->plugin] = $row;
			}
			return $published_only?$published:$all;
    	}
    	static function getTypeId($name) {
			$types = JFHelper::loadTypes();
			return (!empty($types[$name]->id))?$types[$name]->id:0;
    	}
    	static function loadStylesheet() {
    		if (!defined('FWG_STYLESHEET_LOADED')) {
				$app = JFactory::getApplication();

				$path = 'templates/'.$app->getTemplate().'/css/';
				if (file_exists(JPATH_SITE.'/'.$path.'com_fwgallerylight.css')) {
                    JHTML::stylesheet($path.'com_fwgallerylight.css');
				} else {
					JHTML::stylesheet(FWG_ASSETS.'css/style.css');
				}
				define('FWG_STYLESHEET_LOADED', true);
    		}
    	}
        static function encodeDate($date) {
            if (!$date or (is_string($date) and $date[0]=='0')) {
                return '';
            } else {
	        	$params = new JFParams;
	        	$date_format = str_replace(array(
					'%Y',
					'%d',
					'%B'
				), array(
					'Y',
					'd',
					'F'
				), $params->get('date_format'));
                return JHTML::date($date, $date_format);
            }
        }
        static function decodeDate($date) {
            if ($date = explode('/', $date) and count($date) == 3) {
                return $date[2].'-'.$date[0].'-'.$date[1];
            } elseif ($date = @strtotime($date)) {
                return date('Y-m-d', $date);
            } else {
                return '';
            }
        }
        static function getFileMimeImage($file) {
            if (preg_match('/\.([^\.]{2,4})$/',$file->filename,$m)) {
                $filename = 'images/mime/'.$m[1].'_icon.png';
                if (file_exists(JPATH_COMPONENT_SITE.'/'.$filename)) {
                    return FWG_ASSETS.$filename;
                } else {
                    return FWG_ASSETS.'images/mime/file_icon.png';
                }
            }
        }
        static function isFileExists($file) {
			$path = 'files/'.$file->_user_id.'/';
			if ($file->original_filename and file_exists(FWG_STORAGE_PATH.$path.$file->original_filename)) {
				return true;
			}
        }
        static function getFileFilename($file, $prefix='') {
			$path = 'files/'.$file->_user_id.'/';
			if ($file->original_filename and file_exists(FWG_STORAGE_PATH.$path.$file->original_filename)) {
				return FWG_STORAGE.$path.$file->original_filename;
			} elseif (isset($file->parent) and $gallery = JFHelper::findGalleryImage($file, $prefix)) {
				return FWG_STORAGE.'files/'.$gallery->_user_id.'/'.$gallery->original_filename;
			} else {
				return FWG_ASSETS.'images/no_'.$prefix.'image.jpg';
			}
        }
		static function findGalleryImage($gallery, $prefix='') {
			static $galleries;
			if (!is_array($galleries)) {
				$db = JFactory::getDBO();
				$user = JFactory::getUser();
				$where = array(
					'p.published = 1'
				);
				if (!$user->authorise('core.login.admin')) {
					if ($user->id) {
						$where[] = '(p.gid = 0 OR p.gid IS NULL OR EXISTS(SELECT * FROM #__usergroups AS ug WHERE pg.lft=ug.lft AND ug.id IN ('.implode(',',$user->getAuthorisedGroups()).')))';
					} else $where[] = '(p.gid = 0 OR p.gid IS NULL)';
				}
				$db->setQuery('
SELECT
	p.id,
	p.user_id AS _user_id,
	p.parent,
	CASE WHEN (SELECT f.id FROM #__fwg_files AS f WHERE f.project_id = p.id AND selected = 1 LIMIT 1) IS NOT NULL THEN (SELECT f.id FROM #__fwg_files AS f WHERE f.project_id = p.id AND selected = 1 LIMIT 1) ELSE (SELECT id FROM #__fwg_files AS f WHERE f.project_id = p.id ORDER BY ordering LIMIT 1) END AS _file_id,
	CASE WHEN (SELECT filename FROM #__fwg_files AS f WHERE f.project_id = p.id AND selected = 1 LIMIT 1) IS NOT NULL THEN (SELECT filename FROM #__fwg_files AS f WHERE f.project_id = p.id AND selected = 1 LIMIT 1) ELSE (SELECT filename FROM #__fwg_files AS f WHERE f.project_id = p.id ORDER BY ordering LIMIT 1) END AS filename,
	CASE WHEN (SELECT original_filename FROM #__fwg_files AS f WHERE f.project_id = p.id AND selected = 1 LIMIT 1) IS NOT NULL THEN (SELECT original_filename FROM #__fwg_files AS f WHERE f.project_id = p.id AND selected = 1 LIMIT 1) ELSE (SELECT original_filename FROM #__fwg_files AS f WHERE f.project_id = p.id ORDER BY ordering LIMIT 1) END AS original_filename
FROM
	#__fwg_projects AS p
	LEFT JOIN #__usergroups AS pg ON (pg.id = p.gid)
WHERE
	'.implode(' AND ', $where));
				$galleries = (array)$db->loadObjectList();
			}
			/* first, look all child galleries */
			foreach ($galleries as $g) {
				if ($g->parent == $gallery->id and !empty($g->_file_id)) {
					return $g;
				}
			}
			/* second - look all grand child galleries */
			foreach ($galleries as $g) {
				if ($g->parent == $gallery->id and $buff = JFHelper::findGalleryImage($g, $prefix)) {
					return $buff;
				}
			}

		}
        static function getLogoViewFilename($obj=null) {
            if ($obj and $obj->logo and file_exists(FWG_STORAGE_PATH.'files/'.$obj->user_id.'/'.$obj->logo)) {
                return FWG_STORAGE.'files/'.$obj->user_id.'/'.$obj->logo;
            } else {
                return FWG_ASSETS.'images/no_th_image.jpg';
            }
        }
        static function getWatermarkFilename() {
        	$params = JComponentHelper::getParams('com_fwgallerylight');
        	if ($watermark = $params->get('watermark_file')) {
        		if (file_exists(FWG_STORAGE_PATH.$watermark)) {
        			return FWG_STORAGE.$watermark;
        		}
        	}
        }
        function loadGalleries() {
        	static $galleries;
        	if (!is_array($galleries)) {
        		$db = JFactory::getDBO();
        		$db->setQuery('SELECT * FROM #__fwg_projects');
        		$galleries = (array)$db->loadObjectList();
        	}
        	return $galleries;
        }
        function getParentGalleriesIDS($gallery_id) {
        	$ids = array();

        	$galleries = JFHelper::loadGalleries();
        	if ($galleries) foreach ($galleries as $gallery) {
        		if ($gallery->id == $gallery_id) {
        			$ids[] = $gallery->parent;
        			break;
        		}
        	}
        	if ($ids) {
				do {
					$found = false;
					foreach ($galleries as $gallery) {
						if ($gallery->parent and in_array($gallery->id, $ids) and !in_array($gallery->parent, $ids)) {
							$ids[] = $gallery->parent;
							$found = true;
						}
					}
				} while($found);
        	}
        	return $ids;
        }
        function getItemid($view='galleries', $id=0, $default=0, $exact=false) {
        	$menu = JMenu::getInstance('site');
        	$item = null;
        	if ($items = $menu->getItems('link', 'index.php?option=com_fwgallerylight&view='.$view)) {
				if ($id) {
/* first, have a look for direct hit */
	        		foreach ($items as $menuItem) {
	    				if ((is_string($menuItem->params) and preg_match('/id\='.$id.'\s/ms', $menuItem->params))
	    				 or (is_object($menuItem->params) and $id == $menuItem->params->get('id'))) {
		        			$item = $menuItem;
		        			break;
	    				}
	        		}
/* then have a look for a any existing parent hit */
	        		if (!$item and $view == 'gallery') {
	        			if ($ids = JFHelper::getParentGalleriesIDS($id)) {
			        		foreach ($items as $menuItem) {
			    				if ((is_string($menuItem->params) and preg_match('/id\='.implode('|', $ids).'\s/ms', $menuItem->params))
			    				 or (is_object($menuItem->params) and in_array($menuItem->params->get('id'), $ids))) {
				        			$item = $menuItem;
				        			break;
			    				}
			        		}
	        			}
	        		}
				}
/* then take first menu element */
        		if (!$item) $item = $items[0];
        	}
        	if (!$exact and !$item) $item = $menu->getItems('link', 'index.php?option=com_fwgallerylight&view=galleries', true);

        	if ($item) return $item->id;
        	elseif ($default) return $default;
        	elseif (!$exact and $item = $menu->getActive()) return $item->id;
        }
		static function getIP() {
			if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
			    $ip = getenv('HTTP_CLIENT_IP');
			else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
			    $ip = getenv('REMOTE_ADDR');
			else if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
			    $ip = getenv('HTTP_X_FORWARDED_FOR');
			else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
			    $ip = $_SERVER['REMOTE_ADDR'];
			else
				$ip = '127.0.0.1';
			return $ip;
		}
		static function getGps($exifCoord, $hemi) {
		    $degrees = count($exifCoord) > 0 ? JFHelper::gps2Num($exifCoord[0]) : 0;
		    $minutes = count($exifCoord) > 1 ? JFHelper::gps2Num($exifCoord[1]) : 0;
		    $seconds = count($exifCoord) > 2 ? JFHelper::gps2Num($exifCoord[2]) : 0;
		    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;
		    return floatval($flip * ($degrees +($minutes/60)+($seconds/3600)));
		}
		static function gps2Num($coordPart) {
		    $parts = explode('/', $coordPart);
		    if (count($parts) <= 0)
		        return 0;
		    else if (count($parts) == 1)
		        return $parts[0];
		    else
		    	return floatval($parts[0]) / floatval($parts[1]);
		}
		static function getGalleryColor($id) {
			static $galleries;
			if (!is_array($galleries)) {
				$db = JFactory::getDBO();
				$db->setQuery('SELECT id, color, parent FROM #__fwg_projects ORDER BY parent');
				$galleries = $db->loadObjectList('id');
			}

			if (isset($galleries[$id])) {
				if ($galleries[$id]->color and preg_match('/[0-9a-fA-F]{3,6}/', $galleries[$id]->color)) return $galleries[$id]->color;
				else return JFHelper::getGalleryColor($galleries[$id]->parent);
			} else {
				$params = new JFParams;
				return $params->get('gallery_color');
			}
		}
		static function request($url) {
			if (function_exists('curl_init')) {
				$ch = curl_init();
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec ($ch);
				curl_close($ch);
				return $result;
			} elseif (function_exists('file_get_contents')) {
				return file_get_contents($url);
			}
		}
		static function detectIphone() {
			if ($user_agent = JArrayHelper::getValue($_SERVER, 'HTTP_USER_AGENT')) {
				$mobile_oses = array('iPhone','iPod','iPad','iPaid');
				foreach ($mobile_oses as $wos) if (strpos($user_agent, $wos) !== false) {
					return true;
				}
			}
		}
		static function humanFileSize($val) {
			if ($val > 1073741824) return round($val / 1073741824, 2).' Gb';
			if ($val > 1048576) return round($val / 1048576, 2).' Mb';
			elseif ($val > 1024) return round($val / 1024, 2).' Kb';
			elseif ($val and is_numeric($val)) return $val.' b';
			else return $val;
		}
		static function clearImageCache($id = null) {
			jimport('joomla.filesystem.folder');
			jimport('joomla.filesystem.file');
			$cache_path = JPATH_SITE.'/cache/fwgallery/images/';
			if (is_dir($cache_path)) {
				if ($files = JFolder::files($cache_path, $id?('^'.$id.'_'):'.*')) {
					foreach ($files as $file) {
						JFile::delete($cache_path.$file);
					}
				}
			}
		}
		static function findUnicFilename($path, $filename) {
			jimport('joomla.filesystem.filename');
			$ext = strtolower(JFile::getExt($filename));
			$name = strtolower(trim(JFile::makeSafe(JFile::stripExt($filedata['name'])), '-'));
			$result = '';
			$index = 0;
			do {
				$result = $name.(($index > 0)?('-'.$index):'').($ext?('.'.$ext):'');
				$index++;
			} while (file_exists($path.$result));
			return $result;
		}
		static function findRandUnicFilename($path, $ext) {
			$result = '';
			do {
				$result = md5(rand()).($ext?('.'.$ext):'');
			} while (file_exists($path.$result));
			return $result;
		}
    }
}
if (!function_exists('apache_request_headers')) {
    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        return($arh);
    }
}
