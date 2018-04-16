<?
    $hideColunms = array('client_id','invoice_type');

    $saleInvoice = array('customer_invoice',);
    $purchaseInvoice = array('client_invoice', 'vendor_invoice');
    $returnInvoice = array('');
    $paymentInvoice = array('client_invoice', 'customer_invoice', 'vendor_invoice');
    $clientLink = array('client_invoice', 'customer_invoice', 'vendor_invoice', 'search_invoice', 'production_team', 'customers', 'clients');
    $stockLink = array('stock_detail');

    $newArray = array('client_invoice', 'customer_invoice', 'vendor_invoice', 'search_invoice', 'backup', 'stock_detail', 'profile');

    $saleBtnText = 'Sale Invoice';
    $purchaseBtnText = 'Purchase Invoice';
    $returnBtnText = 'Return Invoice';
    $paymentBtnText = 'Paid Payment';

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
                                if(in_array($controller, $saleInvoice)){?>
                                    <a href="javascript:callAjax('1', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-inverse"><?=$saleBtnText?></button></a> &nbsp;
                            <?}
                                if(in_array($controller, $purchaseInvoice)){?>
                                    <a href="javascript:callAjax('2', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-success"><?=$purchaseBtnText?></button> &nbsp;</a> &nbsp;
                            <?}
                                if(in_array($controller, $returnInvoice)){?>
                                    <a href="javascript:callAjax('3', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-info"><?=$returnBtnText?></button> &nbsp;</a> &nbsp;
                            <?}
                                if(in_array($controller, $paymentInvoice)){?>
                                    <a href="javascript:callAjax('4', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-danger"><?=$paymentBtnText?></button></a>
                            <?}
                                if(!in_array($controller, $newArray)){?>
                                    <a href="javascript:callAjax('', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-success ">Create New</button></a>
                            <?}
                                if($controller ==  'profile'){?>
                                    <a href="javascript:callAjax('', '<?=ADMIN_URL.$controller?>/new_')"><button class="btn btn-large btn-success ">Create Backup</button></a>
                            <?}?>
                    </p> <?
                } ?>

                <?
                if($controller == 'stock_detail' && $headingText != '')
                {
                    echo '<legend> <h3>'.$headingText.'</h3> </legend>';
                }



                if($controller == 'search_invoice'){?>
                    <form action="<?=ADMIN_URL.$controller?>" method="post">
                        <div class="row-fluid">
                            <div class="span6">
                                <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                    <label>
                                        Search (Client - Customer - Employee): <input type="text" aria-controls="DataTables_Table_0" name="searchedText" value="<?=$this->input->post('searchedText')?>">
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
                                <?
                                    foreach ($colunms as $key => $value)
                                    {
                                        $currentValue = '';
                                        if(strtolower($value) == 'id' && $idColunm)
                                        {
                                            $currentValue = "<th style='width:20px;' class='hide'>".$value."</th>";
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
                                $rowTotal= 0;
                                for($a = 0 ; $a < count($table) ; $a++)
                                {
                                    $row = $table[$a];
                                    ?>
                                        <tr class="<?=($a%2 == 0) ? 'odd' : 'even'?>" id="row-<?=$row->id?>" >
                                            <?
                                                foreach ($colunms as $key => $value)
                                                {
                                                    $currentValue = '';
                                                    if($value == 'id' && $idColunm)
                                                    {
                                                        $id = $row->$value;
                                                        $currentValue = "<td class='center hide'>".$id."</td>";
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
                                                        $row->$value = "invoice(".base64_encode($invoice).") - ".$entries;
                                                        
                                                        //$param1 = $row->id;
                                                        $param1 = "$row->id|$row->type";

                                                        $param2 = ADMIN_URL.strtolower($obj->showClientType($row->client_type))."_invoice/view_";
                                                        ob_start();
                                                        /**/?><!--<td class="center"><a href="javascript:callAjax('<?/*=$param1*/?>', '<?/*=$param2*/?>')"><?/*=$row->$value*/?></a></td>--><?
                                                        ?><td class="center"><?=$row->$value?></td><?
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
                                                        $currentValue = "<td class='center'><a href='" . ADMIN_URL . "stock_detail?id=" . $obj->encrypt($row->id) . "'>" . $row->$value . "</a></td>";
                                                    }
                                                    else if(in_array($value, $hideColunms))
                                                    {
                                                        $currentValue = "<th class='hide'>".$value."</th>";
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
                                                    else
                                                    {
                                                        $currentValue = "<td class='center'>".$row->$value."</td>";
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
                                                                ?>
                                                                <a href="javascript:statusRow('<?=$id?>', '<?=ADMIN_URL.$controller?>/delete_', '-1')" class="btn btn-danger">
                                                                    <i class="icon-trash icon-white"></i>
                                                                </a>
                                                            <?
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
                            ?>
                        </tbody>
                    </table>

                    <div class="row-fluid">
                        <div class="span12">
                            <div class="dataTables_info" id="DataTables_Table_0_info">
                                Total : <?=$totalRows?> Entries
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
                                                if ($pageNo > 1)
                                                {
                                                    ?><li class="prev"><a href="<?=ADMIN_URL.$controller.'/index/'.($pageNo-1)?>">← Previous</a></li><?
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
                                                if ($pageNo == 5)
                                                {
                                                    $startNo = 1;
                                                }
                                                $dot1 = 1;
                                                $dot2 = 1;
                                                for ($a = 1; $a <= $totalPages; $a++)
                                                {
                                                    $url = ADMIN_URL.$controller.'/index/'.$a;

                                                    if ($a == $pageNo) // current page
                                                    {
                                                        ?><li class="active"><a href="#"><?=$a?></a></li><?
                                                    }
                                                    else
                                                    {
                                                        if ($a <= $startNo)
                                                        {
                                                            ?><li><a href="<?=$url?>"><?=$a?></a></li><?
                                                        }
                                                        else if ($a >= $pageNo - 2 && $a <= $pageNo + 2)
                                                        {
                                                            ?><li><a href="<?=$url?>"><?=$a?></a></li><?
                                                        }
                                                        else if ($a == $totalPages || ($a == $totalPages - 1 && $pageNo >= 6))
                                                        {
                                                            ?><li><a href="<?=$url?>"><?=$a?></a></li><?
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
                                                    ?><li class="next"><a href="<?=ADMIN_URL.$controller.'/index/'.($pageNo+1)?>">Next → </a></li><?
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



<!--            SET @total = 0;

            SELECT
            i.date,
            id.desc 'description',
            IF(i.`type_i` = 1 OR i.`type_i` = 3, i.total_amount, '') 'received',
            IF(i.`type_i` = 2 OR i.`type_i` = 4, i.total_amount, '') 'paid',
            @total := @total + IF(i.`type_i` = 1 OR i.`type_i` = 3, i.total_amount, 0) - IF(i.`type_i` = 2 OR i.`type_i` = 4, i.total_amount, 0) 'balance'

            FROM invoice i
            INNER JOIN invoice_detail id ON i.id = id.invoice_id
            WHERE i.`status` = '1' AND i.client_id = '5'
            GROUP BY i.id-->