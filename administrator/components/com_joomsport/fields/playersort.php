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
class JFormFieldPlayerSort extends JFormField
{
    /**
     * Element name.
     *
     * @var string
     */
    protected $type = 'playersort';
    protected function getInput()
    {
        $db = JFactory::getDBO();
        $doc = JFactory::getDocument();

        $query = "SELECT CONCAT('efields_', id) as id,CONCAT(name) as name FROM #__bl_extra_filds WHERE display_playerlist='1' AND type='0' ORDER BY ordering";
        $db->setQuery($query);
        $efields = $db->loadObjectList();

        $query = 'SELECT DISTINCT(ev.id),ev.id, ev.e_name as name'
                        .' FROM #__bl_events as ev, #__bl_match_events as me, #__bl_match as m, #__bl_matchday as md'
                        .' WHERE (ev.id = me.e_id OR (ev.sumev1 = me.e_id OR ev.sumev2 = me.e_id)) AND me.match_id = m.id'
                        .' AND m.m_id=md.id  AND (ev.player_event = 1 OR ev.player_event = 2)'
                        .' ORDER BY ev.ordering';
        $db->setQuery($query);
        $events = $db->loadObjectList();
        $error = $db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }

        $selectbox[] = JHtml::_('select.option', 0, JText::_('BLBE_FIRSTNAME'), 'id', 'name');

        if (count($efields)) {
            $selectbox = array_merge($selectbox, $efields);
        }
        if (count($events)) {
            $selectbox = array_merge($selectbox, $events);
        }

        $html = JHTML::_('select.genericlist',   $selectbox, 'jform[request][playersort]', 'class="inputbox" size="1" ', 'id', 'name', $this->value);

        return $html;
    }
}
