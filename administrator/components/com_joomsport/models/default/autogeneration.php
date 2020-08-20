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

class autogenerationJSModel extends JSPRO_Models
{
    public $_lists = null;
    public $sid = null;
    public $grid = null;
    public $sgr = null;
    public $md_type = null;
    public $md_name = null;

    public function __construct()
    {
        parent::__construct();

        $this->sgr = JRequest::getVar('sid', 0, '', 'string');
        if ($this->sgr == '0') {
            $this->sid = 0;
            $this->grid = 0;
        } else {
            $ex = explode('|', $this->sgr);
            $this->sid = $ex[0];
            $this->grid = $ex[1];
        }
        $this->md_type = JRequest::getVar('md_type', 0, '', 'int');
        $this->md_name = JRequest::getVar('mday_name', 'Matchday', '', 'string');

        $generatem = isset($_POST['autogeneration']) && $_POST['autogeneration'] == 'true';
        if ($generatem) {
            $msg = $this->generate();
            $msgType = 'warning';
            $app = JFactory::getApplication();
            $link = 'index.php?option=com_joomsport&task=autogeneration';
            if(!$msg){
                $msg = 'Succesfully generated. Please check the generated Matches on <a href="index.php?option=com_joomsport&task=matchday_list&s_id='.$this->sid.'">Matchday list</a> layout';
            
                $msgType = 'message';
            }
            $app->redirect($link, $msg, $msgType);
        } else {
            $this->getData();
        }
    }

    public function getData()
    {
        $javascript = 'onchange = "document.adminForm.submit();"';
        $type_tourn = array();
        $type_tourn[] = JHTML::_('select.option',  0, JText::_('Round-robin'), 'id', 'name');
        $type_tourn[] = JHTML::_('select.option',  1, JText::_('BLBE_KNOCK'), 'id', 'name');
        $type_tourn[] = JHTML::_('select.option',  2, JText::_('BLBE_DOUBLE'), 'id', 'name');

        $lang_val = JText::_('MOD_JS_TT_SELSEASGROUPS');
        $lang_val2 = JText::_('MOD_JS_TT_SELALLSEAS');
        $lang_val3 = JText::_('MOD_JS_TT_NOSEAS');

        $this->_lists['t_type'] = JHTML::_('select.genericlist',   $type_tourn, 'md_type', 'class="inputbox" size="1" '.$javascript, 'id', 'name', $this->md_type);

        $query = "SELECT CONCAT(t.name,' ',s.s_name) as name,s.s_id as id FROM #__bl_tournament as t, #__bl_seasons as s WHERE s.t_id = t.id AND t.published=1 AND s.published=1";
        $this->db->setQuery($query);

        $rows = $this->db->loadObjectList();

        $selectbox = array();
        if (!empty($rows)) {
            for ($i = 0;$i < count($rows);++$i) {
                $row = $rows[$i];
                $selectbox[] = JHTML::_('select.optgroup',  $row->name, 'id', 'name');
                $query = 'SELECT group_name,id FROM #__bl_groups  WHERE s_id = '.$row->id;
                $this->db->setQuery($query);
                $gr = $this->db->loadObjectList();
                $selectbox[] = JHTML::_('select.option',  $row->id.'|0', $lang_val, 'id', 'name');
                for ($j = 0;$j < count($gr);++$j) {
                    $selectbox[] = JHTML::_('select.option',  $row->id.'|'.$gr[$j]->id, $gr[$j]->group_name, 'id', 'name');
                }
            }
            $javascript = 'onchange="javascript:  document.adminForm.submit();"';

            $jqre = '<select name="sid" id="sid" class="styled jfsubmit" class="chzn-done" size="1" '.$javascript.' style="display:block !important;">';
            $jqre .= '<option value="0">'.$lang_val2.'</option>';
            $selectbox = array();

            for ($i = 0;$i < count($rows);++$i) {
                $row = $rows[$i];
                $jqre .= '<optgroup label="'.htmlspecialchars($row->name).'">';
                $query = 'SELECT group_name,id FROM #__bl_groups  WHERE s_id = '.$row->id;
                $this->db->setQuery($query);
                $gr = $this->db->loadObjectList();
                $jqre .= '<option value="'.$row->id.'|0" '.(($this->sgr == ($row->id.'|0')) ? 'selected' : '').'>'.$lang_val.'</option>';
                for ($j = 0;$j < count($gr);++$j) {
                    //$selectbox[] = JHTML::_('select.option',  $row->id.'|'.$gr[$j]->id,$gr[$j]->group_name, 'id', 'name' ); 
                    $jqre .= '<option value="'.$row->id.'|'.$gr[$j]->id.'" '.(($this->sgr == ($row->id.'|'.$gr[$j]->id)) ? 'selected' : '').'>'.$gr[$j]->group_name.'</option>';
                }
                $jqre .= '</optgroup>';
            }
            $jqre .= '</select>';
            //$html = JHTML::_('select.genericlist',   $selectbox, 'jform[params][sidgid]', 'class="inputbox" size="1" '.$javascript, 'id', 'name', $this->value);
        } else {
            $jqre = '<select name="jform[params][sidgid]" id="playerzdf_id" class="chzn-done" size="1" style="display:block !important;">';
            $jqre .= '<option value="0">'.$lang_val3.'</option>';

            $jqre .= '</select>';
        }

        $this->_lists['seasons'] = $jqre;

        if ($this->sid) {
            $query = 'SELECT s.*, t.t_single FROM #__bl_seasons as s'
                            .' JOIN #__bl_tournament as t ON s.t_id = t.id'
                            ." WHERE s.s_id={$this->sid}";
            $this->db->setQuery($query);
            $season = $this->db->loadObject();

            if ($season->t_single == 1) {
                $query = "SELECT t.id, CONCAT(t.first_name,' ',t.last_name) as name"
                                .' FROM #__bl_season_players as st, #__bl_players as t'
                                .($this->grid ? ' JOIN #__bl_grteams as gr ON gr.g_id='.$this->grid.' AND gr.t_id = t.id ' : '')

                                .' WHERE st.season_id = '.intval($this->sid).' AND t.id = st.player_id'
                                .' ORDER BY t.first_name';

                $this->db->setQuery($query);

                $this->_lists['teams'] = $this->db->loadObjectList();
                $error = $this->db->getErrorMsg();
                if ($error) {
                    return JError::raiseError(500, $error);
                }
            } else {
                $query = 'SELECT t.id, t.t_name as name'
                                .' FROM #__bl_season_teams as st, #__bl_teams as t'
                                .($this->grid ? ' JOIN #__bl_grteams as gr ON gr.g_id='.$this->grid.' AND gr.t_id = t.id ' : '')
                                .' WHERE st.season_id = '.intval($this->sid).' AND t.id = st.team_id'
                                .' ORDER BY t.t_name';

                $this->db->setQuery($query);

                $this->_lists['teams'] = $this->db->loadObjectList();
                $error = $this->db->getErrorMsg();
                if ($error) {
                    return JError::raiseError(500, $error);
                }
            }

            $format[] = JHTML::_('select.option',  0, JText::_('BLBE_SELFORM'), 'id', 'name');
            if ($this->md_type == 1) {
                $format[] = JHTML::_('select.option',  2, 2, 'id', 'name');
            }
            $format[] = JHTML::_('select.option',  4, 4, 'id', 'name');
            $format[] = JHTML::_('select.option',  8, 8, 'id', 'name');
            $format[] = JHTML::_('select.option',  16, 16, 'id', 'name');
            $format[] = JHTML::_('select.option',  32, 32, 'id', 'name');
            $format[] = JHTML::_('select.option',  64, 64, 'id', 'name');

            $this->_lists['format_kn'] = JHTML::_('select.genericlist',   $format, 'format_post', 'class="inputbox" size="1" id="format_post" onchange="checkQnty(this);"', 'id', 'name', 0);
        }

        $this->_lists['sid'] = $this->sid;
        $this->_lists['md_type'] = $this->md_type;
    }
    public function generate()
    {
        if ($this->md_type) {
            $format_post = JRequest::getVar('format_post', 0, '', 'int');
            $teams_knock = JRequest::getVar('teams_knock', array(0), '', 'array');

            $this->algoritm_knock($format_post, $teams_knock);
        } else {
            $team_number_id = JRequest::getVar('team_number_id', array(0), '', 'array');
                //$team_number_rand = JRequest::getVar( 'team_number_rand', array(0), '', 'array' );
                $rounds = JRequest::getVar('rounds', 0, '', 'int');
            if (count($team_number_id) < 4) {
                //something wrong
                return JText::_('BLBE_WARN_LESSGENER');
            } else {
                /*$teams = array();
                    for($intA = 0; $intA < count($team_number_id); $intA++){
                        $teams[$team_number_rand[$intA] - 1] = $team_number_id[$intA];
                    }*/

                    $this->algoritm1($team_number_id, $rounds);
                    return '';
            }
        }
    }

    public function algoritm1($teams, $rounds)
    {
        if (count($teams) % 2 != 0) {
            array_push($teams, 0);
        }
        $halfarr = count($teams) / 2;

        $round_day = 1;
        for ($intR = 0; $intR < $rounds; ++$intR) {
            $duo_teams = array_chunk($teams,  $halfarr);
            $duo_teams[1] = array_reverse($duo_teams[1]);
            $continue = true;
            $first_team = $duo_teams[0][0];
            $last_team = $duo_teams[1][0];
            while ($continue) {
                $intB = 0;
                $matchday_id = $this->create_mday(0, $this->md_name.' '.$round_day, $round_day);

                foreach ($duo_teams[0] as $home) {
                    if ($intR % 2 == 0) {
                        $row['home'] = $home;
                        $row['away'] = $duo_teams[1][$intB];
                    } else {
                        $row['away'] = $home;
                        $row['home'] = $duo_teams[1][$intB];
                    }
                        //$row['day'] = $round_day;
                        if ($row['home'] && $row['away']) {
                            $this->addMatch($row, $matchday_id, $intB);
                        }
                    ++$intB;
                }
                ++$round_day;

                $tmp = $duo_teams[0][$halfarr - 1];
                $to_top = $duo_teams[1][0];
                unset($duo_teams[1][0]);
                unset($duo_teams[0][$halfarr - 1]);
                array_push($duo_teams[1], $tmp);
                $duo_teams[1] = array_values($duo_teams[1]);
                $arr_start = array($duo_teams[0][0], $to_top);
                $arr_end = array_slice($duo_teams[0], 1);
                if (count($arr_end)) {
                    $arr_start = array_merge($arr_start, $arr_end);
                }
                $duo_teams[0] = $arr_start;
                if ($duo_teams[1][0] == $last_team) {
                    $continue = false;
                }
            }
        }
    }

    public function addMatch($row, $matchday_id, $ordering)
    {
        //echo $row['day'] . '. ' .$row['home'].' vs '.$row['away'].'<br />';

            //
            $match = new JTableMatch($this->db);

        $match->load(0);

        $match->m_id = $matchday_id;

        $match->team1_id = intval($row['home']);

        $match->team2_id = intval($row['away']);

        $match->published = 1;
        $match->m_played = 0;

        if ($this->md_type) {
            $match->k_ordering = $ordering;
            $match->k_stage = 1;
        }

        if (!$match->check()) {
            JError::raiseError(500, $match->getError());
        }

        if (!$match->store()) {
            JError::raiseError(500, $match->getError());
        }
        $match->checkin();
    }

    public function algoritm_knock($format_post, $teams_knock)
    {
        $participiants = array();
        array_rand($teams_knock);
        $participiants = $teams_knock;
        $half = intval($format_post / 2);
        if (count($teams_knock) >= $format_post) {
            $participiants = array_slice($participiants, 0, $format_post);
            $duo_teams = array_chunk($participiants,  $half);
        }

        if (count($teams_knock) < $format_post) {
            $duo_teams = array_chunk($participiants,  $half);
            for ($intA = 0; $intA < $half; ++$intA) {
                if (!isset($duo_teams[1][$intA])) {
                    $duo_teams[1][$intA] = -1;
                }
            }
            array_rand($duo_teams[1]);
        }
        $matchday_id = $this->create_mday($format_post, $this->md_name);
        for ($intA = 0; $intA < count($duo_teams[0]); ++$intA) {
            $row['home'] = $duo_teams[0][$intA];
            $row['away'] = $duo_teams[1][$intA];

            $this->addMatch($row, $matchday_id, $intA);
        }
    }

    public function create_mday($format, $name, $ordering)
    {
        $post = array();
        $post['k_format'] = $format;
        $post['t_type'] = $this->md_type;
        $post['m_name'] = $name;
        $post['s_id'] = $this->sid;
        $post['ordering'] = $ordering;
        $row = new JTableMday($this->db);
        if (!$row->bind($post)) {
            JError::raiseError(500, $row->getError());
        }
        if (!$row->check()) {
            JError::raiseError(500, $row->getError());
        }

        if (!$row->store()) {
            JError::raiseError(500, $row->getError());
        }
        $row->checkin();

        return $row->id;
    }
}
