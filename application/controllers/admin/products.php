<?php

    class Products extends Admin_Controller
    {
        var $controller = 'products';
        var $viewName = 'products_view';
        var $table = 'products';
        var $table2 = 'product_detail';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $searchedText = $_GET['searchedText'];

            $where = '';
            if($searchedText != '') {
                $where = " AND `name` LIKE '%$searchedText%'";
            }

            $query = "SELECT id, `name`, `desc`, `status` FROM ".$this->table." WHERE `status` != '-1' $where ORDER BY `name`";

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
            $insert = array('name' => $this->input->post('name'), 'desc' => $this->input->post('desc'), 'created_at' => time());
            $productId = $this->insert($this->table, $insert);

            $this->actionResponse($productId, 1);

            $itemType = $this->input->post('itemType');
            $items    = $this->input->post('items');
            $qty      = $this->input->post('qty');


            for($a = 0 ; $a < count($items) ; $a++)
            {
                $insert2 = array('product_id' => $productId, 'itemType' => $itemType[$a], 'item_id' => $items[$a], 'quantity' => $qty[$a]);
                $this->insert($this->table2, $insert2);
            }


            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            $this->data['data']       = $this->select('id, name as item_name, desc as description', $this->table, "id = " . $this->input->post('id'))->row();

            $query2 = "SELECT pd.itemType, pd.`quantity`  FROM products p INNER JOIN `product_detail` pd ON p.`id` = pd.`product_id` WHERE p.`id` = '".$this->input->post('id')."' AND pd.`status` = '1' GROUP BY pd.`id`";
            $this->data['data2']['data']       = $this->executeQuery($query2)->result();
            $this->data['data2']['colunms']       = array('itemType', 'quantity');

            $html = $this->loadHtml('detail_view', $this->data);
            echo $html;
        }

        function edit_()
        {
            $this->data['submitPath'] = '/update_';
            $this->data['data']       = $this->select('', $this->table, "id = " . $this->input->post('id'))->row();
            $this->data['data2']       = $this->select('', $this->table2, "product_id = " . $this->input->post('id')." AND `status` != '-1'")->result();
            $html = $this->loadHtml($this->viewName, $this->data);
            echo $html;
        }

        function update_()
        {
            $update = array('name' => $this->input->post('name'), 'desc' => $this->input->post('desc'), 'modified_at' => time());

            if($this->update($this->table, $update, 'id', $this->input->post('id')))
            {
                $this->actionResponse($this->input->post('id'), 2);
            }

            $itemType = $this->input->post('itemType');
            $items = $this->input->post('items');
            $qty = $this->input->post('qty');

            if($this->update($this->table2, array('status' => '-1'), 'product_id', $this->input->post('id')))
            {
                for($a = 0 ; $a < count($items) ; $a++)
                {
                    $insert2 = array('product_id' => $this->input->post('id'), 'itemType' => $itemType[$a], 'item_id' => $items[$a], 'quantity' => $qty[$a]);
                    $this->insert($this->table2, $insert2);
                }
            }

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


        function saveAndNew()
        {
            $insert = array('name' => $this->input->post('name'), 'desc' => $this->input->post('desc'), 'created_at' => time());
            $productId = $this->insert($this->table, $insert);

            if($productId > 0)
            {
                $itemType = array_filter(explode("|", $this->input->post('itemType')));
                $qty      = array_filter(explode("|", $this->input->post('qty')));

                for ($a = 0; $a < count($itemType); $a++)
                {
                    $insert2 = array('product_id' => $productId, 'itemType' => $itemType[$a], 'item_id' => 0, 'quantity' => $qty[$a]);
                    $this->insert($this->table2, $insert2);
                }

                echo "1";
            }

        }
    }