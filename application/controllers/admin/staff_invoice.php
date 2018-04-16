<?php

    class Staff_Invoice extends Admin_Controller
    {
        var $controller = 'staff_invoice';

        var $tableInvoice = 'invoice';
        var $tableInvoiceDetail = 'invoice_detail';
        var $tableClients = 'clients';
        var $tableItems = 'items';
        var $tableProducts = 'products';

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

            $query            = "SELECT CONCAT(i.`id`,'|',i.`type_i`) 'id', i.`date`, c.`name`, i.`type_i` 'type', i.`id` 'invoice_no', i.reference_no, i.`total_amount` 'total', i.`status`, i.`client_id` FROM invoice i INNER JOIN clients c ON i.`client_id` = c.`id` WHERE i.`status` != '-1' AND i.`type_c` = '" . $this->type_c . "' $where ORDER BY `date` ASC";
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
//            $this->data['selectBox_clients']  = $this->select('id, name', 'clients', "`status` != '-1' AND `type` = '" . $this->type_c . "'")->result();
//            $this->data['selectBox_items']    = $this->executeQuery("SELECT id, NAME, CONCAT(`name`, ' (', qty_per_bag, ')') AS `name` FROM items WHERE `status` = '1'")->result();
//            $this->data['selectBox_products'] = $this->select('id, name', 'products', "`status` != '-1'")->result();
            $this->data['type_i'] = $this->input->post('id');

            if($this->data['type_i'] == '5')
            {

            }
            echo $this->loadHtml('staff_salary_view', $this->data);
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
                'description' => $this->input->post('description'),
                'created_at' => time()
            );

            $invoiceId = $this->insert($this->tableInvoice, $insert);
            $this->actionResponse($invoiceId, 1);

            if($invoiceType == '2')
            {
                $product     = $this->input->post('product');
                $qty         = $this->input->post('qty');
                $rate        = $this->input->post('rate');
                $otherAmount = $this->input->post('otherAmount');
                $subTotal    = $this->input->post('subTotal');
                $price       = $this->input->post('price');
                $desc        = $this->input->post('desc');

                $allProductInInvoice = array();

                for ($a = 0; $a < count($product); $a++)
                {
                    $productId = $product[$a];

                    if($productId < 1 || $productId == '' || in_array($productId, $allProductInInvoice))
                    {
                        continue;
                    }

                    $allProductInInvoice[] = $productId;


                    $insert2 = array(
                        'invoice_id' => $invoiceId,
                        'product_id' => $productId,
                        'qty' => $qty[$a],
                        'rate' => $rate[$a],
                        'price' => $price[$a],
                        'otherAmount' => $otherAmount[$a],
                        'subTotal' => $subTotal[$a],
                        'desc' => $desc[$a],
                        'created_at' => time()
                    );

                    $invoiceDetailId = $this->insert($this->tableInvoiceDetail, $insert2);

                    $rowNo = $a;
                    //$itemId = $this->input->post('item-'.$rowNo);
                    $type = $this->input->post('type-'.$rowNo);

                    $itemQty = $this->input->post('total-'.$rowNo);
                    $itemRate = $this->input->post('item-rate-'.$rowNo);
                    $itemPrice = $this->input->post('item-price-'.$rowNo);

                    for($b = 0 ; $b < count($itemRate) ; $b++)
                    {
                        $insert3 = array(
                            'invoiceDetailId' => $invoiceDetailId,
                            'invoiceId' => $invoiceId,
                            'productId' => $productId,
                            //'itemId' => $itemId[$b],
                            'type' => $type[$b],
                            'itemQty' => $itemQty[$b],
                            'itemRate' => $itemRate[$b],
                            'itemPrice' => $itemPrice[$b]
                        );

                        $this->insert('invoice_items', $insert3);
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

            redirect(ADMIN_URL . $this->controller);
        }

        function view_()
        {
            list($id, $type_i) = explode('|', $this->input->post('id'));

            if($type_i == '2')
            {
                $query1 = "SELECT i.`id`, i.`type_i`, i.reference_no, i.`date`, c.`name`, i.`total_amount`, i.`status` FROM " . $this->tableInvoice . " i INNER JOIN " . $this->tableClients . " c ON i.`client_id` = c.`id`  WHERE i.`status` != '-1' AND i.`type_c` = '" . $this->type_c . "' AND i.`id` = '" . $id . "'";
                $this->data['data']             = $this->executeQuery($query1)->row();

                $query2  = "SELECT p.id, p.`name` 'product', id.`qty` 'quantity', id.`subTotal`, id.`otherAmount`, id.`price` FROM " . $this->tableInvoiceDetail . " id INNER JOIN " . $this->tableProducts . " p ON id.`product_id` = p.`id` WHERE id.`invoice_id` = '" . $id . "' AND id.`status` != '-1' GROUP BY id.`id`";
                $this->data['data2']['data']    = $this->executeQuery($query2)->result();
                $this->data['data2']['colunms'] = array('product', 'quantity', 'subTotal', 'otherAmount', 'price');

                //$query3 =  "SELECT ii.`productId`, i.`name`, pd.`quantity`, id.qty, (pd.`quantity`*id.qty) 'quantity', TRUNCATE(((pd.`quantity`*id.qty)/i.qty_per_bag),2) 'total_bags' FROM invoice_items ii INNER JOIN products p ON ii.`productId` = p.`id` INNER JOIN `product_detail` pd ON p.`id` = pd.`product_id` INNER JOIN items i ON ii.`itemId` = i.`id` INNER JOIN invoice_detail id ON id.`id` = ii.invoiceDetailId WHERE ii.`invoiceId` = '" . $id ."' AND ii.`status` = '1' GROUP BY ii.`id` ";
                $query3 =  "SELECT * FROM `invoice_items` WHERE `invoiceId` = '" . $id ."' AND `status` != '-1' GROUP BY `id` ";
                $result    = $this->executeQuery($query3)->result();

                foreach($result as $row) {
                    $this->data['data3'][$row->productId][] = $row;
                }

                $this->data['data3']['colunms'] = array('itemQty', 'itemRate', 'itemPrice');
            }
            else if($type_i == '4')
            {
                $query1 = "SELECT i.`id`, i.`type_i`, i.reference_no, i.`date`, c.`name`, i.`total_amount`, i.`status` FROM ".$this->tableInvoice." i INNER JOIN ".$this->tableClients." c ON i.`client_id` = c.`id`  WHERE i.`status` != '-1' AND i.`type_c` = '".$this->type_c."' AND i.`id` = '".$id."'";
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
            $this->data['controller']         = $this->controller;

            $this->data['data']       = $this->select('', $this->tableInvoice, "id = " . $id)->row();
            $this->data['data2']      = $this->select('', $this->tableInvoiceDetail, "invoice_id = " . $id . " AND `status` != '-1'")->result();

            $this->data['selectBox_clients']  = $this->select('id, name', 'clients', "`status` != '-1' AND `type` = '" . $this->type_c . "'")->result();
            $this->data['selectBox_items']    = $this->executeQuery("SELECT id, NAME, CONCAT(`name`, ' (', qty_per_bag, ')') AS `name` FROM items WHERE `status` = '1'")->result();
            $this->data['selectBox_products'] = $this->select('id, name', 'products', "`status` != '-1'")->result();

            if($type_i == 2)
            {
                $data3 = array();
                foreach($this->data['data2'] as $row2)
                {
                    $queryII  = "SELECT * FROM invoice_items ii WHERE ii.`invoiceId` = '" . $id . "' AND ii.productId = '" . $row2->product_id . "' AND ii.`status` != '-1' GROUP BY ii.`id` ORDER BY ii.`id`";
                    //$queryII  = "SELECT * FROM invoice_items ii INNER JOIN `product_detail` pd ON ii.`productId` = pd.`product_id` WHERE ii.`invoiceId` = '" . $id . "' AND ii.productId = '" . $row2->product_id . "' AND ii.`status` != '-1' AND pd.status != '-1' GROUP BY ii.`id` ORDER BY ii.`id`";
                    $resultII = $this->executeQuery($queryII)->result();

                    $queryPD  = "SELECT * FROM `product_detail` pd WHERE pd.product_id = '" . $row2->product_id . "' AND pd.status != '-1' ORDER BY `pd`.`id`";
                    $resultPD = $this->executeQuery($queryPD)->result();

                    for ($a = 0; $a < count($resultII); $a++)
                    {
                        $rowII = $resultII[$a];
                        $rowPD = $resultPD[$a];

                        $queryI = "SELECT * FROM items i WHERE i.id = '" . $rowII->itemId . "' AND i.`status` != '-1'";
                        $rowI   = $this->executeQuery($queryI)->row();

                        $param['itemType']      = $rowPD->itemType;
                        $param['itemInProduct'] = $rowPD->quantity;

                        $param['type']          = $rowII->type;
                        $param['itemRate']       = $rowII->itemRate;
                        $param['itemPrice']       = $rowII->itemPrice;


                        $data3[$rowII->invoiceDetailId][] = $param;
                        unset($param);
                    }
                }
                $this->data['data3'] = $data3;

                echo $this->loadHtml('customer_invoice_view', $this->data);
            }

            if($type_i == 4)
            {
                echo $this->loadHtml('customer_payment_invoice_view', $this->data);
            }
        }

        function update_()
        {
            $invoiceId = $this->input->post('id');
            $type_i = $this->input->post('type_i');

            $update = array(
                'date' => strtotime($this->input->post('date')),
                'time' => strtotime($this->input->post('time')),
                'client_id' => $this->input->post('client_id'),
                'reference_no' => addslashes($this->input->post('reference_no')),
                'total_amount' => $this->input->post('total_amount'),
                'description' => $this->input->post('description'),
                'modified_at' => time()
            );

            if ($this->update($this->tableInvoice, $update, 'id', $invoiceId))
            {
                $this->actionResponse($this->input->post('id'), 2);
            }

            if ($this->update($this->tableInvoiceDetail, array('status' => '-1'), 'invoice_id', $invoiceId))
            {
                if ($type_i == 2)
                {
                    $this->update('invoice_items', array('status' => '-1'), 'invoiceId', $invoiceId);

                    $product     = $this->input->post('product');
                    $qty         = $this->input->post('qty');
                    $rate        = $this->input->post('rate');
                    $otherAmount = $this->input->post('otherAmount');
                    $subTotal    = $this->input->post('subTotal');
                    $price       = $this->input->post('price');
                    $desc        = $this->input->post('desc');

                    $allProductInInvoice = array();

                    for ($a = 0; $a < count($product); $a++)
                    {
                        $productId = $product[$a];

                        if($productId < 1 || $productId == '' || in_array($productId, $allProductInInvoice))
                        {
                            continue;
                        }

                        $allProductInInvoice[] = $productId;


                        $insert2 = array(
                            'invoice_id' => $invoiceId,
                            'product_id' => $productId,
                            'qty' => $qty[$a],
                            'rate' => $rate[$a],
                            'price' => $price[$a],
                            'otherAmount' => $otherAmount[$a],
                            'subTotal' => $subTotal[$a],
                            'desc' => $desc[$a],
                            'created_at' => time()
                        );

                        $invoiceDetailId = $this->insert($this->tableInvoiceDetail, $insert2);

                        $rowNo = $a;
                        //$itemId = $this->input->post('item-'.$rowNo);
                        $type = $this->input->post('type-'.$rowNo);

                        $itemQty = $this->input->post('total-'.$rowNo);
                        $itemRate = $this->input->post('item-rate-'.$rowNo);
                        $itemPrice = $this->input->post('item-price-'.$rowNo);

                        for($b = 0 ; $b < count($itemRate) ; $b++)
                        {
                            $insert3 = array(
                                'invoiceDetailId' => $invoiceDetailId,
                                'invoiceId' => $invoiceId,
                                'productId' => $productId,
                                //'itemId' => $itemId[$b],
                                'type' => $type[$b],
                                'itemQty' => $itemQty[$b],
                                'itemRate' => $itemRate[$b],
                                'itemPrice' => $itemPrice[$b]
                            );

                            $this->insert('invoice_items', $insert3);
                        }

                    }
                }
                else if ($type_i == 4)
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

        function getItems()
        {
            $rowNo = $this->input->post('rowNo');
            $productId = $this->input->post('productId');

            //$query = "SELECT i.`id`, i.`name` 'item_name', pd.`quantity`, pd.itemType  FROM product_detail pd INNER JOIN products p ON pd.`product_id` = p.`id` INNER JOIN `items` i ON pd.`item_id` = i.id WHERE p.`id` = '" . $productId . "' AND pd.`status` != '-1' GROUP BY pd.`id` ORDER BY pd.`id`";
            $query = "SELECT pd.`id`, pd.`quantity`, pd.itemType FROM product_detail pd INNER JOIN products p ON pd.`product_id` = p.`id` WHERE p.`id` = '" . $productId . "' AND pd.`status` != '-1' GROUP BY pd.`id` ORDER BY pd.`id`";
            $data  = $this->executeQuery($query)->result();

            $itemsSelectBox = $this->executeQuery("SELECT id, NAME, CONCAT(`name`, ' (', qty_per_bag, ')') AS `name` FROM items WHERE `status` = '1'")->result();

            ob_start();

            if(count($data) > 0)
            {
                ?><table style="width: 100%; background: #E6E6FA"><?

                for($a = 0 ; $a < count($data) ; $a++)
                {
                    ?>
                    <tr class="product-contain-items-<?=$rowNo?>">
                        <td>
                             <span style="width: 120px; float: left"> Qty ( <?=ucfirst($data[$a]->itemType)?> ) </span>
                             <input type="text" value="<?=$data[$a]->quantity?>" name="item-qty-<?=$rowNo?>[]" id="item-qty-<?=$rowNo?>-<?=$a?>" readonly style="width: 60px"/>
                        </td>
                        <td>
                            <span>Total : </span>
                            <input type="text" value="" name="total-<?=$rowNo?>[]" id="total-<?=$rowNo?>-<?=$a?>" style="width: 60px" readonly/>
                        </td>
                        <td>
                            <input type="text" value="" name="type-<?=$rowNo?>[]" id="type-<?=$rowNo?>-<?=$a?>" style="width: 60px" placeholder="Type"/>
                        </td>

                        <td class="error-parent">
                            <span>Rate : </span>
                            <input type="text" value="" name="item-rate-<?=$rowNo?>[]" id="item-rate-<?=$rowNo?>-<?=$a?>" class="[c-e]" style="width: 60px" onkeyup="calculateAmount('<?=$rowNo?>')"/>
                        </td>

                        <td>
                            <span>Price : </span>
                            <input type="text" value="" name="item-price-<?=$rowNo?>[]" id="item-price-<?=$rowNo?>-<?=$a?>" style="width: 60px" readonly/>
                        </td>


                    </tr>
                    <?
                }
            }
            ?>
            <input type="hidden" name="item-length-<?=$rowNo?>" id="item-length-<?=$rowNo?>" value="<?=count($data)?>"/>
            </table>

            <?
            $html = ob_get_clean();

            echo $html;
        }

        function getTotalBags()
        {
            $itemId = $this->input->post('itemId');
            $total = $this->input->post('total');

            $query = "SELECT * FROM items WHERE id = '".$itemId."'";
            $data  = $this->executeQuery($query)->row();

            $perBag = $data->qty_per_bag;
            $result = array('perBag' => $perBag, 'totalBags' => number_format(($total / $perBag), 2));

            echo json_encode($result);
        }
    }
