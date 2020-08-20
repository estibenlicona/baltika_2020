<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewImage extends JViewLegacy {
	function display($tmpl=null) {
		$model = $this->getModel();
		switch ($this->getLayout()) {
			case 'image' :
				if ($filename = $model->getImage()) {
					$send_data = true;
					if (!headers_sent()) {
						$headers = apache_request_headers();
						$time = filemtime($filename);
						$info = getimagesize($filename);
						header('Cache-Control: public');
						header("Pragma: cache");
						if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $time)) {
							header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 304);
							$send_data = false;
						} else {
							header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 200);
						}
						header('Content-Length: '.filesize($filename));
						header("Content-type: {$info['mime']}");
					}
					if ($send_data) print file_get_contents($filename);
				}
			break;
		}
		die();
	}
}
