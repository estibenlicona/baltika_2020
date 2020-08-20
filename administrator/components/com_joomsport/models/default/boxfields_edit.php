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
// No direct access.
defined('_JEXEC') or die;

require dirname(__FILE__).'/../models.php';

class boxfields_editJSModel extends JSPRO_Models
{
    public $_data = null;
    public $_lists = null;
    public $_mode = 1;
    public $_id = null;
    public function __construct()
    {
        parent::__construct();

        $this->getData();
    }

    public function getData()
    {
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        $is_id = $cid[0];

        $row = new JTableBoxFields($this->db);
        $row->load($is_id);
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }
        $published = ($row->id) ? $row->published : 1;
        $displayonfe = ($row->id) ? $row->displayonfe : 1;
        $complex = ($row->id) ? $row->complex : 0;
        $parent = ($row->id) ? $row->parent_id : 0;
        $ftype = ($row->id) ? $row->ftype : 0;
        $Coptions = ($row->id) ? json_decode($row->options, true) : array();
        $this->_lists['published'] = JHTML::_('select.booleanlist',  'published', 'class="inputbox"', $published, JText::_('JPUBLISHED'), JText::_('JUNPUBLISHED'));
        $this->_lists['displayonfe'] = JHTML::_('select.booleanlist',  'displayonfe', 'class="inputbox"', $displayonfe);
        
        $this->_lists['complex'] = JHTML::_('select.booleanlist',  'complex', 'class="inputbox" onchange="boxfield_hide();"', $complex);
        
        $is_parent[] = JHTML::_('select.option',  0, JText::_('BLBE_NOPARENT'), 'id', 'name');
            
        $this->db->setQuery("SELECT * FROM #__bl_box_fields WHERE complex='1' ORDER BY name");
        $parents = $this->db->loadObjectList();
        if (count($parents)) {
            $is_parent = array_merge($is_parent, $parents);
        }
        $this->_lists['parent'] = JHTML::_('select.genericlist',   $is_parent, 'parent_id', 'class="inputbox" size="1"', 'id', 'name', $parent);

        $this->_lists['calctype'] = JHTML::_('select.booleanlist',  'ftype', 'class="inputbox" onchange="boxfield_type_hide();"', $ftype, JText::_('BLBE_BOXFLD_COMPLEX'), JText::_('BLBE_BOXFLD_SUM'));
        
        $simplex[] = JHTML::_('select.option',  0, JText::_('BLBE_BOX_SELECT_FIELD'), 'id', 'name');
        
        $this->db->setQuery("SELECT * FROM #__bl_box_fields WHERE complex='0' AND ftype='0' ORDER BY name");
        $depend = $this->db->loadObjectList();
        if (count($depend)) {
            $simplex = array_merge($simplex, $depend);
        }
        $this->_lists['depend1'] = JHTML::_('select.genericlist',   $simplex, 'options[depend1]', 'class="inputbox" size="1"', 'id', 'name', (isset($Coptions['depend1'])?$Coptions['depend1']:0));
        $this->_lists['depend2'] = JHTML::_('select.genericlist',   $simplex, 'options[depend2]', 'class="inputbox" size="1"', 'id', 'name', (isset($Coptions['depend2'])?$Coptions['depend2']:0));
        
        
        $calc[] = JHTML::_('select.option',  0, '/', 'id', 'name');
        $calc[] = JHTML::_('select.option',  1, '*', 'id', 'name');
        $calc[] = JHTML::_('select.option',  2, '+', 'id', 'name');
        $calc[] = JHTML::_('select.option',  3, '-', 'id', 'name');
        $calc[] = JHTML::_('select.option',  4, '"/"', 'id', 'name');
        $this->_lists['calc'] = JHTML::_('select.genericlist',   $calc, 'options[calc]', 'class="inputbox" size="1" style="width:55px"', 'id', 'name', (isset($Coptions['calc'])?$Coptions['calc']:0));
        
        $query = "SELECT cfg_value FROM #__bl_config WHERE cfg_name='boxExtraField'";
        $this->db->setQuery($query);

        $boxExtraField = $this->db->loadResult();
        $this->_lists['efs2'] = null;
        if($boxExtraField){
            $query = "SELECT sel_value as name,id"
                    . " FROM #__bl_extra_select"
                . " WHERE fid=".intval($boxExtraField)
                . " ORDER BY eordering,id";
            $this->db->setQuery($query);
            $efs = $this->db->loadObjectList();
            $hval = isset($Coptions['extraVals'])?$Coptions['extraVals']:array();
           
            $this->_lists['efs2'] = JHTML::_('select.genericlist',   $efs, 'options[extraVals][]', 'class="inputbox" multiple size="1"', 'id', 'name', $hval);

        }
        
        $this->_lists['languages'] = $this->getLanguages();
        $this->_lists['translation'] = array();
        if(count($this->_lists['languages']) && $row->id){
            $this->_lists['translation'] = $this->getTranslation('boxfields_'.$row->id);
           
        }
        
        $this->_data = $row;
    }
    public function orderFields()
    {
        $cid = JRequest::getVar('cid', array(), 'post', 'array');
        $order = JRequest::getVar('order', array(), 'post', 'array');
        $row = new JTableBoxFields($this->db);
        $total = count($cid);

        if (empty($cid)) {
            return JError::raiseWarning(500, JText::_('No items selected'));
        }
        // update ordering values
        for ($i = 0; $i < $total; ++$i) {
            $row->load((int) $cid[$i]);
            if ($row->ordering != $order[$i]) {
                $row->ordering = $order[$i];
                if (!$row->store()) {
                    return JError::raiseError(500, $this->db->getErrorMsg());
                }
                // remember to reorder this category
            }
        }
    }

    public function saveFields()
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            return JError::raiseError(303, '');
        }
        $post = JRequest::get('post');
        $post['options'] = json_encode(JRequest::getVar('options', array(), 'post', 'array'));
        $row = new JTableBoxFields($this->db);

        

        if (!$row->bind($post)) {
            JError::raiseError(500, $row->getError());
        }

        if (!$row->check()) {
            JError::raiseError(500, $row->getError());
        }
        // if new item order last in appropriate group
        if (!$row->store()) {
            JError::raiseError(500, $row->getError());
        }
        $row->checkin();
        
        if($row->complex != 1){
            $tblCOl = 'boxfield_'.$row->id;
            $this->db->setQuery("SHOW COLUMNS FROM #__bl_box_matches LIKE '".$tblCOl."'");
            $is_col = $this->db->loadResult();
            if (!$is_col) {
                $this->db->setQuery('ALTER TABLE #__bl_box_matches ADD `'.$tblCOl."`  FLOAT NULL DEFAULT NULL");
                $this->db->query();
            }
        }
        
        //translation
        if(isset($_POST['translation']) && count($_POST['translation'])){
            $this->db->setQuery(
                    "DELETE FROM #__bl_translations WHERE jsfield='boxfields_".$row->id."'"
                    );
            $this->db->query();
            foreach ($_POST['translation'] as $key => $value) {
                $translation = json_encode($value);
                $translation = nl2br($translation);
                $translation = str_replace("\r\n", "", $translation);
                
                $this->db->setQuery(
                        "INSERT INTO #__bl_translations(jsfield,translation,languageID)"
                        ." VALUES('boxfields_".$row->id."','".addslashes($translation)."','".$key."')"
                        );
                $this->db->query();
            }
        }
        
        
        $this->_id = $row->id;
    }
}
