<?php

    class Stock_Detail extends Admin_Controller
    {
        var $controller = 'stock_detail';
        var $id;
        var $type;

        var $table = '';
        var $table2 = 'stock_detail';
        var $table3 = 'clients';
        var $table4 = 'products';
        var $table5 = 'items';

        var $data = array();
        var $headingText = '';

        function __construct()
        {
            parent::__construct();

            if(isset($_GET['id'])){
                list($this->id, $this->type) = explode('|', $this->decrypt($this->input->get('id')));
            }
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            if($this->id > 0)
            {
                if($this->type == '1')
                {
                    $row = $this->select('', $this->table5, 'id ='.$this->id)->row();
                    $this->headingText = 'Item Name : '.$row->name.'('.$row->qty_per_bag.')';
                    
                    $query = "SELECT s.id, s.`type_c` 'client_type', c.`name`, s.client_id, s.date, s.`type_i` 'type', s.`type_i` 'invoice_type', CONCAT('Invoice(',(s.`id`),') - Entries(',COUNT(s.`id`),')') 'invoice_detail', SUM(CASE WHEN s.`type_i` = 1 THEN sd.`qty` ELSE 0 END) 'debit', SUM(CASE WHEN s.`type_i` = 2 OR s.`type_i` = 3 THEN sd.`qty` ELSE 0 END) 'credit', SUM(CASE WHEN s.`type_i` = 1 THEN sd.`qty` ELSE (-sd.`qty`) END) 'balance' FROM items i INNER JOIN `stock_detail` sd ON i.`id` = sd.`item_id` INNER JOIN `stock` s ON s.`id` = sd.`stock_id` INNER JOIN `clients` c ON c.`id` = s.`client_id` WHERE s.`status` = '1' AND i.`id` = '" . $this->id . "' GROUP BY s.`id`";
                }
                if($this->type == '2')
                {
                    $row = $this->select('', $this->table4, 'id ='.$this->id)->row();
                    $this->headingText = 'Product Name : '.$row->name;

                    $query = "SELECT i.id, i.`type_c` 'client_type', c.`name`, i.client_id, i.date, i.`type_i` 'type', i.`type_i` 'invoice_type', CONCAT('Invoice(',(i.`id`),') - Entries(',COUNT(i.`id`),')') 'invoice_detail', SUM(CASE WHEN i.`type_i` = 1 THEN id.`qty` ELSE 0 END) 'debit', SUM(CASE WHEN i.`type_i` = 2 OR i.`type_i` = 3 THEN id.`qty` ELSE 0 END) 'credit', SUM(CASE WHEN i.`type_i` = 1 THEN id.`qty` ELSE (-id.`qty`) END) 'balance' FROM `products` p INNER JOIN `invoice_detail` id ON p.`id` = id.`product_id` INNER JOIN invoice i ON i.`id` = id.`invoice_id` INNER JOIN `clients` c ON c.`id` = i.`client_id` WHERE i.`status` = '1' AND p.`id` = '" . $this->id . "' GROUP BY i.`id`";
                }
            }
            else
            {
                $query = "SELECT 'items' AS 'client_id', CONCAT(i.`id`,'|1') 'id', i.`name`, CONCAT('Purchase : (', SUM(CASE WHEN s.`type_i` = 1 THEN 1 ELSE 0 END),')', ' & Send (', SUM(CASE WHEN (s.`type_i` = 2 OR s.`type_i` = 3) THEN 1 ELSE 0 END),')') 'detail', SUM(CASE WHEN s.`type_i` = 1 THEN sd.`qty` ELSE (-sd.`qty`) END) AS 'Reamining' FROM items i INNER JOIN `stock_detail` sd ON i.`id` = sd.`item_id` INNER JOIN `stock` s ON s.`id` = sd.`stock_id` WHERE s.`status` = '1' GROUP BY i.`id`";
                $query .= " UNION ALL ";
                $query .= "SELECT 'product' AS 'client_id', CONCAT(p.`id`,'|2') 'id', p.`name`, CONCAT('Purchase : (', SUM(CASE WHEN i.`type_i` = 1 THEN 1 ELSE 0 END),')', ' & Send (', SUM(CASE WHEN (i.`type_i` = 2) THEN 1 ELSE 0 END),')') 'detail', SUM(CASE WHEN i.`type_i` = 1 THEN id.`qty` ELSE (-id.`qty`) END) AS 'Reamining' FROM `products` p INNER JOIN `invoice_detail` id ON p.`id` = id.`product_id` INNER JOIN invoice i ON i.`id` = id.`invoice_id` WHERE i.`status` = '1' GROUP BY p.`id` ORDER BY `client_id`, id";
            }


            $temp             = new template();
            $temp->query      = $query;
            $temp->action      = false;
            $temp->controller = $this->controller;
            $temp->headingText = $this->headingText;

            $pageNo = $this->getUrlValue(4);
            if(!empty($pageNo))
            {
                $temp->pageNo = $pageNo;
            }

            $this->loadView($temp->pagination(), $this->data);
        }
    }


    ?>