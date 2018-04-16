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
                        <label class="control-label">User Name : </label>
                        <? $users = $obj->executeQuery("SELECT `id`, CONCAT(firstName,' ',lastName, ' (', email,')') 'username' FROM (`users`) WHERE `userType` = '2' AND STATUS != '-1'")->result();?>
                        <div class="controls">
                            <select name="userId" id="userId" class="[c-e]">
                                <?=$obj->selectBox_db('id', 'username', $users, (isset($data->userId) ? $data->userId:''))?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Name : </label>

                        <div class="controls">
                            <input type="text" name="name" id="name" value="<?=(isset($data->name) ? $data->name:'')?>" class="input-xlarge focused [c-e]" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Address : </label>

                        <div class="controls">
                            <input type="text" name="address" id="address" value="<?=(isset($data->address) ? $data->address:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">City : </label>

                        <? $cities = $obj->select("id, name", 'cities', "STATUS != '-1'")->result();?>
                        <div class="controls">
                            <select name="cityId" id="cityId" class="[c-e]">
                                <?=$obj->selectBox_db('id', 'name', $cities, (isset($data->cityId) ? $data->cityId:''))?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Longitude : </label>

                        <div class="controls">
                            <input type="text" name="longitude" id="longitude" value="<?=(isset($data->longitude) ? $data->longitude:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Latitude : </label>

                        <div class="controls">
                            <input type="text" name="latitude" id="latitude" value="<?=(isset($data->latitude) ? $data->latitude:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Hall Type : </label>

                        <div class="controls">
                            <select name="hallType" id="hallType">
                                <?=$obj->selectBox_array(array('1'=> 'Banquet', '2'=> 'Open'), (isset($data->hallType) ? $data->hallType:''))?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Capacity : </label>

                        <div class="controls">
                            <input type="text" name="capacity" id="capacity" value="<?=(isset($data->capacity) ? $data->capacity:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Partition : </label>

                        <div class="controls">
                            <input type="text" name="partition" id="partition" value="<?=(isset($data->partition) ? $data->partition:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Parking : </label>

                        <div class="controls">
                            <input type="text" name="parking" id="parking" value="<?=(isset($data->parking) ? $data->parking:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Open Area : </label>

                        <div class="controls">
                            <input type="text" name="openArea" id="openArea" value="<?=(isset($data->openArea) ? $data->openArea:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Ladies Waiter : </label>

                        <div class="controls">
                            <input type="text" name="ladiesWaiter" id="ladiesWaiter" value="<?=(isset($data->ladiesWaiter) ? $data->ladiesWaiter:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Watier Ratio : </label>

                        <div class="controls">
                            <input type="text" name="watierRatio" id="watierRatio" value="<?=(isset($data->watierRatio) ? $data->watierRatio:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Outside Catring : </label>

                        <div class="controls">
                            <input type="text" name="outsideCatring" id="outsideCatring" value="<?=(isset($data->outsideCatring) ? $data->outsideCatring:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Landscape : </label>

                        <div class="controls">
                            <input type="text" name="landscape" id="landscape" value="<?=(isset($data->landscape) ? $data->landscape:'')?>" class="input-xlarge focused" />
                        </div>
                    </div>


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

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><?=$submitBtnText?></button>
                        <button class="btn" type="button" onclick="closeAjax()">Back</button>
                    </div>
                </fieldset>
            </form>

        </div>
    </div>
</div>