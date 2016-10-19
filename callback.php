<?php
	echo "This is a callback";
	$data = array("time" => time(), "code" => "nocode");
	if (isset($_GET['code']) && !empty($_GET['code']))
	{
		echo "<br>Code Found : code => ".$_GET['code'];
		$data['code'] = $_GET['code'];
		$_SESSION['AUTHZ_CODE'] = $_GET['code'];
		if (isset($_GET['state']) && !empty($_GET['state']))
		{
			$data['state'] = $_GET['state'];
			$_SESSION['STATE'] = $_GET['state'];
		}
		echo "<br>Getting Token";
		// Get Token and Token Id

		$data['grant_type'] = "authorization_code";
		$data['redirect_uri'] = $FS_URL.$URL_CALLBACK;
		$data['client_id'] = $CLIENT_ID;
		$data['client_secret'] = $CLIENT_SECRET;
		$data['code'] = $_SESSION['AUTHZ_CODE'];

		echo "display options : ";
		print_r($data);

		$postfields = http_build_query($data);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $FC_URL."token");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_TIMEOUT, 4);

		$result = curl_exec ($ch);

		curl_close ($ch);

		$data['result'] = $result;
		echo "<br>GEtting Tokens Result recorded".$data['result'];

		// Result is a JSON object
		$data['tokens'] = json_decode($data['result']);

		// get Data from user with result

		echo "<br>GEtting DATA";


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
		//curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'GET');
		$curlData = curl_exec($curl); 
		if(!curl_errno($curl))
			$data['curlResult'] = $curlData;
		else
			$data['curlError'] = curl_error($curl);
		curl_close($curl);
		
		echo "data result => ".$data['curlResult'];

		$_SESSION['data'] = $data;
	}
	file_put_contents('resultAuth.txt', $data);
	echo "<br>Process Ended";
?>