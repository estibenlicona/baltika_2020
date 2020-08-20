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
class JFormFieldDaterange extends JFormField
{
    /**
     * Element name.
     *
     * @var string
     */
    protected $type = 'daterange';
    protected function getInput()
    {
        $html = '';
        $value = $this->value;
        
        $html = '<div>'
                . '<table>'
                . '<thead>'
                . '<tr>'
                . '<th>'
                . JText::_('BLBE_PAST')
                . '</th>'
                . '<th>'
                . JText::_('BLBE_TODAY')
                . '</th>'
                . '<th>'
                . JText::_('BLBE_FUTURE')
                . '</th>'
                . '</tr>'
                . '</thead>'
                . '<tbody>'
                . '<tr>'
                . '<td>'
                . '<input style="width:30px;" type="number" name="'.$this->name.'[past]" value="'.(isset($value['past'])?$value['past']:'').'" />'
                . '</td>'
                . '<td style="text-align:center;">'
                . '<input type="checkbox" name="'.$this->name.'[today]" value="1" '.((isset($value['today']) && $value['today'] == 1)?'checked':'').' />'
                . '</td>'
                . '<td>'
                . '<input style="width:30px;" type="number" name="'.$this->name.'[future]"  value="'.(isset($value['future'])?$value['future']:'').'" />'
                . '</td>'
                . '</tr>'
                . '</tbody>'
                . '</table>'
                . '</div>';
        
        return $html;
        
    }
}
