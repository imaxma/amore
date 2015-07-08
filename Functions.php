<?php
	
	//把长的日期表示改为“x月x日”！	
	function String2MonthDay($date) {
		$month = date("m", strtotime($date))+0;
		$day = date("d", strtotime($date))+0;
		
		return $month."月".$day."日";
	}
		
	//计算总延迟天数。
	function CalculateTotalDelayDays($BatchID) {
		
		$querySQL="SELECT delay_DE, delay_AFE, delay_DI, delay_TAX, delay_Customs, delay_Inspection, delay_Warehouse FROM data_customs WHERE ID=$BatchID LIMIT 1";
		//echo "<pre>".$querySQL;
			
		if ($result=mysql_query($querySQL)) {
			while ($row = mysql_fetch_array($result)){				
				$TotalDelayDays= $row["delay_DE"] + $row["delay_AFE"] + $row["delay_DI"] + $row["delay_TAX"] + $row["delay_Customs"] + $row["delay_Inspection"] + $row["delay_Warehouse"];
			}
		}
		return $TotalDelayDays;
	}
		
	//根据ID获取报关公司的名字
	function GetCustomsCompanyName($ID) {
		$querySQL="SELECT name FROM data_customs_com WHERE ID=$ID LIMIT 1";
		if ($result=mysql_query($querySQL)) {
			while ($row = mysql_fetch_array($result)){
				return $row["name"];
			}
		}
	}
	
	//获得标准天数
	function GetStandardDays($Type, $Step) {
		$Standard = array
		(
			"full"=>array("DE"=>2, "AFE"=>1, "DI"=>1, "TAX"=>0, "Customs"=>1, "Inspection"=>1, "Warehouse"=>0, "Total"=>6),
			"air"=>array("DE"=>1, "AFE"=>1, "DI"=>1, "TAX"=>0, "Customs"=>1, "Inspection"=>1, "Warehouse"=>0, "Total"=>5),
			"bulk"=>array("DE"=>3, "AFE"=>1, "DI"=>1, "TAX"=>0, "Customs"=>1, "Inspection"=>1, "Warehouse"=>0, "Total"=>7)
		);

		return $Standard[$Type][$Step];
	}
	
	//获得某一步骤的标准日期
	function CalculateStandardDays($Type, $Step, $PreStepTime) 
	{
		$StandardTime = $PreStepTime;
		$StandardDays = GetStandardDays($Type, $Step);
		//echo "<pre>".$StandardTime."<pre>".$StandardDays;
		while($StandardDays-- > 0) { //如果 0.5 天呢？？？？？
			do {
				$StandardTime+=3600*24;
			} while(!IsWorkDay($StandardTime));
		}
		return $StandardTime;
	}
	
	//查看是否是WorkDay
	Function IsWorkDay($TargetTime)
	{
		//目前仅判断周六和周日。
		if(Date("w", $TargetTime) == 0 || Date("w", $TargetTime) == 6)
			return false;
		else
			return true;
	}
	
	//计算两个日期之间的工作天数。
	function CalculateDays($Date, $PreStepDate)
	{
		$PreStepTime = strtotime($PreStepDate);
		if($PreStepTime > $Date) {
			//fatal error!
			return 0;
		}
			
		$i=0;
		while (Date("Y-m-d", $PreStepTime) !== Date("Y-m-d", $Date)) {
			$PreStepTime+=3600*24;
			if(IsWorkDay($PreStepTime))
				$i+=1;
		}
		return $i;
	}
	
	//计算某批次下某一步骤的延迟天数。
	function CalculateDelayDays($BatchID, $Step, $Date) {
		
		$querySQL="SELECT Type, ETD, ETA, DE, AFE, DI, TAX, Customs, Inspection, Warehouse FROM data_customs WHERE ID=$BatchID LIMIT 1";
		//echo "<pre>".$querySQL;
			
		if ($result=mysql_query($querySQL)) 
		{
			while ($row = mysql_fetch_array($result)){
				switch ($Step) 
				{
					case "DE":
						if(!isset($row['ETA'])) {
							return "wrong";
						} else if (strtotime($row['ETA']) > $Date) {
							return "impossible";
						}						
						$StandardDays = GetStandardDays($row['Type'], "DE");
						$ActualDays = CalculateDays($Date, $row['ETA']);
						break;
					case "AFE":
						if(!isset($row['DE'])) {
							return "wrong";
						} else if (strtotime($row['DE']) > $Date) {
							return "impossible";
						}
						$StandardDays = GetStandardDays($row['Type'], "AFE");
						$ActualDays = CalculateDays($Date, $row['DE']);
						break;
					case "DI":
						if(!isset($row['AFE'])) {
							return "wrong";
						} else if (strtotime($row['AFE']) > $Date) {
							return "impossible";
						}
						$StandardDays = GetStandardDays($row['Type'], "DI");
						$ActualDays = CalculateDays($Date, $row['AFE']);
						break;
					case "TAX":
						if(!isset($row['DI'])) {
							return "wrong";
						}else if (strtotime($row['DI']) > $Date) {
							return "impossible";
						}
						$StandardDays = GetStandardDays($row['Type'], "TAX");
						$ActualDays = CalculateDays($Date, $row['DI']);
						break;
					case "Customs":
						if(!isset($row['TAX'])) {
							return "wrong";
						}else if (strtotime($row['TAX']) > $Date) {
							return "impossible";
						}
						$StandardDays = GetStandardDays($row['Type'], "Customs");
						$ActualDays = CalculateDays($Date, $row['TAX']);
						break;
					case "Inspection":
						if(!isset($row['Customs'])) {
							return "wrong";
						}else if (strtotime($row['Customs']) > $Date) {
							return "impossible";
						}
						$StandardDays = GetStandardDays($row['Type'], "Inspection");
						$ActualDays = CalculateDays($Date, $row['Customs']);
						break;
					case "Warehouse":
						if(!isset($row['Inspection'])) {
							return "wrong";
						}else if (strtotime($row['Inspection']) > $Date) {
							return "impossible";
						}
						$StandardDays = GetStandardDays($row['Type'], "Warehouse");
						$ActualDays = CalculateDays($Date, $row['Inspection']);
						break;
					case "Total":
						if(!isset($row['ETA'])) {
							return "wrong";
						}else if (strtotime($row['ETA']) > $Date) {
							return "impossible";
						}
						$StandardDays = GetStandardDays($row['Type'], "Total");
						$ActualDays = CalculateDays($Date, $row['ETA']);
						break;
					default:
						return "wrong";
						exit;		
				};
				$DelayDays= $ActualDays - $StandardDays;
			}
		}
		return $DelayDays;
	}

	//返回json格式的错误代码。
	function ReturnError($SucceedOrNot, $Extra = array())
	{
		if($SucceedOrNot)
			$ErrorArray = array("ret"=>"success");
		else
			$ErrorArray = array("ret"=>"error");

		if($Extra != null) {
			$result=array_merge($ErrorArray, $Extra);
		} else {
			$result = $ErrorArray;
		}
		return json_encode($result);
	}
		
	//
	function QuerySingleItem($BatchID, $BatchNum) {
		if(!empty($BatchNum))
			$querySQL = "SELECT * FROM data_customs WHERE BatchNum='".$BatchNum."' LIMIT 1";
		else if(!empty($BatchID))
			$querySQL = "SELECT * FROM data_customs WHERE ID='".$BatchID."' LIMIT 1";
		
		$result=mysql_query($querySQL);
		if ($result != null) {
			while ($row = mysql_fetch_array($result)){
				$Item=array();
				$Item["id"]=$row["Id"];
				$Item["batch"]=$row["BatchNum"];
				$Item["customsid"]=$row["CustomsID"];
				$Item["type"]=$row["Type"];
				$Item["customs"]=GetCustomsCompanyName($row["CustomsID"]);
				$Item["etd"]=Date("Y-m-d", strtotime($row["ETD"])); //String2MonthDay($row["ETD"]);
				$Item["eta"]=Date("Y-m-d", strtotime($row["ETA"])); //String2MonthDay($row["ETA"]);
				if(!empty($row["Notes"]))
					$Item["notes"]=$row["Notes"];

				//DE
				$DE=array();
				if(isset($row["DE"])) {
					$DE["date"]=Date("Y-m-d", strtotime($row["DE"]));
					if($row["delay_DE"] != 0) 
						$DE["delay"]=$row["delay_DE"];
				} else {
					$DE_standard_time=CalculateStandardDays($row["Type"], "DE", strtotime($row["ETA"]));
					$DE["standard"]=Date("Y-m-d", $DE_standard_time);
				}
				$Item["DE"]=$DE;
				
				//AFE
				$AFE=array();
				if(isset($row["AFE"])) {
					$AFE["date"]=Date("Y-m-d", strtotime($row["AFE"]));
					if($row["delay_AFE"] != 0) 
						$AFE["delay"]=$row["delay_AFE"];
				} else {
					if(isset($row["DE"]))
						$AFE_standard_time=CalculateStandardDays($row["Type"], "AFE", strtotime($row["DE"]));
					else
						$AFE_standard_time=CalculateStandardDays($row["Type"], "AFE", $DE_standard_time);
										
					$AFE["standard"]=Date("Y-m-d", $AFE_standard_time);
				}
				$Item["AFE"]=$AFE;
				
				//DI
				$DI=array();
				if(isset($row["DI"])) {
					$DI["date"]=Date("Y-m-d", strtotime($row["DI"]));
					if($row["delay_DI"] != 0) 
						$DI["delay"]=$row["delay_DI"];
				} else {
					if(isset($row["AFE"]))
						$DI_standard_time=CalculateStandardDays($row["Type"], "DI", strtotime($row["AFE"]));
					else
						$DI_standard_time=CalculateStandardDays($row["Type"], "DI", $AFE_standard_time);
					
					$DI["standard"]=Date("Y-m-d", $DI_standard_time);
				}
				$Item["DI"]=$DI;
				
				//TAX
				$TAX=array();
				if(isset($row["TAX"])) {
					$TAX["date"]=Date("Y-m-d", strtotime($row["TAX"]));
					if($row["delay_TAX"] != 0) 
						$TAX["delay"]=$row["delay_TAX"];
				} else {
					if(isset($row["DI"]))
						$TAX_standard_time=CalculateStandardDays($row["Type"], "TAX", strtotime($row["DI"]));
					else
						$TAX_standard_time=CalculateStandardDays($row["Type"], "TAX", $DI_standard_time);
					
					$TAX["standard"]=Date("Y-m-d", $TAX_standard_time);
				}
				$Item["TAX"]=$TAX;
				
				//Customs
				$Customs=array();
				if(isset($row["Customs"])) {
					$Customs["date"]=Date("Y-m-d", strtotime($row["Customs"]));
					if($row["delay_Customs"] != 0) 
						$Customs["delay"]=$row["delay_Customs"];
				} else {
					if(isset($row["TAX"]))
						$Customs_standard_time=CalculateStandardDays($row["Type"], "Customs", strtotime($row["TAX"]));
					else
						$Customs_standard_time=CalculateStandardDays($row["Type"], "Customs", $TAX_standard_time);
					
					$Customs["standard"]=Date("Y-m-d", $Customs_standard_time);
				}
				$Item["Customs"]=$Customs;
				
				//Inspection
				$Inspection=array();
				if(isset($row["Inspection"])) {
					$Inspection["date"]=Date("Y-m-d", strtotime($row["Inspection"]));
					if($row["delay_Inspection"] != 0) 
						$Inspection["delay"]=$row["delay_Inspection"];
				} else {
					if(isset($row["Customs"]))
						$Inspection_standard_time=CalculateStandardDays($row["Type"], "Inspection", strtotime($row["Customs"]));
					else
						$Inspection_standard_time=CalculateStandardDays($row["Type"], "Inspection", $Customs_standard_time);
					
					$Inspection["standard"]=Date("Y-m-d", $Inspection_standard_time);
				}
				$Item["Inspection"]=$Inspection;
				
				//Warehouse
				$Warehouse=array();
				if(isset($row["Warehouse"])) {
					$Warehouse["date"]=Date("Y-m-d", strtotime($row["Warehouse"]));
					if($row["delay_Warehouse"] != 0) 
						$Warehouse["delay"]=$row["delay_Warehouse"];
				} else {
					if(isset($row["Inspection"]))
						$Warehouse_standard_time=CalculateStandardDays($row["Type"], "Warehouse", strtotime($row["Inspection"]));
					else
						$Warehouse_standard_time=CalculateStandardDays($row["Type"], "Warehouse", $Inspection_standard_time);
					
					$Warehouse["standard"]=Date("Y-m-d", $Warehouse_standard_time);
				}
				$Item["Warehouse"]=$Warehouse;
				
				$MediaItems= $Item;
			}
		}
		
		if(isset($MediaItems) and count($MediaItems)>0) {
			return $MediaItems;
		} else {
			return null;
		}
	}
	
	//计算平均延迟率和延迟天数
	function CalculateRate($CustomsID) {
		$querySQL = "SELECT TotalDelay FROM data_customs WHERE CustomsID=$CustomsID AND status='done' ";
		$result = mysql_query($querySQL);
		if($result) {
			$TotalCount = mysql_num_rows($result);
			//当没有数据时，返回空
			if($TotalCount == 0) {
				return array("av_delaydays"=>'-', "av_punctualrate"=>'-');
			}
			
			$DelayDays_Sum = 0;
			$Punctual_Sum = $TotalCount;
			
			while($row=mysql_fetch_array($result)) {
				if(isset($row["TotalDelay"]) && $row["TotalDelay"] > 0) {
					$DelayDays_Sum += $row["TotalDelay"];
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
		
	//
	function CheckAuth_buyer() {
		if(empty($_SESSION['curr_amore_admin_id'])) {
			exit(ReturnError(false, array("reason"=>"尚未登录")));
		}
	}
	
	function CheckAuth_customs() {
		if(empty($_SESSION['curr_customs_com_id'])) {
			exit(ReturnError(false, array("reason"=>"尚未登录")));
		}
	}
	
	function CheckAuth_customs_or_buyer() {
		if(empty($_SESSION['curr_customs_com_id']) && empty($_SESSION['curr_amore_admin_id']))
			return false;
	}

?>