<div class="row-fluid sortable ui-sortable">
    <div class="box span12" style="margin: 0">
        <div class="box-header well" data-original-title="">
            <h2><i class="icon-edit"></i> <?=$obj->createHeading($controllerName)?></h2>

            <div class="box-icon">
                <a href="javascript:closeAjax()" class="btn btn-close btn-round"><i class="icon-remove"></i></a>

            </div>
        </div>
        <div class="box-content">
            <form enctype="multipart/form-data" method="post" id="my-form" action="<?=$controllerName.$submitPath?>" style="padding: 20px 0 0 0" onsubmit="return formValidation(this.id)" class="form-horizontal">
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">Name : </label>

                        <div class="controls">
                            <input type="text" name="name" id="name" value="<?=(isset($data->name) ? $data->name:'')?>" class="input-xlarge focused [c-e]" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Decription : </label>

                        <div class="controls">
                            <input type="text" name="desc" id="desc" value="<?=(isset($data->desc) ? $data->desc:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Image : </label>

                        <div class="controls">
                            <input type="file" name="image" id="image" class="file-upload"/>
                        </div>
                    </div>

                    <?
                        $submitBtnText = 'Save';
                        if($submitPath == '/update_')
                        {
                            $submitBtnText = 'Update';
                            ?>
                                <input type="hidden" name="id" id="id" value="<?=(isset($data->id) ? $data->id:'')?>"/>

                                <div class="control-group">
                                    <label class="control-label">&nbsp;</label>

                                    <div class="controls">
                                        <img src='<?=DOMAIN_URL.$data->image?>' width='100'/>
                                    </div>
                                </div>
                            <?
                        }
                    ?>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><?=$submitBtnText?></button>
                        <button class="btn" type="button" onclick="closeAjax()">Back</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
    <!--/span-->

</div>