<?php

    class Login_Controller extends My_Controller
    {
        public function __construct()
        {
            parent::__construct();

            if($this->session->userdata('admin_login_data'))
            {
                redirect(ADMIN_URL.'dashboard');
            }
        }

        function loadView($data = '')
        {
            $this->load->view('admin/header_view');
            $this->load->view('admin/login_view', $data);
        }

        function authenticate($userName, $password)
        {
            $row = $this->select("", "users", "username = '".$userName."'")->row();

            if($row)
            {
                $hashPassword = $this->decrypt($row->password, $row->salt);

                if($password == $hashPassword)
                {
                    $admin_login_data = array(
                        'id' => $row->id,
                        'username' => $row->username,
                        'adminRole' => $row->type,
                        'complete_name' => $row->firstName.' '.$row->lastName,
                        'company_name' => $row->company_name,
                        'per_page_rows' => $row->per_page_rows
                    );

                    $this->session->set_userdata('admin_login_data',$admin_login_data);
                    redirect(ADMIN_URL);
                }
                else
                {
                    $error = 'Invalid Password.';
                }
            }
            else
            {
                $error = 'Invalid User Name.';
            }

            return $error;
        }
    }
