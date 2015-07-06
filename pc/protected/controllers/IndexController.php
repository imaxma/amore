<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/17 0017
 * Time: 22:02
 */
class IndexController extends ModulesController
{
    /*
     * index
     */
    public function actionIndex()
    {
        //$this->render('index');
        $url = Yii::app()->user->returnUrl;
        $this->redirect($url.'index/Customs');
    }



    /*
     * 通关进度customs
     * $type 货物类型　整柜，空运，散装 'full','air','bulk'
     * $customs 报关公司ID
     */
    public function actionCustoms($value=null)
    {
        $value = trim($value);
        $where = "";
        if (!empty($value))
        {
            switch($value)
            {
                case '整柜':
                    $where = "s.Type='full'";
                    break;
                case '空运':
                    $where = "s.Type='air'";
                    break;
                case '散货':
                    $where = "s.Type='bulk'";
                    break;
                default :
                    if (is_numeric($value))
                    {
                        $where = "s.BatchNum='$value'";
                    }
                    else
                    {
                        $where = "c.Name like '%$value%'";
                    }
                    break;
            }
        }
        if (!empty($where))
            $where = " WHERE ".$where;
        $sql = "SELECT count(*) FROM {{customs}} s LEFT JOIN {{customs_com}} c ON s.CustomsID=c.Id ".$where;
        $cnt = Yii::app()->db->createCommand($sql)->queryScalar();
        $per = 10;
        $page = new Pagination($cnt, $per);

        $sql = "SELECT s.*, c.Name as Company_Name FROM {{customs}} s LEFT JOIN {{customs_com}} c ON s.CustomsID=c.Id ".$where." ORDER BY s.CreatedTime DESC ".$page->limit;;
        $result = Yii::app()->db->createcommand($sql)->queryAll();
        $page_list = $page->fpage(array(3,4,5,6,7));

        $MediaItems = array();
        if (!empty($result))
        {
            foreach ($result as $row)
            {
                $Item = array();
                $Item["Id"] = $row["Id"];
                $Item["BatchNum"] = $row["BatchNum"];
                $Item["Type"] = $row["Type"];
                $Item["CustomsID"] = $row["CustomsID"];
                $Item["Company_Name"] = $row["Company_Name"];
                $Item["etd"] = $this->String2MonthDay($row["ETD"]);
                $Item["eta"] = $this->String2MonthDay($row["ETA"]);
                $Item["Notes"] = $row["Notes"];

                //DE
                $DE = array();
                if (isset($row["DE"]))
                {
                    $DE["date"] = Date("d日", strtotime($row["DE"]));
					$DE["delay"]='';
                    if ($row["delay_DE"] != 0)
                    {
                        $DE["delay"] = $row["delay_DE"];
                    }
                }
                else
                {
                    $DE_standard_time = $this->CalculateStandardDays($row["Type"], "DE", strtotime($row["ETA"]));
                    $DE["standard"] = Date("d日", $DE_standard_time);
                }
                $Item["DE"] = $DE;

                //AFE
                $AFE=array();
                if (isset($row["AFE"]))
                {
                    $AFE["date"] = Date("d日", strtotime($row["AFE"]));
					$AFE["delay"]='';
                    if ($row["delay_AFE"] != 0)
                    {
                        $AFE["delay"] = $row["delay_AFE"];
                    }
                }
                else
                {
                    if (isset($row["DE"]))
                    {
                        $AFE_standard_time = $this->CalculateStandardDays($row["Type"], "AFE", strtotime($row["DE"]));
                    }
                    else
                    {
                        $AFE_standard_time = $this->CalculateStandardDays($row["Type"], "AFE", $DE_standard_time);
                    }
                    $AFE["standard"] = Date("d日", $AFE_standard_time);
                }
                $Item["AFE"] = $AFE;

                //DI
                $DI=array();
                if (isset($row["DI"]))
                {
                    $DI["date"] = Date("d日", strtotime($row["DI"]));
					$DI["delay"]='';
                    if ($row["delay_DI"] != 0)
                    {
                        $DI["delay"] = $row["delay_DI"];
                    }
                }
                else
                {
                    if (isset($row["AFE"]))
                    {
                        $DI_standard_time = $this->CalculateStandardDays($row["Type"], "DI", strtotime($row["AFE"]));
                    }
                    else
                    {
                        $DI_standard_time = $this->CalculateStandardDays($row["Type"], "DI", $AFE_standard_time);
                    }

                    $DI["standard"]=Date("d日", $DI_standard_time);
                }
                $Item["DI"]=$DI;

                //TAX
                $TAX=array();
                if (isset($row["TAX"]))
                {
                    $TAX["date"] = Date("d日", strtotime($row["TAX"]));
					$TAX["delay"]='';
                    if ($row["delay_TAX"] != 0)
                    {
                        $TAX["delay"] = $row["delay_TAX"];
                    }
                }
                else
                {
                    if(isset($row["DI"]))
                    {
                        $TAX_standard_time = $this->CalculateStandardDays($row["Type"], "TAX", strtotime($row["DI"]));
                    }
                    else
                    {
                        $TAX_standard_time = $this->CalculateStandardDays($row["Type"], "TAX", $DI_standard_time);
                    }

                    $TAX["standard"]=Date("d日", $TAX_standard_time);
                }
                $Item["TAX"] = $TAX;

                //Customs
                $Customs = array();
                if (isset($row["Customs"]))
                {
                    $Customs["date"] = Date("d日", strtotime($row["Customs"]));
					$Customs["delay"]='';
                    if($row["delay_Customs"] != 0)
                    {
                        $Customs["delay"] = $row["delay_Customs"];
                    }
                }
                else
                {
                    if(isset($row["TAX"]))
                    {
                        $Customs_standard_time = $this->CalculateStandardDays($row["Type"], "Customs", strtotime($row["TAX"]));
                    }
                    else
                    {
                        $Customs_standard_time = $this->CalculateStandardDays($row["Type"], "Customs", $TAX_standard_time);
                    }

                    $Customs["standard"] = Date("d日", $Customs_standard_time);
                }
                $Item["Customs"] = $Customs;

                //Inspection
                $Inspection = array();
                if (isset($row["Inspection"]))
                {
                    $Inspection["date"] = Date("d日", strtotime($row["Inspection"]));
					$Inspection["delay"]='';
                    if($row["delay_Inspection"] != 0)
                    {
                        $Inspection["delay"] = $row["delay_Inspection"];
                    }

                }
                else
                {
                    if(isset($row["Customs"]))
                    {
                        $Inspection_standard_time = $this->CalculateStandardDays($row["Type"], "Inspection", strtotime($row["Customs"]));
                    }
                    else
                    {
                        $Inspection_standard_time = $this->CalculateStandardDays($row["Type"], "Inspection", $Customs_standard_time);
                    }

                    $Inspection["standard"] = Date("d日", $Inspection_standard_time);
                }
                $Item["Inspection"] = $Inspection;

                //Warehouse
                $Warehouse = array();
                if (isset($row["Warehouse"]))
                {
                    $Warehouse["date"] = Date("d日", strtotime($row["Warehouse"]));
					$Warehouse["delay"]='';
                    if ($row["delay_Warehouse"] != 0)
                    {
                        $Warehouse["delay"] = $row["delay_Warehouse"];
                    }
                }
                else
                {
                    if (isset($row["Inspection"]))
                    {
                        $Warehouse_standard_time = $this->CalculateStandardDays($row["Type"], "Warehouse", strtotime($row["Inspection"]));
                    }
                    else
                    {
                        $Warehouse_standard_time = $this->CalculateStandardDays($row["Type"], "Warehouse", $Inspection_standard_time);
                    }

                    $Warehouse["standard"] = Date("d日", $Warehouse_standard_time);
                }
                $Item["Warehouse"] = $Warehouse;

                $MediaItems[]= $Item;
            }
        }
        //报关公司列表
        $date = date('Y-m-d H:i:s');
        $company = CustomsCom::model();
        $company = $company->findAll('ContractStart<=:ContractStart AND ContractEnd>=:ContractEnd AND AccessStatus=:AccessStatus',array(
            ':ContractStart' => $date,
            ':ContractEnd' => $date,
            ':AccessStatus' => 'normal'
        ));
        $this->render('customs',array(
            'page_list' => $page_list,
            'result' => $MediaItems,
            'company' => $company
        ));
    }

    /*
     * AJAX添加新批次
     */
    public function actionAddCustoms()
    {
        if (isset($_POST['BatchNum']) && isset($_POST['Type']) && isset($_POST['CustomsID']) && isset($_POST['ETD']) && isset($_POST['ETA']))
        {
            $model = new Customs();
            $model->BatchNum = $_POST['BatchNum'];
            $model->Type = $_POST['Type'];
            $model->CustomsID = $_POST['CustomsID'];
            $model->ETD = $_POST['ETD'];
            $model->ETA = $_POST['ETA'];
            $model->CreatorID = Yii::app()->session['amore_admin_id'];
            $model->CreatedTime = date('Y-m-d H:i:s');
            $result = $model->find('BatchNum=:BatchNum',array(':BatchNum'=>$_POST['BatchNum']));
            if (empty($result))
            {
                if ($model->save())
                {
                    $id = $model->attributes['Id'];
                    echo json_encode(array('status'=>'success','Id'=>$id));
                }
                else
                {
                    echo json_encode(array('status'=>'error'));
                }
            }
            else
            {
                echo json_encode(array('status'=>'repeat'));
            }
        }
        else
        {
            echo json_encode(array('status'=>'error'));
        }
    }


    /*
     * DEL 通关信息
     */
    public function actionAjaxDelCustoms()
    {
        if (isset($_POST['Id']) && !empty($_POST['Id']))
        {
            $model = Customs::model();
            if ($model->deleteByPk($_POST['Id']))
            {
                echo "success";
            }
            else
            {
                echo "error";
            }
        }
        else
        {
            echo "error";
        }
    }


    /*
     * SEND MESSAGE
     */
    public function actionAjaxSendMessage()
    {
        //取得报关公司CustomsID
        $result = '';
        if (isset($_POST['Id']) && !empty($_POST['Id']))
        {
            $model = CustomsOperator::model();
            $model = $model->findAll('CustomsID=:CustomsID',array(':CustomsID'=>$_POST['Id']));
            foreach ($model as $k=>$v)
            {
                $result[$k]['email'] = $v['Email'];
                $result[$k]['phone'] = $v['Phone'];
                $result[$k]['Name'] = $v['Name'];
            }
            /*
             * 此处写发送短信代码
             * 由于一个公司可能有多个操作人员，所以可能要发送多条短信　　
             * $result 即为所有操作人员联系方式集合
             */
            echo 'success';
        }
        else
        {
            echo 'error';
        }
    }


    /*************************************************************************
     *
     *供应商管理
     *
     ***************************************************************************/

    /*
     * 通关公司
     */
    public function actionCompany($value=null)
    {
        $result = '';
        $model = CustomsCom::model();

        $criteria = new CDbCriteria;
        if (!empty($value))
        {
            $criteria->addCondition("Name like '%$value%' OR Number like '%$value%'");
        }
        $criteria->order="Id DESC";

        $count = $model->count($criteria);

        $pager = new CPagination($count);
        $pager->pageSize = 10;
        $pager->applyLimit($criteria);

        $result = $model->findAll($criteria);

        $this->render('company',array(
            'model' => $result,
            'pages' => $pager
        ));
    }

    /*
     * 添加供应商
     */
    public function actionAddCompany()
    {
        if (isset($_POST['Number']) && isset($_POST['Name']) && isset($_POST['ContractStart']) && isset($_POST['ContractEnd'])) {
            $model = new CustomsCom();
            $model->Number = $_POST['Number'];
            $model->Name = $_POST['Name'];
            $model->ContractStart = $_POST['ContractStart'];
            $model->ContractEnd = $_POST['ContractEnd'];
            $model->AccessStatus = $_POST['AccessStatus'];
            $result = $model->find('Number=:Number',array(':Number'=>$_POST['Number']));
            if (empty($result))
            {
                if ($model->save())
                {
                    $id = $model->attributes['Id'];
                    echo json_encode(array('status' => 'success', 'Id' => $id));
                }
                else
                {
                    echo json_encode(array('status' => 'error'));
                }
            }
            else
            {
                echo json_encode(array('status' => 'repeat'));
            }
        }
        else
        {
            echo json_encode(array('status' => 'error'));
        }
    }

    /*
     * 删除供应商
     */
    public function actionDelCompany()
    {
        if (isset($_POST['id']) && !empty($_POST['id']))
        {
            $model = CustomsCom::model();
            if ($model->deleteByPk($_POST['id']))
            {
                echo 'success';
            }
            else
            {
                echo 'error';
            }
        }
        else
        {
            echo 'error';
        }
    }

    /*
     * 修改供应商状态
     */
    public function actionUpdateCompanyStatus()
    {
        if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['status']) && !empty($_POST['status']))
        {
            $model = CustomsCom::model();
            $model = $model->findByPk($_POST['id']);
            if ($_POST['status'] == 'normal')
            {
                $model->AccessStatus = 'forbidden';
            }
            else
            {
                $model->AccessStatus = 'normal';
            }
            if ($model->update())
            {
                echo 'success';
            }
            else
            {
                echo 'error';
            }
        }
        else
        {
            echo 'error';
        }
    }

    /*
     * 修改供应商合同结束日期
     */
    public function actionUpdateCompanyEnd()
    {
        if (isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['date']) && !empty($_POST['date']))
        {
            $model = CustomsCom::model();
            $model = $model->findByPk($_POST['id']);
            $model->ContractEnd = date('Y-m-d H:i:s',strtotime($_POST['date'])+86399);
            if ($model->update())
            {
                echo 'success';
            }
            else
            {
                echo 'error';
            }
        }
        else
        {
            echo 'error';
        }
    }




    /*
     * 查看供应商
     */
    public function actionShowCompany($id)
    {
        $sql = "SELECT o.*, c.Number, c.Name as Company_name FROM {{customs_operator}} o LEFT JOIN {{customs_com}} c ON o.CustomsID=c.Id WHERE o.CustomsID=:id ORDER BY o.CreatedTime";
        $result = Yii::app()->db->createCommand($sql)->bindValue(':id',$id)->queryAll();
        $this->render('showcompany',array(
            'result' => $result
        ));
    }

    /*
     * 查看报关人员
     */
    public function actionOperator($id)
    {
        $model = CustomsOperator::model();
        $model = $model->findByPk($id);
        $this->render('operator',array(
            'model' => $model
        ));
    }

    /*
     * 添加报关人员
     */
    public function actionAddOperator()
    {
        if (isset($_POST['Email']) && isset($_POST['Name']) && isset($_POST['Phone']) && isset($_POST['CustomsID']))
        {
            $model = new CustomsOperator();
            $model->Email = $_POST['Email'];
            $model->Name = $_POST['Name'];
            $model->Phone = $_POST['Phone'];
            $model->CustomsID = $_POST['CustomsID'];
            $password = $this->create_password(6);
            $model->Password = md5($password);
            $model->CreatedTime = date('Y-m-d H:i:s');
            $result = $model->find('Phone=:Phone',array(':Phone'=>$_POST['Phone']));
            if (empty($result))
            {
                if ($model->save())
                {
                    $id = $model->attributes['ID'];
                    echo json_encode(array('status' => 'success', 'Id' => $id, 'password' => $password));
                }
                else
                {
                    echo json_encode(array('status' => 'error'));
                }
            }
            else
            {
                echo json_encode(array('status' => 'repeat'));
            }
        }
        else
        {
            echo json_encode(array('status' => 'error'));
        }
    }


    /*
     * 重置密码
     */
    public function actionResetPassword()
    {
        if (isset($_POST['id']) && !empty($_POST['id']))
        {
            $model = CustomsOperator::model();
            $model = $model->findByPk($_POST['id']);
            $password = $this->create_password(6);
            $model->Password = md5($password);
            if ($model->update())
            {
                echo json_encode(array('status' => 'success', 'password' => $password));
            }
            else
            {
                echo json_encode(array('status' => 'error'));
            }
        }
        else
        {
            echo json_encode(array('status' => 'error'));
        }
    }


    /*
     * 删除报送员
     */
    public function actionDelOperator()
    {
        if (isset($_POST['id']) && !empty($_POST['id']))
        {
            $model = CustomsOperator::model();
            if ($model->deleteByPk($_POST['id']))
            {
                echo 'success';
            }
            else
            {
                echo 'error';
            }
        }
        else
        {
            echo 'error';
        }
    }


    /*
     * 修改密码
     */
    public function actionUpdatePassword()
    {
        $user_id = Yii::app()->session['amore_admin_id'];
        $model = AmoreAdmin::model();
        //$model->setScenario('update');
        $model = $model->findByPk($user_id);
        if (isset($_POST['ajax']) && $_POST['ajax']==='setting_info')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['AmoreAdmin']))
        {
            foreach	($_POST['AmoreAdmin'] as $k=>$v)
            {
                $model->$k = $v;
            }
            if ($model->validate())
            {
                $model->Password = md5($model->Password);
                if ($model->update())
                {
                    Yii::app()->user->setFlash('success','修改密码成功！');
                    $this->redirect(Yii::app()->user->returnUrl.'index');
                }
                else
                {
                    Yii::app()->user->setFlash('error','修改密码失败！');
                    $this->refresh();
                }
            }
        }
        $this->render('config',array(
            'model' => $model,
            'user_id' => $user_id,
        ));
    }
	
	/*
     * 通关导出EXCEL
     */
    public function actionExportCustoms()
    {
        $sql = "SELECT s.*, c.Name as Company_Name FROM {{customs}} s LEFT JOIN {{customs_com}} c ON s.CustomsID=c.Id WHERE s.Status='done' ORDER BY s.CreatedTime DESC ";
        $result = Yii::app()->db->createcommand($sql)->queryAll();

        $MediaItems = array();
        if (!empty($result))
        {
            foreach ($result as $row)
            {
                $Item = array();
                $Item["Id"] = $row["Id"];
                $Item["BatchNum"] = $row["BatchNum"];
                $Item["Type"] = $row["Type"];
                $Item["CustomsID"] = $row["CustomsID"];
                $Item["Company_Name"] = $row["Company_Name"];
                $Item["etd"] = $row["ETD"];
                $Item["eta"] = $row["ETA"];
                $Item["Notes"] = $row["Notes"];
                $Item["CreatedTime"] = $row["CreatedTime"];
				$Item["TotalDelay"] = $row["TotalDelay"];

                //DE
                $DE = array();
                $DE["date"] = $row["DE"];
                $DE["delay"] = $row["delay_DE"];
                $Item["DE"] = $DE;

                //AFE
                $AFE=array();
                $AFE["date"] = $row["AFE"];
                $AFE["delay"] = $row["delay_AFE"];
                $Item["AFE"] = $AFE;

                //DI
                $DI=array();
                $DI["date"] = $row["DI"];
                $DI["delay"] = $row["delay_DI"];
                $Item["DI"]=$DI;

                //TAX
                $TAX=array();
                $TAX["date"] = $row["TAX"];
                $TAX["delay"] = $row["delay_TAX"];
                $Item["TAX"] = $TAX;

                //Customs
                $Customs = array();
                $Customs["date"] = $row["Customs"];
                $Customs["delay"] = $row["delay_Customs"];
                $Item["Customs"] = $Customs;

                //Inspection
                $Inspection = array();
                $Inspection["date"] = $row["Inspection"];
                $Inspection["delay"] = $row["delay_Inspection"];
                $Item["Inspection"] = $Inspection;

                //Warehouse
                $Warehouse = array();
                $Warehouse["date"] = $row["Warehouse"];
                $Warehouse["delay"] = $row["delay_Warehouse"];
                $Item["Warehouse"] = $Warehouse;

                $MediaItems[]= $Item;
            }
        }

        $this->render('export',array(
            'result' => $MediaItems
        ));
    }
	
	function CalculateRate($CustomsID) {
        $querySQL = "SELECT TotalDelay FROM data_customs WHERE CustomsID=$CustomsID AND status='done' ";
        $result = Yii::app()->db->createCommand($querySQL)->queryAll();
        if($result) {
            $TotalCount = count($result);
            //当没有数据时，返回空
            if($TotalCount == 0) {
                return array("av_delaydays"=>'-', "av_punctualrate"=>'-');
            }

            $DelayDays_Sum = 0;
            $Punctual_Sum = $TotalCount;

            foreach ($result as $v)
            {
                if(isset($v["TotalDelay"]) && $v["TotalDelay"] > 0) {
                    $DelayDays_Sum += $v["TotalDelay"];
                    $Punctual_Sum--;
                }
            }

            $av_delaydays = round((float)$DelayDays_Sum / $TotalCount, 2);
            $av_punctualrate = round((float)($Punctual_Sum*100) / $TotalCount, 2);

            return array("av_delaydays"=>$av_delaydays, "av_punctualrate"=>$av_punctualrate);
        } else {
            return array("av_delaydays"=>'-', "av_punctualrate"=>'-');
        }
    }



}