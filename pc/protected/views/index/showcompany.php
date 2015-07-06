<style>
    .amore-tr {height:30px; line-height: 30px;  font-size: 12px;}
    .amore-tr th { height:20px; line-height: 20px !important; text-align: center;}
    .amore-tr td { height:30px; line-height: 30px !important; text-align: center;}

    .amore-search { margin-bottom: 20px; padding: 0px;}
</style>
<div class="row">
    <nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo Yii::app()->user->returnUrl;?>index">Amore</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo Yii::app()->user->returnUrl;?>index/customs">通关进度 <span class="sr-only">(current)</span></a></li>
                    <li class="active"><a href="<?php echo Yii::app()->user->returnUrl;?>index/company">报关公司管理</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="<?php echo Yii::app()->user->name;?>"><?php echo Yii::app()->session['name'];?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo Yii::app()->user->returnUrl;?>login/Logout">退出</a></li>
                            <li><a href="<?php echo Yii::app()->user->returnUrl;?>index/updatepassword">修改密码</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</div><br>
<div class="row">
    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr class="active amore-tr">
                    <th><input type="checkbox"></th>
                    <th>报送公司</th>
                    <th>姓名</th>
                    <th>Email</th>
                    <th>手机号码</th>
                    <th>密码</th>
                    <th>最后一次登录</th>
                    <th>最后一次短信</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($result))
            {
                foreach ($result as $val)
                {
            ?>
                    <tr class="amore-tr" id="<?php echo $val['ID'];?>">
                        <td><input type="checkbox"></td>
                        <td><?php echo $val['Company_name'];?></td>
                        <td><?php echo $val['Name'];?></td>
                        <td><?php echo $val['Email'];?></td>
                        <td><?php echo $val['Phone'];?></td>
                        <td><?php echo $val['Password'];?></td>
                        <td><?php echo $val['LastLoginTime'];?></td>
                        <td><?php echo $val['LastSMTime'];?></td>
                        <td>
                            <a class="btn btn-default" href="javascript:void(0);" role="button" id="reset" data-id="<?php echo $val['ID'];?>">密码重置</a>
                            <a class="btn btn-default operator_del" href="javascript:void(0);" role="button" data-id="<?php echo $val['ID'];?>">删除</a>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $('#reset').on('click',function(){
        var id = $(this).attr('data-id');
        $.post('<?php echo Yii::app()->user->returnUrl;?>index/ResetPassword',{id:id},function(data){
            if (data.status == 'success')
            {
                layer.msg('重置成功!');
            }
            else
            {
                layer.msg('重置失败!');
            }
        },'json');
    });

    $('.operator_del').on('click',function(){
        var deleteEnable = confirm("确认删除？");
        if(deleteEnable) {
            var id = $(this).attr('data-id');
            $.post('<?php echo Yii::app()->user->returnUrl;?>index/DelOperator', {id: id}, function (data) {
                if (data == 'success') {
                    layer.msg('删除成功!');
                    $('#' + id).hide();
                }
                else {
                    layer.msg('删除失败!');
                }
            });
        }
    });
</script>
