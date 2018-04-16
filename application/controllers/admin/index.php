<?php

    class Index extends Admin_Controller
    {
        function __construct()
        {
            parent::__construct();
        }

        function index()
        {
            redirect(ADMIN_URL.'dashboard');
        }

        function logout()
        {
            $this->session->unset_userdata('admin_login_data');
            redirect(ADMIN_URL, 'refresh');
        }
    }