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
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
require_once(dirname(__FILE__).'/../includes/func.php');
$mainframe = JFactory::getApplication();
$doc = JFactory::getDocument();

function itgetVer(){
		$version = new JVersion;
		$joomla = $version->getShortVersion();
		return substr($joomla,0,3);
	}
if(itgetVer() >= '1.6'){
		JHtml::_('behavior.framework', true);
		$doc->addCustomTag( '<script type="text/javascript" src="components/com_joomsport/includes/slimbox/js/slimbox.js"></script>' );
	}else{
		JHtml::_('behavior.mootools');
		if(isset($mainframe->MooToolsVersion) && $mainframe->MooToolsVersion){
			$doc->addCustomTag( '<script type="text/javascript" src="components/com_joomsport/includes/slimbox/js/slimbox.js"></script>' );
		}else{
			$doc->addCustomTag( '<script type="text/javascript" src="components/com_joomsport/includes/slimbox/js15/slimbox.js"></script>' );
		}
	}
$doc->addCustomTag( '<link rel="stylesheet" type="text/css"  href="components/com_joomsport/css/admin_bl.css" />' );
$doc->addCustomTag( '<link rel="stylesheet" type="text/css"  href="components/com_joomsport/css/joomsport.css" />' );
$doc->addCustomTag( '<script type="text/javascript" src="components/com_joomsport/js/joomsport.js"></script>' );
$doc->addCustomTag( '<script type="text/javascript" src="components/com_joomsport/js/styled-long.js"></script>' );
$doc->addCustomTag( '<link rel="stylesheet" type="text/css"  href="components/com_joomsport/includes/slimbox/css/slimbox.css" />' );


?>
<?php
$db			= JFactory::getDBO();
$user	= JFactory::getUser();
 $sid = JRequest::getVar( 'sid', 0, 'request', 'int' );
	 $tid = JRequest::getVar( 'tid', 0, 'request', 'int' );
	


		if ( $user->get('guest')) {

			$return_url = $_SERVER['REQUEST_URI'];
			$return_url = base64_encode($return_url);
			
			if(itgetVer() >= '1.6'){
				$uopt = "com_users";
			}else{
				$uopt = "com_user";
			}
			$return	= 'index.php?option='.$uopt.'&view=login&return='.$return_url;

			// Redirect to a login form
			$mainframe->redirect( $return, JText::_('BLMESS_NOT_LOGIN') );
			
		}
	
	$query = "SELECT COUNT(*) FROM #__bl_teams as t, #__bl_moders as m WHERE m.tid=t.id AND m.uid=".$user->id." AND t.id=".$tid;
	$db->setQuery($query);	
	
		
	if(!$db->loadResult()){
		JError::raiseError( 403, JText::_('Access Forbidden') );
			return;
	}
	$Itemid = JRequest::getInt('Itemid'); 
	
	
class JoomsportControllerModer extends JControllerLegacy
{
	protected $js_prefix = '';
	protected $mainframe = null;
	protected $option = 'com_joomsport';
	
	function __construct(){
		parent::__construct();
		$this->mainframe = JFactory::getApplication();
		$this->js_SetPrefix();
		$this->js_GetDBTables();
		$this->session = JFactory::getSession();
	}
	private function js_SetPrefix(){
		$this->js_prefix = '';
		$db	= JFactory::getDBO();
		$query = "SELECT name FROM #__bl_addons WHERE published='1'";
		$db->setQuery($query);
		$addon = $db->loadResult();
		if($addon){
			$this->js_prefix = $addon;
		}
		
	}
	private function js_GetDBTables(){
		$path = JPATH_SITE.'/components/com_joomsport/tables/';
		if($this->js_prefix){
			if(is_file($path.$this->js_prefix.".php")){
				require($path.$this->js_prefix.".php");
			}else{
				require($path."default.php");
			}
		}else{
			require($path."default.php");
		}
	}
	private function js_Model($name){
		$path = dirname(__FILE__).'/../models/';
		if($this->js_prefix){
			if(is_file($path.$this->js_prefix."/".$name.".php")){
				require($path.$this->js_prefix."/".$name.".php");
			}else{
				require($path."default/".$name.".php");
				
			}
		}else{
			require($path."default/".$name.".php");
			
		}
	}
	private function js_Layout($task){
		$path = dirname(__FILE__).'/../views/'.$task;
		
		require($path."/view.html.php");
		
	}
//update	
	function set_sess($msg,$typeMess){
		$this->session->set('errMess', $msg);		
		$this->session->set('typeMess', $typeMess);
	}
	function display($cachable = false, $urlparams = false)
	{
		$view = JRequest::getCmd( 'view' );
		$task = JRequest::getCmd( 'task' );
		if(!$view) {
			//if($task){
				//$view = $task;
			//}else{
				$view = 'edit_team';
			//}	
		}
		
		
		$vName		= JRequest::getCmd('view', 'edit_team');
		
		$this->js_Model($vName);
		$classname = $vName."JSModel";
		$unviews = array('admin_player','edit_team','edit_matchday','edit_match','adplayer_edit');
		if(in_array($vName,$unviews)){
			$model = new $classname(2);
		}else{
			$model = new $classname();
		}
		
		$this->js_Layout($vName);
		$classname_l = "JoomsportView".$vName;
		
		$layout = new $classname_l($model);
		
                $tpl = null;

                if (js_mobile::isMobile()) {
                    $this->mobile();
                    $tpl = '_mobile';
                }
		$layout->display($tpl);
	
		
		return $this;
		
	}
	
	
	
	function edit_team()
	{
		JRequest::setVar( 'view', 'edit_team' );
		JRequest::setVar( 'edit', true );
		$this->display();
	}
	
	function team_save(){
		$vName = 'edit_team';
		$this->js_Model($vName);
		$classname = $vName."JSModel";
		$model = new $classname(2);
		$model->SaveAdmTeam();
		$Itemid = JRequest::getInt('Itemid'); 
		$link = "index.php?option=com_joomsport&controller=moder&view=edit_team&tid=".$model->id."&Itemid=".$Itemid;

		$msg = JText::_('BLMESS_UPDSUCC');
		$typeMess = 1;
		$this->set_sess($msg,$typeMess);
		
		$this->setRedirect($link);
	}
	
	
	
	
	///---------------Matchday--------------------------/
	
	function edit_matchday()
	{
		JRequest::setVar( 'view', 'edit_matchday' );
		JRequest::setVar( 'edit', true );
		$this->display();
	}
	
	
	
	function matchday_save(){
		
		$vName = 'edit_matchday';
		$this->js_Model($vName);
		$classname = $vName."JSModel";
		$model = new $classname(2);
		$model->AdmMDSave();
			
		$Itemid = JRequest::getInt('Itemid'); 

		$link = "index.php?option=com_joomsport&controller=moder&view=edit_matchday&tid=".$model->tid."&sid=".$model->sid."&mid=".$model->mid."&Itemid=".$Itemid;
		
		$this->setRedirect( $link );
	}
	
	
	
	
	///---------------Match--------------------------/
	
	
	function moderedit_match()
	{
		JRequest::setVar( 'view', 'edit_match' );
		JRequest::setVar( 'edit', true );
		$this->display();
	}
	
	
	function match_save(){
		$vName = 'edit_match';
		$this->js_Model($vName);
		$classname = $vName."JSModel";
		$model = new $classname(2);
		$model->saveAdmmatch();
		
		$tid = JRequest::getVar( 'tid', 0, '', 'int' );
		$isapply = JRequest::getVar( 'isapply', 0, '', 'int' );
		$Itemid = JRequest::getInt('Itemid'); 
		if(!$isapply){
			$this->setRedirect("index.php?option=com_joomsport&controller=moder&view=edit_matchday&mid=".$model->m_id."&sid=".$model->s_id."&tid=".$tid."&Itemid=".$Itemid);
		}else{
			$this->setRedirect("index.php?option=com_joomsport&controller=moder&view=edit_match&cid[]=".$model->id."&sid=".$model->s_id."&tid=".$tid."&Itemid=".$Itemid);
		}
	} 
	function match_invite(){
		$vName = 'edit_match';
		$this->js_Model($vName);
		$classname = $vName."JSModel";
		$model = new $classname(2); //update saveAdmmatch???
		$model->saveAdmmatch();
		$model->inviteModerMatch();
		
		$tid = JRequest::getVar( 'tid', 0, '', 'int' );
		$isapply = JRequest::getVar( 'isapply', 0, '', 'int' );
		$Itemid = JRequest::getInt('Itemid'); 
		
		$this->setRedirect("index.php?option=com_joomsport&controller=moder&view=edit_match&cid[]=".$model->id."&sid=".$model->season_id."&tid=".$tid."&Itemid=".$Itemid);
		
	} 
	
	
	//--- Players
	
	function admin_player()
	{
		JRequest::setVar( 'view', 'admin_player' );
		
		
		$this->display();
	}

	function adplayer_edit()
	{
		JRequest::setVar( 'view', 'adplayer_edit' );
		JRequest::setVar( 'edit', true );
		$this->display();
	}
	function mdplayer_del()
	{
		$user	=& JFactory::getUser();
		$mainframe = JFactory::getApplication();;
		$db			=& JFactory::getDBO();
		$tid 		= JRequest::getVar( 'tid', 0, '', 'int' );
		$cid 		= JRequest::getVar( 'cid', array(0), '', 'array' );

		JArrayHelper::toInteger($cid, array(0));
		
		if(count($cid)){
	
			$cids = implode(',',$cid);
	
			$db->setQuery("DELETE FROM #__bl_players WHERE id IN (".$cids.") AND created_by = ".$user->id);
	
			$db->query();
	
		}
		$Itemid = JRequest::getInt('Itemid');
			
		$this->setRedirect("index.php?option=com_joomsport&controller=moder&view=admin_player&tid=".$tid."&Itemid=".$Itemid);
	}
	function adplayer_apply(){
	
		$this->adplayer_save(1);
	}
	function adplayer_save($apl = 0){
		$vName = 'adplayer_edit';
		$this->js_Model($vName);
		$classname = $vName."JSModel";
		$model = new $classname(2);
		$model->savAdmPlayer();
		
		
		$Itemid = JRequest::getInt('Itemid');
		if($apl){
			$link = "index.php?option=com_joomsport&controller=moder&view=adplayer_edit&cid[]=".$model->id."&tid=".$model->tid."&Itemid=".$Itemid;
		}else{
			$link = "index.php?option=com_joomsport&controller=moder&view=admin_player&tid=".$model->tid."&Itemid=".$Itemid;
		}
		$msg = ($model->is_first)?JText::_('BLFA_EDITPLAYERMSGMODER'):JText::_('BLFA_ADDPLAYERMSGMODER');
		$typeMess = 1;
		
		$this->set_sess($msg,$typeMess);
		$this->setRedirect($link);
	}
	

	function mobile(){
            $doc = JFactory::getDocument();
            $doc->addCustomTag( '<link rel="stylesheet" type="text/css"  href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800" />' );
            $doc->addCustomTag( '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">' );   
            $doc->addCustomTag( '<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">' );            
            $doc->addCustomTag( '<link rel="stylesheet" type="text/css"  href="components/com_joomsport/css/mobile/tableStyle.css" />' );
            $doc->addCustomTag( '<link rel="stylesheet" type="text/css"  href="components/com_joomsport/css/mobile/tableStyle2.css" />' );
            $doc->addCustomTag( '<link rel="stylesheet" type="text/css"  href="components/com_joomsport/css/mobile/lightbox.css" />' );           
            JHtml::_('bootstrap.framework');
            $doc->addCustomTag( '<script type="text/javascript" src="components/com_joomsport/js/imagelightbox.min.js"></script>' );
        }
	
}	
?>