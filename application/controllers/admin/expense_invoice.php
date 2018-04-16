<?php

    class Expense_Invoice extends Admin_Controller
    {
        var $controller = 'expense_invoice';

        var $tableInvoice = 'invoice_expense';
        var $tableInvoiceDetail = 'invoice_expense_detail';
        var $tableStaff = 'clients';

        var $type_c = '5';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $searchedText = trim($_GET['searchedText']);

            $where = '';
            if($searchedText != '') {
                $where = " AND (c.`name` LIKE '%$searchedText%' OR i.`reference_no` LIKE '%$searchedText%')";
            }

            $query            = "SELECT CONCAT(i.`id`,'|',i.`type_i`) 'id', i.`date`, i.`id` 'invoice_no', i.reference_no, i.`total_amount` 'total', i.`status`, i.`client_id` FROM invoice_expense i WHERE i.`status` != '-1' AND i.`type_c` = '" . $this->type_c . "' $where ORDER BY `date` ASC";
            $temp             = new template();
            $temp->query      = $query;
            $temp->controller = $this->controller;
            $pageNo = $this->getUrlValue(4);
            if (!empty($pageNo))
            {
                $temp->pageNo = $pageNo;
            }
            $this->loadView($temp->pagination(), $this->data);
        }

        function new_()
        {
            $this->data['submitPath']         = '/save_';
            $this->data['controller']         = $this->controller;
            $this->data['selectBox_expense_type']  = $this->select('id, name', 'expense_type', "`status` = '1'")->result();
            $query = "SELECT c.`id`, CONCAT(c.`name`,' - ', ss.salary) 'name' FROM clients c INNER JOIN `staff_salaries` ss ON c.`id` = ss.`staffId` WHERE c.`status` = '1' AND ss.`status` = '1' ORDER BY c.id";
            $this->data['selectBox_staff']  = $this->executeQuery($query)->result();
            $this->data['type_i'] = $this->input->post('id');

            echo $this->loadHtml('expense_invoice_view', $this->data);
        }

        function save_()
        {
            $invoiceType = $this->input->post('type_i');

            $insert = array(
                'type_c' => $this->type_c,
                'type_i' => $invoiceType,
                'date' => strtotime($this->input->post('date')),
                'time' => time(),
                //'client_id' => $this->input->post('staffId'),
                'reference_no' => addslashes($this->input->post('reference_no')),
                'total_amount' => $this->input->post('total_amount'),
                'description' => '',
                'created_at' => time()
            );

            $invoiceId = $this->insert($this->tableInvoice, $insert);
            if($invoiceId > 0)
            {
                $this->actionResponse($invoiceId, 1);
                $expense_type = $this->input->post('expense_type');
                $staffId      = $this->input->post('staffId');
                $desc         = $this->input->post('desc');
                $amount       = $this->input->post('amount');

                for ($a = 0; $a < count($expense_type); $a++)
                {
                    if ($expense_type[$a] < 1)
                    {
                        continue;
                    }

                    $insert2 = array(
                        'invoice_expense_id' => $invoiceId,
                        'expense_type_id' => $expense_type[$a],
                        'staffId' => $staffId[$a],
                        'desc' => $desc[$a],
                        'amount' => $amount[$a],
                        'created_at' => time()
                    );

                    $invoiceDetailId = $this->insert($this->tableInvoiceDetail, $insert2);
                }
            }

            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            list($id, $type_i) = explode('|', $this->input->post('id'));

            $query1 = "SELECT i.`id`, i.`type_i`, i.`type_c`, i.reference_no, i.`date`, i.`total_amount`, i.`status` FROM ".$this->tableInvoice." i WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type_c."' AND i.`id` = '".$id."'";
            $this->data['data']             = $this->executeQuery($query1)->row();

            $query2   = "SELECT et.`name` 'expense_type', c.`name` 'staff_name', i.`desc` 'description', i.`amount` FROM `invoice_expense_detail` i INNER JOIN `expense_type` et ON i.`expense_type_id` = et.`id` LEFT JOIN clients c ON i.`staffId` = c.`id` WHERE i.`status` != '-1' AND et.`status` != '-1' AND i.`invoice_expense_id` = '".$id."'";
            $this->data['data2']['data']    = $this->executeQuery($query2)->result();
            $this->data['data2']['colunms'] = array('expense_type', 'staff_name', 'description', 'amount');

            $html = $this->loadHtml('detail_view', $this->data);
            echo $html;
        }

        function edit_()
        {
            $this->data['submitPath'] = '/update_';
            $this->data['controller']         = $this->controller;

            list($id, $type_i) = explode('|', $this->input->post('id'));

            $this->data['data']       = $this->select('', $this->tableInvoice, "id = " . $id)->row();
            $this->data['data2']      = $this->select('', $this->tableInvoiceDetail, "invoice_expense_id = " . $id . " AND `status` != '-1'")->result();

            $query = "SELECT c.`id`, CONCAT(c.`name`,' - ', ss.salary) 'name' FROM clients c INNER JOIN `staff_salaries` ss ON c.`id` = ss.`staffId` WHERE c.`status` = '1' AND ss.`status` = '1' ORDER BY c.id";
            $this->data['selectBox_staff']  = $this->executeQuery($query)->result();
            $this->data['selectBox_expense_type']  = $this->select('id, name', 'expense_type', "`status` = '1'")->result();
            
            echo $this->loadHtml('expense_invoice_view', $this->data);
        }

        function update_()
        {
            $invoiceId = $this->input->post('id');
            $type_i = $this->input->post('type_i');

            $update = array(
                'date' => strtotime($this->input->post('date')),
                'time' => strtotime($this->input->post('time')),
                //'client_id' => $this->input->post('client_id'),
                'reference_no' => addslashes($this->input->post('reference_no')),
                'total_amount' => $this->input->post('total_amount'),
                'description' => $this->input->post('description'),
                'modified_at' => time()
            );

            if ($this->update($this->tableInvoice, $update, 'id', $invoiceId))
            {
                $this->actionResponse($this->input->post('id'), 2);
            }

            if ($this->update($this->tableInvoiceDetail, array('status' => '-1'), 'invoice_expense_id', $invoiceId))
            {
                $expense_type = $this->input->post('expense_type');
                $staffId      = $this->input->post('staffId');
                $desc         = $this->input->post('desc');
                $amount       = $this->input->post('amount');

                for ($a = 0; $a < count($expense_type); $a++)
                {
                    if ($expense_type[$a] < 1)
                    {
                        continue;
                    }

                    $insert2 = array(
                        'invoice_expense_id' => $invoiceId,
                        'expense_type_id' => $expense_type[$a],
                        'staffId' => $staffId[$a],
                        'desc' => $desc[$a],
                        'amount' => $amount[$a],
                        'created_at' => time()
                    );

                    $invoiceDetailId = $this->insert($this->tableInvoiceDetail, $insert2);
                }
            }

            redirect(ADMIN_URL . $this->controller);
        }

        function delete_()
        {
            list($id, $type_i) = explode('|', $this->input->post('id'));
            $update = array('status' => '-1', 'deleted_at' => time());

            $this->actionResponse(1, 3);
            echo $this->update($this->tableInvoice, $update, 'id', $id);

        }

        function status_()
        {
            list($id, $type_i) = explode('|', $this->input->post('id'));
            $update = array('status' => $this->input->post('status'));

            $this->actionResponse(1, 4);
            echo $this->update($this->tableInvoice, $update, 'id', $id);
        }
    }
