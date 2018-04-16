<div class="row-fluid sortable ui-sortable">
    <div class="box span12" style="margin: 0">
        <div class="box-header well" data-original-title="">
            <h2><i class="icon-edit"></i> Form Elements</h2>

            <div class="box-icon">
                <a href="javascript:closeAjax()" class="btn btn-close btn-round"><i class="icon-remove"></i></a>

            </div>
        </div>
        <div class="box-content">
            <form action="<?=$controllerName.$submitPath?>" method="post" id="my-form" style="padding: 20px 0 0 0" onsubmit="return formValidation(this.id)" class="form-horizontal" >
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">First Name : </label>

                        <div class="controls">
                            <input type="text" name="firstName" id="firstName" value="<?=(isset($data->firstName) ? $data->firstName:'')?>" class="input-xlarge focused [c-e]" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Last Name : </label>

                        <div class="controls">
                            <input type="text" name="lastName" id="lastName" value="<?=(isset($data->lastName) ? $data->lastName:'')?>" class="input-xlarge focused [c-e]" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Company Name : </label>

                        <div class="controls">
                            <input type="text" name="company_name" id="company_name" value="<?=(isset($data->company_name) ? $data->company_name:'')?>" class="input-xlarge focused [c-e]" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">User Name : </label>

                        <div class="controls">
                            <input type="text" name="username" id="username" value="<?=(isset($data->username) ? $data->username:'')?>" class="input-xlarge focused [c-e]" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Password : </label>

                        <div class="controls">
                            <input type="text" name="password" id="password" value="<?=(isset($data->password) ? $obj->decrypt($data->password, $data->salt):'')?>" class="input-xlarge focused [c-e]" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Per Page Rows : </label>

                        <div class="controls">
                            <input type="text" name="per_page_rows" id="per_page_rows" value="<?=(isset($data->per_page_rows) ? $data->per_page_rows:'')?>" class="input-xlarge focused [c-e]" />
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <?
                            $submitBtnText = 'Save';
                            if($submitPath == '/update_')
                            {
                                $submitBtnText = 'Update';
                                ?>
                                    <input type="hidden" name="id" id="id" value="<?=(isset($data->id) ? $data->id:'')?>"/>
                                <?
                            }
                        ?>
                        <button type="submit" class="btn btn-primary"><?=$submitBtnText?></button>
                        <button class="btn" type="button" onclick="closeAjax()">Back</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
    <!--/span-->

</div>