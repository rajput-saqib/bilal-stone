<?php

    class Admin_Controller extends My_Controller
    {
        var $pageLimit = 1;
        function __construct()
        {
            date_default_timezone_set("Asia/Karachi");
            parent::__construct();

            if(!$this->session->userdata('admin_login_data'))
            {
                redirect(ADMIN_URL.'login');
            }
        }

        function getURL($controller = '')
        {
            $url = ADMIN_URL;

            if($controller != '')
            {
                $url .= '/'.$controller;
            }

            return $url;
        }

        function loadView($pageData, $data = '')
        {
            $data['obj'] = new My_Controller();

            $data['loginData']    = $this->session->userdata['admin_login_data'];
            if(isset($this->session->userdata['notification']))
            {
                $data['notification'] = $this->session->userdata['notification'];
            }

            $finalData['header'] = $this->load->view('admin/header_view', $data, true);
            $finalData['topMenu'] = $this->load->view('admin/topMenu', $data, true);
            $finalData['leftMenu'] = $this->load->view('admin/leftMenu', $data, true);
            $finalData['footer'] = $this->load->view('admin/footer_view', $data, true);

            $finalData['pageData'] = $pageData;

            $data['notification'] = $this->session->unset_userdata('notification');

            $this->load->view('admin/master_view', $finalData);
        }

        function loadHtml($htmlView, $data)
        {
            $data['obj'] = new My_Controller();
            $finalData['html'] = $this->load->view('admin/'.$htmlView, $data, true);
            $html = $this->load->view('admin/master_html', $finalData, true);

            return $html;
        }

        function actionResponse($id, $cond, $msg = '')
        {
            if($id > 0)
            {
                $array['status'] = '1';

                if($cond == 0)
                {
                    $array['msg'] = $msg;
                }
                else if($cond == 1)
                {
                    $array['msg'] = 'Data Insert Successfully.';
                }
                else if($cond == 2)
                {
                    $array['msg'] = 'Data Update Successfully.';
                }
                else if($cond == 3)
                {
                    $array['msg'] = 'Data Delete Successfully.';
                }

                else if($cond == 4)
                {
                    $array['msg'] = 'Status Changes Successfully.';
                }
                else
                {
                    $array['msg'] = 'Action Perform Successfully.';
                }
            }
            else
            {
                $array = array('status' => '0', 'msg' => 'Something Went Wrong.');
            }

            $this->session->set_userdata('notification', $array);
        }

        static function fileMap($fileName = '')
        {
            ob_start();
            ?>
                <div>
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?=ADMIN_URL?>">Home</a>
                            <?
                            if($fileName != '')
                            {
                                ?>
                                    <span class="divider">/</span>
                                </li>
                                <li>
                                    <a href="<?=ADMIN_URL.$fileName?>"><?=ucfirst($fileName)?></a>
                                <?
                            }?>
                        </li>
                    </ul>
                </div>
            <?
            $html = ob_get_clean();

            return $html;
        }
    }