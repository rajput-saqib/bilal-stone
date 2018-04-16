<?php

    class Theme extends Admin_Controller
    {
        var $controller = 'theme';
        var $viewName = 'theme_view';
        var $table = 'theme';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $query = "SELECT * FROM ".$this->table." WHERE `status` != '-1' ";;

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
            $insert = array('name' => $this->input->post('name'), 'desc' => $this->input->post('desc'), 'createdAt' => time());
            $this->actionResponse($this->insert($this->table, $insert), 1);
            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            $this->data['data']       = $this->select('id, name as event_name, desc as Description', $this->table, "id = " . $this->input->post('id'))->row();
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
            $update = array('name' => $this->input->post('name'), 'desc' => $this->input->post('desc'), 'updatedAt' => time());
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

        function fileUploadConfig($path, $type = '*', $size = 8)
        {
            $config['upload_path']   = $path;
            $config['allowed_types'] = $type;
            $config['max_size']      = $size*1024;
            $config['remove_spaces'] = true;
            $config['encrypt_name']  = true;

            return $config;
        }

        function fileUpload($fileName, $path = '')
        {
            if($path = '')
            {
                $path = FRONT_IMAGE_PATH;
            }

            $config = $this->fileUploadConfig($path);
            $this->load->library('upload', $config);
            $this->upload->do_upload($fileName);
            $uploadData = array($this->upload->data());

            return $uploadData;
        }

        function uploadImages($fileName)
        {
            $uploadData = $this->fileUpload($fileName);

            $fileNameNew = $uploadData[0]['file_name'];

            $data = array(
                'product_id' => 0,
                'image' => $fileNameNew
            );

            $imageID = $this->insert('themeimages', $data);

            ob_start();
            ?>

            <div id='image_<?=$imageID?>'>
                <img src='<?=ASSETS_PATH.'front/products/'.$fileNameNew?>' title='<?=$fileNameNew?>'/>
                <a href="javascript:deleteRow('<?=$imageID?>','<?=$this->getURL("products/deleteImage");?>','<?="image_".$imageID?>')">Delete</a>
            </div>
            <?
            $html['div'] = ob_get_clean();
            echo json_encode($html);
        }
    }
?>

    <script type="text/javascript">

        $(document).ready(function(){
            $(function() {
                $('#imagesUpload').uploadify({
                    'swf'      : '<?=ADMIN_IMAGE_PATH?>uploadify.swf',
                    'uploader' : '<?=ADMIN_URL.'theme/uploadImages'?>',
                    'onUploadSuccess': function(data,data1) {
                        var json = $.parseJSON(data1);
                        $("#image-uploaded").append(json.div);
                    }
                });
            });
        });

    </script>