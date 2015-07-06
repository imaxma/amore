<?php
require('../config.php');
require('../functions.php');

	session_start();
		
	$jsonData=file_get_contents("php://input");
	$jsonD=json_decode($jsonData); //解析JSON
	if($jsonD) {
		$_POST["username"] = $jsonD->username;
		$_POST["password"] = $jsonD->password;
		$jsonD->remember == true ?  $_POST["remember"]=true : $_POST["remember"]=false;
	}
	///
	
	if (!isset($_POST["username"]) || !isset($_POST["password"])) { exit(ReturnError(false, array("reason"=>"参数错误"))); };
		
	$Password = null;

	$querySQL="SELECT Id, CustomsID, Password FROM data_customs_operator WHERE name='".strtolower($_POST["username"])."' OR phone='".strtolower($_POST["username"])."' LIMIT 1";
		
	//echo $querySQL;
	$result=mysql_query($querySQL); 
	if($result && $row = mysql_fetch_array($result)) {
		$Password=$row["Password"];
		$User = $_POST["username"];

		if((md5($Password) === $_POST["password"]) || ($Password === $_POST["password"])) {
			
			//判断所在公司是否在合同期
			$query="SELECT ContractStart, ContractEnd, AccessStatus FROM data_customs_com WHERE id=".$row['CustomsID']." LIMIT 1";
			$ret = mysql_query($query);
			if($ret && $com=mysql_fetch_array($ret)) {
				if($com['AccessStatus'] !== 'normal') {
					exit(ReturnError(false, array("reason"=>"账号被禁用！")));
				}
									
				$Start = $com['ContractStart'];
				$End = $com['ContractEnd'];
				if(time() < strtotime($Start) || time() > (strtotime($End)+3600*24)) {
					exit(ReturnError(false, array("reason"=>"不在合同期内！")));
				}
			} else {
				exit(ReturnError(false, array("reason"=>"账号不存在！")));
			}
			
			//满足所有条件：
			$_SESSION['curr_operator_id'] = $row["Id"];
			$_SESSION['curr_customs_com_id'] = 	$row["CustomsID"];
			
			setcookie("username", $User, time()+3600*24*30); 
			if(isset($_POST["remember"])) {
				if($_POST["remember"]==true){
					setcookie("password", $Password, time()+3600*24*30); 	
				} else {
					setcookie("password", null);
				}
			}
			
			mysql_query("UPDATE data_customs_operator SET LastLoginTime='".Date("Y-m-d H:i:s")."' WHERE id=".$_SESSION['curr_operator_id']);
			exit(ReturnError(true));
		} else {
			exit(ReturnError(false, array("reason"=>"密码错误！")));
		}			
	} else {
		exit(ReturnError(false, array("reason"=>"账号不存在！")));
	}
	
?>