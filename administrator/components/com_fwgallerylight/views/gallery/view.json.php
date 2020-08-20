<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class fwGallerylightViewGallery extends fwgView {
    function display($tmpl=null) {
        $model = $this->getModel();
        $data = new stdclass;

        switch ($this->getLayout()) {
        case 'save' :
            $data->result = $model->save();
            $data->msg = $model->getError();
            break;
        }

        die(json_encode($data));
    }
}
