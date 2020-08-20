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

require(dirname(__FILE__).'/../models.php');

class event_editJSModel extends JSPRO_Models
{
	var $_data = null;
	var $_lists = null;
	var $_mode = 1;
	var $_id = null;
	function __construct()
	{
		parent::__construct();
	
		$this->getData();
	}

	function getData()
	{
		
		$cid 		= JRequest::getVar( 'cid', array(0), '', 'array' );
		$is_id = $cid[0];
		
		$row 	= new JTableEvents($this->db);
		$row->load($is_id);
		$error = $this->db->getErrorMsg();
		if ($error)
		{
			return JError::raiseError(500, $error);
		}

		$javascript = 'onChange = "View_eventimg();"';
		$this->_lists['image'] = JHTML::_('list.images',  'e_img', $row->e_img,$javascript,'media/bearleague/events' );
		
		$jas = 'onChange = "calctpfun();"';
		$is_tourn[] = JHTML::_('select.option',  0, JText::_('BLBE_MATCH'), 'id', 'name' ); 
		$is_tourn[] = JHTML::_('select.option',  1, JText::_('BLBE_PLAYER'), 'id', 'name' ); 
		$is_tourn[] = JHTML::_('select.option',  2, JText::_('BLBE_SUMM'), 'id', 'name' ); 
		
		$this->_lists['player_event'] = JHTML::_('select.genericlist',   $is_tourn, 'player_event', 'class="inputbox" size="1" '.$jas, 'id', 'name', $row->player_event );
		
		$is_rt[] = JHTML::_('select.option',  0, JText::_('BLBE_SUM'), 'id', 'name' ); 
		$is_rt[] = JHTML::_('select.option',  1, JText::_('BLBE_AVG'), 'id', 'name' ); 
		
		$this->_lists['restype'] = JHTML::_('select.genericlist',   $is_rt, 'result_type', 'class="inputbox" size="1"', 'id', 'name', $row->result_type );
	
		$is_sumev[] = JHTML::_('select.option',  0, JText::_('BLBE_SELEVENT'), 'id', 'name' ); 
		$query = "SELECT e_name as name,id FROM #__bl_events WHERE player_event='1' ORDER BY e_name";
		$this->db->setQuery($query);
		$evns = $this->db->loadObjectList();
		if(count($evns)){
			$is_sumev = array_merge($is_sumev,$evns);
		}
		$this->_lists['sumev1'] = JHTML::_('select.genericlist',   $is_sumev, 'sumev1', 'class="inputbox" size="1" ', 'id', 'name', $row->sumev1 );
		$this->_lists['sumev2'] = JHTML::_('select.genericlist',   $is_sumev, 'sumev2', 'class="inputbox" size="1" ', 'id', 'name', $row->sumev2 );
        $this->_lists["post_max_size"] = $this->getValSettingsServ("post_max_size");

		$this->_data = $row;
		
	}
	
	public function orderEvent(){
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );
		
		$row		= new JTableEvents($this->db);
		$total		= count( $cid );
		
		if (empty( $cid )) {
			return JError::raiseWarning( 500, JText::_( 'No items selected' ) );
		}
		// update ordering values
		for ($i = 0; $i < $total; $i++)
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					return JError::raiseError( 500, $this->db->getErrorMsg() );
				}
			
				
			}
		}
	}
	
	
	public function saveEvent()
	{
		$post		= JRequest::get( 'post' );
		$post['e_descr'] = JRequest::getVar( 'e_descr', '', 'post', 'string', JREQUEST_ALLOWRAW );
		$row 	= new JTableEvents($this->db);
		$error = $this->db->getErrorMsg();
		if ($error)
		{
			return JError::raiseError(500, $error);
		}
		if ($this->isPostedImage()) {
			 if (false !== ($filename = $this->uploadEventImage())) {
			 	$post['e_img'] = $filename;
			 }
		}
		if (!$row->bind( $post )) {
			JError::raiseError(500, $row->getError() );
		}
		if (!$row->check()) {
			JError::raiseError(500, $row->getError() );
		}
		// if new item order last in appropriate group
		if (!$row->store()) {
			JError::raiseError(500, $row->getError() );
		}
		$row->checkin();
		
		$this->_id = $row->id;
	}
	
	public function deleteEvent($cid){
		if(count($cid)){
			$cids = implode(',',$cid);
			$this->db->setQuery("DELETE FROM #__bl_events WHERE id IN (".$cids.")");
			$this->db->query();
			$this->db->setQuery("DELETE FROM #__bl_match_events WHERE e_id IN (".$cids.")");
			$this->db->query();
			$error = $this->db->getErrorMsg();
			if ($error)
			{
				return JError::raiseError(500, $error);
			}
		}
	}
	
	protected function isPostedImage()
	{
		return isset($_FILES['new_event_img']['name'])
			&& !empty($_FILES['new_event_img']['tmp_name']);
	}
	
	protected function uploadEventImage()
	{
		$pathinfo = pathinfo($_FILES['new_event_img']['name']);
		$baseDir = $this->getEventsImagesDir();
		
		$sameNameImgsCnt = 0;
		$filename = $pathinfo['basename'];
		while (is_file($baseDir . $filename)) {
			$sameNameImgsCnt++;
			$filename = sprintf('%s_%d.%s',
				$pathinfo['filename'],
				$sameNameImgsCnt,
				$pathinfo['extension']	
			);
		}
		
		if ($this->uploadFile(
			$_FILES['new_event_img']['tmp_name'],
			$filename,
			$baseDir
		)) {
			return $filename;
		}
		
		return false;
	}
	
	public function getEventsImagesDir()
	{
		return JPATH_ROOT . '/media/bearleague/events/';
	}
	
}