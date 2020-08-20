<?php
/**
 * FW Gallery Light 3.5.0
 * @copyright (C) 2017 Fastw3b
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.fastw3b.net/ Official website
 **/
defined('_JEXEC') or die('Restricted access');

function fwGallerylightBuildRoute(&$query) {
	$menu = JMenu :: getInstance('site');
	$menuItem = null;
	if (empty($query['Itemid'])) $menuItem = $menu->getActive();
	else $menuItem = $menu->getItem($query['Itemid']);

	$segments = array();
	$view = JArrayHelper :: getValue($query, 'view');
	$layout = JArrayHelper :: getValue($query, 'layout');
	if ($view) {
		if (empty($menuItem->query) or (!empty($menuItem->query) and $view != JArrayHelper :: getValue($menuItem->query, 'view'))) $segments[] = $query['view'];
		unset($query['view']);
	}
	if ($layout) {
		if (empty($menuItem->query) or (!empty($menuItem->query) and $layout != JArrayHelper :: getValue($menuItem->query, 'layout'))) $segments[] = $query['layout'];
		unset($query['layout']);
	}
	if (!empty($query['id'])) {
		if (!(!empty($menuItem->params) and $view == JArrayHelper :: getValue($menuItem->query, 'view') and preg_match('/id\='.(int)$query['id'].'\s/ms', $menuItem->params))) $segments[] = $query['id'];
		unset($query['id']);
	}
	return $segments;
}

function fwGallerylightParseRoute($segments) {
	$vars = array();
	$views = array(
		'galleries'=>array('list'),
		'gallery'=>array('modal','modal_add','modal_image_add','list','glist'),
		'image'=>array('default','print','image')
	);
	$menu = JMenu :: getInstance('site');
	if ($item = $menu->getActive()) {
		foreach ($item->query as $key=>$val) {
			$vars[$key] = $val;
		}
	}

	if (!empty($segments[0])) {
		if (!empty($vars['view']) and $vars['view'] == $segments[0]) {
			$vars['layout'] = $segments[0];
			if (!empty($segments[1])) {
				$vars['id'] = $segments[1];
			}
		} elseif (in_array($segments[0], array_keys($views))) {
			$vars['view'] = $segments[0];
			if (!empty($segments[1])) {
				if (in_array($segments[1], $views[$segments[0]])) {
					$vars['layout'] = $segments[1];
					if (!empty($segments[2])) $vars['id'] = $segments[2];
				} else {
					$vars['id'] = $segments[1];
				}
			}
		} else {
			if (!empty($vars['view'])) {
				if (in_array($segments[0], $views[$vars['view']])) {
					$vars['layout'] = $segments[0];
				} else $vars['id'] = $segments[0];
			} else $vars['id'] = $segments[0];
		}
	}
//print_r($segments); print_r($vars); die();
	return $vars;
}
