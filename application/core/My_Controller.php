<?php

    if (!defined('BASEPATH')) { exit('No direct script access allowed'); }

    include dirname(__FILE__)."/../controllers/admin/template.php";

    class My_Controller extends CI_Controller
    {
        var $userType = array('1' => 'User', '2' => 'Agency');

        function __construct()
        {
            parent::__construct();
            $this->load->helper('url');
            $this->load->helper('file');

            $this->load->library('session');
            $this->load->library('encrypt');

            $this->load->model('my_model');
        }

        function encrypt($value, $salt = '')
        {
            if($salt == '')
            {
                $salt = $this->config->item('encryption_key');
            }

            $result = $this->encrypt->encode($value, $salt);
            $result = str_replace(array('+', '/', '='), array('-', '_', '~'), $this->encrypt->encode($value, $salt));

            return $result;
        }

        function decrypt($value, $salt = '')
        {
            if($salt == '')
            {
                $salt = $this->config->item('encryption_key');
            }

            $result = $this->encrypt->decode($value, $salt);
            $result = $this->encrypt->decode(str_replace(array('-', '_', '~'), array('+', '/', '='), $value), $salt);

            return $result;
        }

        function getKeys($data)
        {
            $result = array();

            if(count($data) > 0)
            {
                foreach ($data as $key => $value)
                {
                    $result[] = $key;
                }
            }

            return $result;
        }

        function createHeading($value)
        {
            $result = str_replace('_', ' ', $value);

            return ucwords($result);
        }

        function select($column = '', $table, $where = '')
        {
            $result = $this->my_model->selectTable($column, $table, $where);

            return $result;
        }

        function executeQuery($query)
        {
            $result = $this->my_model->executeQuery($query);

            return $result;
        }

        function insert($table, $data)
        {
            $result = $this->my_model->insertIntoTable($table, $data);

            return $result;
        }

        function update($table, $data, $whereColunm, $whereValue)
        {
            $result = $this->my_model->updateQuery($table, $data, $whereColunm, $whereValue);

            return $result;
        }

        function deleteRow($table, $where)
        {
            $result = $this->my_model->deleteRow($table, $where);

            return $result;
        }

        function getUrlValue($no)
        {
            $value = $this->uri->segment($no);
            return $value;
        }

        function showStatus($value)
        {
            if($value == 0)
            {
                $html = "<span class='label label-warning'>Inactive</span>";
            }
            else if($value == 1)
            {
                $html = "<span class='label label-success'>Active</span>";
            }
            else
            {
                $html = "<span class='label label-important'>Banned</span>";
            }

            return $html;
        }

        function showDate($value)
        {
            $result = 'N/A';
            if($value != '')
            {
                $result = date('d-m-Y', $value);
            }

            return $result;
        }

        function showDateWithTime($value)
        {
            $result = 'N/A';
            if($value != '')
            {
                $result = date('d-m-Y H:i:s', $value);
            }

            return $result;
        }

        function showInvoiceType($value, $controller = '')
        {
            $result = '';

            if($controller == '')
            {
                if ($value == '1')
                {
                    $result = 'sale';
                }
                else if ($value == '2')
                {
                    $result = 'purchase';
                }
                else if ($value == '3')
                {
                    $result = 'return';
                }
                else if ($value == '4')
                {
                    $result = 'payment';
                }
                else if ($value == '5')
                {
                    $result = 'staff';
                }
            }
            else
            {
                if ($value == '1')
                {
                    $result = '<span style="color:#7bb33d">Purchase</span>';
                }
                else if ($value == '2')
                {
                    $result = '<span style="color:#f7b42c">Sale</span>';
                }
                else if ($value == '3')
                {
                    $result = '<span style="color: #9e6ab8">Production</span>';
                }
                else if ($value == '4')
                {
                    $result = '<span style="color:#d41e24">Payment</span>';
                }
                else if ($value == '5')
                {
                    $result = '<span style="color:#0000ff">Staff</span>';
                }
            }
            return $result;
        }

        function showClientType($value)
        {
            $result = '';

            if($value == '1')
            {
                $result = 'Supplier';
            }
            else if($value == '2')
            {
                $result = 'Customer';
            }
            else if($value == '3')
            {
                $result = 'Vendor';
            }
            else if($value == '5')
            {
                $result = 'Staff';
            }

            return $result;
        }

        function selectBox_db($id, $title, $data = array(), $selected = '')
        {
            ob_start();

            if($data)
            {
                ?><option value="" <?if(!$selected) echo "SELECTED";?> > ----- Select ----- </option><?
                foreach($data as $key => $value)
                {
                    ?>
                        <option value="<?=$value->$id?>" <?if($selected != '' && $selected == $value->$id) echo "SELECTED";?> > <?=$value->$title?> </option>
                    <?
                }
            }
            else
            {
                ?><option selected value=""> Not Available </option><?
            }

            echo ob_get_clean();
        }

        function selectBox_array($data, $selected = '')
        {
            ob_start();
            if($data)
            {
                ?><option value=""> ----- Select ----- </option><?
                foreach($data as $key => $value)
                {
                    ?>
                        <option value="<?=$key?>" <?if($selected != '' && $selected == $key) echo "SELECTED";?> > <?=$value?> </option>
                    <?
                }
            }
            else
            {
                ?><option selected value=""> Not Available </option><?
            }

            $html = ob_get_clean();
            return $html;
        }

        function convertPostIntoArray($array)
        {
            $result = 'array(';
            foreach($array as $key => $value)
            {
                $result .= "'".$key."' => $&this->input->post('".$key."'), <br/>";
            }

            $result .= ');';

            $return = str_replace('&', '', $result);
            echo '<pre>';print_r($return );echo '</pre>';
        }

        function savefile($data, $key)
        {
            return base64_encode($this->encrypt(json_encode($data), md5($key)));
        }

        function generateRandomString($length = 10)
        {
            $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString     = '';
            for ($i = 0; $i < $length; $i++)
            {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            return $randomString;
        }

        function fileUpload($fieldName, $folder)
        {
            $response = '';

            if(!empty($_FILES[$fieldName]['name']))
            {
                $config['upload_path']   = './assets/' .$folder;
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']      = '8024';
                $config['remove_spaces'] = true;
                $config['encrypt_name']  = true;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload($fieldName))
                {
                    $response = array('error' => $this->upload->display_errors());
                }
                else
                {
                    $response = 'assets/'.$folder.'/'.$this->upload->data()['file_name'];
                }
            }

            return $response;
        }
    }
