<?php
require('../config.php');
require('../functions.php');
	
	session_start();
	
	$_GET = array_change_key_case($_GET, CASE_LOWER);
	$_POST = array_change_key_case($_POST, CASE_LOWER);
	
	////////////////
	$jsonData=file_get_contents("php://input");
	$jsonD=json_decode( $jsonData); //解析JSON
	if($jsonD) {
		if(isset($jsonD->notes)) $_POST["notes"] = $jsonD->notes;
		if(isset($jsonD->id)) $_POST["id"] = $jsonD->id; 
	}	
	////////////////
	
	if (isset($_GET["notes"])) { $Notes=$_GET["notes"]; } else if (isset($_POST["notes"])) { $Notes=$_POST["notes"]; };
	if (isset($_GET["id"])) { $BatchID=$_GET["id"]; } else if (isset($_POST["id"])) { $BatchID=$_POST["id"]; };
	
	//重要参数必须存在！
	if (!isset($Notes) || !isset($BatchID)) { echo ReturnError(false, array("reason"=>"wrong parameter")); exit; };
	if (count($Notes) > 255 ) { echo ReturnError(false, array("reason"=>"wrong parameter")); exit; };
				
	$updateSQL="UPDATE data_customs SET Notes='".$Notes."' WHERE ID=$BatchID AND CustomsID=".$_SESSION['curr_customs_com_id']." LIMIT 1";
	//echo "<pre>".$updateSQL;

	$result = mysql_query($updateSQL);
	if(mysql_affected_rows() > 0) {
		echo ReturnError(true);
	} else {
		echo ReturnError(false, array("reason"=>"未修改"));
	}
		
?>