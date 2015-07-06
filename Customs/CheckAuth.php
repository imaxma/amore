<?php
require('../config.php');
require('../functions.php');

	session_start();
	
	if(empty($_SESSION['curr_customs_com_id'])) {
		exit(ReturnError(false, array("reason"=>"尚未登录")));
	} else {
		exit(ReturnError(true));
	}
?>