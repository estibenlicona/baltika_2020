<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewmatchday_edit extends JViewLegacy
{
	var $_model = null;
	function __construct(& $model){
		$this->_model = $model;
	}
	function display($tpl = null)
	{
		global $mainframe, $option;

		$db		= JFactory::getDBO();
		$uri	= JFactory::getURI();

		// Get data from the model
		$items		= $this->_model->_data;
		$lists		= $this->_model->_lists;
	
		$this->addToolbar($this->_model->_mode);
		
		
		$editor = JEditor::getInstance();
		$this->assignRef('editor',		$editor);
		$this->assignRef('lists',		$lists);
		$this->assignRef('row',		$items);
		
                if(isset($lists['race']['tmpl'])){
                    $tpl = $lists['race']['tmpl'];
                }
                
		require_once(dirname(__FILE__).'/tmpl/default'.($tpl?"_".$tpl:"").'.php');
	}
	
	protected function addToolbar($edit)
	{
		$text = ( $edit ? JText::_( 'BLBE_EDIT' ) : JText::_( 'BLBE_NEW' ) );
		JToolBarHelper::title( JText::_( 'BLBE_MATCHDAY' ).': <small><small>[ '. $text.' ]</small></small>', 'match.png' );
		JToolBarHelper::apply('matchday_apply');
		JToolBarHelper::save('matchday_save');
		
		$version = new JVersion;
		$joomla_v = $version->getShortVersion();
		if(substr($joomla_v,0,3) >= '1.7'){
			JToolBarHelper::save2new('matchday_save_new');
		}else{
			JToolBarHelper::save('matchday_save_new',JText::_("JSTOOL_SAVE_NEW"));
		}
		if ( $edit ) {
			JToolBarHelper::cancel( 'matchday_list', JText::_('BLBE_CLOSE') );
		} else {
			JToolBarHelper::cancel('matchday_list');
		}
		
	}
}