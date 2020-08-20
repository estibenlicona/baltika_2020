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
class JFormFieldPlayerEvents extends JFormField
{
    /**
     * Element name.
     *
     * @var string
     */
    protected $type = 'playerevents';
    protected function getInput()
    {
        $options = array(
                JHtml::_('select.option', 'yes', JText::_('JYES')),
                JHtml::_('select.option', 'no', JText::_('JNO')),
            );

        //$html = JHTML::_('select.genericlist',   $selectbox, 'jform[params][playersort]', 'class="inputbox" size="1" ', 'id', 'name', $this->value);
                $html = JHTML::_('select.booleanlist',  'jform[request][playerevents]', 'class=""', $this->value);

        return $html;
    }
}
