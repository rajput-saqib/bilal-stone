<div class="row-fluid sortable ui-sortable">
    <div class="box span12" style="margin: 0">
        <div class="box-header well" data-original-title="">
            <h2><i class="icon-edit"></i> Form Elements</h2>

            <div class="box-icon">
                <a href="javascript:closeAjax()" class="btn btn-close btn-round"><i class="icon-remove"></i></a>

            </div>
        </div>
        <div class="box-content">
            <form action="<?= $controllerName . $submitPath ?>" method="post" id="my-form" style="padding: 20px 0 0 0"
                  onsubmit="return formValidation(this.id)" class="form-horizontal">
                <fieldset>
                    <div class="control-group span6">
                        <label class="control-label">Reference No : </label>
                        <div class="controls">
                            <input type="text" name="reference_no" id="reference_no" value="<?= (isset($data->reference_no) ? $data->reference_no : '') ?>" class="input-xlarge focused [c-e]"/>
                        </div>
                    </div>
                    <div class="control-group span6">
                        <label class="control-label">Date : </label>
                        <div class="controls">
                            <input type="text" name="date" id="date" value="<?= (isset($data->date) ? $obj->showDate($data->date) : '') ?>" class="input-xlarge datepicker focused [c-e]"/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Client Name : </label>

                        <div class="controls">
                            <select name="client_id" id="client_id" class="chosen [c-e]">
                                <?= $obj->selectBox_db('id', 'name', $selectBox_clients, (isset($data->client_id) ? $data->client_id : '')) ?>
                            </select>
                        </div>
                    </div>


                    <div class="control-group">
                        <label class="control-label">&nbsp;</label>

                        <div class="controls">
                            <div class="box-content">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="add-more-container">

                                    <?
                                        if(isset($data))
                                        {
                                            for ($a = 0; $a < count($data2); $a++)
                                            {
                                                ?>
                                                    <tr class="add-more" id="add-more-<?=$a?>">
                                                        <td>
                                                            <div class="controls" style="margin: 0">
                                                                <select name="product[]" id="product-<?=$a?>" class="[c-e]">
                                                                    <?= $obj->selectBox_db('id', 'name', $selectBox_products, (isset($data2[$a]->product_id) ? $data2[$a]->product_id : '')) ?>
                                                                </select>
                                                            </div>
                                                        </td>

                                                        <td class="center"><input type="text" name="qty[]" id="qty-<?=$a?>" value="<?= (isset($data2[$a]->qty) ? $data2[$a]->qty : '') ?>" class="input-xlarge focused [c-e] custom-width" onkeyup="setAmount('<?=$a?>')"/></td>
                                                        <td class="center"><input type="text" name="rate[]" id="rate-<?=$a?>" value="<?= (isset($data2[$a]->rate) ? $data2[$a]->rate : '') ?>" class="input-xlarge focused [c-e] custom-width" onkeyup="setAmount('<?=$a?>')"/></td>
                                                        <td class="center">
                                                            <input type="text" name="price[]" id="price-<?=$a?>" value="<?= (isset($data2[$a]->price) ? $data2[$a]->price : '') ?>" class="input-xlarge focused [c-e] custom-width"/>

                                                            <? if ($a == 0)
                                                            {
                                                                ?><span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('invoice')"> </span>
                                                            <?
                                                            }
                                                            else
                                                            {
                                                                ?><span class="icon32 icon-color add-more-btn icon-close" onclick="removeRow('add-more-<?= $a ?>')"></span>
                                                            <?
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                <?
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                                <tr class="add-more" id="add-more-0">
                                                    <td>
                                                        <div class="controls" style="margin: 0">
                                                            <select name="product[]" id="product-0" class="[c-e]">
                                                                <?= $obj->selectBox_db('id', 'name', $selectBox_products) ?>
                                                            </select>
                                                        </div>
                                                    </td>

                                                    <td class="center"><input type="text" name="qty[]" id="qty-0" value="" class="input-xlarge focused [c-e] custom-width" onkeyup="setAmount('0')"/></td>
                                                    <td class="center"><input type="text" name="rate[]" id="rate-0" value="" class="input-xlarge focused [c-e] custom-width" onkeyup="setAmount('0')"/></td>
                                                    <td class="center">
                                                        <input type="text" name="price[]" id="price-0" value="" class="input-xlarge focused [c-e] custom-width"/>
                                                        <span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('invoice');"> </span>
                                                    </td>
                                                </tr>
                                            <?
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="control-group span6">
                           <label class="control-label">&nbsp;</label>
                           <div class="controls">&nbsp;</div>
                       </div>
                       <div class="control-group span6">
                           <div class="controls" style="padding: 0 84px">
                               <span>Total Amount :</span>
                               <input type="text" name="total_amount" id="total_amount" value="<?= (isset($data->total_amount) ? $data->total_amount : '') ?>" class="input-xlarge custom-width focused [c-e]"/>
                           </div>
                       </div>
                    </div>

                    <div class="form-actions">
                        <?
                            $submitBtnText = 'Save Changes';
                            if ($submitPath == '/update_')
                            {
                                $submitBtnText = 'Update Changes';
                                ?>
                                <input type="hidden" name="id" id="id"
                                       value="<?= (isset($data->id) ? $data->id : '') ?>"/>
                                <?
                            }
                        ?>
                        <button type="submit" class="btn btn-primary"><?= $submitBtnText ?></button>
                        <input type="hidden" name="type_i" id="type_i" value="3"/>
                        <button class="btn" type="button" onclick="closeAjax()">Back</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
    <!--/span-->

</div>

<style type="text/css">
    #add-more-0 {
        margin: 0 !important;
    }

    .add-more {
        margin: 10px 0 0 0;
    }

    .add-more-btn {
        margin: -8px 0 0 0;
    }

    .custom-width
    {
        width: 120px;
    }

</style>

    <script type="text/javascript">

        $(document).ready(function(){
            setChosen();
            setDatepicker();
        });

        function setChosen()
        {
            $('.chosen').chosen();
        }

        function setDatepicker()
        {
            $('.datepicker').datepicker();
        }

        function setAmount(no)
        {
            var rate = parseInt($('#rate-'+no).val());
            console.log(rate);
            var qty = parseInt($('#qty-'+no).val());
            console.log(qty);
            var amount = (qty != 'NaN' || rate != 'NaN') ? (rate*qty) : '';
            console.log(amount);
            $('#amount-'+no).val(amount);

            var total_amount = 0;
            var length = $('.add-more').length;

            for(var a = 0 ; a < length ; a++)
            {
                total_amount += parseInt($('#amount-'+a).val());
            }

            $('#total_amount-'+no).val(total_amount);
        }

    </script>