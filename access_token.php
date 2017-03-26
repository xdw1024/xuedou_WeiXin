<?php 
	$appID = 'wx932ef8f57be572a5';
	$appsecret = '09516fea1a6a98f5e83b966d25cc6290';
	$url_access_token = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appID.'&secret='.$appsecret;

	// 1. 初始化
	$ch = curl_init();
	// 2. 设置选项，包括URL
	curl_setopt($ch,CURLOPT_URL,$url_access_token);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_HEADER,0);
	// 3. 执行并获取HTML文档内容
	$output = curl_exec($ch);
	if($output === FALSE ){
		echo "CURL Error:".curl_error($ch);
	}
	else{
		$obj = json_decode($output);
		echo '<pre>';
		print_r($obj);
		echo '</pre>';

		$access_token = $obj->access_token;
		$url_server_ip = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$access_token;
		$my = curl_init();
		curl_setopt($my,CURLOPT_URL,$url_server_ip);
		curl_setopt($my,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($my,CURLOPT_HEADER,0);
		$myoutput = curl_exec($my);
		if($myoutput === FALSE ){
			echo "CURL Error:".curl_error($my);
		}
		else{
			$str = json_decode($myoutput);
			echo '<pre>';
			print_r($str);
			echo '</pre>';
		}
		curl_close($my);
	}
	// 4. 释放curl句柄
	curl_close($ch);


