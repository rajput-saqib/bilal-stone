<?php

    class Customers extends Admin_Controller
    {
        var $controller = 'customers';
        var $viewName = 'clients_view';
        var $table = 'clients';
        var $type = '2';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $query = "SELECT c1.`id`, c1.`name`, c1.`title`, (SELECT SUM(IF(type_i=".$this->type.", +i.total_amount, -i.total_amount)) FROM invoice i WHERE i.`status` = '1' AND i.client_id = c1.`id`) 'Total Balance', `status`, id 'client_id' FROM clients c1 WHERE `status` != '-1' AND `type` = '".$this->type."'";

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
            $insert = array('type' => $this->type, 'title' => $this->input->post('title'), 'name' => $this->input->post('name'), 'address' => $this->input->post('address'), 'mobile' => $this->input->post('mobile'), 'desc' => $this->input->post('desc'), 'created_at' => time());
            $this->actionResponse($this->insert($this->table, $insert), 1);
            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            $this->data['data']       = $this->select('id, title, name, address, mobile, desc as description', $this->table, "id = " . $this->input->post('id')." AND `type` = '".$this->type."'")->row();
            $html = $this->loadHtml('detail_view_clients', $this->data);
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
            $update = array('title' => $this->input->post('title'), 'name' => $this->input->post('name'), 'address' => $this->input->post('address'), 'mobile' => $this->input->post('mobile'), 'desc' => $this->input->post('desc'), 'modified_at' => time());
            $this->actionResponse($this->update($this->table, $update, 'id', $this->input->post('id')), 2);
            redirect(ADMIN_URL . $this->controller);
        }

        function delete_()
        {
            $update = array('status' => '-1', 'deleted_at' => time());
            echo $this->update($this->table, $update, 'id', $this->input->post('id'));
        }

        function status_()
        {
            $update = array('status' => $this->input->post('status'));
                $this->actionResponse(1, 4);
            echo $this->update($this->table, $update, 'id', $this->input->post('id'));
        }
    }