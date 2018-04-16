<?php

    class Expense_Type extends Admin_Controller
    {
        var $controller = 'expense_type';
        var $viewName = 'expense_type_view';
        var $table = 'expense_type';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $query = "SELECT id, `name` AS 'expense_name', `desc` AS 'Description', `status` FROM ".$this->table." WHERE `status` != '-1' ";;

            $temp             = new template();
            $temp->query      = $query;
            $temp->controller = $this->controller;

            $pageNo = $this->getUrlValue(4);
            if(!empty($pageNo))
            {
                $temp->pageNo = $pageNo;
            }

            $this->loadView($temp->pagination(), $this->data);
        }

        function new_()
        {
            $this->data['submitPath'] = '/save_';
            $html = $this->loadHtml($this->viewName, $this->data);
            echo $html;
        }

        function save_()
        {
            $insert = array('name' => $this->input->post('name'), 'desc' => $this->input->post('desc'), 'created_at' => time());
            $this->actionResponse($this->insert($this->table, $insert), 1);
            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            $this->data['data']       = $this->select('id, name as expense_name, desc as Description', $this->table, "id = " . $this->input->post('id'))->row();
            $html = $this->loadHtml('detail_view', $this->data);
            echo $html;
        }

        function edit_()
        {
            $this->data['submitPath'] = '/update_';
            $this->data['data']       = $this->select('', $this->table, "id = " . $this->input->post('id'))->row();
            $html = $this->loadHtml($this->viewName, $this->data);
            echo $html;
        }

        function update_()
        {
            $update = array('name' => $this->input->post('name'), 'desc' => $this->input->post('desc'), 'modified_at' => time());
            $this->actionResponse($this->update($this->table, $update, 'id', $this->input->post('id')), 2);
            redirect(ADMIN_URL . $this->controller);
        }

        function delete_()
        {
            $update = array('status' => '-1');
            echo $this->update($this->table, $update, 'id', $this->input->post('id'));
        }

        function status_()
        {
            $update = array('status' => $this->input->post('status'));
            $this->actionResponse(1, 4);
            echo $this->update($this->table, $update, 'id', $this->input->post('id'));
        }
    }