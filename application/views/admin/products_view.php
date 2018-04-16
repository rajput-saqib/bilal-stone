<div class="row-fluid sortable ui-sortable">
    <div class="box span12" style="margin: 0">
        <div class="box-header well" data-original-title="">
            <h2><i class="icon-edit"></i> Form Elements</h2>

            <div class="box-icon">
                <a href="javascript:closeAjax()" class="btn btn-close btn-round"><i class="icon-remove"></i></a>

            </div>
        </div>
        <div class="box-content">
            <form action="<?= ADMIN_URL.$controllerName . $submitPath ?>" name="my-form" method="post" id="my-form" style="padding: 20px 0 0 0"
                  onsubmit="return formValidation(this.id)" class="form-horizontal">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">Product Name : </label>

                        <div class="controls">
                            <input type="text" name="name" id="name"
                                   value="<?= (isset($data->name) ? $data->name : '') ?>"
                                   class="input-xlarge focused [c-e]"/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Decription : </label>

                        <div class="controls">
                            <input type="text" name="desc" id="desc"
                                   value="<?= (isset($data->desc) ? $data->desc : '') ?>" class="input-xlarge focused"/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Item : </label>

                        <div class="controls" id="add-more-container">
                            <?
                                $items = $obj->executeQuery("SELECT `id`, `name` FROM `items` WHERE `status` != '-1'")->result();
                                if (isset($data))
                                {
                                    for ($a = 0; $a < count($data2); $a++)
                                    {
                                        ?>
                                            <div class="add-more" id="add-more-<?= $a ?>">

                                                <select name="itemType[]" id="itemType-0" class="[c-e] itemType">
                                                    <?=$obj->selectBox_array(array('stone' => 'Stone', 'bit' => 'Bit', 'sticker_9' => 'sticker_9', 'sticker_12' => 'sticker_12'), (isset($data2[$a]->itemType) ? $data2[$a]->itemType : ''))?>
                                                </select>

                                                <select name="items[]" id="items-<?= $a ?>" style="display: none">
                                                    <?= $obj->selectBox_db('id', 'name', $items, (isset($data2[$a]->item_id) ? $data2[$a]->item_id : '')) ?>
                                                </select>

                                                <input type="text" name="qty[]" id="qty-<?= $a ?>" value="<?= (isset($data2[$a]->quantity) ? $data2[$a]->quantity : '') ?>" class="input-xlarge focused [c-e] qty" style="width: 5%"/>

                                                <? if ($a == 0)
                                                {
                                                    ?><span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('products')"> </span>
                                                <?
                                                }
                                                else
                                                {
                                                    ?><span class="icon32 icon-color add-more-btn icon-close" onclick="removeRow('add-more-<?= $a ?>')"></span>
                                                <?
                                                } ?>
                                        </div><?
                                    }
                                }
                                else
                                {
                                    ?>
                                    <div class="add-more" id="add-more-0">
                                        <select name="itemType[]" id="itemType-0" class="[c-e] itemType">
                                            <?=$obj->selectBox_array(array('stone' => 'Stone', 'bit' => 'Bit', 'sticker_9' => 'sticker_9', 'sticker_12' => 'sticker_12'), (isset($data2[$a]->itemType) ? $data2[$a]->itemType : ''))?>
                                        </select>

                                        <select name="items[]" id="items-0" style="display: none">
                                            <?= $obj->selectBox_db('id', 'name', $items, (isset($data->userId) ? $data->userId : '')) ?>
                                        </select>
                                        <input type="text" name="qty[]" id="qty-0" value="<?= (isset($data->desc) ? $data->desc : '') ?>" class="input-xlarge focused [c-e] qty" style="width: 7%" placeholder="Quantity"/>
                                        <span class="icon32 icon-color icon-plus add-more-btn" id="add-more-btn" onclick="addMore('products')"></span>
                                    </div>
                                <? } ?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <?
                            $submitBtnText = 'Save';
                            if ($submitPath == '/update_')
                            {
                                $submitBtnText = 'Update';
                                ?>
                                <input type="hidden" name="id" id="id"
                                       value="<?= (isset($data->id) ? $data->id : '') ?>"/>
                                <?
                            }
                        ?>
                        <button type="submit" class="btn btn-primary"><?= $submitBtnText ?></button>
                        <button class="btn" type="button" onclick="closeAjax()">Back</button>
                        &nbsp; &nbsp; &nbsp; &nbsp;
                        <button type="button" class="btn btn-primary" onclick="saveAndNew()">Save & New</button>

                        <div id="results">123</div>
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


</style>

<script type="text/javascript">

    function saveAndNew(){
        console.log("saveAndNew()");

        var x = $("my-form").serializeArray();
        $.each(x, function(i, field){
            $("#results").append(field.name + ":" + field.value + " ");
        });


        if(formValidation("my-form")) {

            var name = $("#name").val();
            var desc = $("#desc").val();

            var itemType = "";
            $('.itemType').each(function() { itemType += $(this).val()+"|"; });

            var qty = "";
            $('.qty').each(function() { qty += $(this).val()+"|"; });

            $.ajax({
                type : "POST",
                url : "/admin/products/saveAndNew",
                data : "&name=" + name + "&desc=" + desc + "&itemType=" + itemType + "&qty=" + qty,
                success : function (data)
                {
                    if(data == 1) {

                        $("#name").val("");
                        showNotification('Data Insert Successfully.', 'green');

                    }
                }
            });

        }
    }

</script>