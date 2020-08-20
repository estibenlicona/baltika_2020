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
		$data = new stdclass;
    	switch ($this->getLayout()) {
			case 'check_update' :
			$data = (object)$model->checkUpdate();
			$data->msg = $model->getError();
			break;
			case 'verify_code' :
			$data = (object)$model->verifyCode();
			$data->msg = $model->getError();
			break;
			case 'revoke_code' :
			$data = (object)$model->revokeCode();
			$data->msg = $model->getError();
			break;
			case 'update_package' :
			$data->result = $model->updatePackage();
			$data->msg = $model->getError();
			break;
		}
    	die(json_encode($data));
    }
}
