<?php

    class Fooditem extends Admin_Controller
    {
        var $controller = 'fooditem';
        var $viewName = 'fooditem_view';
        var $table = 'fooditem';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $query = "SELECT id, `name`, IF(image != '', image, 'assets/default.png') 'image', `desc`, createdAt, `status` FROM fooditem WHERE `status` != '-1'";

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
            $upload = $this->fileUpload('image', 'food');

            if(isset($upload['error']))
            {
                $this->actionResponse(1, 0, $upload['error']);
            }
            else
            {
                $insert = array(
                    'name' => $this->input->post('name'),
                    'image' => $upload,
                    'desc' => $this->input->post('desc'),
                    'createdAt' => time()
                );
                $this->actionResponse($this->insert($this->table, $insert), 1);

            }

            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            $this->data['data']       = $this->select('id, name as item_name, desc as Description, image, createdAt', $this->table, "id = " . $this->input->post('id'))->row();
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
            $upload = $this->fileUpload('image', 'food');

            if(isset($upload['error']))
            {
                $this->actionResponse(1, 0, $upload['error']);
            }
            else
            {
                $update = array(
                    'name' => $this->input->post('name'),
                    'image' => $upload,
                    'desc' => $this->input->post('desc'),
                    'updatedAt' => time()
                );
                $this->actionResponse($this->update($this->table, $update, 'id', $this->input->post('id')), 2);
            }

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