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

class boxfields_listJSModel extends JSPRO_Models
{
    public $_data = null;
    public $_lists = null;
    public $_total = null;

    public $_pagination = null;
    public $limit = null;
    public $limitstart = null;
    public $filtr_type = null;

    public function __construct()
    {
        parent::__construct();

        $mainframe = JFactory::getApplication();

        // Get the pagination request variables
        $this->limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $this->limitstart = $mainframe->getUserStateFromRequest('com_joomsport.limitstart_boxfields', 'limitstart', 0, 'int');
        // In case limit has been changed, adjust limitstart accordingly
        $this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
        if ($this->getTotal() <= $this->limitstart) {
            $this->limitstart = 0;
        }
        $this->getPagination();

        $this->getData();
        $query = 'SELECT DISTINCT (p.id)
					FROM #__bl_box_fields AS p';
        $this->db->setQuery($query);
        $this->_lists['totteams'] = $this->db->loadResult();
    }

    public function getData()
    {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
            $this->_data = $this->_getList($query, $this->limitstart, $this->limit);
            $error = $this->db->getErrorMsg();
            if ($error) {
                return JError::raiseError(500, $error);
            }

        }

        return $this->_data;
    }

    public function getTotal()
    {
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }
    public function _getListCount($query)
    {
        $this->db->setQuery($query);
        $tot = $this->db->loadObjectList();

        return count($tot);
    }

    public function _getList($query, $limitstart, $limit)
    {
        $this->db->setQuery($query, $limitstart, $limit);
        $tot = $this->db->loadObjectList();

        return $tot;
    }

    public function getPagination()
    {
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->limitstart, $this->limit);
        }

        return $this->_pagination;
    }

    public function _buildQuery()
    {
        $orderby = $this->_buildContentOrderBy();

        $query = 'SELECT b1.* FROM #__bl_box_fields as b1'
                . ' LEFT JOIN #__bl_box_fields as b2'
                . ' ON b1.parent_id = b2.id OR (b1.parent_id = 0 AND b1.id = b2.id)';

        $query .= $orderby;

        return $query;
    }

    public function _buildContentOrderBy()
    {
        $mainframe = JFactory::getApplication();

        $this->_lists['sortfield'] = $mainframe->getUserStateFromRequest('com_joomsport.boxfield_list_field', 'sortfield', 'ordering', 'string');
        $this->_lists['sortway'] = $mainframe->getUserStateFromRequest('com_joomsport.boxfield_list_way', 'sortway', 'ASC', 'string');

        $orderby = ' ORDER BY b2.ordering,b2.id, b1.parent_id, b1.ordering, b2.id, b1.id, '.$this->_lists['sortfield'].' '.$this->_lists['sortway'];

        return $orderby;
    }
}
