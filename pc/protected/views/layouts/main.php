<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>Amore</title>
    <!-- Bootstrap -->
    <link href="<?php echo Yii::app()->request->baseUrl;?>/assets/index/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl;?>/assets/index/css/base.css" rel="stylesheet">

    <script src="<?php echo Yii::app()->request->baseUrl;?>/assets/index/js/jquery-1.8.2.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl;?>/assets/index/js/bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl;?>/assets/index/layer/layer.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="<?php echo Yii::app()->request->baseUrl;?>/assets/index/js/html5shiv.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl;?>/assets/index/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php echo $content; ?>
    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo Yii::app()->user->returnUrl;?>index">
                    <img alt="Amore" src="...">
                </a>
            </div>
            <p class="navbar-text navbar-right"> Copyright © 2015 Amore 版权所有</p>
        </div>
</nav>
</body>
</html>