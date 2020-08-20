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
        $app = JFactory::getApplication();
		$input = $app->input;

        $this->assign('row', $model->getObj());
        if (!$this->row->id) {
        	$app->redirect(
        		JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=galleries&Itemid='.JFHelper :: getItemid('galleries'), false)),
        		JText :: _('FWG_IMAGE_NOT_FOUND_OR_ACCESS_DENIED')
    		);
        }

        $this->user = JFactory::getUser();
        $this->own = $this->row->_user_id == $this->user->id;
        $this->previous_image = $model->getPreviousImage($this->row);
        $this->next_image = $model->getNextImage($this->row);

		$this->params = new JFParams;

		switch ($this->params->get('comments_type')) {
			case 'disqus' :
				$this->assign('comments_type', 'disqus');
			break;
			default :
				$this->assign('comments_type', '');
		}

		$doc = JFactory :: getDocument();
		$doc->setTitle($this->row->name);

        $pathway = $app->getPathway();
        if ($this->params->get('id') != $this->row->id) {
        	if ($path = $model->loadCategoriesPath($this->row->project_id)) foreach ($path as $item) {
		        $pathway->addItem(
		        	$item->name,
		        	JFHelper::checkLink(JRoute::_('index.php?option=com_fwgallerylight&view=gallery&id='.$item->id.':'.JFilterOutput :: stringURLSafe($item->name).
						'&Itemid='.JFHelper :: getItemid('gallery', $item->id, $input->getInt('Itemid')).'#fwgallerytop'))
				);
        	}
	        $pathway->addItem($this->row->name);
        }
        if ($this->params->get('display_social_sharing')) {
			if ($doc->_links) foreach ($doc->_links as $key => $val) {
				if ($val['relation'] == 'canonical') {
					unset($doc->_links[$key]);
					break;
				}
			}
			$uri = JURI :: getInstance();
			$doc->addCustomTag('<meta property="og:url" content="'.$uri->toString().'"/>');
			$doc->addCustomTag('<meta property="og:image" content="'.JRoute::_('index.php?option=com_fwgallerylight&view=image&layout=image&format=raw&id='.$this->row->id, false, -2).'"/>');
			$doc->addCustomTag('<meta property="og:title" content="'.$this->escape($this->row->name).'"/>');
			if ($this->row->descr) $doc->addCustomTag('<meta property="og:description" content="'.$this->escape($this->row->descr).'"/>');
        }
        parent::display($tmpl);
    }
    function download() {
        $model = $this->getModel();
        $image = $model->getObj();
        if ($image->id and JFHelper :: isFileExists($image, 'orig')) {
			if (!headers_sent()) {
				jimport('joomla.filesystem.file');
				$ext = strtolower(JFile :: getExt($image->filename));
				if ($ext == 'jpeg') $ext = 'jpg';
				header('Content-type: image/'.$ext);
				header('Content-Disposition: attachment; filename="'.$image->filename.'"');
				header('Pragma: public');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Cache-Control: private', false);
			}
			echo file_get_contents(JFHelper :: getFileFilename($image, 'orig'));
	    	die();
        } else {
        	$app = JFactory :: getApplication();
        	$app->redirect(JFHelper::checkLink(JRoute :: _('index.php?option=com_fwgallerylight&view=galleries', false)), JText :: _('FWG_IMAGE_NOT_FOUND'));
        }
    }
}
