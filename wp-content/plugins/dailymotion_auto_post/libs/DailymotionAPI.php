<?php 

class FB_DailyMotion {
	private $apiKey, $apiSecret, $dm;
	private $apiUrl = "https://api.dailymotion.com/";
	
	function __construct($apiK, $apiS) {
		$this->apiKey = $apiK;
		$this->apiSecret = $apiS;
	}
   
	function getUserVideos($userID, $pageNo=1 , $limit=100) {
		$query = $this->genApiUrl("user/".$userID . "/videos/", array("page" => $pageNo, "limit" => $limit));
		$result = $this->decode($this->curl($query));
		
		if($this->isError($result))
			return false;
			
		
		return $result;
	}
	
	function getVideo($videoID, $fields) {
		$query = $this->genApiUrl("video/".$videoID, array("fields" => $fields));
		$result = $this->decode($this->curl($query));
		
		if($this->isError($result))
			return false;
			
		
		return $result;
	}
	
	function genApiUrl($path, $params = array()) {
		$getVars = "";
		if(!empty($params)) {
			$count = 0;
			$getVars .= "?";
			foreach($params as $param => $val) {
				$getVars .= ($count > 0) ? "&" : "";
				$getVars .= $param . "=" . urlencode($val);
				$count++;
			}
		}
		
		return $this->apiUrl . $path . $getVars;
	}
	
	function isError($result) {
		if(array_key_exists("error",$result))
			return true;
			
		return false;
	}
	
	function decode($json) {
		return (array) json_decode($json);
	}
	
	function curl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}