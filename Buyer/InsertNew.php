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
		if(isset($jsonD->batch)) $_POST["batch"] = $jsonD->batch;
		if(isset($jsonD->type)) $_POST["type"] = $jsonD->type;
		if(isset($jsonD->customsid)) $_POST["customsid"] = $jsonD->customsid;
		if(isset($jsonD->etd)) $_POST["etd"] = strtotime($jsonD->etd);
		if(isset($jsonD->eta)) $_POST["eta"] = strtotime($jsonD->eta);
	}
	////////////////////////////////////////////////////////////////////////
		
	if (isset($_GET["batch"])) { $Batch=$_GET["batch"]; } else if (isset($_POST["batch"])) { $Batch=$_POST["batch"]; };
	if (isset($_GET["etd"])) {	$ETD=$_GET["etd"]; } else if (isset($_POST["etd"])) { $ETD=$_POST["etd"]; };
	if (isset($_GET["eta"])) {	$ETA=$_GET["eta"]; } else if (isset($_POST["eta"])) { $ETA=$_POST["eta"]; }
	if (isset($_GET["customsid"])) { $CustomsID=$_GET["customsid"]; } else if (isset($_POST["customsid"])) { $CustomsID=$_POST["customsid"]; }
	if (isset($_GET["type"])) {	$Type=$_GET["type"]; } else if (isset($_POST["type"])) { $Type=$_POST["type"]; }
		
	//重要参数必须存在！
	if (!isset($Batch) || !isset($ETD) || !isset($ETA) || !isset($CustomsID) || !isset($Type)) 
	{ 
		echo ReturnError(false, array("reason"=>"wrong parameter"));  exit; 
	}
	
	//if($ETD > $ETA) { echo ReturnError(false, array("reason"=>"ETA和ETD日期错误！"));  exit; };
	
	//判断是否批次号已经存在！
	$ret = mysql_query("SELECT * FROM data_customs WHERE BatchNum='".$Batch."' LIMIT 1");
	if(mysql_num_rows($ret)) {
		exit(ReturnError(false, array("reason"=>"已存在！")));
	}	
	
	$sqltmp="ETD='".Date("Y-m-d H:i:s",$ETD)."', ETA='".Date("Y-m-d H:i:s",$ETA)."', batchNum='".$Batch."', customsID=$CustomsID, type='".$Type."', ";
		
	$sqltmp.="CreatorID=".$_SESSION['curr_amore_admin_id'].", "; 
		
	$insertSQL="INSERT INTO data_customs SET ".$sqltmp." Status='inprogress', CreatedTime='".date("Y-m-d H:i:s")."'";

	$ret = mysql_query($insertSQL);
	
	if($ret)
		echo ReturnError(true);
	else
		echo ReturnError(false);

?>