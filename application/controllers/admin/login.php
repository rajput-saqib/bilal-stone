<?php

    class Login extends Login_Controller
    {
        function __construct()
        {
            parent::__construct();
        }

        function index()
        {
            if($_POST)
            {
                $this->post();
            }
            else
            {
                $this->loadView();
            }
        }

        function post()
        {
            $data = array();
            $userName = $this->input->post('username');
            $password = $this->input->post('password');

            if(!empty($userName) && !empty($password))
            {
                $data['error'] = $this->authenticate($userName, $password);
            }

            $this->loadView($data);
        }
    }