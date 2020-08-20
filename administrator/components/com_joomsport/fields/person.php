<?php
/*------------------------------------------------------------------------
# JoomSport Professional 
# ------------------------------------------------------------------------
# BearDev development company 
# Copyright (C) 2011 JoomSport.com. All Rights Reserved.
# @license - http://joomsport.com/news/license.html GNU/GPL
# Websites: http://www.JoomSport.com 
# Technical Support:  Forum - http://joomsport.com/helpdesk/
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.form.formfield');
class JFormFieldPerson extends JFormField
{
    /**
     * Element name.
     *
     * @var string
     */
    protected $type = 'person';
    protected function getInput()
    {
        $db = JFactory::getDBO();
        $doc = JFactory::getDocument();

        
        $query_add = "SELECT id,CONCAT(first_name,' ',last_name) as name "
                     .' FROM #__bl_persons'
                     .' ORDER BY first_name';

        $db->setQuery($query_add);
        $leagues = $db->loadObjectList();

        $leagues_bulk[] = JHTML::_('select.option', '', JText::_('BLBE_SELPERSON'),"id","name");
        if($leagues){
            $leagues_bulk = array_merge($leagues_bulk,$leagues);
        }
        
        $html = JHTML::_('select.genericlist',   $leagues_bulk, $this->name, ' size="1" required="required"', 'id', 'name', $this->value);
        return $html;
        
    }
}
