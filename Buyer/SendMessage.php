<?php
require('../config.php');
require('../functions.php');
require('SMS.php');

	session_start();
	CheckAuth_buyer();

	$_GET = array_change_key_case($_GET, CASE_LOWER);
	$_POST = array_change_key_case($_POST, CASE_LOWER);
	
	///////////
	$jsonData=file_get_contents("php://input");
	$jsonD=json_decode($jsonData); //解析JSON
	if(!empty($jsonD)) {
		if(isset($jsonD->id)) $_POST["id"] = $jsonD->id;
	}
	///////////////
	
	if (isset($_GET["id"])) { $BatchID=$_GET["id"]; } else if (isset($_POST["id"])) { $BatchID=$_POST["id"]; };
	
	//重要参数必须存在！
	if (!isset($BatchID)) { echo ReturnError(false, array("reason"=>"wrong parameter")); exit; };
		
	//$querySQL="SELECT CustomsID FROM data_customs WHERE ID=$BatchID LIMIT 1";
	$ret=mysql_query("SELECT CustomsID, BatchNum, Type FROM data_customs WHERE ID=$BatchID LIMIT 1");
	if($ret) {
		while($row = mysql_fetch_array($ret)) {
			$row['Type']=='air' ? $type='空运' : $row['Type']=='full' ? $type='整柜' : $type='散货';
			$content = "爱茉莉工厂提醒您：批次号为".$row['BatchNum']."(".$type.")的通关货物需要您尽快更新进度。";
			$result=mysql_query("SELECT phone FROM data_customs_operator WHERE CustomsID=".$row['CustomsID']);
			if($result) {
				while($row1 = mysql_fetch_array($result)) {
					if(!empty($row1['phone'])) {
						if(false == SendMessage($row1['phone'], $content)) {
							$bFailed = true;
						}
					}
				}
			} else {
				echo ReturnError(false, array("reason"=>"无有效的报关人员"));
				exit;
			}
		}
	} else {
		echo ReturnError(false, array("reason"=>"批次不存在！"));
		exit;
	}
	
	if(isset($bFailed))
		echo ReturnError(false, array("reason"=>"发送失败"));
	else
		echo ReturnError(true);
?>