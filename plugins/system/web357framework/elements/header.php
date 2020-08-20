<?php
/* ======================================================
# Web357 Framework for Joomla! - v1.7.6 (Free version)
# -------------------------------------------------------
# For Joomla! CMS
# Author: Web357 (Yiannis Christodoulou)
# Copyright (©) 2009-2019 Web357. All rights reserved.
# License: GPLv2 or later, http://www.gnu.org/licenses/gpl-2.0.html
# Website: https:/www.web357.com/
# Demo: https://demo.web357.com/joomla/web357framework
# Support: support@web357.com
# Last modified: 23 Apr 2019, 11:29:38
========================================================= */

defined('JPATH_BASE') or die;

require_once(JPATH_PLUGINS . DIRECTORY_SEPARATOR . "system" . DIRECTORY_SEPARATOR . "web357framework" . DIRECTORY_SEPARATOR . "elements" . DIRECTORY_SEPARATOR . "elements_helper.php");

jimport('joomla.form.formfield');

class JFormFieldHeader extends JFormField {
	
	function getInput()
	{
		return "";
	}

	function getLabel()
	{
		// Retrieving request data using JInput
		$jinput = JFactory::getApplication()->input;

		if (method_exists($this, 'fetchTooltip')):
			$label = $this->fetchTooltip($this->element['label'], $this->description, $this->element, $this->options['control'], $this->element['name'] = '');
		else:
			$label = parent::getLabel();
		endif;
		
		// Get Joomla's version
		$jversion = new JVersion;
		$short_version = explode('.', $jversion->getShortVersion()); // 3.8.10
		$mini_version = $short_version[0].'.'.$short_version[1]; // 3.8
		
		if (version_compare($mini_version, "2.5", "<=")) :
			// v2.5
			$jversion_class = 'vj25x';
		elseif (version_compare($mini_version, "3.0", "<=")) :
			// v3.0.x
			$jversion_class = 'vj30x';
		elseif (version_compare($mini_version, "3.1", "<=")) :
			// v3.1.x
			$jversion_class = 'vj31x';
		elseif (version_compare($mini_version, "3.2", "<=")) :
			// v3.2.x
			$jversion_class = 'vj32x';
		elseif (version_compare($mini_version, "3.3", "<=")) :
			// v3.3.x
			$jversion_class = 'vj33x';
		elseif (version_compare($mini_version, "3.4", "<=")) :
			// v3.4.x
			$jversion_class = 'vj34x';
		else:
			// other
			$jversion_class = 'j00x';
		endif;
		
		// There are two types of class, the w357_large_header, w357_small_header, w357_xsmall_header.
		$class = (!empty($this->element['class'])) ? $this->element['class'] : '';
		
		// Output
		return '<div class="w357frm_param_header '.$class.' '.$jversion_class.' '.$jinput->get('option').'">'.$label.'</div>';
	}

}