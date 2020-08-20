<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewboxfields_edit extends JViewLegacy
{
    public $_model = null;
    public function __construct(&$model)
    {
        $this->_model = $model;
    }
    public function display($tpl = null)
    {
        global $mainframe, $option;

        $db = JFactory::getDBO();
        $uri = JFactory::getURI();

        // Get data from the model
        $items = $this->_model->_data;
        $lists = $this->_model->_lists;

        $this->addToolbar($this->_model->_mode);

        $editor = JEditor::getInstance();
        $this->assignRef('editor',        $editor);
        $this->assignRef('lists',        $lists);
        $this->assignRef('row',        $items);

        require_once dirname(__FILE__).'/tmpl/default'.($tpl ? '_'.$tpl : '').'.php';
    }

    protected function addToolbar($edit)
    {
        $text = ($edit ? JText::_('BLBE_EDIT') : JText::_('BLBE_NEW'));
        JToolBarHelper::title(JText::_('BLBE_BOXMENAF').': <small><small>[ '.$text.' ]</small></small>', 'additional.png');
        if (JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            JToolBarHelper::apply('boxfields_apply');
            JToolBarHelper::save('boxfields_save');
            JToolBarHelper::save2new('boxfields_save_new');
            
        }
        if ($edit) {
            JToolBarHelper::cancel('boxfields_list', JText::_('BLBE_CLOSE'));
        } else {
            JToolBarHelper::cancel('boxfields_list');
        }
    }
}
