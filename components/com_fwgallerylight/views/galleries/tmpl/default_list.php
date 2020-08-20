<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/
defined('_JEXEC') or die('Restricted access');

foreach ($this->list as $row) {
    $this->row = $row;
    echo $this->loadTemplate('item');
}
