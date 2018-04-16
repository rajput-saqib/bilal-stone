<?php

    class Profile extends Admin_Controller
    {
        var $controller = 'profile';
        var $viewName = 'profile_view';
        var $viewName2 = 'backup_view';
        var $table = 'users';

        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $query = "SELECT id, `username`, firstName 'first_name', lastName 'last_name', `company_name` FROM ".$this->table." WHERE `id` = '2'";

            $temp             = new template();
            $temp->query      = $query;
            $temp->controller = $this->controller;
            $temp->view        = false;
            $temp->delete     = false;

            $pageNo = $this->getUrlValue(4);
            if(!empty($pageNo))
            {
                $temp->pageNo = $pageNo;
            }

            $this->loadView($temp->pagination(), $this->data);
        }

        function new_()
        {
            $this->data['submitPath'] = '/createBackup';
            $html = $this->loadHtml($this->viewName2, $this->data);
            echo $html;
        }

        function createBackup()
        {
            $userName = $this->input->post('username');
            $password = $this->input->post('password');

            if(!empty($userName) && !empty($password))
            {
                $result = true;
                $row = $this->select("", "users", "username = '".$userName."'")->row();

                if($row)
                {
                    $hashPassword = $this->decrypt($row->password, $row->salt);

                    if($password == $hashPassword)
                    {
                        $result = false;
                        $this->downloadFile();
                    }
                }

                if($result)
                {
                    $this->actionResponse(1, 0, 'Invalid UserName/ Password.');
                }
                else
                {
                    $this->actionResponse(1, 0, 'File Downloading.');
                }

                redirect(ADMIN_URL.$this->controller);
            }
        }

        function downloadFile()
        {
            $tables = $this->executeQuery('SHOW TABLES')->result();

            $data = [];
            $database = $this->db->database;
            foreach($tables as $key => $value)
            {
                $index = 'Tables_in_'.$database;
                $data[$value->$index] = json_encode($this->select('', $value->$index)->result());
            }

            header('Content-type: text/plain');
            header('Content-disposition: attachment; filename="backup_'.time().'.sql"');

            echo base64_encode(json_encode($data));
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
            $salt = $this->generateRandomString();
            $password = $this->encrypt($this->input->post('password'), $salt);
            $update = array('username' => $this->input->post('username'), 'firstName' => $this->input->post('firstName'), 'lastName' => $this->input->post('lastName'), 'password' => $password, 'salt' => $salt, 'company_name' => $this->input->post('company_name'));
            $this->actionResponse($this->update($this->table, $update, 'id', '2'), 2);
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