<?php

    class Supplier_Invoice extends Admin_Controller
    {
        var $controller = 'supplier_invoice';

        var $tableInvoice = 'invoice';
        var $tableInvoiceDetail = 'invoice_detail';
        var $tableInvoiceItems = 'invoice_items';

        var $tableClient = 'clients';
        var $tableItems = 'items';
        var $type_c = '1';
        var $data = array();

        function __construct()
        {
            parent::__construct();
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $query = "SELECT CONCAT(i.`id`,'|',i.`type_i`) 'id', i.`date`, c.`name`, i.`type_i` 'type', i.`id` 'invoice_no', i.reference_no, i.`total_amount`, i.`status`, i.`client_id` FROM ".$this->tableInvoice." i INNER JOIN ".$this->tableClient." c ON i.`client_id` = c.`id`  WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type_c."' ORDER BY i.`date` ASC";

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
            $this->data['selectBox_clients'] = $this->select('id, name', 'clients', "`status` != '-1' AND `type` = '".$this->type_c."'")->result();
            $this->data['selectBox_items'] = $this->executeQuery("SELECT id, NAME, CONCAT(`name`, ' (', qty_per_bag, ')') AS `name` FROM ".$this->tableItems." WHERE `status` = '1'")->result();
            $this->data['type_i'] = $this->input->post('id');

            if($this->data['type_i'] == '1')
            {
                echo $this->loadHtml('supplier_invoice_view', $this->data);
            }
            else if($this->data['type_i'] == '4')
            {
                echo $this->loadHtml('supplier_payment_invoice_view', $this->data);
            }
            else
            {
                redirect(ADMIN_URL . $this->controller);
            }
        }

        function save_()
        {
            $invoiceType = $this->input->post('type_i');

            $insert = array(
                'type_c' => $this->type_c,
                'type_i' => $invoiceType,
                'date' => strtotime($this->input->post('date')),
                'time' => strtotime($this->input->post('time')),
                'client_id' => $this->input->post('client_id'),
                'reference_no' => addslashes($this->input->post('reference_no')),
                'total_amount' => $this->input->post('total_amount'),
                'created_at' => time()
            );

            $invoiceId = $this->insert($this->tableInvoice, $insert);
            $this->actionResponse($invoiceId, 1);

            if($invoiceType == '1')
            {
                $items = $this->input->post('items');
                $qty     = $this->input->post('qty');
                $rate    = $this->input->post('rate');
                $price   = $this->input->post('price');

                $allItemsInInvoice = array();
                for ($a = 0; $a < count($items); $a++)
                {
                    $itemsId = $items[$a];

                    if($itemsId < 1 || $itemsId == '' || in_array($itemsId, $allItemsInInvoice))
                    {
                        continue;
                    }

                    $allItemsInInvoice[] = $itemsId;

                    $insert2 = array('invoice_id' => $invoiceId, 'created_at' => time());
                    $invoiceDetailId = $this->insert($this->tableInvoiceDetail, $insert2);

                    $insert3 = array(
                        'invoiceId' => $invoiceId,
                        'invoiceDetailId' => $invoiceDetailId,
                        'itemId' => $itemsId,
                        'itemQty' => $qty[$a],
                        'itemRate' => $rate[$a],
                        'itemPrice' => $price[$a]
                    );

                    $invoiceItemId = $this->insert($this->tableInvoiceItems, $insert3);
                }
            }
            else if($invoiceType == '4')
            {
                $priceArray = $this->input->post('price');
                $desc       = $this->input->post('desc');

                for ($a = 0; $a < count($priceArray); $a++)
                {
                    $price = $priceArray[$a];

                    if($price == '')
                    {
                        continue;
                    }

                    $insert2 = array(
                        'invoice_id' => $invoiceId,
                        'product_id' => '',
                        'qty' => '',
                        'rate' => '',
                        'price' => $price,
                        'desc' => $desc[$a],
                        'created_at' => time()
                    );

                    $this->insert($this->tableInvoiceDetail, $insert2);
                }
            }

            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            list($id, $type_i) = explode('|', $this->input->post('id'));

            if($type_i == '1')
            {
                $query1 = "SELECT i.`id`, i.`type_i`, i.`type_c`, c.`name`, i.`reference_no`, i.`date`, i.`total_amount` FROM invoice i INNER JOIN `clients` c ON i.`client_id` = c.`id` WHERE i.`status` = '1' AND i.`id` = '".$id."'";
                $this->data['data']             = $this->executeQuery($query1)->row();

                $query2  = "SELECT it.`name`, ii.`itemQty`, ii.`itemRate`, ii.`itemPrice` FROM invoice_items ii INNER JOIN items it ON ii.`itemId` = it.`id` WHERE ii.`status` = '1' AND ii.`invoiceId`= '".$id."' GROUP BY ii.`id`";
                $this->data['data2']['data']    = $this->executeQuery($query2)->result();
                $this->data['data2']['colunms'] = array('name', 'itemQty', 'itemRate', 'itemPrice');
            }
            else if($type_i == '4')
            {
                $query1 = "SELECT i.`id`, i.`type_i`, i.`type_c`, i.reference_no, i.`date`, c.`name`, i.`total_amount`, i.`status` FROM ".$this->tableInvoice." i INNER JOIN ".$this->tableClient." c ON i.`client_id` = c.`id`  WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type_c."' AND i.`id` = '".$id."'";
                $this->data['data']             = $this->executeQuery($query1)->row();

                $query2   = "SELECT i.`price`, i.`desc` 'description' FROM ".$this->tableInvoiceDetail." i WHERE i.`invoice_id` = '" . $id . "' AND i.`status` != '-1' GROUP BY i.`id`";
                $this->data['data2']['data']    = $this->executeQuery($query2)->result();
                $this->data['data2']['colunms'] = array('description', 'price');
            }


            $html = $this->loadHtml('detail_view', $this->data);
            echo $html;
        }

        function edit_()
        {
            list($id, $type_i) = explode('|', $this->input->post('id'));

            $this->data['type_i']     = $type_i;
            $this->data['submitPath'] = '/update_';
            $this->data['controller'] = $this->controller;

            $this->data['data']       = $this->select('', $this->tableInvoice, "id = " . $id)->row();
            $this->data['data2']      = $this->select('', $this->tableInvoiceDetail, "invoice_id = " . $id . " AND `status` != '-1'")->result();

            $this->data['selectBox_clients']  = $this->select('id, name', 'clients', "`status` != '-1' AND `type` = '" . $this->type_c . "'")->result();
            $this->data['selectBox_items']    = $this->executeQuery("SELECT id, NAME, CONCAT(`name`, ' (', qty_per_bag, ')') AS `name` FROM items WHERE `status` = '1'")->result();
            $this->data['selectBox_products'] = $this->select('id, name', 'products', "`status` != '-1'")->result();

            if($type_i == 1)
            {
                $this->data['data3']      = $this->select('', $this->tableInvoiceItems, "invoiceId = " . $id . " AND `status` != '-1'")->result();
                echo $this->loadHtml('supplier_invoice_view', $this->data);
            }

            if($type_i == 4)
            {
                echo $this->loadHtml('supplier_payment_invoice_view', $this->data);
            }
        }

        function update_()
        {
            $invoiceId = $this->input->post('id');
            $invoiceType = $this->input->post('type_i');

            $update = array(
                'date' => strtotime($this->input->post('date')),
                'time' => strtotime($this->input->post('time')),
                'client_id' => $this->input->post('client_id'),
                'reference_no' => addslashes($this->input->post('reference_no')),
                'total_amount' => $this->input->post('total_amount'),
                'modified_at' => time()
            );

            if ($this->update($this->tableInvoice, $update, 'id', $invoiceId))
            {
                $this->actionResponse($this->input->post('id'), 2);
            }

            $delete = array('status' => '-1');
            if ($this->update($this->tableInvoiceDetail, $delete, 'invoice_id', $invoiceId))
            {
                if($invoiceType == '1')
                {
                    if($this->update($this->tableInvoiceItems, $delete, 'invoiceId', $invoiceId))
                    {
                        $items = $this->input->post('items');
                        $qty     = $this->input->post('qty');
                        $rate    = $this->input->post('rate');
                        $price   = $this->input->post('price');

                        $allItemsInInvoice = array();
                        for ($a = 0; $a < count($items); $a++)
                        {
                            $itemsId = $items[$a];

                            if($itemsId < 1 || $itemsId == '' || in_array($itemsId, $allItemsInInvoice))
                            {
                                continue;
                            }

                            $allItemsInInvoice[] = $itemsId;

                            $insert2 = array('invoice_id' => $invoiceId, 'created_at' => time());
                            $invoiceDetailId = $this->insert($this->tableInvoiceDetail, $insert2);

                            $insert3 = array(
                                'invoiceId' => $invoiceId,
                                'invoiceDetailId' => $invoiceDetailId,
                                'itemId' => $itemsId,
                                'itemQty' => $qty[$a],
                                'itemRate' => $rate[$a],
                                'itemPrice' => $price[$a]
                            );

                            $invoiceItemId = $this->insert($this->tableInvoiceItems, $insert3);
                        }
                    }
                }
                else if($invoiceType == '4')
                {
                    $priceArray = $this->input->post('price');
                    $desc       = $this->input->post('desc');

                    for ($a = 0; $a < count($priceArray); $a++)
                    {
                        $price = $priceArray[$a];

                        if($price == '')
                        {
                            continue;
                        }

                        $insert2 = array(
                            'invoice_id' => $invoiceId,
                            'product_id' => '',
                            'qty' => '',
                            'rate' => '',
                            'price' => $price,
                            'desc' => $desc[$a],
                            'created_at' => time()
                        );

                        $this->insert($this->tableInvoiceDetail, $insert2);
                    }
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