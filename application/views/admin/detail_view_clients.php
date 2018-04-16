<div class="row-fluid sortable ui-sortable">
    <div class="box span12" style="margin: 0">
        <div class="box-header well" data-original-title="">
            <h2><i class="icon-edit"></i> <?=$obj->createHeading($controllerName);?></h2>

            <div class="box-icon">
                <a href="javascript:closeAjax()" class="btn btn-close btn-round"><i class="icon-remove"></i></a>

            </div>
        </div>
        <div class="box-content">
            <form action="" method="post" id="my-form" style="padding: 20px 0 0 0" class="form-horizontal" >
                <fieldset>
                    <?
                        $colspan = ($controllerName == 'customer_invoice') ? 5 : 4;


                        if(count($data) > 0)
                        {
                            foreach ($data as $key => $value)
                            {
                                if (strtolower($key) == 'id')
                                {
                                    $id = $value;
                                    continue;
                                }
                                if (strtolower($key) == 'image')
                                {
                                    $value = "<img src='" . DOMAIN_URL . $value . "' width='100'/>";
                                }
                                if (strtolower($key) == 'created_at' || strtolower($key) == 'modified_at' || strtolower($key) == 'date')
                                {
                                    $value = $obj->showDate($value);
                                }
                                if (strtolower($key) == 'status')
                                {
                                    $value = $obj->showStatus($value);
                                }
                                if (strtolower($key) == 'total_amount')
                                {
                                    $value = number_format($value, 2, '.', ',');
                                }

                                if (strtolower($key) == 'type_c' || strtolower($key) == 'type_i')
                                {
                                    continue;
                                }
                                ?>
                                <div class="control-group">
                                    <label class="control-label"><?= $obj->createHeading($key) ?></label>

                                    <div class="controls"><?= $value ?></div>
                                </div>
                            <?
                            }
                        }

                        if(isset($data2) && count($data2['data']) > 0)
                        {
                            ?>
                            <div class="control-group">
                                <label class="control-label">&nbsp;</label>
                            <div class="box span6" style="">
                                <div class="box-content">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <?
                                                foreach ($data2['colunms'] as $colunmName)
                                                {
                                                    ?><th><?=$obj->createHeading($colunmName)?></th><?
                                                }
                                            ?>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            <?
                                                foreach ($data2['data'] as $key => $value2)
                                                {
                                                    echo '<tr>';
                                                    foreach ($data2['colunms'] as $colunmName)
                                                    {
                                                        ?>
                                                        <td><?= $obj->createHeading($value2->$colunmName) ?></td><?
                                                    }

                                                    if($data3[$value2->id])
                                                    {
                                                        echo '<tr><td colspan="'.$colspan.'"><table style="background: #E6E6FA; width: 100%">';

                                                        foreach ($data3[$value2->id] as $value3)
                                                        {
                                                            echo '<tr>';
                                                            foreach ($data3['colunms'] as $colunmName3)
                                                            {
                                                                ?><td style="border: 0"><?=$obj->createHeading($colunmName3)?> (<?=$value3->$colunmName3?>)</td><?
                                                            }
                                                            echo '</tr>';
                                                        }

                                                        echo '</table></td><tr>';
                                                    }

                                                    echo '</tr>';
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?
                        }
                    ?>
                </fieldset>
            </form>

            <div class="form-actions" style="padding-left : 9%">
                <button class="btn btn-success" type="button" onclick="closeAjax()">Back</button>
                <button class="btn btn-success" type="button" onclick="callAjax('<?=$id?>', '<?=ADMIN_URL.$controllerName?>/edit_')">Edit</button>
            </div>
        </div>
    </div>
</div>