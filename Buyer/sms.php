<?php

function SendMessage($mobile, $content) {
	$target = "http://www.wemediacn.net/webservice/smsservice.asmx/SendSMS";
	
	if(empty($mobile) || empty($content)) { return false; }
	
	$data = "mobile=".$mobile."&FormatID=8&Content=".$content."&ScheduleDate=2010-1-1&TokenID=7103008230301295";
	$result =  xml_to_array(Post($data, $target));
	if(!empty($result['string']) && substr(trim($result['string']), 0, 2) === 'OK') {
		return true;
	} else {
		return false;
	}	
}

function Post($curlPost,$url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		$return_str = curl_exec($curl);
		curl_close($curl);
		return $return_str;
}
function xml_to_array($xml){
	$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
	if(preg_match_all($reg, $xml, $matches)){
		$count = count($matches[0]);
		for($i = 0; $i < $count; $i++){
		$subxml= $matches[2][$i];
		$key = $matches[1][$i];
			if(preg_match( $reg, $subxml )){
				$arr[$key] = xml_to_array( $subxml );
			}else{
				$arr[$key] = $subxml;
			}
		}
	}
	return $arr;
}
?>
