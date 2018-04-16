<?php

    class Staff extends Admin_Controller
    {
        var $controller = 'staff';
        var $viewName = 'staff_view';
        var $table = 'clients';
        var $table2 = 'staff_salaries';
        var $type = '5';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $temp             = new template();

            $query = "SELECT c.`id`, c.`id` 'client_id', c.`name`, c.`title` 'Designation', ss.salary, c.status FROM clients c INNER JOIN `staff_salaries` ss ON c.`id` = ss.`staffId` WHERE c.`status` != '-1' AND ss.`status` = '1' ORDER BY c.`name`";
            $this->data['data']       = $this->executeQuery($query)->row();

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
            $staffId = $this->insert($this->table, $insert);

            if($staffId > 0)
            {
                $insertSalary = array('staffId' => $staffId, 'salary' => $this->input->post('salary'), 'created_at' => time());
                $this->actionResponse($this->insert($this->table2, $insertSalary), 1);
            }

            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            $query = "SELECT c.id, c.title 'Designation', c.name, c.address, c.mobile, ss.`salary`, c.desc as 'description' FROM clients c INNER JOIN `staff_salaries` ss ON c.`id` = ss.`staffId` WHERE c.`status` != '-1' AND ss.`status` = '1' AND c.`id` = '".$this->input->post('id')."'";
            $this->data['data']       = $this->executeQuery($query)->row();
            $html = $this->loadHtml('detail_view_clients', $this->data);
            echo $html;
        }

        function edit_()
        {
            $this->data['submitPath'] = '/update_';

            $query = "SELECT c.*, ss.`salary` FROM clients c INNER JOIN `staff_salaries` ss ON c.`id` = ss.`staffId` WHERE c.`status` != '-1' AND ss.`status` = '1' AND c.`id` = '".$this->input->post('id')."'";
            $this->data['data']       = $this->executeQuery($query)->row();

            $html = $this->loadHtml($this->viewName, $this->data);
            echo $html;
        }

        function update_()
        {
            $staffId = $this->input->post('id');

            $update = array('title' => $this->input->post('title'), 'name' => $this->input->post('name'), 'address' => $this->input->post('address'), 'mobile' => $this->input->post('mobile'), 'desc' => $this->input->post('desc'), 'modified_at' => time());

            if($this->update($this->table, $update, 'id', $staffId)) {

                $this->update($this->table2, array('status' => '-1'), 'staffId', $staffId);
                $insertSalary = array('staffId' => $staffId, 'salary' => $this->input->post('salary'), 'created_at' => time());
                $this->actionResponse($this->insert($this->table2, $insertSalary), 1);

            }

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