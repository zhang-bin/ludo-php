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
    input[type='checkbox'] {
        margin-top: 0px;
    }
    .resource {
        font-size: 14px;
    }
</style>
<div class="row-fluid">
    <form id="addRole" class="form-horizontal form" action="<?=$change ? url('permission/changeRole') : url('permission/addRole')?>">
        <div class="control-group">
            <label class="control-label">角色名称
                <span class='red'>*</span>
            </label>
            <div class="controls">
                <input type="text" name="role" id="role" value="<?=$role['role']?>" required="required" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">角色描述
                <span class='red'>*</span>
            </label>
            <div class="controls">
                <input type="text" name="descr" id="descr" value="<?=$role['descr']?>" />
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
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
                                        <?=$resource?> -- <input type="checkbox" class="checkAll" />
                                    </div>
                                </legend>
                                <div class="operation">
                                    <?php foreach ($operations as $operation => $operationId) {?>
                                        <input type="checkbox" name="permission[<?=$operationId['id']?>]" <?=($operationId['checked']) ? 'checked' : ''?>  class="checkOne" />
                                        &nbsp;
                                        <span><?=$operation?></span>
                                        &emsp;
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
                                        <?=$resource?> -- <input type="checkbox" class="checkAll" name="permission[<?=$resourceId['id']?>]" <?=($resourceId['checked']) ? 'checked' : ''?> />
                                    </div>
                                </legend>
                                <div class="operation">
                                    <?php foreach ($subMenuPermissions[$resource] as $operation => $operationId) {?>
                                        <input type="checkbox" name="permission[<?=$operationId['id']?>]" <?=($operationId['checked']) ? 'checked' : ''?>  class="checkOne" />
                                        &nbsp;
                                        <span><?=$operation?></span>
                                        &emsp;
                                    <?php }?>
                                </div>
                            </fieldset>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <input type="hidden" id="id" name="id" value="<?=$role['id']?>" />
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="javascript:history.go(-1);" class="btn">返回</a>
        </div>
    </form>
</div>
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