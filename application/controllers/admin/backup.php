<?php

    class Backup extends Admin_Controller
    {
        var $controller = 'backup';
        var $viewName = 'backup_view';
        var $table = 'backup';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $query        = "SELECT `id`, `name`, `created_at` FROM " . $this->table . " WHERE `status` != '-1'";
            $temp         = new template();
            $temp->query  = $query;

            $temp->status = false;
            $temp->view   = false;
            $temp->edit   = false;
            $temp->delete = false;

            $temp->controller = $this->controller;
            $pageNo = $this->getUrlValue(4);
            if (!empty($pageNo))
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

        }

        function view_()
        {

        }

        function edit_()
        {
            $this->data['submitPath'] = '/update_';
            $this->data['data']       = $this->select('', $this->table, "id = " . $this->input->post('id'))->row();
            $html                     = $this->loadHtml($this->viewName, $this->data);
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