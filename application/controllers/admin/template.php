<?php

	error_reporting(0);
    session_start();
    
    class Template extends My_Controller
    {
        var $controller = 'pagination';
        var $viewName = '';
        var $data = array();

        var $query = '';
        var $table = array();
        var $colunms = '';

        var $action = true;
        var $new = true;
        var $edit = true;
        var $status = true;
        var $view = true;
        var $delete = true;
        var $idColunm = true;

        var $idColunmName = 'id';
        var $filter = '';

        var $headingText = '';

        var $totalRows = '0';
        var $pageNo = 1;
        var $start = 0;
        var $pageLimit = 20;
        var $totalPages = 0;

        var $secondData = 0;
        var $error = '';

        function __construct()
        {
            parent::__construct();
            $perPageRows = $this->session->userdata['admin_login_data']['per_page_rows'];
            $this->pageLimit = ($perPageRows > 0) ? $perPageRows : $this->pageLimit;
        }
                
        function pagination()
        {
            $this->totalRows = $this->executeQuery($this->query)->num_rows();

            if($this->pageNo > 1)
            {
                $_SESSION['pageNo'] = $this->pageNo;
                $_SESSION['controller'] = $this->getUrlValue(2);
            }

            if($this->getUrlValue(4) == '' && $this->getUrlValue(2) == $_SESSION['controller'] && $this->totalRows > ($_SESSION['pageNo']*$this->pageLimit))
            {
                $this->pageNo = $_SESSION['pageNo'];
            }

            if($_GET['id'] != '' && ($this->controller == 'search_invoice' || $this->controller == 'product_stock' || $this->controller == 'item_stock'))
            {
                $this->pageNo = 1;
                $this->pageLimit = 10000;
            }

            if($this->controller == 'account_ledger')
            {
                //$this->pageNo = 1;
                //$this->pageLimit = 1000;
            }

            $this->start = ($this->pageNo*$this->pageLimit)-$this->pageLimit;

            if($this->start < 0)
            {
                $this->start = 0;
            }

            $this->query .= ' LIMIT '.$this->start.', '.$this->pageLimit;

            $data = $this->executeQuery($this->query);
            $this->table = $data->result();
            $this->colunms = $this->getKeys($data->row());
            $this->totalPages = ceil($this->totalRows / $this->pageLimit);


            $result = $this->setVariables();


            $result = $this->load->view('admin/pagination_view', $result, true);

            return $result;
        }

        function setVariables()
        {
            $data = array();
            $data['query']   = $this->query;
            $data['controller']   = $this->controller;
            $data['table']        = $this->table;
            $data['colunms']      = $this->colunms;
            $data['obj']      =  new My_Controller();


            $data['action']       = $this->action;
            $data['new']          = $this->new;
            $data['edit']         = $this->edit;
            $data['status']       = $this->status;
            $data['view']         = $this->view;
            $data['delete']       = $this->delete;
            $data['idColunm']     = $this->idColunm;
            $data['idColunmName'] = $this->idColunmName;
            $data['pageLimit']    = $this->pageLimit;
            $data['filter']       = $this->filter;
            $data['totalRows']    = $this->totalRows;
            $data['pageNo']       = $this->pageNo;
            $data['totalPages']   = $this->totalPages;
            $data['headingText']   = $this->headingText;
            $data['secondData']   = $this->secondData;
            $data['errorInDatabase']   = json_encode($this->error);
            
            return $data;
        }



        function getTemplate()
        {
            $request['controller'] = $this->controller;
            $request['data'] = $this->data;


            $result = $this->load->view('admin/'.$this->viewName, $request, true);

            return $result;
        }

        function paginationDirect($param){

            $data                 = array();
            $data['controller']   = $param['controller'];
            $data['table']        = $param['table'];
            $data['colunms']      = $param['colunms'];
            $data['obj']          = new My_Controller();

            $data['action']       = (!isset($param['action'])) ? false : $param['action'];
            $data['new']          = (!isset($param['new'])) ? false : $param['new'];
            $data['edit']         = (!isset($param['edit'])) ? false : $param['edit'];
            $data['status']       = (!isset($param['status'])) ? false : $param['status'];
            $data['view']         = (!isset($param['view'])) ? false : $param['view'];
            $data['delete']       = (!isset($param['delete'])) ? false : $param['delete'];

            $data['idColunm']     = true;
            $data['idColunmName'] = 'id';
            $data['pageLimit']    = count($data['table']);
            $data['filter']       = 0;
            $data['totalRows']    = count($data['table']);
            $data['pageNo']       = 1;
            $data['totalPages']   = 1;
            $data['headingText']   = $param['headingText'];;

            $result = $this->load->view('admin/pagination_view', $data, true);

            return $result;
        }
    }