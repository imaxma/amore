<?php
require('../config.php');
require('../functions.php');

	//session_start();
		
	$jsonData=file_get_contents("php://input");
	$jsonD=json_decode($jsonData); //解析JSON
	if($jsonD) {
		$_POST["username"] = $jsonD->username;
		$_POST["password"] = $jsonD->password;
		$_POST["newpassword"] = $jsonD->new_password;
	}
	///
	//echo $_POST["password"]."   ";
	//echo $_POST["newpassword"]."   ";
	if (!isset($_POST["username"]) || !isset($_POST["newpassword"]) || !isset($_POST["password"])) { exit(ReturnError(false, array("reason"=>"参数错误"))); };
		
	$querySQL="SELECT Id, CustomsID, Password FROM data_customs_operator WHERE name='".strtolower($_POST["username"])."' OR phone='".$_POST["username"]."' LIMIT 1";
		
	//echo $querySQL;
	$result=mysql_query($querySQL); 
	if($result && $row = mysql_fetch_array($result)) {

		if($row["Password"] === $_POST["password"]) {
			
			mysql_query("UPDATE data_customs_operator SET Password='".$_POST["newpassword"]."' WHERE id=".$row['Id']." LIMIT 1");
			
			if(mysql_affected_rows() == 0) {
				exit(ReturnError(false, array("reason"=>"修改失败")));
			} else {
				exit(ReturnError(true));
			}
		} else {
			exit(ReturnError(false, array("reason"=>"密码错误！")));
		}			
	} else {
		exit(ReturnError(false, array("reason"=>"账号不存在！")));
	}
	
?>