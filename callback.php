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

		fwrite($fp, "POST /reposter.php HTTP/1.1\r\n");
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
		// => openid (sub)










		$_SESSION['data'] = $data;
	}
	file_put_contents('resultAuth.txt', $data);
	echo "<br>Process Ended";
?>