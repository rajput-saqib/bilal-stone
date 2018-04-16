<?php

    class Dashboard extends Admin_Controller
    {
        var $controllerName = '';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controllerName;
        }

        function index()
        {
            $this->loadView($this->load->view('admin/dashboard_view', $this->data, true));
        }
    }