<?
    $hideColunms = array('client_id','invoice_type', 'used', 'invoice_no');

    $supplierInvoice = array('supplier_invoice');
    $customerInvoice = array('customer_invoice',);
    $vendorInvoice = array('vendor_invoice');
    $staffInvoice = array('staff_invoice');

    $paymentInvoice = array('supplier_invoice', 'customer_invoice', 'vendor_invoice');
    $clientLink = array('supplier_invoice', 'customer_invoice', 'vendor_invoice', 'search_invoice', 'production_team', 'customers', 'clients', 'staff');
    $stockLink = array('stock_detail', 'product_stock', 'item_stock');

    $newArray = array('supplier_invoice', 'customer_invoice', 'vendor_invoice', 'search_invoice', 'backup', 'stock_detail', 'profile', 'product_stock', 'item_stock', 'expense_ledger');

    $saleBtnText = 'Sale Invoice';
    $purchaseBtnText = 'Purchase Invoice';
    $returnBtnText = 'Return Invoice';
    $paymentBtnText = 'Paid Payment';
    $staffBtnText = 'Staff Salaries';

    if($controller == 'customer_invoice')
    {
        $paymentBtnText = 'Received Payment';
    }

    if($controller == 'vendor_invoice')
    {
        $saleBtnText = 'Send Items';
        $purchaseBtnText = 'POI DC';
        $returnBtnText = 'Return Items';
    }
?>
            <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">

                <? if ($new)
                { ?>
                    <p style="float: left">

                            <?
                                if(in_array($controller, $supplierInvoice)){?>
                                    <a href="javascript:callAjax('1', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-success"><?=$purchaseBtnText?></button> &nbsp;</a> &nbsp;
                            <?}
                                if(in_array($controller, $customerInvoice)){?>
                                    <a href="javascript:callAjax('2', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-inverse"><?=$saleBtnText?></button></a> &nbsp;
                            <?}
                                if(in_array($controller, $vendorInvoice)){?>
                                    <a href="javascript:callAjax('3', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-info"><?=$purchaseBtnText?></button> &nbsp;</a> &nbsp;
                            <?}
                                if(in_array($controller, $paymentInvoice)){?>
                                    <a href="javascript:callAjax('4', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-danger"><?=$paymentBtnText?></button></a>
                            <?}
/*                                if(in_array($controller, $staffInvoice)){*/?><!--
                                    <a href="javascript:callAjax('5', '<?/*=ADMIN_URL.$controller*/?>/new_')"><button class="btn btn-large btn-danger"><?/*=$staffBtnText*/?></button></a>
                            --><?/*}*/
                                if(!in_array($controller, $newArray)){?>
                                    <a href="javascript:callAjax('', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-success ">Create New</button></a>
                            <?}
                                if($controller ==  'profile'){?>
                                    <a href="javascript:callAjax('', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-success ">Create Backup</button></a>
                            <?}?>
                    </p> <?
                } ?>

                <?
                if(($controller == 'product_stock' || $controller == 'item_stock') && $headingText != '')
                {
                    echo '<legend> <h3>'.$headingText.'</h3> </legend>';
                }

                if($controller == 'search_invoice'){?>
                    <form action="<?=ADMIN_URL.$controller?>" method="post">
                        <div class="row-fluid">
                            <div class="">
                                <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                    <label>
                                        Search Text : <input type="text" aria-controls="DataTables_Table_0" name="searchedText" value="<?=$this->input->post('searchedText')?>">
                                        &nbsp; &nbsp; By Client : <input type="radio" name="searchBy" value="1" <?=($this->input->post('searchBy') == 1 || $this->input->post('searchBy') == "") ? "checked": ""?> />
                                        &nbsp; &nbsp; By Invoice : <input type="radio" name="searchBy" value="2" <?=($this->input->post('searchBy') == 2) ? "checked": "" ?>/>
                                        &nbsp; &nbsp; <input type="submit" value="Search" name="submit" class="btn btn-success"/>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                <?}




                $placeHolder = 'Enter Name';

                if($controller == 'items' || $controller == 'products' || $controller == 'vendor_invoice' || $controller == 'customer_invoice'){

                    if($controller == 'vendor_invoice' || $controller == 'customer_invoice'){
                        $placeHolder = "Name or Reference No.";
                    }
                ?>
                    <form action="<?=ADMIN_URL.$controller?>" method="GET" style="float: right">
                        <div class="row-fluid">
                            <div class="">
                                <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                    <label>
                                        Search : <input type="text" aria-controls="DataTables_Table_0" name="searchedText" placeholder="<?=$placeHolder?>" value="<?=$_GET['searchedText']?>">
                                        <input type="submit" value="Search" name="submit" class="btn btn-success"/>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                <?}

                if($controller == 'payment_ledger' || $controller == 'account_ledger' || $controller == 'item_stock' || $controller == 'product_stock' || $controller == 'expense_ledger'){?>
                    <form action="<?=ADMIN_URL.$controller?>" method="GET">
                        <div class="row-fluid">
                            <div class="">
                                <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                    <label>
                                        <?
                                            if($controller == 'product_stock') {
                                                ?><input type="text" aria-controls="DataTables_Table_0" name="searchedText" placeholder="Product Name" value="<?=$_GET['searchedText']?>"><?
                                            }
                                        ?>
                                        <input type="text" name="startDate" placeholder="Start Date" class="datepicker" value="<?=$_GET['startDate']?>">
                                        <input type="text" name="endDate" placeholder="End Date" class="datepicker" value="<?=$_GET['endDate']?>">

                                        <?
                                            if($controller == 'product_stock') {
                                                ?>
                                                    <span>
                                                        <select name="client_id" id="client_id" class="[c-e] chosen">
                                                            <?= $obj->selectBox_db('id', 'name', $secondData, $_GET['client_id']) ?>
                                                        </select>
                                                    </span>
                                                <?
                                            }
                                        ?>

                                        <input type="submit" value="Search" name="submit" class="btn btn-success"/>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                <?}?>

                <?
                    if($table)
                    {
                        ?>
                            <table class="table table-striped table-bordered bootstrap-datatable datatable_ dataTable" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                            <thead>
                            <tr role="row">
                                <th>No.</th>
                                <?
                                    foreach ($colunms as $key => $value)
                                    {
                                        $currentValue = '';
                                        if(strtolower($value) == 'id' && $idColunm)
                                        {
                                            $currentValue = "<th style='width:20px;' class='hide'>".$value."</th>";
                                        }
                                        else if($value == 'date')
                                        {
                                            $currentValue = "<th style='width:70px;' >".$obj->createHeading($value)."</th>";
                                        }
                                        else if($value == 'reference_no')
                                        {
                                            $currentValue = "<th style='width:300px;' >".$obj->createHeading($value)."</th>";
                                        }
                                        else if(in_array($value, $hideColunms))
                                        {
                                            $currentValue = "<th class='hide'>".$value."</th>";
                                        }
                                        else
                                        {
                                            $currentValue = "<th>".$obj->createHeading($value)."</th>";
                                        }

                                        echo $currentValue;
                                    }

                                    if($action)
                                    {
                                        ?><th style="width: 18%">Action</th><?
                                    }
                                ?>
                            </tr>
                        </thead>

                        <tbody role="alert" aria-live="polite" aria-relevant="all">

                            <?
                                $totalDebit = 0;
                                $totalCredit = 0;

                                $totalMakeSheet = 0;
                                $totalSaleSheet = 0;

                                $rowTotal= 0;
                                $total_paid = 0;

                                for($a = 0 ; $a < count($table) ; $a++)
                                {
                                    $row = $table[$a];

                                    $itemNo = ($pageNo == 0) ? $pageNo : $pageNo-1;
                                    ?>
                                        <tr class="<?=($a%2 == 0) ? 'odd' : 'even'?>" id="row-<?=$row->id?>" >

                                            <td><?=(($itemNo*20)+($a+1))?></td>
                                            <?
                                                foreach ($colunms as $key => $value)
                                                {
                                                    $currentValue = '';
                                                    if($value == 'id' && $idColunm)
                                                    {
                                                        $id = $row->$value;
                                                        $currentValue = "<td class='center hide'>".$id."</td>";
                                                    }
                                                    else if(in_array($value, $hideColunms))
                                                    {
                                                        $currentValue = "<th class='hide'>".$value."</th>";
                                                    }
                                                    else if($value == 'status')
                                                    {
                                                        $statusValue = $row->$value;
                                                        $currentValue = "<td class='center' style='width: 40px'>".$obj->showStatus($statusValue)."</td>";
                                                    }
                                                    else if($value == 'type')
                                                    {
                                                        $invoiceType = $row->$value;
                                                        $currentValue = "<td class='center' style='width: 60px'>".ucfirst($obj->showInvoiceType($invoiceType, $controller))."</td>";
                                                    }
                                                    else if($value == 'client_type')
                                                    {
                                                        $clientType = $row->$value;
                                                        $currentValue = "<td class='center' style='width: 120px'>".ucfirst($obj->showClientType($clientType))."</td>";
                                                    }
                                                    else if($value == 'invoice_no')
                                                    {
                                                        $invoiceNo = $row->$value;
                                                        $currentValue = "<td class='center' style='width: 120px'>".base64_encode($invoiceNo)."</td>";
                                                    }
                                                    else if($value == 'invoice_detail')
                                                    {
                                                        list($invoice, $entries) = explode('-',$row->$value);
                                                        $invoice = trim(preg_replace("/[^0-9 ]/", '', $invoice));
                                                        $row->$value = "invoice(".base64_encode($invoice).")";//.$entries

                                                        $param1 = $row->id;

                                                        if($row->client_type == 3)
                                                        {
                                                            //$param1 = "$row->id|$row->invoice_type";
                                                        }

                                                        $param1 = "$row->id|$row->invoice_type";

                                                        $param2 = ADMIN_URL.strtolower($obj->showClientType($row->client_type))."_invoice/view_";
                                                        ob_start();
                                                        ?><td class="center"><a href="javascript:callAjax('<?=$param1?>', '<?=$param2?>')"><?=$row->$value?></a></td><?
                                                        $currentValue = ob_get_clean();
                                                    }
                                                    else if($value == 'created_at' || $value == 'modified_at' || $value == 'date')
                                                    {
                                                        $currentValue = "<td class='center'>".$obj->showDate($row->$value)."</td>";
                                                    }
                                                    else if($value == 'image')
                                                    {
                                                        $currentValue = "<td class='center' style='width: 120px'><img src='".DOMAIN_URL.$row->$value."' width='100'/></td>";
                                                    }
                                                    else if($value == 'name' && in_array($controller, $clientLink))
                                                    {
                                                        $currentValue = "<td class='center'><a href='" . ADMIN_URL . "search_invoice?id=" . $obj->encrypt($row->client_id) . "'>" . $row->$value . "</a></td>";
                                                    }
                                                    else if($value == 'detail' && in_array($controller, $stockLink))
                                                    {
                                                        $currentValue = "<td class='center'><a href='" . ADMIN_URL .$controller. "?id=" . $obj->encrypt($row->id)."&".$_SERVER['QUERY_STRING'] . "'>" . $row->$value . "</a></td>";
                                                    }
                                                    else if($value == 'total_amount' || $value == 'total')
                                                    {
                                                        $currentValue = "<td class='center'>".number_format($row->$value, 2, '.', ',')."</td>";
                                                    }
                                                    else if($value == 'balance')
                                                    {
                                                        $rowTotal += (str_replace(',', '', $row->debit)-str_replace(',', '', $row->credit));
                                                        $currentValue = "<td class='center'>".number_format($rowTotal, 2, '.', ',')."</td>";
                                                    }
                                                    else if($value == 'remaining')
                                                    {
                                                        $currentValue = "<td class='center'>".number_format(($row->$value - $row->used), 2)."</td>";
                                                    }
                                                    else if($value == 'staff_name') {
                                                        $currentValue = "<td class='center'><a href='" . ADMIN_URL . "staff?id=" . $obj->encrypt($row->id) . "'>" . $row->$value . "</a></td>";
                                                    }
                                                    else if($value == 'total_paid') {
                                                        $total_paid += $row->$value;
                                                        $currentValue = "<td class='center'>".$total_paid."</td>";
                                                    }
                                                    else
                                                    {
                                                        $currentValue = "<td class='center'>".$row->$value."</td>";
                                                    }

                                                    if($value == 'make_sheet') {
                                                        $totalMakeSheet += $row->$value;
                                                    }

                                                    if($value == 'sale_sheet') {
                                                        $totalSaleSheet += $row->$value;
                                                    }

                                                    if($value == 'debit') {

                                                        $totalDebit += str_replace(',', '', $row->debit);
                                                    }

                                                    if($value == 'credit') {
                                                        $totalCredit += str_replace(',', '', $row->credit);
                                                    }

                                                    echo $currentValue;
                                                }

                                                if($action)
                                                {
                                                    ?>
                                                        <td class="center" style="text-align: center;">
                                                            <?
                                                            if($status && isset($statusValue))
                                                            {
                                                                if($statusValue == 0)
                                                                {
                                                                    ?>
                                                                        <a href="javascript:statusRow('<?=$id?>', '<?=ADMIN_URL.$controller.'/status_'?>', '1')" class="btn btn-inverse" title="Enable" id="row-<?=$id?>-enable">
                                                                            <i class="icon icon-check icon-white"></i>
                                                                        </a>
                                                                    <?
                                                                }
                                                                else
                                                                {
                                                                    ?>
                                                                        <a href="javascript:statusRow('<?=$id?>', '<?=ADMIN_URL.$controller.'/status_'?>', '0')" class="btn btn-inverse" title="Disable" id="row-<?=$id?>-disable">
                                                                            <i class="icon icon-close icon-white"></i>
                                                                        </a>
                                                                    <?
                                                                }
                                                                ?>
                                                            <?
                                                            }

                                                            if($view)
                                                            {
                                                                ?>
                                                                <a href="javascript:callAjax('<?=$id?>', '<?=ADMIN_URL.$controller?>/view_')" class="btn btn-success" title="View">
                                                                    <i class="icon-zoom-in icon-white"></i>
                                                                </a>
                                                            <?
                                                            }


                                                            if($edit)
                                                            {
                                                                ?>
                                                                <a href="javascript:callAjax('<?=$id?>', '<?=ADMIN_URL.$controller?>/edit_')" class="btn btn-info" title="Edit">
                                                                    <i class="icon-edit icon-white"></i>
                                                                </a>
                                                            <?
                                                            }

                                                            if ($delete)
                                                            {
                                                                if ($controller ==  'expense_type' && $id == 1)
                                                                {
                                                                }else{?>
                                                                <a href="javascript:statusRow('<?=$id?>', '<?=ADMIN_URL.$controller?>/delete_', '-1')" class="btn btn-danger">
                                                                    <i class="icon-trash icon-white"></i>
                                                                </a>
                                                            <?}
                                                            }

                                                            if($controller ==  'backup')
                                                            {
                                                                ?>
                                                                <a href="'<?=ADMIN_URL.$controller?>/save_" class="btn btn-danger">
                                                                    <i class="icon-download-alt icon-white"></i>
                                                                </a>
                                                            <?
                                                            }?>
                                                        </td>
                                                    <?
                                                }
                                            ?>
                                        </tr>
                                    <?
                                }


                                if($controller == 'account_ledger')
                                {
                                    ?>
                                        <tr>
                                            <td colspan="5"></td>
                                            <td><b><?=number_format($totalMakeSheet)?></b></td>
                                            <td><b><?=number_format($totalSaleSheet)?></b></td>
                                            <td><b><?=number_format($totalCredit, 2)?></b></td>
                                            <td><b><?=number_format($totalDebit, 2)?></b></td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr><td colspan="10"></td></tr>

                                        <tr>
                                            <td colspan="5">Final Result</td>
                                            <td><b><?=number_format($secondData['makeSheet'])?></b></td>
                                            <td><b><?=number_format($secondData['saleSheet'])?></b></td>
                                            <td><b><?=number_format($secondData['credit'], 2)?></b></td>
                                            <td><b><?=number_format($secondData['debit'], 2)?></b></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    <?
                                }
                                else if($controller == 'payment_ledger')
                                {
                                    ?>
                                        <tr>
                                            <td colspan="5"></td>
                                            <td><b><?=number_format($totalCredit, 2)?></b></td>
                                            <td><b><?=number_format($totalDebit, 2)?></b></td>
                                            <td>&nbsp;</td>
                                        </tr>

                                        <tr><td colspan="8"></td></tr>

                                        <tr>
                                            <td colspan="5">Final Result</td>
                                            <td><b><?=number_format($secondData['credit'], 2)?></b></td>
                                            <td><b><?=number_format($secondData['debit'], 2)?></b></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    <?
                                }


                            ?>
                        </tbody>
                    </table>

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="dataTables_info" id="DataTables_Table_0_info">
                                Total : <?=$totalRows?> Entries

                                <?if($error) {?>
                                    <div class="query-error" style="display: none"><?=$error?></div>
                                <?}?>
                            </div>
                        </div>

                        <?
                            if($totalRows > $pageLimit)
                            {
                                ?>

                                <div class="span12 center">
                                    <div class="dataTables_paginate paging_bootstrap pagination" style="float: right;">
                                        <ul>
                                            <?
                                                $searchedURL = '?'.$_SERVER['QUERY_STRING'];

                                                if ($pageNo > 1)
                                                {
                                                    ?><li class="prev"><a href="<?=ADMIN_URL.$controller.'/index/'.($pageNo-1).$searchedURL?>">← Previous</a></li><?
                                                }

                                                $startNo = 2;
                                                if ($pageNo == 5)
                                                {
                                                    $startNo = 1;
                                                }
                                                if ($pageNo == 6)
                                                {
                                                    $startNo = 2;
                                                }
                                                $endNo = 1;

                                                $dot1 = 1;
                                                $dot2 = 1;
                                                for ($a = 1; $a <= $totalPages; $a++)
                                                {
                                                    $url = ADMIN_URL.$controller.'/index/'.$a;

                                                    if ($a == $pageNo) // current page
                                                    {
                                                        ?><li class="active"><a href="#" style="color: red; background: lightblue"><?=$a?></a></li><?
                                                    }
                                                    else
                                                    {
                                                        if ($a <= $startNo)
                                                        {
                                                            ?><li><a href="<?=$url.$searchedURL?>"><?=$a?></a></li><?
                                                        }
                                                        else if ($a >= $pageNo - 2 && $a <= $pageNo + 2)
                                                        {
                                                            ?><li><a href="<?=$url.$searchedURL?>"><?=$a?></a></li><?
                                                        }
                                                        else if ($a == $totalPages || ($a == $totalPages - 1 && $pageNo >= 6))
                                                        {
                                                            ?><li><a href="<?=$url.$searchedURL?>"><?=$a?></a></li><?
                                                        }
                                                        else
                                                        {
                                                            if ($dot1 == 1)
                                                            {
                                                                $dot1 = 2;
                                                                ?><li><a href="javascript:void(0)" class="active">...</a></li><?
                                                            }
                                                            else
                                                            {
                                                                if ($pageNo <= ($totalPages - 1) && $dot2 == 1 && $a > $pageNo && $pageNo > 4)
                                                                {
                                                                    $dot2 = 2;
                                                                    ?><li><a href="javascript:void(0)" class="active">...</a></li><?
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($pageNo < $totalPages)
                                                {
                                                    ?><li class="next"><a href="<?=ADMIN_URL.$controller.'/index/'.($pageNo+1).$searchedURL?>">Next → </a></li><?
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            <?}
                        ?>
                    </div>
                </div>
                <?
            }
            else
            {
                ?>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="dataTables_info error-class" id="DataTables_Table_0_info" style="margin: 0">
                                No Recored Found.
                            </div>
                        </div>
                    </div>
                </div>
                <?
            }
        ?>