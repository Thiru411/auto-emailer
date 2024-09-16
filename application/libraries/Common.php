
<?php
class Common {
	public function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->library("session");
		$this->CI->load->library('email');
	}
    
function callAPI($method, $url, $data, $headers = false) {
	$curl = curl_init();
	 
	switch ($method) {
		case "POST":
			curl_setopt($curl, CURLOPT_POST, 1);
			if ($data) {
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data); }
				break;
		case "PUT":
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
			if ($data)
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			break;
			case "GET":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
		default:
			if ($data)
				$url = sprintf("%s?%s", $url, http_build_query($data));
	}

	// OPTIONS:
	curl_setopt($curl, CURLOPT_URL, $url);
	if(!$headers){
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'APIKEY: 111111111111111111111',
		'Content-Type: application/json',
		));
	}else{
		curl_setopt($curl, CURLOPT_HTTPHEADER,$headers );
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

	// EXECUTE:
	$result = curl_exec($curl);
	if(!$result){die("Connection Failure");}
	curl_close($curl);
	//print_r("++++".$data);
	return $result;
}

    public function response($scode,$flag,$msg,$resp){
        header("HTTP/1.1 $scode $flag", $flag, $scode);
        $ret = array(
                'status' => $flag,
                'message' => $msg,
                'data' => $resp

        );
        return $ret;
    }
}
?>