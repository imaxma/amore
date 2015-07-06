<style>
    .amore-tr {height:30px; line-height: 30px; font-size: 12px;}
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
    <div class="container amore-search">
        <div class="col-lg-8">
        </div><!-- /.col-lg-6 -->
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="批次编号/类型/报关公司" id="search-val" value="<?php echo isset($_GET['value']) ? $_GET['value'] : '';?>">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button" id="search">查找</button>
          </span>
            </div><!-- /input-group -->
        </div><!-- /.col-lg-6 -->
    </div>
</div><!-- /.row -->
<div class="row">
    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr class="active amore-tr">
                    <th><input type="checkbox"></th>
                    <th>批次编号</th>
                    <th>类型</th>
                    <th>报关公司</th>
                    <th>ETD</th>
                    <th>ETA</th>
                    <th>换单</th>
                    <th>报验</th>
                    <th>审单</th>
                    <th>付税</th>
                    <th>报关</th>
                    <th>查验</th>
                    <th>入库</th>
                    <th>备注</th>
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
                    <tr class="amore-tr" id="<?php echo $val['Id'];?>">
                        <td><input type="checkbox"></td>
                        <td><?php echo $val['BatchNum'];?></td>
                        <td><?php if ($val['Type'] == 'full'){echo '整柜';}elseif ($val['Type'] == 'air'){echo '空运';}else{echo '散货';}?></td>
                        <td><?php echo $val['Company_Name'];?></td>
                        <td><?php echo $val['etd'];?></td>
                        <td><?php echo $val['eta'];?></td>
                        <td <?php echo isset($val['DE']['date']) ? 'class="success"' : '';?>>
                            <?php echo isset($val['DE']['date']) ? $val['DE']['delay'] != "" ? $val['DE']['date'].'<span style="color:#c31a1a;">('.$val['DE']['delay'].')</span>' : $val['DE']['date'] : '';?>
                            <?php echo isset($val['DE']['standard']) ? $val['DE']['standard'] : '';?>
                        </td>
                        <td <?php echo isset($val['AFE']['date']) ? 'class="success"' : '';?>>
                            <?php echo isset($val['AFE']['date']) ? $val['AFE']['delay'] != "" ? $val['AFE']['date'].'<span style="color:#c31a1a;">('.$val['AFE']['delay'].')</span>' : $val['AFE']['date'] : '';?>
                            <?php echo isset($val['AFE']['standard']) ? $val['AFE']['standard'] : '';?>
                        </td>
                        <td <?php echo isset($val['DI']['date']) ? 'class="success"' : '';?>>
                            <?php echo isset($val['DI']['date']) ? $val['DI']['delay'] != "" ? $val['DI']['date'].'<span style="color:#c31a1a;">('.$val['DI']['delay'].')</span>' : $val['DI']['date'] : '';?>
                            <?php echo isset($val['DI']['standard']) ? $val['DI']['standard'] : '';?>
                        </td>
                        <td <?php echo isset($val['TAX']['date']) ? 'class="success"' : '';?>>
                            <?php echo isset($val['TAX']['date']) ? $val['TAX']['delay'] != "" ? $val['TAX']['date'].'<span style="color:#c31a1a;">('.$val['TAX']['delay'].')</span>' : $val['TAX']['date'] : '';?>
                            <?php echo isset($val['TAX']['standard']) ? $val['TAX']['standard'] : '';?>
                        </td>
                        <td <?php echo isset($val['Customs']['date']) ? 'class="success"' : '';?>>
                            <?php echo isset($val['Customs']['date']) ? $val['Customs']['delay'] != "" ? $val['Customs']['date'].'<span style="color:#c31a1a;">('.$val['Customs']['delay'].')</span>' : $val['Customs']['date'] : '';?>
                            <?php echo isset($val['Customs']['standard']) ? $val['Customs']['standard'] : '';?>
                        </td>
                        <td <?php echo isset($val['Inspection']['date']) ? 'class="success"' : '';?>>
                            <?php echo isset($val['Inspection']['date']) ? $val['Inspection']['delay'] != "" ? $val['Inspection']['date'].'<span style="color:#c31a1a;">('.$val['Inspection']['delay'].')</span>' : $val['Inspection']['date'] : '';?>
                            <?php echo isset($val['Inspection']['standard']) ? $val['Inspection']['standard'] : '';?>
                        </td>
                        <td <?php echo isset($val['Warehouse']['date']) ? 'class="success"' : '';?>>
                            <?php echo isset($val['Warehouse']['date']) ? $val['Warehouse']['delay'] != "" ? $val['Warehouse']['date'].'<span style="color:#c31a1a;">('.$val['Warehouse']['delay'].')</span>' : $val['Warehouse']['date'] : '';?>
                            <?php echo isset($val['Warehouse']['standard']) ? $val['Warehouse']['standard'] : '';?>
                        </td>
                        <td>
                            <p class="tips-p" data-tips-id="tips-<?php echo $val['Id'];?>" id="tips-<?php echo $val['Id'];?>" style="cursor:pointer; width:40px; margin-bottom: 0px; overflow:hidden; text-overflow:ellipsis; -o-text-overflow:ellipsis; -webkit-text-overflow:ellipsis; -moz-text-overflow:ellipsis; white-space:nowrap;">
                                <?php echo $val['Notes'];?>
                            </p>
                        </td>
                        <td>
                            <a class="btn btn-default del-customs" href="javascript:void(0);" role="button" data-del-customs="<?php echo $val['Id'];?>">删除</a>
                            <a class="btn btn-default remind" href="javascript:void(0);" role="button" data-remind="<?php echo $val['CustomsID'];?>" data-batchnum="<?php echo $val['BatchNum'];?>">提醒</a>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
            </tbody>
        </table>
        <nav>
            <ul class="pager">
                <?php if (!empty($page_list))echo $page_list;?>
            </ul>
        </nav>
    </div>
</div>
<div class="row">
    <div class="container">
        <div class="col-lg-8">
        </div><!-- /.col-lg-6 -->
        <div class="col-lg-2">
            <a class="btn btn-default" href="<?php echo Yii::app()->user->returnUrl;?>index/ExportCustoms" role="button">导出到EXCEL</a>
        </div><!-- /.col-lg-6 -->
        <div class="col-lg-1">
            <a class="btn btn-default" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#myModal">创建新批次</a>
        </div><!-- /.col-lg-6 -->
    </div>
</div><!-- /.row -->


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加供应商</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal addcustoms">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">批次编号</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="BatchNum" placeholder="21***">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">类型</label>
                        <div class="col-sm-6">
                            <label class="radio-inline">
                                <input type="radio" name="Type" value="full"> 整柜
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="Type" value="air"> 空运
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="Type" value="bulk"> 散货
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">报关公司</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="CustomsID">
                                <?php
                                foreach ($company as $v)
                                {
                                    echo '<option value="'.$v['Id'].'">'.$v['Name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">ETD</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="ETD" placeholder="ETD:2015-07-01" onBlur="checkDate();">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">ETA</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="ETA" placeholder="ETA:2015-07-01" onBlur="checkDate2();">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary customs_add">提交</button>
            </div>
        </div>
    </div>
</div>


<script>
    var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;
    $('.customs_add').on('click',function()
    {
        var BatchNum = $('#BatchNum').val();
        var CustomsID = $('#CustomsID').val();
        var ETD = $('#ETD').val();
        var ETA = $('#ETA').val();
        var Type = $("input[name='Type']:checked").val();
        var start = document.getElementById("ETD").value;
        var over = document.getElementById("ETA").value;

        var startTime = new Date(start.replace("-", "/").replace("-", "/"));
        var overTime = new Date(over.replace("-", "/").replace("-", "/"));

        if (BatchNum == '' || CustomsID == '' || ETD == '' || ETA == '' || Type == '')
        {
            layer.msg('请填写完整后再提交!');
        }
        else if(!DATE_FORMAT.test(start))
        {
            layer.tips('您输入的日期格式有误，正确格式应为 2015-01-01 !', '#ETD');
        }
        else if(!DATE_FORMAT.test(over))
        {
            layer.tips('您输入的日期格式有误，正确格式应为 2015-01-01 !', '#ETA');
        }
        else if(overTime < startTime)
        {
            layer.tips('ETD不能晚于ETA!', '#ETA');
        }
        else
        {
            $.post('<?php echo Yii::app()->user->returnUrl;?>index/AddCustoms',{BatchNum:BatchNum,CustomsID:CustomsID,ETD:ETD,ETA:ETA,Type:Type},function(data){
                if (data.status == 'success')
                {
                    layer.msg('添加成功!');
                    $('#myModal').modal('hide');
                }
                else if (data.status == 'repeat')
                {
                    layer.msg('批次编号已存在!')
                }
                else
                {
                    layer.msg('添加失败!');
                }
            },'json');
        }
    });

    $('.del-customs').on('click',function(){
        var deleteEnable = confirm("确认删除？");
        if(deleteEnable)
        {
            var Id = $(this).attr('data-del-customs');
            $.post('<?php echo Yii::app()->user->returnUrl;?>index/AjaxDelCustoms',{Id:Id},function(data){
                if (data == 'success')
                {
                    layer.msg('删除成功!');
                    $('#'+Id).hide();
                }
                else
                {
                    layer.msg('删除失败!');
                }
            });
        }
    });


    $('#search').on('click',function(){
        var val = $('#search-val').val();
        if (val == '')
        {
            layer.msg('请输入要查询的关键字!');
        }
        else
        {
            location.href = "<?php echo Yii::app()->user->returnUrl;?>index/customs?value="+val;
        }
    });

    $('.tips-p').on('click',function(){
        var text = $(this).html();
        var id = $(this).attr('data-tips-id');
        layer.tips(text, '#'+id);
    });


    function checkDate(){
        var birthday = document.getElementById("ETD").value;
        if(DATE_FORMAT.test(birthday)){

        } else {
            layer.tips('您输入的日期格式有误，正确格式应为 2012-01-01 !', '#ETD');
        }
    }

    function checkDate2(){
        var birthday = document.getElementById("ETA").value;
        if(DATE_FORMAT.test(birthday)){
            var startTime = $("#ETD").val();
            var start = new Date(startTime.replace("-", "/").replace("-", "/"));
            var endTime = $("#ETA").val();
            var end = new Date(endTime.replace("-", "/").replace("-", "/"));
            if(end < start){
                layer.tips('ETD不能晚于ETA!', '#ETA');
            }
        } else {
            layer.tips('您输入的日期格式有误，正确格式应为2012-01-01!', '#ETA');
        }
    }

    $('.remind').on('click',function(){
        var remind = $(this).attr('data-remind');
        var batchnum = $(this).attr('data-batchnum');
        var remindEnable = confirm("确定向("+batchnum+")批次发出短信提醒？");
        if(remindEnable)
        {
            $.post('<?php echo Yii::app()->user->returnUrl;?>index/AjaxSendMessage',{Id:remind},function(data){
                if (data == 'success')
                {
                    layer.msg('提醒成功!');
                }
                else
                {
                    layer.msg('提醒失败!');
                }
            });
        }
    });
</script>
