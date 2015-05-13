<?php 
$change = isset($_GET['id']) ? true : false;
$gTitle = $change ? '修改角色' : '添加角色';
include tpl('header');
?>
<style>
    fieldset {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px dotted #ccc;
    }
    legend {
        background: #fff;
        color: #777;
        height: 20px;
        line-height: 18px;
        padding: 2px 8px;
        font-size: 12px;
        border: 0;
        width: auto;
    }
    .resource {
        font-size: 14px;
    }
    .checkbox-inline {
        padding-top: 0 !important;
    }
</style>
<form id="addRole" class="form form-horizontal" action="<?=$change ? url('permission/changeRole') : url('permission/addRole')?>">
    <div class="form-group">
        <label for="role" class="col-sm-2 control-label">角色名称</label>
        <div class="col-sm-4">
            <input type="text" name="role" id="role" class="form-control" value="<?=$role['role']?>" required="required" />
        </div>
    </div>
    <div class="form-group">
        <label for="descr" class="col-sm-2 control-label">角色描述</label>
        <div class="col-sm-4">
            <input type="text" name="descr" class="form-control" id="descr" value="<?=$role['descr']?>" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10 ludo-tabs">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#function" data-toggle="tab">功能模块</a></li>
                <li><a href="#menu" data-toggle="tab">菜单</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="function">
                    <?php foreach ($modulePermissions as $resource => $operations) {?>
                        <fieldset class="permission">
                            <legend>
                                <div class="resource">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="checkAll" />
                                        <?=$resource?>
                                    </label>
                                </div>
                            </legend>
                            <div class="operation">
                                <?php foreach ($operations as $operation => $operationId) {?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="permission[<?=$operationId['id']?>]" <?=($operationId['checked']) ? 'checked' : ''?>  class="checkOne" />
                                        <?=$operation?>
                                    </label>
                                <?php }?>
                            </div>
                        </fieldset>
                    <?php }?>
                </div>
                <div class="tab-pane" id="menu">
                    <?php foreach ($menuPermissions as $resource => $resourceId) {?>
                        <fieldset class="permission">
                            <legend>
                                <div class="resource">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="checkAll" name="permission[<?=$resourceId['id']?>]" <?=($resourceId['checked']) ? 'checked' : ''?> />
                                        <?=$resource?>
                                    </label>
                                </div>
                            </legend>
                            <div class="operation">
                                <?php foreach ($subMenuPermissions[$resource] as $operation => $operationId) {?>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="permission[<?=$operationId['id']?>]" <?=($operationId['checked']) ? 'checked' : ''?>  class="checkOne" />
                                        <?=$operation?>
                                    </label>
                                <?php }?>
                            </div>
                        </fieldset>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" id="id" name="id" value="<?=$role['id']?>" />
            <button type="submit" class="btn btn-success"><?=SUBMIT?></button>
            <input type="hidden" name="_token" value="<?=csrf_token()?>" />
            <a href="javascript:history.go(-1);" class="btn btn-default"><?=CANCEL?></a>
        </div>
    </div>
</form>
<?php View::startJs();?>
<script type="text/javascript">
$(document).ready(function(){
    $(".checkAll").click(function(){
        if ($(this).prop("checked")) {
            $(this).closest('.permission').find(".checkOne").prop("checked", true);
        } else {
            $(this).closest('.permission').find(".checkOne").prop("checked", false);
        }
    });
});
</script>
<?php View::endJs();?>
<?php include tpl('footer');?>