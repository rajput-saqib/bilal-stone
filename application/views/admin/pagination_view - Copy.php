
            <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">

                <? if ($new)
                { ?>
                    <p>
                        <a href="javascript:callAjax('', '<?=ADMIN_URL.$controller?>/new_')">
                            <button class="btn btn-large btn-success ">Create New</button>
                        </a>
                    </p> <?
                } ?>

                <div class="row-fluid" style="display: none">
                    <div class="span6">
                        <div id="DataTables_Table_0_length" class="dataTables_length">
                            <label>
                                <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0">
                                    <option value="10" selected="selected">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                records per page</label>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="dataTables_filter" id="DataTables_Table_0_filter"><label>
                                Search: <input type="text" aria-controls="DataTables_Table_0"></label>
                        </div>
                    </div>
                </div>

                <table class="table table-striped table-bordered bootstrap-datatable datatable_ dataTable" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                    <?
                    if($table)
                    {
                        ?>
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
                                                    else if($value == 'createdAt' || $value == 'updatedAt')
                                                    {
                                                        $currentValue = "<td class='center'>".$obj->showDate($row->$value)."</td>";
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
                                                                <a href="javascript:statusRow('<?=$id?>', '<?=ADMIN_URL.$controller?>/delete_')" class="btn btn-danger">
                                                                    <i class="icon-trash icon-white"></i>
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
                    <?}?>
                </table>

                <div class="row-fluid">
                    <div class="span12">
                        <div class="dataTables_info" id="DataTables_Table_0_info">
                            Total : <?=$totalRows?> Entries
                        </div>
                    </div>

                    <?
                        if($totalRows > $pageLimit)
                        {?>

                            <div class="span12 center">
                                <div class="dataTables_paginate paging_bootstrap pagination" style="float: right;">
                                    <ul>
                                        <?
                                            if ($pageNo > 1)
                                            {
                                                ?><li class="prev disabled"><a href="#">← Previous</a></li><?
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
                                                if ($a == $pageNo) // current page
                                                {
                                                    ?><li class="active"><a href="#"><?=$a?></a></li><?
                                                }
                                                else
                                                {
                                                    if ($a <= $startNo)
                                                    {
                                                        ?><li class="active"><a href="#"><?=$a?></a></li><?
                                                    }
                                                    else if ($a >= $pageNo - 2 && $a <= $pageNo + 2)
                                                    {
                                                        ?><li class="active"><a href="#"><?=$a?></a></li><?
                                                    }
                                                    else if ($a == $totalPages || ($a == $totalPages - 1 && $pageNo >= 6))
                                                    {
                                                        ?><li class="active"><a href="#"><?=$a?></a></li><?
                                                    }
                                                    else
                                                    {
                                                        if ($dot1 == 1)
                                                        {
                                                            $dot1 = 2;
                                                            ?><li class="active"><a href="#">...</a></li><?
                                                        }
                                                        else
                                                        {
                                                            if ($pageNo <= ($totalPages - 1) && $dot2 == 1 && $a > $pageNo && $pageNo > 4)
                                                            {
                                                                $dot2 = 2;
                                                                ?><li class="active"><a href="#">...</a></li><?
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            if ($pageNo < $totalPages)
                                            {
                                                ?><li class="next"><a href="#">Next → </a></li><?
                                            }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        <?}
                    ?>
                </div>
            </div>
