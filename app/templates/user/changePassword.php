<?php
$gTitle = '修改密码';
include tpl ('header');
?>
<form method="post" class="form-horizontal form" action="<?=url('user/changePassword')?>">
    <div class="form-group">
        <label for="username" class="col-sm-2 control-label">用户名</label>
        <div class="col-sm-4">
            <p class="form-control-static"><?=$user['username']?></p>
        </div>
    </div>
    <div class="form-group">
        <label for="newPassword" class="col-sm-2 control-label">密码</label>
        <div class="col-sm-4">
            <input type="password" class="form-control" name="newPassword" id="newPassword" />
        </div>
    </div>
    <div class="form-group">
        <label for="confirmPassword" class="col-sm-2 control-label">再次输入密码</label>
        <div class="col-sm-4">
            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <input type="hidden" id="id" name="id" value="<?=$user['id']?>" />
            <input id="submitBtn" type="submit" value="<?=SUBMIT?>" class="btn btn-success" />
            <input type="button" onclick="javascript:history.back();" value="<?=CANCEL?>" class="btn btn-default" />
        </div>
	</div>
</form>
<?php include tpl('footer');?>