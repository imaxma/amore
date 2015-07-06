<?php

	require('../config.php');
	require('../functions.php');
	
	//session_start();
	//CheckAuth_buyer();
			
	$querySQL="SELECT id, name, AccessStatus, ContractStart, ContractEnd FROM data_customs_com WHERE 1";

	$result = mysql_query($querySQL);
	if($result) {
		while($row = mysql_fetch_array($result)) {
			$Company = array();
			$Company["id"]=$row["id"];
			$Company["name"]=$row["name"];
			if($row['AccessStatus'] === 'normal') {  //没有被禁止
				$Start = $row['ContractStart'];		//合同开始日
				$End = $row['ContractEnd'];          //合同结束日+24小时
				if(time() < strtotime($Start) || time() > (strtotime($End)+3600*24)) {
					$Company["status"] = false;     //不在合同期
				} else {
					$Company["status"] = true;		//正常状态
				}
			} else {
				$Company["status"] = false;
			}
			
			$AllCompany[] = $Company;
		}
	}
	
	if(isset($AllCompany) and count($AllCompany)>0) {
		echo json_encode($AllCompany);
	} else {
		echo "{}";
	}
?>