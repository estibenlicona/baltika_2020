<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

$i = 0;
$dd = false;
foreach ($this->list as $row) {
    $this->row = $row;
	$classes = array('fwg-single-gallery-item', 'fwg-loading');
	if ($this->item_class) {
		$classes[] = $this->item_class;
		if ($this->item_class == 'fwg-masonry' and (($i == 1 and rand(1, 2) == 1) or ($i == 3 and !$dd and rand(1, 2) == 1) or ($i == 4 and !$dd))) {
			$classes[] = 'fwg-double';
			$dd = true;
		}
	} else {
		if ($row->_type_name == 'Video') {
			$classes[] = 'fwg-item-video';
		} else {
			$classes[] = 'fwg-item-image';
		}
	}
?>
    <li class="<?php echo implode(' ', $classes); ?>" data-category="<?php echo $this->escape($row->_project_name); ?>">
<?php
    echo $this->loadTemplate('item');
?>
    </li>
<?php
	$i++;
}
