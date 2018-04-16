<?php


    class Admin_Model extends CI_Model
    {
        function __construct()
        {
            parent::__construct();
            $this->load->database();
        }

        function selectTable($column = '', $table, $where = '')
        {
            if ($column != "")
            {
                $this->db->select($column);
            }

            $this->db->from($table);

            if ($where != "")
            {
                $this->db->where($where);
            }

            $data = $this->db->get();

            return $data;
        }

        function executeQuery($query)
        {
            $data = $this->db->query($query);

            return $data;
        }



        function deleteRow($table, $where)
        {
            if ($this->db->delete($table, $where))
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function insertIntoTable($table, $data, $return = '')
        {
            $this->db->insert($table, $data);
            if($return)
            {
                return $this->db->insert_id();
            }
        }

        function updateQuery($table, $data, $whereC, $whereD)
        {
            $this->db->where($whereC, $whereD);
            $done = $this->db->update($table, $data);

            if($done)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }


