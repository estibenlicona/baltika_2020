<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewGallery extends JViewLegacy {
    function display($tmpl=null) {
        $model = $this->getModel();
        $app = JFactory::getApplication();
		$input = $app->input;

		switch ($this->getLayout()) {
		case 'publish' :
			$data = (object)array(
				'result' => $model->publish(),
				'msg' => $model->getError()
			);
			die(json_encode($data));
			break;
		case 'unpublish' :
			$data = (object)array(
				'result' => $model->unpublish(),
				'msg' => $model->getError()
			);
			die(json_encode($data));
			break;
		case 'acl' :
			$data = (array)JFHelper :: getGroups();
			die(json_encode($data));
			break;
		case 'update' :
			$data = (object)array(
				'result' => $model->update(),
				'msg' => $model->getError()
			);
			die(json_encode($data));
			break;
		case 'delete' :
			$data = (object)array(
				'result' => $model->delete(),
				'msg' => $model->getError()
			);
			die(json_encode($data));
        case 'save' :
            $data = (object)array(
                'result' => $model->save(),
                'msg' => $model->getError()
            );
            die(json_encode($data));
		break;
        case 'list' :
            $this->setLayout('default');
            $data = new stdclass;
            $this->params = new JFParams;
            $this->list = (array)$model->getList();
            $data->msg = $model->getError();
			$this->item_class = $input->getCmd('ic');

            $this->images_a_row = $this->params->get('images_a_row', 3);
            if (!$this->images_a_row) $this->images_a_row = 3;
            $this->images_columns = (int)(12 /$this->images_a_row);

            $data->html = $this->loadTemplate('list');
            die(json_encode($data));
        break;
        case 'glist' :
            $input->set('glimit', $input->getInt('limit'));
            $input->set('glimitstart', $input->getInt('limitstart'));
            $this->setLayout('default');
            $data = new stdclass;
            $this->params = new JFParams;
            $this->subcategories = (array)$model->loadSubCategories();
            $data->msg = $model->getError();

            $this->galleries_a_row = $this->params->get('galleries_a_row', 3);
            if (!$this->galleries_a_row) $this->galleries_a_row = 3;
            $this->columns = (int)(12 /$this->galleries_a_row);

            $data->html = $this->loadTemplate('glist');
            die(json_encode($data));
        break;
		default :
			$input->set('limit', '0');
			$input->set('limitstart', '0');
			$list = array(
				'images' => array()
			);
			if ($list['images'] = (array)$model->getList(false)) foreach ($list['images'] as $i=>$row) {
				$list['images'][$i]->descr = htmlentities(strip_tags($row->descr));
				$list['images'][$i]->link = JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$row->id, false, -2);
				$list['images'][$i]->color = JFHelper :: getGalleryColor($row->project_id);
			} else {
				$image = new stdclass;
				$image->descr = '';
				$image->link = '';
				$image->color = '';
				$image->id = 0;
				$image->project_id = 0;
				$image->type_id = 0;
				$image->user_id = 0;
				$image->published = '1';
				$image->ordering = 0;
				$image->hits = 0;
				$image->name = '';
				$image->filename = '';
				$image->created = date('Y-m-d');
				$image->longitude = 0;
				$image->latitude = 0;
				$image->selected = 0;
				$image->copyright = '';
				$image->link = JURI :: root(false).'components/com_fwgallerylight/assets/images/no_image.jpg';
				$list['images']['1'] = $image;
			}
			die(json_encode($list));
		}
    }
}
