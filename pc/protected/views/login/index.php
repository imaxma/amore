<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>系统登录</title>
    <link href="<?php echo Yii::app()->request->baseUrl;?>/assets/index/css/login.css" rel='stylesheet' type='text/css' />
    <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/assets/index/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $(".screenbg ul li").each(function(){
                $(this).css("opacity","0");
            });
            $(".screenbg ul li:first").css("opacity","1");
            var index = 0;
            var t;
            var li = $(".screenbg ul li");
            var number = li.size();
            function change(index){
                li.css("visibility","visible");
                li.eq(index).siblings().animate({opacity:0},3000);
                li.eq(index).animate({opacity:1},3000);
            }
            function show(){
                index = index + 1;
                if(index<=number-1){
                    change(index);
                }else{
                    index = 0;
                    change(index);
                }
            }
            t = setInterval(show,8000);
            //根据窗口宽度生成图片宽度
            var width = $(window).width();
            $(".screenbg ul img").css("width",width+"px");
        });
    </script>

</head>

<body>

<div class="login-box">
    <h1>Amore系统后台登录</h1>
    <?php
    $form = $this->beginWidget('CActiveForm',array(
            'id'=>'login',
            'enableAjaxValidation' =>true,
            'enableClientValidation'=>true,
            'clientOptions'=>array(
                'validateOnSubmit'=>true,     //提交时的验证
                'validateOnChange'=>true,     //输入框值改变时的验证
            )
        )
    );
    ?>
        <div class="name">
            <label>手  机：</label>
<!--            <input type="text" name="" id="" tabindex="1" autocomplete="off" />-->
            <?php echo $form->textField($model,'phone',array('maxlength'=>'32','placeholder'=>'phone','tabindex'=>'1'));?>
            <?php echo $form->error($model,'phone'); ?>
        </div>
        <div class="password">
            <label>密  码：</label>
<!--            <input type="password" name="" maxlength="16" id="" tabindex="2"/>-->
            <?php echo $form -> passwordField($model,'password',array('class'=>'logininput','placeholder'=>'密码','autocomplete'=>'off','tabindex'=>'2')); ?>
            <?php echo $form->error($model,'password'); ?>
        </div>
        <div class="remember">
            <?php echo$form->checkBox($model,'rememberMe',array('checked'=>'checked','tabindex'=>'4'));?>
            <label>记住密码</label>
        </div>
        <div class="login">
            <button type="submit" tabindex="5">登录</button>
        </div>
    <?php $this->endWidget();?>
</div>
<div class="screenbg">
    <ul>
        <li><a href="javascript:;"><img src="<?php echo Yii::app()->request->baseUrl;?>/assets/index/images/0.jpg"></a></li>
    </ul>
</div>

</body>
</html>
