<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();


    /*
     * 发送短信
     */
    public function SendMessage($phone)
    {
        if (!empty($phone))
        {
            echo 'send';
        }
    }


    /*
     * 随机生成密码
     */
    public function create_password($pw_length)
    {
        $randpwd = '';
        /*for ($i = 0; $i < $pw_length; $i++)
        {
            $randpwd .= chr(mt_rand(33, 126));
        }*/
		//暂时统一一个初始密码即可
		$randpwd="123";
        return $randpwd;
            // 调用该函数，传递长度参数$pw_length = 6
        //echo create_password(6);
    }




    /*************************************************Amore 公共函数*****************************************************/
    function String2ShortDate($date)
    {
        $month = date("m", strtotime($date))+0;
        $day = date("d", strtotime($date))+0;
        return $day."日";
    }

    function String2MonthDay($date)
    {
        $month = date("m", strtotime($date))+0;
        $day = date("d", strtotime($date))+0;
        return $month."月".$day."日";
    }

    function Int2ShortDate($date)
    {
        $month = date("m", $date)+0;
        $day = date("d", $date)+0;
        return $day."日";
    }

    //计算总延迟天数。
    function CalculateTotalDelayDays($BatchID) {

        $model = Customs::model();
        $model = $model->findByPk($BatchID);

        if (!empty($model))
        {
            $TotalDelayDays= $model->delay_DE + $model->delay_AFE + $model->delay_DI + $model->delay_TAX + $model->delay_Customs + $model->delay_Inspection + $model->delay_Warehouse;
        }
        return $TotalDelayDays;
    }

    //根据ID获取报关公司的名字
    function GetCustomsCompanyName($ID)
    {
        $model = CustomsCom::model();
        $model = $model->findByPk($ID);
        if (!empty($model))
        {
            return $model->name;
        }
    }

    //获得标准天数
    function GetStandardDays($Type, $Step) {
        $Standard = array
        (
            "full"=>array("DE"=>2, "AFE"=>1, "DI"=>1, "TAX"=>0, "Customs"=>1, "Inspection"=>1, "Warehouse"=>0, "total"=>6),
            "air"=>array("DE"=>1, "AFE"=>1, "DI"=>1, "TAX"=>0, "Customs"=>1, "Inspection"=>1, "Warehouse"=>0, "total"=>5),
            "bulk"=>array("DE"=>3, "AFE"=>1, "DI"=>1, "TAX"=>0, "Customs"=>1, "Inspection"=>1, "Warehouse"=>0, "total"=>7)
        );

        return $Standard[$Type][$Step];
    }

    //获得某一步骤的标准日期
    function CalculateStandardDays($Type, $Step, $PreStepTime)
    {
        $StandardTime = $PreStepTime;
        $StandardDays = $this->GetStandardDays($Type, $Step);
        //echo "<pre>".$StandardTime."<pre>".$StandardDays;
        while($StandardDays-- > 0) { //如果 0.5 天呢？？？？？
            do {
                $StandardTime+=3600*24;
            }
            while(!$this->IsWorkDay($StandardTime));
        }
        return $StandardTime;
    }

    //查看是否是WorkDay
    Function IsWorkDay($TargetTime)
    {
        if(Date("w", $TargetTime) == 0 || Date("w", $TargetTime) == 6)
            return false;
        else
            return true;
    }

    //计算两个日期之间的工作天数。
    function CalculateDays($Date, $PreStepDate)
    {
        $PreStepTime = strtotime($PreStepDate);
        if($PreStepTime > $Date)
        {
            //fatal error!
            return 0;
        }

        $i=0;
        while (Date("Y-m-d", $PreStepTime) !== Date("Y-m-d", $Date))
        {
            $PreStepTime+=3600*24;
            if($this->IsWorkDay($PreStepTime))
            {
                $i+=1;
            }
        }
        return $i;
    }

    //计算某批次下某一步骤的延迟天数。
    function CalculateDelayDays($BatchID, $Step, $Date) {

        $model = Customs::model();
        $model = $model->findByPk($BatchID);
        if (!empty($model))
        {
            switch ($Step)
            {
                case "DE":
                    $StandardDays = $this->GetStandardDays($model->Type, "DE");
                    $ActualDays = $this->CalculateDays($Date, $model->ETA);
                    break;
                case "AFE":
                    $StandardDays = $this->GetStandardDays($model->Type, "AFE");
                    $ActualDays = $this->CalculateDays($Date, $model->DE);
                    break;
                case "DI":
                    $StandardDays = $this->GetStandardDays($model->Type, "DI");
                    $ActualDays = $this->CalculateDays($Date, $model->AFE);
                    break;
                case "TAX":
                    $StandardDays = $this->GetStandardDays($model->Type, "TAX");
                    $ActualDays = $this->CalculateDays($Date, $model->DI);
                    break;
                case "Customs":
                    $StandardDays = $this->GetStandardDays($model->Type, "Customs");
                    $ActualDays = $this->CalculateDays($Date, $model->TAX);
                    break;
                case "Inspection":
                    $StandardDays = $this->GetStandardDays($model->Type, "Inspection");
                    $ActualDays = $this->CalculateDays($Date, $model->Customs);
                    break;
                case "Warehouse":
                    $StandardDays = $this->GetStandardDays($model->Type, "Warehouse");
                    $ActualDays = $this->CalculateDays($Date, $model->Inspection);
                    break;
                case "Total":
                    $StandardDays = $this->GetStandardDays($model->Type, "Total");
                    $ActualDays = $this->CalculateDays($Date, $model->ETA);
                    break;
                default:
                    echo ReturnError(false, array("reason"=>"wrong parameter"));
                    exit;
            }
            $DelayDays= $ActualDays - $StandardDays;
        }
        return $DelayDays;
    }

    //返回json格式的错误代码。
    function ReturnError($SucceedOrNot, $Extra= array())
    {
        if($SucceedOrNot)
        {
            //$ErrorArray = array("ret"=>"OK");
            $ErrorArray = array("ret"=>"success");
        }
        else
        {
            //$ErrorArray = array("ret"=>"Failed");
            $ErrorArray = array("ret"=>"error");
        }
        $result = array_merge_recursive($ErrorArray, $Extra);
        return json_encode($result);
        //return str_replace("\\/", "/", json_encode($result));
    }
	
	//查看合同多久后到期，并提醒
	function ContractAlerts() {
		$AlertGroup=array();
		$querySQL = "SELECT Id, ContractStart, ContractEnd, Name, Alerts FROM data_customs_com WHERE 1";
		$result = mysql_query($querySQL);
		if($result) {
			while($row = mysql_fetch_array($result)) {
				$TimeLeft = strtotime($row["ContractEnd"]) - strtotime(Date("Y-m-d H:i:s"));
				if($TimeLeft>60*24*3600 && $TimeLeft<90*24*3600){				//前三个月 第一次
					if(strpos($row["Alerts"], '3M1') !== true) {
						$Alert['contents'] = $row["Name"]."的合同距离到期不足三个月!";
					}
				} else if($TimeLeft>45*24*3600 && $TimeLeft<60*24*3600) {		//前两个月 第一次
					if(strpos($row["Alerts"], '2M1') !== true) {
						$Alert['contents'] = $row["Name"]."的合同距离到期不足两个月!";
					}
				} else if($TimeLeft>30*24*3600 && $TimeLeft<45*24*3600) {		//前两个月 第二次
					if(strpos($row["Alerts"], '2M2') !== true) {
						$Alert['contents'] = $row["Name"]."的合同距离到期不足两个月!";
					}
				} else if($TimeLeft>21*24*3600 && $TimeLeft<30*24*3600) {		//前一个月 第一次
					if(strpos($row["Alerts"], '1M1') !== true) {
						$Alert['contents'] = $row["Name"]."的合同距离到期不足一个月!";
					}
				} else if($TimeLeft>14*24*3600 && $TimeLeft<21*24*3600) {		//前一个月 第二次
					if(strpos($row["Alerts"], '1M2') !== true) {
						$Alert['contents'] = $row["Name"]."的合同距离到期不足一个月!";
					}
				} else if($TimeLeft>7*24*3600 && $TimeLeft<14*24*3600) {		//前一个月 第三次
					if(strpos($row["Alerts"], '1M3') !== true) {
						$Alert['contents'] = $row["Name"]."的合同距离到期不足两周!";
					}
				} else if($TimeLeft>0 && $TimeLeft<7*24*3600) {					//前一个月 第四次
					if(strpos($row["Alerts"], '1M4') !== true) {
						$Alert['contents'] = $row["Name"]."的合同距离到期不足一周!(最后一次提醒)";
					}
				} else {
					$Alert['contents'] = "";
				}
				
				if(!empty($Alert['contents'])) {
					$Alert['com_id']=$row["Id"];
					$AlertGroup[]=$Alert;
				}
			}
		}
		
		return json_encode($AlertGroup);
	}
	
	//提醒已被确认
	function AlertDone($Id) {
		$querySQL = "SELECT ContractStart, ContractEnd, Alerts FROM data_customs_com WHERE id=$Id LIMIT 1";
		$result = mysql_query($querySQL);
		if($result) {
			while($row = mysql_fetch_array($result)) {
				$TimeLeft = strtotime($row["ContractEnd"]) - strtotime(Date("Y-m-d H:i:s"));
				if($TimeLeft>60*24*3600 && $TimeLeft<90*24*3600){				//前三个月 第一次
					$AlertType = 1;// "'3M1'";
				} else if($TimeLeft>45*24*3600 && $TimeLeft<60*24*3600) {		//前两个月 第一次
					$AlertType = 2;//"'2M1'";
				} else if($TimeLeft>30*24*3600 && $TimeLeft<45*24*3600) {		//前两个月 第二次
					$AlertType = 4;//"'2M2'";
				} else if($TimeLeft>21*24*3600 && $TimeLeft<30*24*3600) {		//前一个月 第一次
					$AlertType = 8;//"'1M1'";
				} else if($TimeLeft>14*24*3600 && $TimeLeft<21*24*3600) {		//前一个月 第二次
					$AlertType = 16;//"'1M2'";
				} else if($TimeLeft>7*24*3600 && $TimeLeft<14*24*3600) {		//前一个月 第三次
					$AlertType = 32;//"'1M3'";
				} else if($TimeLeft>0 && $TimeLeft<7*24*3600) {					//前一个月 第四次
					$AlertType = 64;//"'1M4'";
				}
				
				if(!empty($AlertType)) {
					mysql_query("UPDATE data_customs_com SET Alerts=Alerts|".$AlertType." WHERE id=$Id");
				}
			}
		}
	}
	
	
}