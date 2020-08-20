<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewConfig extends JViewLegacy {
    function display($tmpl=null) {
        $model = $this->getModel();
		$input = JFactory::getApplication()->input;
		switch ($this->getLayout()) {
			case 'manualresize' :
				$this->assign('step', $input->getInt('step', 1));
				$this->assign('images', $model->loadImages());
				if ($this->step == 2) {
					$this->assign('result', $model->resize($this->images));
					echo $this->loadTemplate('step2');
				} else {
					echo $this->loadTemplate('step1');
				}
			break;
		}
        die();
    }
}
