<?php
	echo "This is a callback";
	$data = array("time" => time(), "code" => "nocode");
	if (isset($_GET['code']) && !empty($_GET['code']))
	{
		echo "<br>Code Found";
		$data['code'] = $_GET['code'];
		$_SESSION['AUTHZ_CODE'] = $_GET['code'];
		if (isset($_GET['state']) && !empty($_GET['state']))
		{
			$data['state'] = $_GET['state'];
			$_SESSION['STATE'] = $_GET['state'];
		}
		echo "<br>Getting Token";
		// Get Token and Token Id
		$fp = fsockopen($FC_URL."token", 80);

		$data['grant_type'] = "authorization_code";
		$data['redirect_uri'] = $FS_URL.$URL_CALLBACK;
		$data['client_id'] = $CLIENT_ID;
		$data['client_secret'] = $CLIENT_SECRET;
		$data['code'] = $_SESSION['AUTHZ_CODE'];

		$content = http_build_query($data);

		fwrite($fp, "POST ".$FC_URL."token HTTP/1.1\r\n");
		fwrite($fp, "Host: ".$FS_URL."\r\n");
		fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
		fwrite($fp, "Content-Length: ".strlen($content)."\r\n");
		fwrite($fp, "Connection: close\r\n");
		fwrite($fp, "\r\n");

		fwrite($fp, $content);

		//header('Content-type: text/plain');

		$result = "";
		while (!feof($fp))
		{
		    $result += fgets($fp, 1024);
		}

		$data['result'] = $result;
		echo "<br>Result recorded";

		// Result is a JSON object
		$data['tokens'] = json_decode($data['result']);

		// get Data from user with result

		$askingfor = array();

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $FC_URL."userinfo?schema=openid"); 
		curl_setopt($curl, CURLOPT_PORT , 443); 
		curl_setopt($curl, CURLOPT_VERBOSE, 0); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$headers = array(
		    'Content-type: text/html',
		    'Authorization: Bearer '.$_SESSION['data']['tokens']['access_token']
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'GET');
		$curlData = curl_exec($curl); 
		if(!curl_errno($curl))
			$data['curlResult'] = $curlData;
		else
			$data['curlError'] = curl_error($curl);
		curl_close($curl);

		$_SESSION['data'] = $data;
	}
	file_put_contents('resultAuth.txt', $data);
	echo "<br>Process Ended";
?>