<?php

    if (!defined('BASEPATH'))
    {
        exit('No direct script access allowed');
    }

    class Index extends Admin_Controller
    {
        function __construct()
        {
            parent::__construct();
        }

        function index()
        {
            echo "Front index........";
        }
    }
