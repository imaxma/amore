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
		$_POST["id"] = $jsonD->id;
	}	
	///////////////
	
	if (isset($_GET["id"])) { $BatchID=$_GET["id"]; } else if (isset($_POST["id"])) { $BatchID=$_POST["id"]; };
	
	//重要参数必须存在！
	if (!isset($BatchID)) { echo ReturnError(false, array("reason"=>"wrong parameter")); exit; };
				
	$delSQL="DELETE FROM data_customs WHERE ID=$BatchID LIMIT 1";

	$result = mysql_query($delSQL);
	if(mysql_affected_rows() > 0)
		echo ReturnError(true);
	else
		echo ReturnError(false, "该批次不存在！");
?>