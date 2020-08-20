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
        $row = $model->getObj();
		$buff = new stdclass;

		switch ($this->getLayout()) {
		case 'check_fid' :
			$buff = $model->checkFid();
			break;
		case 'publish' :
			$buff->result = $model->publish();
			$buff->msg = $model->getError();
			break;
		case 'unpublish' :
			$buff->result = $model->unpublish();
			$buff->msg = $model->getError();
			break;
		case 'contact' :
			$buff->result = $model->contact();
			$buff->msg = $model->getError();
			break;
		case 'update' :
			$buff->result = $model->update();
			$buff->msg = $model->getError();
			break;
		case 'test' :
			$buff = $model->testGallery();
		break;
		case 'testUser' :
			$buff = $model->testUser();
		break;
		case 'getPlugins' :
			$buff->plugins = $model->getPlugins();
			$buff->msg = $model->getError();
		break;
		case 'save' :
			$buff->id = $model->save();
			$buff->msg = $model->getError();
		break;
		case 'delete' :
			$buff->result = $model->delete();
			$buff->msg = $model->getError();
		break;
		case 'buy' :
			$buff->link = $model->buy();
			$buff->msg = $model->getError();
		break;
		default:
			$buff->id = $row->id;
			$buff->height = $row->height;
			$buff->width = $row->width;
			$buff->size = $row->size;
			$buff->project_id = $row->project_id;
			$buff->name = $row->name;
			$buff->created = $row->created;
			$buff->com_link = JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=image&id='.$row->id.':'.JFilterOutput :: stringURLSafe($row->name)));
			$buff->link = JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$row->id, false, -2);
			$buff->th_link = JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$row->id.'&w=80&h=60', false, -2);
			$buff->color = JFHelper :: getGalleryColor($row->project_id);
			$buff->_user_name = $row->_user_name;
		}
		die(json_encode($buff));
    }
}
