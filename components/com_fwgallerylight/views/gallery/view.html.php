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
        $this->user = JFactory::getUser();
		$input = $app->input;

        /* collect data for a template */
        switch ($this->getLayout()) {
        case 'modal_add' :
        case 'modal_image_add' :
            JHTML :: _('behavior.core');
            JHTML :: _('behavior.formvalidation');
            JHTML :: addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers');
            JFactory :: getLanguage()->load('com_fwgallerylight', JPATH_ADMINISTRATOR);
            $this->project_id = $input->getInt('project_id');
            $this->function  = $input->getCmd('function', 'jSelectFwgallery');
            break;
        case 'modal' :
            JHTML :: _('behavior.core');
            JHTML :: addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.'/helpers');
            JFactory :: getLanguage()->load('com_fwgallerylight', JPATH_ADMINISTRATOR);
            $this->sublayout = $input->getCmd('sublayout', 'galleries');
            if (!in_array($this->sublayout, array('galleries', 'images'))) $this->sublayout = 'galleries';
            switch ($this->sublayout) {
                case 'images' :
                $this->files = $model->getFiles();
                $this->pagination = $model->getFilesPagination();
                $this->search = $input->getString('search');
                if ($this->search) {
                    $this->pagination->setAdditionalUrlParam('search', $this->search);
                }
		        $this->project_id = $model->getUserState('project_id', 0, 'int');
                if ($this->project_id) {
                    $this->pagination->setAdditionalUrlParam('project_id', $this->project_id);
                }
                $this->function  = 'jSelectFwgalleryImage';
                break;
                case 'galleries' :
                $this->galleries = $model->getGalleries();
                $this->pagination = $model->getGalleriesPagination();
                $this->search = $input->getString('search');
                if ($this->search) {
                    $this->pagination->setAdditionalUrlParam('search', $this->search);
                }
                $this->function  = 'jSelectFwgallery';
                break;
            }
        break;
        default :
            $this->obj = $model->getObj();
            if (!$this->obj->id) {
            	$app->redirect(
            		JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=galleries&Itemid='.JFHelper :: getItemid('galleries'), false))
        		);
            } elseif ($this->obj->gid and !in_array($this->obj->gid, $this->user->getAuthorisedGroups())) {
            	$app->redirect(
            		JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=galleries&Itemid='.JFHelper :: getItemid('galleries'), false)),
            		JText :: _('FWG_GALLERY_ACCESS_DENIED')
        		);
            } elseif (!$this->obj->published) {
            	$app->redirect(
            		JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=galleries&Itemid='.JFHelper :: getItemid('galleries'), false)),
            		JText :: _('FWG_GALLERY_NOT_PUBLISHED')
        		);
            }

    		$this->params = new JFParams;

            $menu = JMenu :: getInstance('site');
            $active_menu_item = $menu->getActive();

            $pathway = $app->getPathway();

/*    		if (!$active_menu_item or ($active_menu_item and strpos($active_menu_item->link, 'galleries') === false ) and $this->params->get('id') != $this->obj->id) {
    	        $pathway->addItem(JText::_('FWG_GALLERIES'), JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=galleries')));
    		}*/

    		$this->path = $model->loadCategoriesPath($this->obj->parent);
            if ($this->path and $this->params->get('id') != $this->obj->id){
            	 foreach ($this->path as $item) {
            	 	$pathway->addItem($item->name, JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=gallery&id='.$item->id.':'.JFilterOutput :: stringURLSafe($item->name).'&Itemid='.JFHelper :: getItemid('gallery', $item->id, $input->getInt('Itemid')))).'#fwgallerytop');
            	 }
            }

            if ($this->params->get('id') != $this->obj->id) {
				$pathway->addItem($this->obj->name);
				$this->title = $this->obj->name;
			} else {
				$this->title = '';
				if ($active_menu_item) {
					$this->title = $active_menu_item->title;
				} else {
					$this->title = $this->params->get('page_heading');
				}
				if (!$this->title) $this->title = $this->obj->name;
			}
//            $this->setLayout('default');
            $this->order = $model->getUserState('order', $this->params->get('ordering_images'));
            $this->subcategory_order = $model->getUserState('subcategory_order', $this->params->get('ordering_galleries'));
            $this->subcategories = $model->loadSubCategories();
            $this->gpagination = $model->getGPagination();
            $this->list = $model->getList();
            $this->pagination = $model->getPagination();
            $this->new_days = $this->params->get('new_days');

            $this->galleries_a_row = $this->params->get('galleries_a_row', 3);
            if (!$this->galleries_a_row) $this->galleries_a_row = 3;
            $this->columns = (int)(12 /$this->galleries_a_row);

            $this->images_a_row = $this->params->get('images_a_row', 3);
            if (!$this->images_a_row) $this->images_a_row = 3;
            $this->images_columns = (int)(12 /$this->images_a_row);
        }

        parent::display($tmpl);
    }
}
