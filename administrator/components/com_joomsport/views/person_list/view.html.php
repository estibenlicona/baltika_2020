<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class JoomsportViewperson_list extends JViewLegacy
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
        $total = $this->_model->_total;
        $pagination = $this->_model->_pagination;

        $this->addToolbar();
        $user = JFactory::getUser();
        $this->assignRef('user',        $user);
        $this->assignRef('lists',        $lists);
        $this->assignRef('rows',        $items);
        $this->assignRef('page',    $pagination);

        require_once dirname(__FILE__).'/tmpl/default'.($tpl ? '_'.$tpl : '').'.php';
    }

    protected function addToolbar()
    {
        if (JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            JToolBarHelper::addNew('person_add');
            JToolBarHelper::editList('person_edit');
        }
        JToolBarHelper::title(JText::_('BLBE_PERSON_LIST'), 'player.png');
        if (JFactory::getUser()->authorise('core.delete', 'com_joomsport')) {
            JToolBarHelper::deleteList('', 'person_del', JText::_('BLBE_DELETE'));
        }
    }
}
