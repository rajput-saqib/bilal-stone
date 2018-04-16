<?php

    class Product_Stock extends Admin_Controller
    {
        var $controller = 'product_stock';
        var $id;
        var $type;

        var $table = 'invoice';
        var $table2 = 'invoice_detail';
        var $table3 = 'clients';
        var $table4 = 'products';
        var $table5 = 'items';

        var $data = array();
        var $headingText = '';
        var $type_c = 2;
        function __construct()
        {
            parent::__construct();

            if(isset($_GET['id'])){
                $this->id = $this->decrypt($this->input->get('id'));
            }
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $whereDate = '';
            $startDate = $_GET['startDate'];
            $endDate = $_GET['endDate'];

            if($startDate !='')
            {
                $whereDate .= " AND i.date >= '".strtotime($startDate)."' ";
            }

            if($endDate !='')
            {
                $whereDate .= " AND i.date <= '".strtotime($endDate)."' ";
            }

            $this->headingText = '';

            $searchedText = $_GET['searchedText'];
            $whereSearchedText = ($searchedText != '') ? " AND (p.`name` LIKE '%".$searchedText."%')" : '';

            $client_id = $_GET['client_id'];
            $whereClient = ($client_id != '') ? " AND i.`client_id` = '".$client_id."'" : '';

            $query = "SELECT p.`id` 'id', p.`name`, CONCAT('Make (', SUM(CASE WHEN i.`type_i` = 3 THEN 1 ELSE 0 END),')',' & Sale (', SUM(CASE WHEN (i.`type_i` = 2) THEN 1 ELSE 0 END),')') 'detail', SUM(CASE WHEN i.`type_i` = 3 THEN id.qty ELSE 0 END) 'Make Products', SUM(CASE WHEN i.`type_i` = 2 THEN id.qty ELSE 0 END) 'Sale Products', SUM(CASE WHEN i.`type_i` = 3 THEN id.`qty` ELSE (- id.`qty`) END) AS 'Reamining' FROM `products` p INNER JOIN `invoice_detail` id ON p.`id` = id.`product_id` INNER JOIN invoice i ON i.`id` = id.`invoice_id` WHERE i.`status` = '1' AND id.`status` = '1' $whereSearchedText $whereDate $whereClient AND (i.`type_i` = 2 OR i.`type_i` = 3) GROUP BY p.`id` ORDER BY p.`name`";

            if($this->id > 0)
            {
                $row = $this->select('', $this->table4, 'id ='.$this->id)->row();
                $this->headingText = 'Product Name : '.$row->name;

                $query = "SELECT i.id, i.`type_c` 'client_type', i.`reference_no`, c.`name`, i.client_id, i.date, i.`type_i` 'type', i.`type_i` 'invoice_type', CONCAT('Invoice(', (i.`id`), ')') 'invoice_detail', SUM(CASE WHEN i.`type_i` = 3 AND id.`product_id` = '" .  $this->id . "' THEN id.`qty` ELSE 0 END) 'debit', SUM(CASE WHEN i.`type_i` = 2 AND id.`product_id` = '" .  $this->id . "' THEN id.`qty` ELSE 0 END) 'credit', 'balance' FROM `products` p INNER JOIN `invoice_detail` id ON p.`id` = id.`product_id` INNER JOIN invoice i ON i.`id` = id.`invoice_id` INNER JOIN `clients` c ON c.`id` = i.`client_id` WHERE i.`status` = '1' AND id.`status` = '1' AND (i.`type_i` = 2 OR i.`type_i` = 3) AND p.`id` = '" .  $this->id . "' $whereDate $whereSearchedText GROUP BY i.id ORDER BY i.`date`, i.`created_at`";
            }
            //$query = "SELECT i.id, i.`type_c` 'client_type', c.`name`, i.client_id, i.date, i.`type_i` 'type', i.`type_i` 'invoice_type', CONCAT('Invoice(', (i.`id`), ')') 'invoice_detail', SUM(CASE WHEN i.`type_i` = 3 AND id.`product_id` = '" .  $this->id . "' THEN id.`qty` ELSE 0 END) 'debit', SUM(CASE WHEN i.`type_i` = 2 AND id.`product_id` = '" .  $this->id . "' THEN id.`qty` ELSE 0 END) 'credit', 'balance' FROM `products` p INNER JOIN `invoice_detail` id ON p.`id` = id.`product_id` INNER JOIN invoice i ON i.`id` = id.`invoice_id` INNER JOIN `clients` c ON c.`id` = i.`client_id` WHERE i.`status` = '1' AND id.`status` = '1' AND (i.`type_i` = 2 OR i.`type_i` = 3) $whereDate GROUP BY i.`id` ORDER BY i.`date` DESC";

            $this->data['selectBox']  = $this->select('id, name', 'clients', "`status` != '-1'")->result();

            $temp             = new template();
            $temp->query      = $query;
            $temp->action      = false;
            $temp->controller = $this->controller;
            $temp->headingText = $this->headingText;
            $temp->secondData = $this->data['selectBox'];

            $pageNo = $this->getUrlValue(4);
            if(!empty($pageNo))
            {
                $temp->pageNo = $pageNo;
            }

            $this->loadView($temp->pagination(), $this->data);
        }
    }


?>
