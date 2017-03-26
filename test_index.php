<?php 
	define('TOKEN','xdw');
	$obj=new Weixin();

	if(!isset($_GET['echostr'])){

		$obj->receive();

	}else{
		$obj->checkSignature();
	}
	

class   Weixin{

		


	public  function checkSignature()
	    {
		        $signature = $_GET["signature"];   //加密签名
		        $timestamp = $_GET["timestamp"]; //时间戳
		        $nonce = $_GET["nonce"];	//随机数
		        		
			$token = TOKEN; //token


			$tmpArr = array($token, $timestamp, $nonce);//组成新数组
			sort($tmpArr, SORT_STRING);//重新排序
			$tmpStr = implode( $tmpArr );//转换成字符串
			$tmpStr = sha1( $tmpStr );  //再将字符串进行加密
			

			if( $tmpStr == $signature ){

				echo  $_GET['echostr'];
			}else{
				return false;
			}
		 }


    public  function  receive(){
    	
    	$obj=$GLOBALS['HTTP_RAW_POST_DATA'];
    	$postSql=simplexml_load_string($obj,'SimpleXMLElement',LIBXML_NOCDATA);


    	$this->logger("接受：\n".$obj);

    	if(!empty($postSql)){

    		switch(trim($postSql->MsgType)){

    			case "text" :
    			$result=$this->receiveText($postSql);

    			

    			if(!empty($result)){
    				echo $result;

    			}else{

    					$xml="<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
					  </xml>";
			   

				echo $result=sprintf($xml,$postSql->FromUserName,$postSql->ToUserName,time(),$postSql->MsgType,"没有这条文本消息");

    			}
    		}
    		
    	}
    }


    private function receiveText($postSql){
    		$content=trim($postSql->Content);

    		if(strstr($content,"你好")){
    			$xml="<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
					  </xml>";
			  

				$result=sprintf($xml,$postSql->FromUserName,$postSql->ToUserName,time(),$postSql->MsgType,"hello");

				return $result;

    		}
    }

    private function logger($content){
    	$logSize=100000;

    	$log="log.txt";

    	if(file_exists($log) && filesize($log)  > $logSize){
    		unlink($log);
    	}

    	file_put_contents($log,date('H:i:s')." ".$content."\n",FILE_APPEND);

    }

}


	



 ?>