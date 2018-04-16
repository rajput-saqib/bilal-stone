<?php

    class Users extends Admin_Controller
    {
        var $controller = 'users';
        var $viewName = 'users_view';
        var $table = 'users';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $query            = "SELECT id, firstName as first_name, lastName as last_name, contactNo as contact_no, email, status FROM users WHERE userType = '1' AND `status` != '-1' ";
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
            $insert = array('firstName' => $this->input->post('firstName'), 'lastName' => $this->input->post('lastName'), 'contactNo' => $this->input->post('contactNo'), 'email' => $this->input->post('email'), 'userType' => 1, 'createdAt' => time());

            $salt = $this->generateRandomString(10);
            $insert['salt'] = $salt;
            $insert['password'] = $this->encrypt($this->input->post('password'), $salt);
            $insert['verifyCode'] = $this->generateRandomString(16);


            $this->actionResponse($this->insert($this->table, $insert), 1);
            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            $this->data['data']       = $this->select('id, firstName as first_name, lastName as last_name, contactNo as contact_no, email, status', $this->table, "id = " . $this->input->post('id'))->row();

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
            $update = array('firstName' => $this->input->post('firstName'), 'lastName' => $this->input->post('lastName'), 'contactNo' => $this->input->post('contactNo'), 'email' => $this->input->post('email'), 'userType' => 1, 'updatedAt' => time());

            $salt = $this->generateRandomString(10);
            $update['salt'] = $salt;
            $update['password'] = $this->encrypt($this->input->post('password'), $salt);
            $update['verifyCode'] = $this->generateRandomString(16);


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