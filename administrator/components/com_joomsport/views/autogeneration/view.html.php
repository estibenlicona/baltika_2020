<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewautogeneration extends JViewLegacy
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

        $lists = $this->_model->_lists;

        $this->assignRef('lists',        $lists);
        $this->addToolbar();

        require_once dirname(__FILE__).'/tmpl/default'.($tpl ? '_'.$tpl : '').'.php';
    }
    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('Matchday generator'), 'config.png');
    }
}
