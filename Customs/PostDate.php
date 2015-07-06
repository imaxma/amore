<?php
require('../config.php'); 
require('../functions.php');

	session_start();
			
	$_GET = array_change_key_case($_GET, CASE_LOWER);
	$_POST = array_change_key_case($_POST, CASE_LOWER);
	
	/////////////////////////////////////////////////////////////////////
	$jsonData=file_get_contents("php://input");
	$jsonD=json_decode(strtolower( $jsonData)); //解析JSON
	if($jsonD) {
		if(isset($jsonD->step)) $_POST["step"] = $jsonD->step;
		if(isset($jsonD->time)) $_POST["date"] = $jsonD->time/1000;
		if(isset($jsonD->id)) $_POST["id"] = $jsonD->id; 
	}	
	////////////////////////////////////////////////////////////////////////
		
	if (isset($_GET["step"])) { $Step=$_GET["step"]; } else if (isset($_POST["step"])) { $Step=$_POST["step"]; };
	if (isset($_GET["date"])) { $Date=$_GET["date"]; } else if (isset($_POST["date"])) { $Date=$_POST["date"]; };
	if (isset($_GET["id"])) { $BatchID=$_GET["id"]; } else if (isset($_POST["id"])) { $BatchID=$_POST["id"]; };
	
	//重要参数必须存在！
	if (!isset($Step) || !isset($Date) || !isset($BatchID)) { echo ReturnError(false, array("reason"=>"参数错误")); exit; };
		
	switch (strtolower($Step))
	{
		case "de":
			$Step="DE";
			$time_Step="time_DE";
			$delay_Step="delay_DE";
			$clear_rest="AFE=null, time_AFE=null, delay_AFE=0, ";
			break;
		case "afe":
			$Step="AFE";
			$time_Step="time_AFE";
			$delay_Step="delay_AFE";
			break;
		case "di":
			$Step="DI";
			$time_Step="time_DI";
			$delay_Step="delay_DI";
			break;
		case "tax":
			$Step="TAX";
			$time_Step="time_TAX";
			$delay_Step="delay_TAX";
			break;
		case "customs":
			$Step="Customs";
			$time_Step="time_Customs";
			$delay_Step="delay_Customs";
			break;
		case "inspection":
			$Step="Inspection";
			$time_Step="time_Inspection";
			$delay_Step="delay_Inspection";
			break;
		case "warehouse":
			$Step="Warehouse";
			$time_Step="time_Warehouse";
			$delay_Step="delay_Warehouse";
			break;
		default:
			echo ReturnError(false, array("reason"=>"wrong parameter")); 
			exit;		
	};
	
	$DelayDays=CalculateDelayDays($BatchID, $Step, $Date);
	if($DelayDays === "wrong") {
		exit(ReturnError(false, array("reason"=>"请按次序提交！")));
	} else if($DelayDays === "impossible") {
		exit(ReturnError(false, array("reason"=>"不能早于上一个步骤！")));
	}
	
	$clear_rest="";
	switch ($Step) 
	{
		case "DE":
			$clear_rest.="AFE=null, time_AFE=null, delay_AFE=0, ";
		case "AFE":
			$clear_rest.="DI=null, DI=null, delay_DI=0, ";
		case "DI":
			$clear_rest.="TAX=null, time_TAX=null, delay_TAX=0, ";
		case "TAX":
			$clear_rest.="Customs=null, time_Customs=null, delay_Customs=0, ";
		case "Customs":
			$clear_rest.="Inspection=null, time_Inspection=null, delay_Inspection=0, ";
		case "Inspection":
			$clear_rest.="Warehouse=null, time_Warehouse=null, delay_Warehouse=0, ";
			$clear_rest.="Status='inprogress', TotalDelay=0, ";
		case "Warehouse":
			break;
	};
	
	$sqltemp="";
	$sqltemp.=$Step."='".Date("Y-m-d H:i:s", $Date)."', ";
	$sqltemp.=$time_Step."='".date("Y-m-d H:i:s")."', ";
	$sqltemp.=$delay_Step."=".$DelayDays.", ";
	$sqltemp.=$clear_rest;
	
	//如果是最后一个步骤，则计算总延迟天数！
	if($Step === "Warehouse") {
		$sqltemp.="TotalDelay=".CalculateDelayDays($BatchID, "Total", $Date).", ";
		$sqltemp.="Status='done', ";
	}
	
	//去除最后一个
	$sqltemp = trim($sqltemp, " ,");
	$updateSQL="UPDATE data_customs SET $sqltemp WHERE ID=$BatchID AND CustomsID=".$_SESSION['curr_customs_com_id']." LIMIT 1";
	
	//echo "<pre>".$updateSQL;
	$result = mysql_query($updateSQL);
	if(mysql_affected_rows() > 0) {
		$info = QuerySingleItem($BatchID, null);
		echo ReturnError(true, $info); 
	} else {
		echo ReturnError(false, array("reason"=>"该批次不存在！"));
	}
				
?>