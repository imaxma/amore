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
</div>
<div class="row">
    <div class="container amore-search">
        <div class="col-lg-8">
        </div><!-- /.col-lg-6 -->
        <div class="col-lg-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="合同编号/报关公司" id="search-val" value="<?php echo isset($_GET['value']) ? $_GET['value'] : '';?>">
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
                    <th>合同编号</th>
                    <th>报关公司</th>
                    <th>合同日期(开始)</th>
                    <th>合同日期(结束)</th>
                    <th>历史正点率(%)</th>
                    <th>平均延迟(日)</th>
                    <th>当前状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($model))
            {
                foreach ($model as $val)
                {
					$CalculateRate = $this->CalculateRate($val['Id']);
            ?>
                    <tr class="amore-tr" id="<?php echo $val['Id'];?>">
                        <td><input type="checkbox"></td>
                        <td><?php echo $val['Number'];?></td>
                        <td><?php echo $val['Name'];?></td>
                        <td><?php echo date('Y-m-d',strtotime($val['ContractStart']));?></td>
                        <td id="edit<?php echo $val['Id'];?>" data-start-date="<?php echo date('Y-m-d',strtotime($val['ContractStart']));?>">
                            <?php echo date('Y-m-d',strtotime($val['ContractEnd']));?><a href="javascript:void(0);" style="margin-left:10px;" onclick="dateedit(<?php echo $val['Id'];?>,'<?php echo date('Y-m-d',strtotime($val['ContractEnd']));?>')">修改</a>
                        </td>
                        <td><?php echo $CalculateRate['av_punctualrate'];?></td>
						<td><?php echo $CalculateRate['av_delaydays'];?></td>
                        <td class="status" data-id="<?php echo $val['Id'];?>" data-status="<?php echo $val['AccessStatus'];?>" style="cursor: pointer;">
                            <?php echo $val['AccessStatus'] == 'normal' ? '<span class="label label-success">正常</span>' : '<span class="label label-default">禁止</span>';?>
                        </td>
                        <td>
                            <a class="btn btn-default" href="<?php echo Yii::app()->user->returnUrl;?>index/showcompany/<?php echo $val['Id'];?>" role="button">查看</a>
                            <a class="btn btn-default addoperator" href="javascript:void(0);" role="button" data-id="<?php echo $val['Id'];?>" data-company-name="<?php echo $val['Name'];?>">添加</a>
                            <a class="btn btn-default company_del" href="javascript:void(0);" role="button" data-id="<?php echo $val['Id'];?>">删除</a>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>

            </tbody>
        </table>
        <nav>
            <?php
            $this->widget('CLinkPager',array(
                'header'=>'',
                'firstPageLabel' => '&#8249;&#8249;',
                'lastPageLabel' => '&#8250;&#8250;',
                'prevPageLabel' => '&#8249;',
                'nextPageLabel' => '&#8250;',
                'pages' => $pages,
                'maxButtonCount'=>8,
                'cssFile'=>false,
                'htmlOptions' =>array("class"=>"pager"),
                'selectedPageCssClass'=>""
            ));
            ?>
        </nav>
    </div>
</div>
<div class="row">
    <div class="container">
        <div class="col-lg-8">
        </div><!-- /.col-lg-6 -->
        <div class="col-lg-2">
        </div><!-- /.col-lg-6 -->
        <div class="col-lg-1">
            <a class="btn btn-default" href="javascript:void(0);" role="button" data-toggle="modal" data-target="#myModal">添加供应商</a>
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
                        <label for="inputEmail3" class="col-sm-3 control-label">合同编号</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="company_number" placeholder="5214231">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">公司名称</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="company_name" placeholder="公司名称">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">访问权限</label>
                        <div class="col-sm-6">
                            <label class="radio-inline">
                                <input type="radio" name="company_AccessStatus" id="inlineRadio1" value="normal" checked> 正常
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="company_AccessStatus" id="inlineRadio2" value="forbidden"> 禁止
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">合同日期</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="company_ContractStart" placeholder="开始日期:2015-07-01" onBlur="checkDate();">
                        </div>
                    </div>
                    <div class="form-group form_dates">
                        <label for="inputEmail3" class="col-sm-3 control-label">合同日期</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="company_ContractEnd" placeholder="结束日期:2015-07-01" onBlur="checkDate2();">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary company_add">提交</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalb" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加报关员</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal addcustoms">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">报关公司</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="operator_company" placeholder="报送公司" readonly>
                            <input type="hidden" class="form-control" id="operator_companyid">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">员工姓名</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="operator_name" placeholder="Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="operator_email" placeholder="E-mail">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-3 control-label">手机</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="operator_phone" placeholder="139****1324">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary company_operator">提交</button>
            </div>
        </div>
    </div>
</div>

<script>
    var DATE_FORMAT = /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;
    $('.company_add').on('click',function()
    {
        var number = $('#company_number').val();
        var name = $('#company_name').val();
        var start = $('#company_ContractStart').val();
        var end = $('#company_ContractEnd').val();
        var status = $("input[name='company_AccessStatus']:checked").val();

        var startTime = new Date(start.replace("-", "/").replace("-", "/"));
        var overTime = new Date(end.replace("-", "/").replace("-", "/"));

        if (number == '' || name == '' || start == '' || end == '' || status == '')
        {
            layer.msg('请填写完整后再提交!');
        }
        else if(!DATE_FORMAT.test(start))
        {
            layer.tips('您输入的日期格式有误，正确格式应为2012-01-01!', '#company_ContractStart');
        }
        else if(!DATE_FORMAT.test(end))
        {
            layer.tips('您输入的日期格式有误，正确格式应为2012-01-01!', '#company_ContractEnd');
        }
        else if(overTime < startTime)
        {
            layer.tips('结束日期不能晚于开始日期!', '#company_ContractEnd');
        }
        else
        {
            $.post('<?php echo Yii::app()->user->returnUrl;?>index/AddCompany',{Number:number,Name:name,ContractStart:start,ContractEnd:end,AccessStatus:status},function(data){
                if (data.status == 'success')
                {
                    layer.msg('添加成功!');
                    $('#myModal').modal('hide');
                }
                else if (data.status == 'repeat')
                {
                    layer.msg('合同编号已存在!');
                }
                else
                {
                    layer.msg('添加失败!');
                }
            },'json');
        }
    });

    $('.company_operator').on('click',function()
    {
        var id = $('#operator_companyid').val();
        var name = $('#operator_name').val();
        var email = $('#operator_email').val();
        var phone = $('#operator_phone').val();
        if (id == '' || name == '' || email == '' || phone == '')
        {
            layer.msg('请填写完整后再提交!');
        }
        else
        {
            $.post('<?php echo Yii::app()->user->returnUrl;?>index/AddOperator',{CustomsID:id,Name:name,Email:email,Phone:phone},function(data){
                if (data.status == 'success')
                {
                    layer.msg('添加成功!');
                    $('#myModalb').modal('hide');
                }
                else if (data.status == 'repeat')
                {
                    layer.msg('手机号码已存在!');
                }
                else
                {
                    layer.msg('添加失败!');
                }
            },'json');
        }
    });

    $('.addoperator').on('click',function(){
        var id = $(this).attr('data-id');
        var name = $(this).attr('data-company-name');
        $('#myModalb').modal('show');
        $('#operator_company').val(name);
        $('#operator_companyid').val(id);
    });


    $('.company_del').on('click',function(){
        var deleteEnable = confirm("确认删除？");
        if(deleteEnable) {
            var id = $(this).attr('data-id');
            $.post('<?php echo Yii::app()->user->returnUrl;?>index/DelCompany', {id: id}, function (data) {
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

    $('#search').on('click',function(){
        var val = $('#search-val').val();
        if (val == '')
        {
            layer.msg('请输入要查询的关键字!');
        }
        else
        {
            location.href = "<?php echo Yii::app()->user->returnUrl;?>index/company?value="+val;
        }
    });

    $('.status').on('click',function(){
        var status = $(this).attr('data-status');
        var id = $(this).attr('data-id');
        var self = $(this);
        $.post('<?php echo Yii::app()->user->returnUrl;?>index/UpdateCompanyStatus', {id:id,status:status}, function (data) {
            if (data == 'success') {
                if (status == 'normal')
                {
                    self.attr('data-status','forbidden');
                    self.html('<span class="label label-default">禁止</span>');
                }
                else
                {
                    self.attr('data-status','normal');
                    self.html('<span class="label label-success">正常</span>');
                }
            }
            else {
                layer.msg('操作失败!');
            }
        });
    });


    function checkDate(){
        var birthday = document.getElementById("company_ContractStart").value;
        if(DATE_FORMAT.test(birthday)){

        } else {
            layer.tips('您输入的日期格式有误，正确格式应为2012-01-01!', '#company_ContractStart');
        }
    }

    function checkDate2(){
        var birthday = document.getElementById("company_ContractEnd").value;
        if(DATE_FORMAT.test(birthday)){
            var startTime = $("#company_ContractStart").val();
            var start = new Date(startTime.replace("-", "/").replace("-", "/"));
            var endTime = $("#company_ContractEnd").val();
            var end = new Date(endTime.replace("-", "/").replace("-", "/"));
            if(end < start){
                layer.tips('结束日期不能晚于开始日期!', '#company_ContractEnd');
            }
        } else {
            layer.tips('您输入的日期格式有误，正确格式应为2012-01-01!', '#company_ContractEnd');
        }
    }


    function dateedit(id,date)
    {
        $('#edit'+id).html('<input type="text" class="form-control" name="overdate" id="overdate'+id+'" value="'+date+'" style="width:50%; display:inline;">'+
        '<a href="javascript:void(0);" style="margin-left:10px;" onclick="datesave('+id+')">确定</span>'+
        '<a href="javascript:void(0);" style="margin-left:10px;" onclick="datecancel('+id+',\''+date+'\')">取消</span>');
    }
    function datecancel(id,date)
    {
        $('#edit'+id).html(date+'<a href="javascript:void(0);" style="margin-left:10px;" onclick="dateedit(\''+id+'\',\''+date+'\')">修改</span>');
    }
    function datesave(id)
    {
        var val = $('#overdate'+id).val();
        if(DATE_FORMAT.test(val))
        {
            var startTime = $('#edit'+id).attr('data-start-date');
            var start = new Date(startTime.replace("-", "/").replace("-", "/"));
            var end = new Date(val.replace("-", "/").replace("-", "/"));
            if(end < start){
                layer.tips('结束日期不能晚于开始日期!', '#edit'+id);
            }
            else
            {
                $.post('<?php echo Yii::app()->user->returnUrl;?>index/UpdateCompanyEnd',{id:id,date:val},function(data){
                    if (data == 'success')
                    {
                        $('#edit'+id).html(val+'<a href="javascript:void(0);" style="margin-left:10px;" onclick="dateedit(\''+id+'\',\''+val+'\')">修改</span>');
                    }
                    else
                    {
                        layer.tips('修改失败!', '#edit'+id);
                    }
                });
            }
        }
        else
        {
            layer.tips('您输入的日期格式有误，正确格式应为2012-01-01!', '#edit'+id);
        }
    }

</script>
