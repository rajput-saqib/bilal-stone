<?php


    class MY_Model extends CI_Model
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
            return $this->db->delete($table, $where);
        }

        function insertIntoTable($table, $data)
        {
            $result = 0;
            if($this->db->insert($table, $data))
            {
                $result = $this->db->insert_id();
            }

            return $result;
        }

        function updateQuery($table, $data, $whereColunm, $whereValue)
        {
            $this->db->where($whereColunm, $whereValue);
            return $this->db->update($table, $data);
        }
    }


