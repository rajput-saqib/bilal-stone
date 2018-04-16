<div class="row-fluid sortable ui-sortable">
    <div class="box span12" style="margin: 0">
        <div class="box-header well" data-original-title="">
            <h2><i class="icon-edit"></i> Form Elements</h2>

            <div class="box-icon">
                <a href="javascript:closeAjax()" class="btn btn-close btn-round"><i class="icon-remove"></i></a>

            </div>
        </div>
        <div class="box-content">

            <form action="<?= ADMIN_URL.$controllerName . $submitPath ?>" method="post" id="my-form" style="padding: 20px 0 0 0" onsubmit="return formValidation('my-form')" class="form-horizontal">
                <fieldset>

                    <div class="control-group" style="margin: 0">
                        <div class="control-group span6">
                            <label class="control-label">POI No : </label>
                            <div class="controls">
                                <input type="text" name="reference_no" id="reference_no" value="<?= (isset($data->reference_no) ? $data->reference_no : '') ?>" class="input-xlarge focused [c-e]"/>
                            </div>
                        </div>
                        <div class="control-group span6">
                            <label class="control-label">Date : </label>
                            <div class="controls">
                                <input type="text" name="date" id="date" value="<?= (isset($data->date) ? $obj->showDate($data->date) : date('m/d/Y')) ?>" class="input-xlarge datepicker  focused [c-e]"/>
                            </div>
                        </div>
                    </div>

                    <div class="control-group" style="margin: 0">
                        <div class="control-group span6">
                            <label class="control-label">Vendor Name : </label>
                            <div class="controls">
                                <select name="client_id" id="client_id" class="chosen [c-e]">
                                    <?= $obj->selectBox_db('id', 'name', $selectBox_clients, (isset($data->client_id) ? $data->client_id : '')) ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group span6">
                            <label class="control-label">Time : </label>
                            <div class="controls">
                                <input type="text" name="time" id="time" value="<?= (isset($data->time) ? date('H:i', $data->time) : date('H:i', time())) ?>" class="input-xlarge focused"/>
                            </div>
                        </div>
                    </div>

                    <div class="control-group">
                            <label class="control-label">&nbsp;</label>

                            <div class="controls">
                                <div class="box-content">
                                    <table class="table" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 48%">Product</th>
                                                <th style="width: 15%">Quantity</th>
                                                <th style="width: 15%">Rate</th>
                                                <th style="width: 20%">Amount</th>
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
                                                            <td colspan="4" style="padding: 0; margin: 0; border: 0">
                                                                <table style="width: 100%;">
                                                                    <tr>
                                                                        <td>
                                                                            <div class="controls" style="margin: 0">
                                                                                <span id="product-no-0" class="product-no"><?=($a+1)?></span>
                                                                                <select name="product[]" id="product-<?=$a?>" class="[c-e] chosen" onchange="addItems(this.value, '<?=$a?>')">
                                                                                    <?= $obj->selectBox_db('id', 'name', $selectBox_products, (isset($data2[$a]->product_id) ? $data2[$a]->product_id : '')) ?>
                                                                                </select>
                                                                            </div>
                                                                        </td>

                                                                        <td style="width: 16%">
                                                                            <input type="text" name="qty[]" id="qty-<?=$a?>" value="<?= (isset($data2[$a]->qty) ? $data2[$a]->qty : '') ?>" class="input-xlarge focused [c-e] custom-width" onkeyup="calculateAmount('<?=$a?>')"/>
                                                                        </td>

                                                                        <td style="width: 16%">
                                                                            <input type="text" name="rate[]" id="rate-<?=$a?>" value="<?= (isset($data2[$a]->rate) ? $data2[$a]->rate : '') ?>" class="input-xlarge focused [c-e] custom-width" onkeyup="calculateAmount('<?=$a?>')"/>
                                                                        </td>

                                                                        <td style="width: 20%">
                                                                            <input type="text" name="price[]" id="price-<?=$a?>" value="<?= (isset($data2[$a]->price) ? $data2[$a]->price : '') ?>" class="input-xlarge focused [c-e] custom-width"/>
                                                                            <? if ($a == 0)
                                                                            {
                                                                                ?><span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('send-item-received')"> </span>
                                                                            <?
                                                                            }
                                                                            else
                                                                            {
                                                                                ?><span class="icon32 icon-color add-more-btn icon-close" onclick="removeInvoiceRow('<?= $a ?>')"></span>
                                                                            <?
                                                                            } ?>
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td colspan="4" id="item-section-<?=$a?>" class="item-section">

                                                                        <?
                                                                            if(count($data3) > 0)
                                                                            {
                                                                                ?><table><?

                                                                                $itemDetail = $data3[$data2[$a]->id];
                                                                                $rowNo = $a;

                                                                                for($b = 0 ; $b < count($itemDetail) ; $b++)
                                                                                {
                                                                                    ?>
                                                                                    <tr class="product-contain-items-<?=$rowNo?>">
                                                                                        <td>
                                                                                            <span style="width: 100px; float: left"> Qty ( <?=ucfirst($itemDetail[$b]['itemType'])?> ) </span>
                                                                                            <input type="text" value="<?=$itemDetail[$b]['itemInProduct']?>" name="item-qty-<?=$rowNo?>[]" id="item-qty-<?=$rowNo?>-<?=$b?>" readonly style="width: 60px"/>
                                                                                        </td>
                                                                                        <td>
                                                                                            <span>Total : </span>
                                                                                            <input type="text" value="<?=($itemDetail[$b]['itemInProduct']*$data2[$a]->qty)?>" name="total-<?=$rowNo?>[]" id="total-<?=$rowNo?>-<?=$b?>" style="width: 60px" readonly/>
                                                                                        </td>
                                                                                        <td>
                                                                                            <input type="text" value="<?=$itemDetail[$b]['type']?>" name="type-<?=$rowNo?>[]" id="type-<?=$rowNo?>-<?=$b?>" style="width: 60px" placeholder="Type"/>
                                                                                        </td>
                                                                                        <td style="width: 270px;">
                                                                                            <span style="margin: 3px 6px 0 0; float: left;">Item : </span>
                                                                                            <select name="item-<?=$rowNo?>[]" id="item-<?=$rowNo?>-<?=$b?>" onchange="getTotalBags(this.value, '<?=$rowNo?>', '<?=$b?>')" class="[c-e] chosen">
                                                                                                <?= $obj->selectBox_db('id', 'name', $selectBox_items, $itemDetail[$b]['item_id']) ?>
                                                                                            </select>
                                                                                            <span id="loading-<?=$rowNo?>-<?=$b?>" style="width: 20px"></span>
                                                                                        </td>
                                                                                        <td>
                                                                                            <span>Bags : </span>
                                                                                            <input type="hidden" name="qty-per-bag-<?=$rowNo?>[]" id="qty-per-bag-<?=$rowNo?>-<?=$b?>" value="<?=$itemDetail[$b]['qty_per_bag']?>" />
                                                                                            <input type="text" name="total-bags-<?=$rowNo?>[]" id="total-bags-<?=$rowNo?>-<?=$b?>" readonly value="<?=number_format((($itemDetail[$b]['itemInProduct']*$data2[$a]->qty)/$itemDetail[$b]['qty_per_bag']), 4)?>" style="width: 60px"/>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?
                                                                                }
                                                                            }                                                                            ?>
                                                                            <input type="hidden" name="item-length-<?=$rowNo?>" id="item-length-<?=$rowNo?>" value="<?=count($itemDetail)?>"/>
                                                                        </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?
                                                }
                                            }
                                            else
                                            {
                                                ?>
                                                    <tr class="add-more" id="add-more-0">
                                                        <td colspan="4" style="padding: 0; margin: 0; border: 0">
                                                            <table style="width: 100%">
                                                                <tr>
                                                                    <td>
                                                                        <div class="controls" style="margin: 0">
                                                                            <span id="product-no-0" class="product-no">1</span>
                                                                            <select name="product[]" id="product-0" class="[c-e] chosen" onchange="addItems(this.value, 0)">
                                                                                <?= $obj->selectBox_db('id', 'name', $selectBox_products) ?>
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                    <td style="width: 16%"><input type="text" name="qty[]" id="qty-0" value="" class="input-xlarge focused [c-e] custom-width" onkeyup="calculateAmount('0')"/></td>
                                                                    <td style="width: 16%"><input type="text" name="rate[]" id="rate-0" value="" class="input-xlarge focused [c-e] custom-width" onkeyup="calculateAmount('0')"/></td>
                                                                    <td style="width: 20%">
                                                                        <input type="text" name="price[]" id="price-0" value="" class="input-xlarge focused [c-e] custom-width"/>
                                                                        <span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('send-item-received');"> </span>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td colspan="4" style="border: 0; display: none" id="item-section-0" class="item-section"></td>
                                                                </tr>
                                                            </table>
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
                       <div class="control-group span6" style="padding-left: 100px;">
                           <div class="controls">
                               <span>Total Amount :</span>
                               <input type="text" name="total_amount" id="total_amount" value="<?= (isset($data->total_amount) ? $data->total_amount : '') ?>" class="input-xlarge custom-width focused [c-e]"/>
                           </div>
                       </div>
                    </div>

                    <div class="control-group">
                        <div class="control-group" style="margin: 0 0 0 14.5%;" id="totalItemsConsumption"></div>
                    </div>

                    <div class="form-actions">
                        <div style="float: right"><span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('send-item-received')"> </span></div>
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

            <div style="display: none">
                <table>
                    <tr id="add-more-xx" style="display: none">
                        <td colspan="4" style="padding: 0; margin: 0; border: 0">
                            <table style="width: 100%">
                                <tr>
                                    <td>
                                        <div class="controls" style="margin: 0">
                                            <span id="product-no-xx" class="product-no">1</span>
                                            <select name="product[]" id="product-xx" class="[c-e]" onchange="addItems(this.value, 0)">
                                                <?= $obj->selectBox_db('id', 'name', $selectBox_products) ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td style="width: 16%"><input type="text" name="qty[]" id="qty-xx" value="" class="input-xlarge focused [c-e] custom-width" onkeyup="calculateAmount('0')"/></td>
                                    <td style="width: 16%"><input type="text" name="rate[]" id="rate-xx" value="" class="input-xlarge focused [c-e] custom-width" onkeyup="calculateAmount('0')"/></td>
                                    <td style="width: 20%">
                                        <input type="text" name="price[]" id="price-xx" value="" class="input-xlarge focused [c-e] custom-width"/>
                                        <span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('send-item-received');"> </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="4" style="border: 0; display: none" id="item-section-xx" class="item-section"></td>
                                </tr>
                            </table>
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

        function calculateAmount(no)
        {
            var rate = parseFloat($('#rate-'+no).val());
            var qty = parseInt($('#qty-'+no).val());
            if(qty > 0 && rate > 0) {
                $('#price-'+no).val((rate*qty).toFixed(2))
            }

            if(qty > 0)
            {
                var itemLength = $('.product-contain-items-'+no).length;
                for(var x = 0 ; x < itemLength ; x++)
                {
                    var itemQty = parseInt($("#item-qty-"+no+"-"+x).val());
                    var totalQty = (itemQty*qty).toFixed(0);
                    $("#total-"+no+"-"+x).val(totalQty);

                    var perBag = parseInt($("#qty-per-bag-"+no+"-"+x).val());
                    if(perBag > 0)
                    {
                        $("#total-bags-"+no+'-'+x).val((totalQty/perBag).toFixed(2));
                    }
                }
            }

            var total_amount = 0;
            var length = $('.add-more').length;

            for(var a = 0 ; a < length ; a++)
            {
                var valueP = parseInt($('#price-'+a).val());
                if(valueP > 0) {
                    total_amount += valueP;
                }
            }

            $('#total_amount').val(total_amount);

            showAllItemDetail();
        }

        function showAllItemDetail()
        {
            var distinctItems = {};

            for (var no = 0 ; no < parseInt($('.add-more').length) ; no++) {
                var itemLength = $('.product-contain-items-' + no).length;

                for (var x = 0 ; x < itemLength ; x++) {

                    if(parseInt($("#item-" + no + "-" + x + " option:selected").val()) > 0) {
                        var itemName = $("#item-" + no + "-" + x + " option:selected").text();
                        var totalBags = parseFloat($("#total-bags-" + no + "-" + x).val());

                        if (itemName in distinctItems) {
                            distinctItems[itemName] += totalBags;
                        }
                        else {
                            distinctItems[itemName] = totalBags;
                        }
                    }
                }
            }

            var html = "";
            $.each( distinctItems, function( key, value ) {
                html += "<div class='main-div'><span class='left-side'>"+key+"</span> <span class='right-side'>("+value.toFixed(2)+" Bags)</span></div>";
            });

           var totalSheet = parseInt(0);
           var length = $('.add-more').length;

            for(var a = 0 ; a < length ; a++)
            {
                var valueQ = parseInt($('#qty-'+a).val());
                if(valueQ > 0) {
                    totalSheet += valueQ;
                }
            }

            if(totalSheet > 0) {
                html += "<div class='main-div'><span class='left-side'>Total Sheets </span><span class='right-side'>"+totalSheet+"</span></div>";
            }
            
            $("#totalItemsConsumption").html(html);
        }

        function addItems(productId, rowNo)
        {
            $("#item-section-"+rowNo).html('<img src="../assets/admin/img/ajax-loaders/ajax-loader-9.gif">').show();

            if(productId > 0)
            {
                var check = true;
                for (var no = 0 ; no < parseInt($('.add-more').length) ; no++)
                {
                    //console.log($("#product-"+no).val()+" == "+productId);
                    if($("#product-"+no).val() == productId && rowNo != no)
                    {
                        $("#product-"+rowNo).addClass('error-border');
                        $("#item-section-"+rowNo).hide();
                        alert("Product Already Select in This Invoice.");
                        check = false;
                        break;
                    }
                }

                $("#product-"+rowNo).removeClass('error-border');

                if(check){
                    $.ajax({
                        url : '<?=ADMIN_URL.$controllerName?>/getItems',
                        type : 'POST',
                        data : {'productId':productId, 'rowNo':rowNo},
                        cache : false,
                        success : function (resp)
                        {
                            $("#item-section-"+rowNo).html(resp);
                            setChosen();
                        },
                        error : function (e)
                        {
                            console.log(e.message);
                            console.log(e);
                        }
                    });
                }
            }
            else
            {
                $("#item-section-"+rowNo).hide();
            }
        }

        function getTotalBags(itemId, rowNo, itemNo)
        {
            $("#loading-"+rowNo+'-'+itemNo).html('<img src="../assets/admin/img/ajax-loaders/ajax-loader-1.gif">').show();

            if(itemId > 0)
            {
                var total = $("#total-"+rowNo+'-'+itemNo).val();

                $.ajax({
                    url : '<?=ADMIN_URL.$controllerName?>/getTotalBags',
                    type : 'POST',
                    data : {'itemId':itemId, 'rowNo':rowNo, 'itemNo':itemNo, 'total':total},
                    cache : false,
                    success : function (response)
                    {
                        $("#loading-"+rowNo+'-'+itemNo).hide();
                        var resp = $.parseJSON(response);

                        $("#total-bags-"+rowNo+'-'+itemNo).val(resp.totalBags);
                        $("#qty-per-bag-"+rowNo+'-'+itemNo).val(resp.perBag);

                        showAllItemDetail();
                    },
                    error : function (e)
                    {
                        console.log(e.message);
                        console.log(e);
                    }
                });
            }
            else
            {
                $("#item-section-"+rowNo).hide();
            }
        }

        function removeInvoiceRow(no)
        {
            $('#add-more-'+no).find('#product-'+no).removeClass('[c-e]').val('');
            $('#add-more-'+no).find('#qty-'+no).removeClass('[c-e]').val(0);
            $('#add-more-'+no).find('#rate-'+no).removeClass('[c-e]').val(0);
            $('#add-more-'+no).find('#price-'+no).removeClass('[c-e]').val(0);
            $('#add-more-'+no).find('.chosen').removeClass('[c-e]').val(0);
            $('#add-more-'+no).hide();

            calculateAmount();
        }

        function setChosen()
        {
            $('.chosen').chosen();
            $('.chzn-container').css("width", "200px").css("font-weight", "normal");
        }

        $(document).ready(function(){
            setChosen();
            showAllItemDetail();
        });

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

    .product-no{padding: 4px 10px; float: left; font-weight: bold; color: #4ba6db; width: 20px;}


</style>