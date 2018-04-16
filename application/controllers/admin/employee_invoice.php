<?php

    class Employee_Invoice extends Admin_Controller
    {
        var $controller = 'employee_invoice';
        var $table = 'stock';
        var $table2 = 'stock_detail';

        var $table_ = 'invoice';
        var $table2_ = 'invoice_detail';

        var $table3 = 'clients';

        var $table4 = 'items';
        var $table4_ = 'products';

        var $type = '3';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $query = "SELECT CONCAT(i.`id`,'|',i.`type_i`) 'id', i.`date`, c.`name`, i.`type_i` 'type', i.`id` 'invoice_no', i.reference_no, i.`total_amount` 'total', i.`status`, i.`client_id` FROM stock i INNER JOIN clients c ON i.`client_id` = c.`id` WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type."' UNION ALL SELECT CONCAT(i.`id`,'|',i.`type_i`) 'id', i.`date`, c.`name`, i.`type_i` 'type', i.`id` 'invoice_no', i.reference_no, i.`total_amount` 'total', i.`status`, i.`client_id` FROM invoice i INNER JOIN clients c ON i.`client_id` = c.`id` WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type."' ORDER BY `type` ";
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
            $this->data['controller'] = $this->controller;
            $this->data['selectBox_clients'] = $this->select('id, name', 'clients', "`status` != '-1' AND `type` = '".$this->type."'")->result();
            $this->data['selectBox_items'] = $this->executeQuery("SELECT id, NAME, CONCAT(`name`, ' (', qty_per_bag, ')') AS `name` FROM items WHERE `status` = '1'")->result();
            $this->data['selectBox_products'] = $this->select('id, name', 'products', "`status` != '-1'")->result();

            if($this->input->post('id') == 1)
            {
                $this->data['type_i'] = '1';
                $temp = 'purchase_invoice_view_';
            }
            if($this->input->post('id') == 2)
            {
                $this->data['type_i'] = '2';
                $temp = 'purchase_invoice_view';
            }
            if($this->input->post('id') == 3)
            {
                $this->data['type_i'] = '3';
                $temp = 'purchase_invoice_view_';
            }
            if($this->input->post('id') == 4)
            {
                $this->data['type_i'] = '3';
                $temp = 'payment_invoice_view_';
            }


            echo $temp;
            $html = $this->loadHtml($temp, $this->data);
            echo $html;
        }

        function save_()
        {
            $insert = array(
                'type_c' => $this->type,
                'type_i' => $this->input->post('type_i'),
                'date' => strtotime($this->input->post('date')),
                'client_id' => $this->input->post('client_id'),
                'reference_no' => addslashes($this->input->post('reference_no')),
                'total_amount' => $this->input->post('total_amount'),
                'created_at' => time()
            );

            if($this->input->post('type_i') == 1)
            {
                $tableName = $this->table;
                $tableName2 = $this->table2;

                $colunmName1 = 'stock_id';
                $colunmName2 = 'item_id';
                $item = $this->input->post('item');
                $limit = count($item);
            }
            if($this->input->post('type_i') == 2)
            {
                $tableName = $this->table_;
                $tableName2 = $this->table2_;

                $colunmName1 = 'invoice_id';
                $colunmName2 = 'product_id';
                $item = $this->input->post('product');
                $limit = count($item);
            }
            if($this->input->post('type_i') == 3)
            {
                $tableName = $this->table;
                $tableName2 = $this->table2;

                $colunmName1 = 'stock_id';
                $colunmName2 = 'item_id';
                $item = $this->input->post('item');
                $limit = count($item);
            }
            if($this->input->post('type_i') == 4)
            {
                $tableName = $this->table;
                $tableName2 = $this->table2;

                $colunmName1 = 'stock_id';
                $colunmName2 = 'item_id';
                $item = $this->input->post('item');
                $limit = count($this->input->post('price'));
            }

            $stock = $this->insert($tableName, $insert);
            $this->actionResponse($stock, 1);

            $qty     = $this->input->post('qty');
            $rate    = $this->input->post('rate');
            $price  = $this->input->post('price');
            $desc  = $this->input->post('desc');

            for($a = 0 ; $a < $limit ; $a++)
            {
                $insert2 = array($colunmName1 => $stock, $colunmName2 => $item[$a], 'qty' => $qty[$a], 'rate' => $rate[$a], 'price' => $price[$a], 'desc' => $desc[$a], 'created_at' => time());
                $this->insert($tableName2, $insert2);
            }

            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            list($id, $type_i) = explode('|', $this->input->post('id'));

            if($type_i == '1')
            {
                $query1 = "SELECT i.`id`, i.`type_i`, i.reference_no, i.`date`, c.`name`, i.`total_amount`, i.`status` FROM ".$this->table." i INNER JOIN ".$this->table3." c ON i.`client_id` = c.`id`  WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type."' AND i.`id` = '".$id."'";

                $query2   = "SELECT p.`name` 'item', id.`qty` 'quantity' FROM ".$this->table2." id INNER JOIN ".$this->table4." p ON id.`item_id` = p.`id` WHERE id.`stock_id` = '" . $id . "' AND id.`status` != '-1' GROUP BY id.`id`";
                $colunms = array('item', 'quantity');
            }

            if($type_i == '2')
            {
                $query1 = "SELECT i.`id`, i.`type_i`, i.reference_no, i.`date`, c.`name`, i.`total_amount`, i.`status` FROM ".$this->table_." i INNER JOIN ".$this->table3." c ON i.`client_id` = c.`id`  WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type."' AND i.`id` = '".$id."'";

                $query2   = "SELECT p.`name` 'product', id.`qty` 'quantity', id.`rate`, id.`price` FROM ".$this->table2_." id INNER JOIN ".$this->table4_." p ON id.`product_id` = p.`id` WHERE id.`invoice_id` = '" . $id . "' AND id.`status` != '-1' GROUP BY id.`id`";
                $colunms = array('product', 'quantity', 'rate', 'price');
            }

            if($type_i == '3')
            {
                $query1 = "SELECT i.`id`, i.`type_i`, i.reference_no, i.`date`, c.`name`, i.`total_amount`, i.`status` FROM ".$this->table." i INNER JOIN ".$this->table3." c ON i.`client_id` = c.`id`  WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type."' AND i.`id` = '".$id."'";

                $query2   = "SELECT p.`name` 'item', id.`qty` 'quantity' FROM ".$this->table2." id INNER JOIN ".$this->table4." p ON id.`item_id` = p.`id` WHERE id.`stock_id` = '" . $id . "' AND id.`status` != '-1' GROUP BY id.`id`";
                $colunms = array('item', 'quantity');
            }

            else if($type_i == '4')
            {
                $query1 = "SELECT i.`id`, i.`type_i`, i.reference_no, i.`date`, c.`name`, i.`total_amount`, i.`status` FROM ".$this->table." i INNER JOIN ".$this->table3." c ON i.`client_id` = c.`id`  WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type."' AND i.`id` = '".$id."'";

                $query2   = "SELECT id.`price`, id.`desc` 'description' FROM ".$this->table2." id WHERE id.`stock_id` = '" . $id . "' AND id.`status` != '-1' GROUP BY id.`id`";
                $colunms = array('description', 'price');
            }

            $this->data['data']             = $this->executeQuery($query1)->row();
            $this->data['data2']['data']    = $this->executeQuery($query2)->result();
            $this->data['data2']['colunms'] = $colunms;

            $html = $this->loadHtml('detail_view', $this->data);
            echo $html;
        }

        function edit_()
        {
            list($id, $type_i) = explode('|', $this->input->post('id'));

            if($type_i == '1')
            {
                $temp = 'purchase_invoice_view_';
                $tableName = $this->table;

                $colunm = 'stock_id';
                $tableName2 = $this->table2;
            }
            if($type_i == '2')
            {
                $temp = 'purchase_invoice_view';
                $tableName = $this->table_;

                $colunm = 'invoice_id';
                $tableName2 = $this->table2_;
            }
            if($type_i == '3')
            {
                $temp = 'purchase_invoice_view_';
                $tableName = $this->table;

                $colunm = 'stock_id';
                $tableName2 = $this->table2;
            }
            else if($type_i == '4')
            {
                $temp = 'payment_invoice_view_';
                $tableName = $this->table;

                $colunm = 'stock_id';
                $tableName2 = $this->table2;
            }

            $this->data['type_i'] = $type_i;
            $this->data['submitPath'] = '/update_';
            $this->data['data']       = $this->select('', $tableName, "id = " . $id)->row();
            $this->data['data2']       = $this->select('', $tableName2, "$colunm = " . $id. " AND `status` != '-1'" )->result();

            $this->data['controller'] = $this->controller;
            $this->data['selectBox_clients'] = $this->select('id, name', 'clients', "`status` != '-1' AND `type` = '".$this->type."'")->result();
            $this->data['selectBox_items'] = $this->executeQuery("SELECT id, NAME, CONCAT(`name`, ' (', qty_per_bag, ')') AS `name` FROM items WHERE `status` = '1'")->result();
            $this->data['selectBox_products'] = $this->select('id, name', 'products', "`status` != '-1'")->result();

            $html = $this->loadHtml($temp, $this->data);
            echo $html;
        }

        function update_()
        {
            $update = array(
                'date' => strtotime($this->input->post('date')),
                'client_id' => $this->input->post('client_id'),
                'reference_no' => addslashes($this->input->post('reference_no')),
                'total_amount' => $this->input->post('total_amount'),
                'modified_at' => time()
            );

            if($this->input->post('type_i') == 1)
            {
                $tableName = $this->table;
                $tableName2 = $this->table2;

                $colunmName1 = 'stock_id';
                $colunmName2 = 'item_id';
                $item = $this->input->post('item');
                $limit = count($item);
            }
            if($this->input->post('type_i') == 2)
            {
                $tableName = $this->table_;
                $tableName2 = $this->table2_;

                $colunmName1 = 'invoice_id';
                $colunmName2 = 'product_id';
                $item = $this->input->post('product');
                $limit = count($item);
            }
            if($this->input->post('type_i') == 3)
            {
                $tableName = $this->table;
                $tableName2 = $this->table2;

                $colunmName1 = 'stock_id';
                $colunmName2 = 'item_id';
                $item = $this->input->post('item');
                $limit = count($item);
            }
            if($this->input->post('type_i') == 4)
            {
                $tableName = $this->table;
                $tableName2 = $this->table2;

                $colunmName1 = 'stock_id';
                $colunmName2 = 'item_id';
                $item = $this->input->post('item');
                $limit = count($this->input->post('price'));
            }

            if($this->update($tableName, $update, 'id', $this->input->post('id')))
            {
                $this->actionResponse($this->input->post('id'), 2);
            }

            $qty     = $this->input->post('qty');
            $rate    = $this->input->post('rate');
            $price  = $this->input->post('price');
            $desc  = $this->input->post('desc');

            if($this->update($tableName2, array('status' => '-1'), $colunmName1, $this->input->post('id')))
            {
                for ($a = 0; $a < $limit; $a++)
                {
                    $insert2 = array($colunmName1 => $this->input->post('id'), $colunmName2 => $item[$a], 'qty' => $qty[$a], 'rate' => $rate[$a], 'price' => $price[$a], 'desc' => $desc[$a], 'modified_at' => time());
                    $this->insert($tableName2, $insert2);
                }
            }

            redirect(ADMIN_URL . $this->controller);
        }

        function delete_()
        {
            list($id, $type_i) = explode('|', $this->input->post('id'));

            if($type_i == '1' || $type_i == '3' || $type_i == '4')
            {
                $tableName = $this->table;
            }
        if($type_i == '2')
            {
                $tableName = $this->table_;
            }

            $update = array('status' => '-1', 'deleted_at' => time());
            $this->actionResponse(1, 3);
            echo $this->update($tableName, $update, 'id', $id);
        }

        function status_()
        {
            list($id, $type_i) = explode('|', $this->input->post('id'));

            if ($type_i == '1' || $type_i == '3' || $type_i == '4')
            {
                $tableName = $this->table;
            }
            if ($type_i == '2')
            {
                $tableName = $this->table_;
             }

            $update = array('status' => $this->input->post('status'));
            $this->actionResponse(1, 4);
            echo $this->update($tableName, $update, 'id', $id);
        }
    }