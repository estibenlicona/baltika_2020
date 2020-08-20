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

class person_editJSModel extends JSPRO_Models
{
    public $_data = null;
    public $_lists = null;
    public $_mode = 1;
    public $_id = null;
    public function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        $this->getData();
    }

    public function getData()
    {
        $mainframe = JFactory::getApplication();
        $cid = JRequest::getVar('cid', array(0), '', 'array');
        $is_id = $cid[0];
        $row = new JTablePersons($this->db);
        $row->load($is_id);
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }
        
        
        $this->_lists['photos'] = $this->getPhotos(6, $row->id);
        
        $query = 'SELECT * FROM #__bl_persons_category ORDER BY name';
        $this->db->setQuery($query);
        $cats = $this->db->loadObjectList();
        $error = $this->db->getErrorMsg();
        if ($error) {
            return JError::raiseError(500, $error);
        }

        $cntr[] = JHTML::_('select.option',  0, JText::_('BLBE_SELECTVALUE'), 'id', 'name');
        $countries = array_merge($cntr, $cats);
        $this->_lists['category'] = JHTML::_('select.genericlist',   $countries, 'category_id', 'class="inputbox" size="1"', 'id', 'name', $row->category_id);

        

        ///-----EXTRAFIELDS---//
        $this->_lists['ext_fields'] = $this->getAdditfields(6, $row->id, 0);

        $this->_data = $row;

        $this->_lists['post_max_size'] = $this->getValSettingsServ('post_max_size');
        $this->_lists['languages'] = $this->getLanguages();
        $this->_lists['translation'] = array();
        if(count($this->_lists['languages']) && $row->id){
            $this->_lists['translation'] = $this->getTranslation('person_'.$row->id);
           
        }
    }

   
    public function savePerson()
    {
        if (!JFactory::getUser()->authorise('core.edit', 'com_joomsport')) {
            return JError::raiseError(303, '');
        }
        $mainframe = JFactory::getApplication();
        $post = JRequest::get('post');
        $post['about'] = JRequest::getVar('about', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $post['def_img'] = JRequest::getVar('ph_default', 0, 'post', 'int');
        

        $row = new JTablePersons($this->db);
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

        require_once 'components/com_joomsport/helpers/images.php';
        $default_id = ImagesHelper::saveImgs($_POST['jsgallery'], $row->id, 6);
        $row->def_img = $default_id;
        $row->store();

        //-------extra fields-----------//
        if (isset($_POST['extraf']) && count($_POST['extraf'])) {
            foreach ($_POST['extraf'] as $p => $dummy) {
                if (intval($_POST['extra_id'][$p])) {
                    

                    $db_season = 0;

                    $query = 'DELETE FROM #__bl_extra_values WHERE f_id = '.intval($_POST['extra_id'][$p]).' AND uid = '.$row->id." AND season_id='".$db_season."'";
                    $this->db->setQuery($query);
                    $this->db->query();
                    if ($_POST['extra_ftype'][$p] == '2') {
                        $query = 'INSERT INTO #__bl_extra_values(f_id,uid,fvalue_text,season_id) VALUES('.$_POST['extra_id'][$p].','.$row->id.",'".addslashes($_POST['extraf'][$p])."',{$db_season})";
                    } else {
                        $query = 'INSERT INTO #__bl_extra_values(f_id,uid,fvalue,season_id) VALUES('.$_POST['extra_id'][$p].','.$row->id.",'".addslashes($_POST['extraf'][$p])."',{$db_season})";
                    }
                    $this->db->setQuery($query);
                    $this->db->query();
                }
            }
        }
        //translation
        if(isset($_POST['translation']) && count($_POST['translation'])){
            $this->db->setQuery(
                    "DELETE FROM #__bl_translations WHERE jsfield='person_".$row->id."'"
                    );
            $this->db->query();
            foreach ($_POST['translation'] as $key => $value) {
                $value['about'] = str_replace("\r\n", "", $value['about']);
                $translation = json_encode($value);
                $translation = nl2br($translation);
                $translation = str_replace("\r\n", "", $translation);
                
                $this->db->setQuery(
                        "INSERT INTO #__bl_translations(jsfield,translation,languageID)"
                        ." VALUES('person_".$row->id."','".addslashes($translation)."','".$key."')"
                        );
                $this->db->query();
            }
        }
        
        $this->_id = $row->id;
    }
}
