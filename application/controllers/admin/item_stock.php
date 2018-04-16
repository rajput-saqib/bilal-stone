<?php

    class Item_Stock extends Admin_Controller
    {
        var $controller = 'item_stock';
        var $id;
        var $type;

        var $table = 'invoice';
        var $table2 = 'invoice_detail';
        var $table3 = 'clients';
        var $table4 = 'products';
        var $table5 = 'items';

        var $data = array();
        var $headingText = '';

        function __construct()
        {
            parent::__construct();
            if (isset($_GET['id']))
            {
                $this->id = $this->decrypt($this->input->get('id'));
            }
            $this->data['controllerName'] = $this->controller;
        }

        function index()
        {
            $table = [];
            if (false)//$this->id > 0 && $whereSearchedText == ''
            {
                $whereSearchedText = '';
                $searchedText      = $this->input->post('searchedText');
                if ($searchedText != '')
                {
                    $whereSearchedText = " AND it.`name` LIKE '%" . $searchedText . "%'";
                }
//                $row               = $this->select('', $this->table5, 'id =' . $this->id)->row();
//                $this->headingText = 'Item Name : ' . $row->name;
//                $query = "SELECT i.`id`, i.`type_c` 'client_type', i.client_id, i.date, i.`type_i` 'type', i.`type_i` 'invoice_type', CONCAT('Invoice(', (i.`id`), ')') 'invoice_detail', SUM(CASE WHEN i.`type_i` = 1  THEN ii.`itemQty`  ELSE NULL END ) 'debit', TRUNCATE((pd.`quantity` * id.`qty`) / it.`qty_per_bag`, 2) 'credit', 'balance' FROM items it INNER JOIN invoice_items ii ON it.`id` = ii.`itemId` INNER JOIN invoice i ON ii.`invoiceId` = i.`id` INNER JOIN `invoice_detail` id ON ii.`invoiceDetailId` = id.`id` LEFT JOIN `products` p ON id.`product_id` = p.`id` LEFT JOIN `product_detail` pd ON p.`id` = pd.`product_id` AND pd.`status` = '1' WHERE i.`status` = '1' AND ii.`status` = '1' AND (i.`type_i` = 1 OR i.`type_i` = 3) AND ii.`itemId` = '".$this->id."' GROUP BY i.`id` ";
            }
            else
            {
                $error = '';
            	
                $productDetailQuery  = "SELECT product_id, `quantity` FROM `product_detail` WHERE `status` = '1' ORDER BY id";
                $productDetail = $this->executeQuery($productDetailQuery)->result();

                $allProducts = [];
                foreach($productDetail as $row)
                {
                    $allProducts[$row->product_id][] = $row->quantity;
                }

                $startDate = strtotime($_GET['startDate']);
                $endDate = strtotime($_GET['endDate']);

                $where = '';
                if($startDate > 0) {
                    $where .= ' AND i.`date` >= '.$startDate;
                }

                if($endDate > 0) {
                    $where .= ' AND i.`date` <= '.$endDate;
                }

                // Vendor
                $itemExist = [];
                $tableVendor = [];

                $queryInvoicesVendor  = "SELECT i.id, id.qty, id.product_id, i.type_i FROM invoice i INNER JOIN `invoice_detail` id ON i.`id` = id.`invoice_id` WHERE i.`status` = '1' AND id.`status` = '1' AND (i.`type_i` = 3) $where GROUP BY id.`id` ORDER BY i.id ";
                $resultInvoicesVendor = $this->executeQuery($queryInvoicesVendor)->result();
                

                foreach($resultInvoicesVendor as $row2)
                {
                    $invoiceId = $row2->id;
                    $productInInvoice = $row2->qty;
                    $productId = $row2->product_id;
                    $invoiceType = $row2->type_i;

                    $queryII  = "SELECT * FROM invoice_items ii WHERE ii.`invoiceId` = '" . $invoiceId . "' AND ii.productId = '" . $productId . "' AND ii.`status` != '-1' GROUP BY ii.`id` ORDER BY ii.`id`";
                    $resultII = $this->executeQuery($queryII)->result();

                    if(empty($resultII))
                    {
                        $error[1][] = 'Invoice ID: '.$invoiceId.' -- Product ID: '.$productId;
                        //echo '<pre>';print_r($queryII);echo '</pre>'; die('-----1');
                    }

                    if(count($allProducts[$productId]) != count($resultII) && $invoiceType == 3)
                    {
                        $error[2][] = 'Invoice ID: '.$invoiceId.' -- Product ID: '.$productId;
                    	//echo $queryII.'<br/>';
                        //echo '<pre>';print_r($allProducts[$productId]);echo '</pre>';
                        //echo '<pre>';print_r($resultII);echo '</pre>';
                        //die('-----2');
                    }

                    for ($a = 0; $a < count($resultII); $a++)
                    {
                        $rowII = $resultII[$a];

                        $queryI = "SELECT *, CONCAT(i.name,' (',i.qty_per_bag,')') 'name' FROM items i WHERE i.id = '" . $rowII->itemId . "' AND i.`status` != '-1'";
                        $rowI   = $this->executeQuery($queryI)->row();

                        if($rowII == "")
                        {
                            $error[3][] = 'Invoice ID: '.$invoiceId.' -- Product ID: '.$productId;
                            //echo '<pre>';print_r("Empty Invoice Items.");echo '</pre>'; die('-----3');
                            continue;
                        }

                        $itemId = $rowII->itemId;
                        $qty_per_bag  = $rowI->qty_per_bag;

                        $pdQuantity = $allProducts[$productId][$a];

                        if($pdQuantity == "")
                        {
                            $error[4][] = 'Invoice ID: '.$invoiceId.' -- Product ID: '.$productId;
                            //echo '<pre>';print_r("Empty Quantity.");echo '</pre>'; die('-----4');
                            continue;
                        }

                        $param = new ArrayObject();
                        $param->invoiceType = 3;
                        $param->id = $itemId;
                        $param->invoiceType = $invoiceType;
                        $param->name = $rowI->name;
                        $param->detail = "--";
                        $param->used = "0";

                        $remaining = ($productInInvoice*$pdQuantity)/$qty_per_bag;
                        $oldRemaining = $tableVendor[$itemId]->remaining;

                        $param->remaining = $remaining+$oldRemaining;
                        $tableVendor[$itemId] = $param;

//                        if($itemId == 110)
//                        {
//                            echo $oldRemaining.'<br/>';
//                            echo $resss += $remaining;
//                            echo '<br/>';
//                        }

                        unset($param);
                    }
                }

                // Supplier
                $tableSupplier = [];
                $queryInvoicesSupplier  = "SELECT i.id, i.type_i FROM invoice i WHERE i.`status` = '1' AND (i.`type_i` = 1) GROUP BY i.`id` ORDER BY i.id ";
                $resultInvoicesSupplier = $this->executeQuery($queryInvoicesSupplier)->result();

                foreach($resultInvoicesSupplier as $row2)
                {
                    $invoiceId = $row2->id;
                    $productInInvoice = $row2->qty;
                    $productId = $row2->product_id;
                    $invoiceType = $row2->type_i;

                    $queryII  = "SELECT * FROM invoice_items ii WHERE ii.`invoiceId` = '" . $invoiceId . "' AND ii.`status` != '-1' GROUP BY ii.`id` ORDER BY ii.`id`";
                    $resultII = $this->executeQuery($queryII)->result();

                    if(empty($resultII))
                    {
                        $error[5][] = 'Invoice ID: '.$invoiceId.' -- Product ID: '.$productId;
                        //echo '<pre>';print_r($queryII);echo '</pre>'; die('-----5');
                    }

                    for ($a = 0; $a < count($resultII); $a++)
                    {
                        $rowII = $resultII[$a];

                        $queryI = "SELECT * FROM items i WHERE i.id = '" . $rowII->itemId . "' AND i.`status` != '-1'";
                        $rowI   = $this->executeQuery($queryI)->row();

                        $itemId = $rowII->itemId;

                        $param = new ArrayObject();
                        $param->id = $itemId;
                        $param->invoiceType = $invoiceType;
                        $param->name = $rowI->name;
                        $param->detail = "--";
                        $param->used = "0";

                        $remaining = $rowII->itemQty;
                        $oldRemaining = $tableSupplier[$itemId]->remaining;
                        //echo $invoiceId.' |  '.$rowPD->name.' |  '.$productId.' | '.$productInInvoice.' | '.$pdQuantity.' | '.$rowI->name.' | '.$itemId.' | '.$qty_per_bag.'<br/>';

                        $param->remaining = $remaining+$oldRemaining;

                        $tableSupplier[$itemId] = $param;
                        unset($param);
                    }
                }
            }

            //$table = array_values($tableVendor);

            $mergeData = array_merge(array_values($tableVendor), array_values($tableSupplier));

            foreach($mergeData as $row)
            {
                $param = new ArrayObject();
                $param->id = $row->id;
                $param->invoiceType = $row->invoiceType;
                $param->name = $row->name;
                $param->detail = $row->detail;
                $param->used = "0";

				$remaining = $row->remaining;
                
				if($row->invoiceType == 3) {
					$remaining = -$remaining;
				}
				

				$oldRemaining = $table[$row->id]->remaining;

				
                $param->remaining = $remaining+$oldRemaining;

                $table[$row->id] = $param;
                unset($param);
            }


            $table = array_values($table);

            $byName = array();
            foreach ($table as $key => $row)
            {
                $byName[$key] = $row->name;
            }
            
            array_multisort($byName, SORT_ASC, $table);

            $param['headingText'] = $this->headingText;
            $param['controller'] = $this->controller;
            $param['table']       = $table;
            $param['colunms']     = ['id', 'name', 'used', 'remaining'];
            $param['error']     = $error;

            $temp              = new template();
            $this->loadView($temp->paginationDirect($param), $this->data);
        }
    }


?>