<div class="row-fluid sortable ui-sortable">
    <div class="box span12" style="margin: 0">
        <div class="box-header well" data-original-title="">
            <h2><i class="icon-edit"></i> Form Elements</h2>

            <div class="box-icon">
                <a href="javascript:closeAjax()" class="btn btn-close btn-round"><i class="icon-remove"></i></a>

            </div>
        </div>
        <div class="box-content">
            <form action="<?=ADMIN_URL.$controllerName.$submitPath?>" method="post" id="my-form" style="padding: 20px 0 0 0" onsubmit="return formValidation(this.id)" class="form-horizontal" >
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">User Name : </label>

                        <div class="controls">
                            <input type="text" name="username" id="username" class="input-xlarge focused [c-e]" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Password : </label>

                        <div class="controls">
                            <input type="password" name="password" id="password" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="form-actions">
                        <? $submitBtnText = 'Save'; ?>
                        <button type="submit" class="btn btn-primary" onclick="closeAjax()"><?=$submitBtnText?></button>
                        <button class="btn" type="button" onclick="closeAjax()">Back</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
    <!--/span-->

</div>