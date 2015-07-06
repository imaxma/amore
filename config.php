<?PHP
	//数据库连接类
	$lnk = @mysql_connect("localhost:3306", "root", ""); //链接服务器
	if($lnk)
	{	
		$dataconnect = @mysql_select_db("amore", $lnk) ;//选择数据库
		if (!$dataconnect)
		{	echo "mysql wrong"; exit;	}
	}	
	mysql_query("SET NAMES 'utf8'");
	
	//设定默认时区！
	date_default_timezone_set('PRC');

?>