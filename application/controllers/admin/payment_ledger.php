<?php

    class Payment_Ledger extends Admin_Controller
    {
        var $controller = 'payment_ledger';

        var $table = 'invoice';
        var $table2 = 'invoice_detail';
        var $table3 = 'invoice_items';
        var $table4 = 'items';
        var $table5 = 'products';
        var $table6 = 'clients';

        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $startDate = strtotime($_GET['startDate']);
            $endDate = strtotime($_GET['endDate']);

            $where = '';
            if($startDate > 0) {
                $where .= ' AND i.`date` >= '.$startDate;
            }

            if($endDate > 0) {
                $where .= ' AND i.`date` <= '.$endDate;
            }

            $query            = "SELECT i.id, type_c 'type', i.date, i.`type_i` 'invoice_type', c.`name`, i.`id` 'invoice_no', i.`reference_no`, i.client_id, IF(i.`type_c` = 1 OR i.`type_c` = 3, FORMAT(i.total_amount, 2), '') 'credit', IF( i.`type_c` = 2, FORMAT(i.total_amount, 2), '') 'debit', 'balance' FROM invoice i INNER JOIN invoice_detail id ON i.id = id.invoice_id INNER JOIN clients c ON i.`client_id` = c.`id` WHERE i.`status` = '1' AND i.`type_i` = '4' AND id.`status` = '1' $where GROUP BY i.id ORDER BY i.`date`, i.`created_at`";

            $forAllInvoices = $this->executeQuery($query)->result();

            $forAllInvoicesData = array();

            foreach($forAllInvoices as $row) {

                //$forAllInvoicesData['makeSheet'] += $row->make_sheet;
                //$forAllInvoicesData['saleSheet'] += $row->sale_sheet;
                $forAllInvoicesData['credit'] += trim(str_replace(',', '', $row->credit));
                $forAllInvoicesData['debit'] += trim(str_replace(',', '', $row->debit));;
            }

            $temp             = new template();
            $temp->action     = false;
            $temp->new        = false;
            $temp->pageNo     = $this->getUrlValue(4);
            $temp->query      = $query;
            $temp->controller = $this->controller;
            $temp->secondData = $forAllInvoicesData;

            $this->loadView($temp->pagination(), $this->data);
        }
    }
