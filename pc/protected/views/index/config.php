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
                    <li class="active"><a href="<?php echo Yii::app()->user->returnUrl;?>index/customs">通关进度 <span class="sr-only">(current)</span></a></li>
                    <li><a href="<?php echo Yii::app()->user->returnUrl;?>index/company">报关公司管理</a></li>
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
</div>
<div class="row">
    <div class="container">
        <div class="col-sm-3 col-md-2"></div>
        <div class="col-xs-12 col-sm-6 col-md-8">
            <?php
            if(Yii::app()->user->hasFlash('success'))
            {
                ?>
                <div class="alert alert-success">
                    <i class="icon-lightbulb"></i>
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php
            }
            ?>
            <?php
            if(Yii::app()->user->hasFlash('error'))
            {
                ?>
                <div class="alert alert-info">
                    <i class="icon-lightbulb"></i>
                    <?php echo Yii::app()->user->getFlash('error'); ?>
                </div>
            <?php
            }
            ?>
            <br><br>
            <h5 class="personal-title">帐户基本信息</h5>
            <br><br>
            <?php
            $form = $this->beginWidget('CActiveForm',array(
                'id'=>'setting_info'
            ));
            echo $form->hiddenField($model,'Id',array('value'=>Yii::app()->session['amore_admin_id']));
            ?>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">&emsp;Name </span>
                <input type="text" class="form-control" placeholder="name" aria-describedby="basic-addon1" value="<?php echo Yii::app()->session['name'];?>" disabled>
            </div>
            <br><br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">&emsp;Phone </span>
                <input type="text" class="form-control" placeholder="Phone" aria-describedby="basic-addon1" value="<?php echo Yii::app()->user->name;?>" disabled>
            </div><br><br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">&emsp;旧密码</span>
                <?php echo $form->passwordField($model,'old_password',array('class'=>'form-control','value'=>''));?>
            </div><?php echo $form->error($model,'old_password'); ?><br><br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">&emsp;新密码</span>
                <?php echo $form->passwordField($model,'Password',array('class'=>'form-control','value'=>''));?>
            </div><?php echo $form->error($model,'Password'); ?><br><br>
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1">确认密码</span>
                <?php echo $form->passwordField($model,'password_again',array('class'=>'form-control','value'=>''));?>
            </div><?php echo $form->error($model,'password_again'); ?>
            <br><br>
            <div class="span6 field-box actions">
                <a href="javascript:void(0)" style="float:right; margin-right:100px;" onClick="document.getElementById('setting_info').submit();" class="btn btn-default">保存</a>
            </div>
            <?php
            $form = $this->endWidget();
            ?>
        </div>
        <div class="col-sm-3 col-md-2"></div>
    </div>
</div>