<?php

    class Hall extends Admin_Controller
    {
        var $controller = 'hall';
        var $viewName = 'hall_view';
        var $table = 'hall';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $query = "SELECT id, `name`, address, capacity, `partition`, parking, openArea, `status` FROM ".$this->table." WHERE `status` != '-1'";

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
            $insert = array(
                'userId' => $this->input->post('userId'),
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'cityId' => $this->input->post('cityId'),
                'longitude' => $this->input->post('longitude'),
                'latitude' => $this->input->post('latitude'),
                'hallType' => $this->input->post('hallType'),
                'capacity' => $this->input->post('capacity'),
                'partition' => $this->input->post('partition'),
                'parking' => $this->input->post('parking'),
                'openArea' => $this->input->post('openArea'),
                'ladiesWaiter' => $this->input->post('ladiesWaiter'),
                'watierRatio' => $this->input->post('watierRatio'),
                'outsideCatring' => $this->input->post('outsideCatring'),
                'landscape' => $this->input->post('landscape'),
                'createdAt' => time()
            );

            $this->actionResponse($this->insert($this->table, $insert), 1);
            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            $this->data['data']       = $this->select('name, address, capacity, partition, parking, openArea, status', $this->table, "id = " . $this->input->post('id'))->row();
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
            $update = array(
                'userId' => $this->input->post('userId'),
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'cityId' => $this->input->post('cityId'),
                'longitude' => $this->input->post('longitude'),
                'latitude' => $this->input->post('latitude'),
                'hallType' => $this->input->post('hallType'),
                'capacity' => $this->input->post('capacity'),
                'partition' => $this->input->post('partition'),
                'parking' => $this->input->post('parking'),
                'openArea' => $this->input->post('openArea'),
                'ladiesWaiter' => $this->input->post('ladiesWaiter'),
                'watierRatio' => $this->input->post('watierRatio'),
                'outsideCatring' => $this->input->post('outsideCatring'),
                'landscape' => $this->input->post('landscape'),
                'createdAt' => time()
            );

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