<?php
/**
 * @package		Youjoomla Extend Elements
 * @author		Youjoomla LLC
 * @website     Youjoomla.com 
 * @copyright	Copyright (c) 2007 - 2010 Youjoomla LLC.
 * @license   PHP files are GNU/GPL V2. CSS / JS / IMAGES are Copyrighted Commercial
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

/**
 * Renders a spacer element
 *
 * @package 	Joomla.Framework
 * @subpackage		Parameter
 * @since		1.5
 */

class JFormFieldYjSpacer extends JFormField
{
	/**
	* Element name
	*
	* @access	protected
	* @var		string
	*/
	
	var	$type = 'YjSpacer';

public function getInput()
	//function fetchElement($name, $value, &$node, $control_name)
	{
		$value = $this->value;
		$options = array ();
		if ($value) {
		$document = JFactory::getDocument();
		$document->addCustomTag('
		<style type="text/css">
		.yjspacer_holder{
			background:#fff;
			padding:5px;
			display:block;
			width:400px;
			text-align:center;
			overflow:hidden;
			margin:0 auto 10px auto;
			border:1px solid #DDDDDD;
			clear:both;
		}
		@media screen and (max-width:680px) {
			.yjspacer_holder{
				width:200px;
			}
		}
		.controls .button2-left.btn{
			margin-left:10px;
			margin-top:3px;
		}
		.controls .yjspacer_holder{
			float:left;
			font-family: Arial,sans-serif;
		}
		.tip-wrap,.tip{
			width:auto!important;
			text-align:left;
		}
		.yjspacer{
			padding:5px;
			background:#DEDEDE;
			border:1px solid #DDDDDD;
			text-shadow:1px 1px #fff;
			font-size:12px;
		}
		#module-sliders input{
			height:20px;
			line-height:20px;
			font-size:12px;
			padding:0 0 0 5px;
		}
		#module-sliders .inputbox{
			height:22px;
			line-height:20px;
			font-size:12px;
			}
		#module-sliders input,#module-sliders option{
			margin:0 5px 0 0;
		}
		#module-sliders .text_area{
			font-size:12px;
		}
		#module-sliders .button2-left{
			margin-top:3px;
		}
		</style>
		
		');
			return '<div class="yjspacer_holder"><div class="yjspacer">'.JText::_($value).'</div></div>';
		} else {
			return '<hr />';
		}
	}
}
