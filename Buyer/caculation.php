<?php
require('../config.php'); 
require('../functions.php');
			
	session_start();
	CheckAuth_buyer();
	
	$_GET = array_change_key_case($_GET, CASE_LOWER);
	$_POST = array_change_key_case($_POST, CASE_LOWER);
	
	///////////
	$jsonData=file_get_contents("php://input");
	$jsonD=json_decode($jsonData); //解析JSON
	if($jsonD) {
		$_POST["lastid"] = $jsonD->LastItemID;
		if(isset($jsonD->Type)) $_POST["type"] = $jsonD->Type;
		if(isset($jsonD->CompanyID)) $_POST["companyid"] = $jsonD->CompanyID;
		if(isset($jsonD->batch)) $_POST["batch"] = $jsonD->batch;
	}
	///////////////
	
	if (isset($_GET["lastid"])) { $LastItemID=$_GET["lastid"]; } else if (isset($_POST["lastid"])) { $LastItemID=$_POST["lastid"]; };
	if (isset($_GET["type"])) { $SortType=$_GET["type"]; } else if (isset($_POST["type"])) { $SortType=$_POST["type"]; };	
	if (isset($_GET["companyid"])) { $SortCompany=$_GET["companyid"]; } else if (isset($_POST["companyid"])) { $SortCompany=$_POST["companyid"]; };
	if (isset($_GET["company"])) { $SortCompanyName=$_GET["company"]; } else if (isset($_POST["company"])) { $SortCompanyName=$_POST["company"]; };
	if (isset($_GET["batch"])) { $BatchNum=$_GET["batch"]; } else if (isset($_POST["batch"])) { $BatchNum=$_POST["batch"]; };
	
	//重要参数必须存在！
	if (!isset($LastItemID)) { echo ReturnError(false, array("reason"=>"wrong parameter")); exit; };
				
	$ItemCount = 6;
		
	$whereTmp="";
	if(isset($SortType)) {
		$whereTmp.="Type='".$SortType."' ";
	} else if (isset($SortCompany)) {
		$whereTmp.="CustomsID='".$SortCompany."' ";
	} else if (isset($BatchNum)) {
		$whereTmp.="BatchNum='".$BatchNum."' ";
	} else {
		$whereTmp.="1";
	}
		
	if($LastItemID > 0)
		$querySQL = "SELECT * FROM data_customs WHERE ".$whereTmp." AND ID<$LastItemID ORDER BY id DESC LIMIT $ItemCount";
	else 
		$querySQL = "SELECT * FROM data_customs WHERE ".$whereTmp." ORDER BY id DESC LIMIT $ItemCount";
	
	function CalculateDelayRate($CustomesID) {
		$querySQL = "SELECT TotalDelay FROM data_customs WHERE CustomsID=$CustomsID AND status='done' ";
		$result = mysql_query($querySQL);
		if($result) {
			$TotalCount = mysql_num_rows($result);
			//当没有数据时，返回空
			if($TotalCount == 0) {
				return array("av_delaydays"=>'-', "av_delayrate"=>'-');
			}
			
			$DelayDays_Sum = 0;
			$Delay_Sum = 0;
			
			while($row=mysql_fetch_array($result)) {
				if(!empty($row["TotalDelay"])) {
					$DelayDays_Sum += $row["TotalDelay"];
					$Delay_Sum++;
				}
			}
			
			$av_delaydays = round((float)$DelayDays_Sum / $TotalCount, 2);
			$av_delayrate = round((float)($Delay_Sum*100) / $TotalCount, 3);
			
			return array("av_delaydays"=>$av_delaydays, "av_delayrate"=>$av_delayrate);
		} else {
			return array("av_delaydays"=>'-', "av_delayrate"=>'-');
		}
	}
	
	
	
	
	
	//echo "<pre>".$querySQL;
	//$MediaItems[]=array();
	if ($result=mysql_query($querySQL)) 
	{
		while ($row = mysql_fetch_array($result)){
			$Item=array();
			$Item["id"]=$row["Id"];
			$Item["batch"]=$row["BatchNum"];
			$Item["customsid"]=$row["CustomsID"];
			$Item["type"]=$row["Type"];
			$Item["customs"]=GetCustomsCompanyName($row["CustomsID"]);
			$Item["etd"]=String2MonthDay($row["ETD"]);
			$Item["eta"]=String2MonthDay($row["ETA"]);
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
								
			$MediaItems[]= $Item;
		}
	}
	
	if(isset($MediaItems) and count($MediaItems)>0) {
		echo json_encode($MediaItems);
		//var_dump( $MediaItems );
	} else {
		echo "{}";
	}
?>