<?php

    class Expense_Ledger extends Admin_Controller
    {
        var $controller = 'expense_ledger';
        var $id;

        var $table = 'invoice_expense';
        var $table2 = 'invoice_expense_detail';
        var $table3 = 'clients';
        var $table4 = 'products';
        var $type = '5';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->executeQuery("SET @total = 0");
            $this->id = $this->decrypt($this->input->get('id'));
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $whereDate = '';
            $startDate = $_GET['startDate'];
            $endDate = $_GET['endDate'];

            if($startDate !='')
            {
                $whereDate .= " AND ie.date >= '".strtotime($startDate)."' ";
            }

            if($endDate !='')
            {
                $whereDate .= " AND ie.date <= '".strtotime($endDate)."' ";
            }

            //$query = "SELECT c.`id`, c.`id` 'client_id', ie.`date`, c.`name`, ied.`amount`, ied.`amount` 'total_paid', c.status FROM clients c INNER JOIN `staff_salaries` ss ON c.`id` = ss.`staffId` INNER JOIN `invoice_expense_detail` ied ON ied.`staffId` = c.`id` INNER JOIN `invoice_expense` ie ON ie.`id` = ied.`invoice_expense_id` WHERE c.`status` != '-1' AND ss.`status` = '1' AND ie.`status` != '-1' AND ied.`status` != '-1' $whereDate ORDER BY ie.`date`";
            $query = "SELECT ie.`id`, ie.`type_i` 'type', ie.`date`, ie.`reference_no`, ie.`total_amount` 'amount', ie.`total_amount` 'total_paid' FROM `invoice_expense` ie INNER JOIN `invoice_expense_detail` ied ON ie.`id` = ied.`invoice_expense_id` WHERE ie.`status` != '-1' AND ied.`status` != '-1' $whereDate GROUP BY ie.`id` ORDER BY ie.`date` ";

            $temp             = new template();
            $temp->query      = $query;
            $temp->action      = false;
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
            $this->data['selectBox_clients'] = $this->select('id, name', 'clients', "`status` != '-1' AND `type` = '".$this->type."'")->result();
            $this->data['selectBox_products'] = $this->select('id, name', 'products', "`status` != '-1'")->result();

            $html = $this->loadHtml($this->showInvoiceType($this->input->post('id')).'_invoice_view', $this->data);
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

            $invoice = $this->insert($this->table, $insert);
            $this->actionResponse($invoice, 1);

            $product = $this->input->post('product');
            $qty     = $this->input->post('qty');
            $rate    = $this->input->post('rate');
            $price  = $this->input->post('price');
            $desc  = $this->input->post('desc');

            for($a = 0 ; $a < count($price) ; $a++)
            {
                $insert2 = array('invoice_id' => $invoice, 'product_id' => $product[$a], 'qty' => $qty[$a], 'rate' => $rate[$a], 'price' => $price[$a], 'desc' => $desc[$a], 'created_at' => time());
                $this->insert($this->table2, $insert2);
            }

            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            $this->data['data']       = $this->executeQuery("SELECT i.`id`, i.`type_i`, i.reference_no, i.`date`, c.`name`, i.`total_amount`, i.`status` FROM ".$this->table." i INNER JOIN ".$this->table3." c ON i.`client_id` = c.`id`  WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type."' AND i.`id` = '".$this->input->post('id')."'")->row();

            if($this->data['data']->type_i < 4)
            {
                $query   = "SELECT p.`name` 'product', id.`qty` 'quantity', id.`rate`, id.`price` FROM ".$this->table2." id INNER JOIN ".$this->table4." p ON id.`product_id` = p.`id` WHERE id.`invoice_id` = '" . $this->input->post('id') . "' AND id.`status` != '-1' GROUP BY id.`id`";
                $colunms = array('product', 'quantity', 'rate', 'price');
            }
            else
            {
                $query   = "SELECT id.`price`, id.`desc` 'description' FROM ".$this->table2." id WHERE id.`invoice_id` = '" . $this->input->post('id') . "' AND id.`status` != '-1' GROUP BY id.`id`";
                $colunms = array('description', 'price');
            }

            $this->data['data2']['data']    = $this->executeQuery($query)->result();
            $this->data['data2']['colunms'] = $colunms;

            $html = $this->loadHtml('detail_view', $this->data);
            echo $html;
        }

        function edit_()
        {
            $this->data['submitPath'] = '/update_';
            $this->data['data']       = $this->select('', $this->table, "id = " . $this->input->post('id'))->row();
            $this->data['data2']       = $this->select('', $this->table2, "invoice_id = " . $this->input->post('id'). " AND `status` != '-1'" )->result();

            $this->data['selectBox_clients'] = $this->select('id, name', 'clients', "`status` != '-1' AND `type` = '".$this->type."'")->result();
            $this->data['selectBox_products'] = $this->select('id, name', 'products', "`status` != '-1'")->result();

            $html = $this->loadHtml($this->showInvoiceType($this->data['data']->type_i).'_invoice_view', $this->data);
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

            if($this->update($this->table, $update, 'id', $this->input->post('id')))
            {
                $this->actionResponse($this->input->post('id'), 2);
            }

            $product = $this->input->post('product');
            $qty     = $this->input->post('qty');
            $rate    = $this->input->post('rate');
            $price  = $this->input->post('price');
            $desc  = $this->input->post('desc');

            if($this->update($this->table2, array('status' => '-1'), 'invoice_id', $this->input->post('id')))
            {
                for ($a = 0; $a < count($price); $a++)
                {
                    $insert2 = array('invoice_id' => $this->input->post('id'), 'product_id' => $product[$a], 'qty' => $qty[$a], 'rate' => $rate[$a], 'price' => $price[$a], 'desc' => $desc[$a], 'created_at' => time());
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
    }