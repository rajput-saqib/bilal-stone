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
                            <input type="text" name="date" id="date" value="<?= (isset($data->date) ? $obj->showDate($data->date) : date('m/d/Y')) ?>" class="input-xlarge datepicker focused [c-e]"/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Supplier Name : </label>

                        <div class="controls">
                            <select name="client_id" id="client_id" class="chosen [c-e]">
                                <?= $obj->selectBox_db('id', 'name', $selectBox_clients, (isset($data->client_id) ? $data->client_id : '')) ?>
                            </select>
                        </div>
                    </div>

                    <?
                        //$controller == 'employee_invoice'
                        ?>
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
                                                for ($a = 0; $a < count($data3); $a++)
                                                {
                                                    ?>
                                                        <tr class="add-more" id="add-more-<?=$a?>">
                                                            <td>
                                                                <div class="controls" style="margin: 0">
                                                                    <span id="item-no-0" class="item-no"><?=($a+1)?></span>
                                                                    <select name="items[]" id="items-<?=$a?>" class="[c-e] chosen">
                                                                        <?= $obj->selectBox_db('id', 'name', $selectBox_items, (isset($data3[$a]->itemId) ? $data3[$a]->itemId : '')) ?>
                                                                    </select>
                                                                </div>
                                                            </td>

                                                            <td class="center"><input type="text" name="qty[]" id="qty-<?=$a?>" value="<?= (isset($data3[$a]->itemQty) ? $data3[$a]->itemQty : '') ?>" class="input-xlarge focused [c-e] custom-width" onkeyup="setAmountSupplier('<?=$a?>')"/></td>
                                                            <td class="center"><input type="text" name="rate[]" id="rate-<?=$a?>" value="<?= (isset($data3[$a]->itemRate) ? $data3[$a]->itemRate : '') ?>" class="input-xlarge focused [c-e] custom-width" onkeyup="setAmountSupplier('<?=$a?>')"/></td>
                                                            <td class="center">
                                                                <input type="text" name="price[]" readonly="readonly" id="price-<?=$a?>" value="<?= (isset($data3[$a]->itemPrice) ? $data3[$a]->itemPrice : '') ?>" class="input-xlarge focused [c-e] custom-width"/>

                                                                <? if ($a == 0)
                                                                {
                                                                    ?><span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('supplier-invoice')"> </span>
                                                                <?
                                                                }
                                                                else
                                                                {
                                                                    ?><span class="icon32 icon-color add-more-btn icon-close" onclick="removeRowSupplier('<?= $a ?>')"></span>
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
                                                                <span id="item-no-0" class="item-no">1</span>
                                                                <select name="items[]" id="items-0" class="[c-e] chosen">
                                                                    <?= $obj->selectBox_db('id', 'name', $selectBox_items) ?>
                                                                </select>
                                                            </div>
                                                        </td>

                                                        <td class="center"><input type="text" name="qty[]" id="qty-0" value="" class="input-xlarge focused [c-e] custom-width" onkeyup="setAmountSupplier('0')"/></td>
                                                        <td class="center"><input type="text" name="rate[]" id="rate-0" value="" class="input-xlarge focused [c-e] custom-width" onkeyup="setAmountSupplier('0')"/></td>
                                                        <td class="center">
                                                            <input type="text" name="price[]" readonly="readonly" id="price-0" value="" class="input-xlarge focused [c-e] custom-width"/>
                                                            <span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('supplier-invoice');"> </span>
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
                           <div class="controls">
                               <span>Total Amount :</span>
                               <input type="text" name="total_amount" id="total_amount" value="<?= (isset($data->total_amount) ? $data->total_amount : '') ?>" class="input-xlarge custom-width focused [c-e]"/>
                           </div>
                       </div>
                    </div>

                    <div class="form-actions">
                        <div style="float: right"><span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('supplier-invoice')"> </span></div>
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
                        <input type="hidden" name="type_i" id="type_i" value="1"/>
                        <button class="btn" type="button" onclick="closeAjax()">Back</button>
                    </div>
                </fieldset>
            </form>

            <div style="display: none">
                  <table>
                      <tr class="add-more" id="add-more-xx" style="display: none">
                          <td>
                              <div class="controls" style="margin: 0">
                                  <span id="item-no-xx" class="item-no">1</span>
                                  <select name="items[]" id="items-xx" class="[c-e]">
                                      <?= $obj->selectBox_db('id', 'name', $selectBox_items) ?>
                                  </select>
                              </div>
                          </td>

                          <td class="center"><input type="text" name="qty[]" id="qty-xx" value="" class="input-xlarge focused [c-e] custom-width" onkeyup="setAmountSupplier('0')"/></td>
                          <td class="center"><input type="text" name="rate[]" id="rate-xx" value="" class="input-xlarge focused [c-e] custom-width" onkeyup="setAmountSupplier('0')"/></td>
                          <td class="center">
                              <input type="text" name="price[]" readonly="readonly" id="price-xx" value="" class="input-xlarge focused [c-e] custom-width"/>
                              <span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('supplier-invoice');"> </span>
                          </td>
                      </tr>
                  </table>
              </div>

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
            $('.chzn-container').css("width", "200px").css("font-weight", "normal");
        }

        function setDatepicker()
        {
            $('.datepicker').datepicker();
        }

        function setAmountSupplier(no)
        {
            var qty = parseInt($('#qty-'+no).val());
            var rate = parseFloat($('#rate-'+no).val());

            var price = (qty > 0 && rate > 0) ? (rate*qty) : '';

            $('#price-'+no).val(price);

            var total_amount = 0;
            var length = $('.add-more').length;

            for(var a = 0 ; a < length ; a++)
            {
                var currentPrice = parseInt($('#price-'+a).val());

                console.log(currentPrice+"----");

                if(currentPrice > 0) {
                    total_amount += currentPrice;
                }
            }

            $('#total_amount').val((total_amount > 0) ? total_amount : '');
        }


        function removeRowSupplier(no)
        {
            $('#add-more-'+no).find('#items-'+no).removeClass('[c-e]').val('');
            $('#add-more-'+no).find('#qty-'+no).removeClass('[c-e]').val(0);
            $('#add-more-'+no).find('#rate-'+no).removeClass('[c-e]').val(0);
            $('#add-more-'+no).find('#price-'+no).removeClass('[c-e]').val(0);
            $('#add-more-'+no).find('.chosen').removeClass('[c-e]').val(0);
            $('#add-more-'+no).hide();

            setAmountSupplier();
        }
    </script>


<style type="text/css">
    .item-section{border: 0; padding: 0 0 0 80px; background: #E6E6FA}
    .item-section table{width: 100%; border: 0;}
    .item-section table tr{ border: 0;}
    .item-section table tr td{font-weight: bold; vertical-align: middle; border: 0;}
    .item-section table tr td input{width: 50px;}

    #totalItemsConsumption .main-div{float: left; width: 100%; line-height: 25px;}
    #totalItemsConsumption .left-side{float: left; font-weight: bold; padding: 0 10px 0 0;}
    #totalItemsConsumption .right-side{float: left; font-weight: bold; padding: 0 0 0 10px;}

    .item-no{padding: 4px 10px; float: left; font-weight: bold; color: #4ba6db; width: 20px;}


</style>