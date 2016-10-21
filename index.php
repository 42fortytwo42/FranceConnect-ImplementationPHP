<?php
	// Variables France Connect
	$FC_URL = "https://fcp.integ01.dev-franceconnect.fr/api/v1/";
	$CLIENT_ID = "634481cb288709102a6d938ee887d7ca51b064d4fa051cc7f18505e827a1eb6a";
	$CLIENT_SECRET = "cfa06852cb0d7503aef5786c24737e1c263e5b4097fd56c3cdaebd77ab331d03";
	$FS_URL = "https://biodeploy.com/";
	$FS_CALLBACK = "api/index.php?callback";
	session_start();
	if (!isset($_SESSION['status']))
		$_SESSION['status'] = "disconnected";
	if (isset($_GET['callback']))
	{
		$data = array();
		if (isset($_GET['code']) && !empty($_GET['code']))
		{
			// Phase ONE
			echo "<br><h2>Phase ONE</h2><br><h3>Get Code from France Connect</h3><br>Code Found : code => ".$_GET['code']."<br>";
			$data['code'] = $_GET['code'];
			$_SESSION['AUTHZ_CODE'] = $_GET['code'];
			echo "<br><h2>Phase TWO</h2><br><h3>Get Token Access and token ID from France Connect</h3><br>";
			// Phase TWO
			$data['grant_type'] = "authorization_code";
			$data['redirect_uri'] = $FS_URL.$FS_CALLBACK;
			$data['client_id'] = $CLIENT_ID;
			$data['client_secret'] = $CLIENT_SECRET;
			$data['code'] = $_SESSION['AUTHZ_CODE'];
			$postfields = http_build_query($data);
			echo "<br>Post Fields To be Sent : ".$postfields."<br>";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $FC_URL."token");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 4);
			$result = curl_exec ($ch);
			curl_close ($ch);
			$data['result'] = $result;
			echo "<br>Result recorded";
			// Result is a JSON object
			$tokens = (array)json_decode($result);
			if (isset($tokens['error']))
			{
				echo "<h1>fatal error blue screen window...</h1>";
				exit();
			}
			else if (isset($tokens['access_token']))
			{
				echo "Access TOKEN => ".$tokens['access_token'];
				// Phase THREE get Data from user with result
				echo "<br><h2>Phase THREE</h2><br><h3>Get User Data from France Connect</h3><br>";
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $FC_URL."userinfo?schema=openid"); 
				curl_setopt($curl, CURLOPT_PORT , 443); 
				curl_setopt($curl, CURLOPT_VERBOSE, 0); 
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 4);
				$headers = array(
				    'Content-type: text/html',
				    'Authorization: Bearer '.$tokens['access_token']
				);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'GET');
				$curlData = curl_exec($curl); 
				if(!curl_errno($curl))
					$data['curlResult'] = $curlData;
				else
					$data['curlError'] = curl_error($curl);
				curl_close($curl);
				$data['resultFull'] = (array)json_decode($data['curlResult']);
				$_SESSION['status'] = "connected";
				if (isset($data['resultFull']['given_name']) && isset($data['resultFull']['family_name']) && isset($data['resultFull']['email']))
				{
					$_SESSION['firstname'] = $data['resultFull']['given_name'];
					$_SESSION['lastname'] = $data['resultFull']['family_name'];
					$_SESSION['email'] = $data['resultFull']['email'];
					echo "FirstName : ".$_SESSION['firstname'];
					echo "LastName : ".$_SESSION['lastname'];
					echo "Email : ".$_SESSION['email'];
				}
				else
					print_r($data['resultFull']);	
			}
			$_SESSION['data'] = $data;
		}
		// Writting some data if needed
		/*** file_put_contents('resultAuth.txt', $data); ***/
		echo "<br><h1>Callback Process Ended</h1>";
	}
	else if (isset($_GET['disconnect']))
	{
		session_unset();
		session_destroy();
		session_start();
		echo "<h3>disconnected</h3>";
	}
	echo "<h1>API France Connect - DEV </h1>";
	echo "<br>Key FC => ".$CLIENT_ID."<br>";
	echo "<br>Secret FC => ".$CLIENT_SECRET."<br>";
	echo "<h2>Parameters</h2>";
	echo "<br>FS_URL => ".$FS_URL;
	echo "<br>FS_CALLBACK => ".$FS_CALLBACK;
?>
<br><br><br>
<?php

	$scope = array("openid", "given_name", "family_name", "email");
	$scopeHttp = implode(' ', $scope);

	$action = $FC_URL."authorize?response_type=code&client_id=".$CLIENT_ID."&redirect_uri=".urlencode($FS_URL.$FS_CALLBACK)."&scope=openid given_name family_name email&state=STATE&nonce=NONCE";
?>
<div style="text-align:center;">
	<a href="<?php echo $action; ?>" style="display:block;margin:0 auto;width:300px;height:100px;background:url('bouton.png');background-size: 100% 100%;border:solid 1px #ccc;"></a>
	<br>

	<a href="https://fcp.integ01.dev-franceconnect.fr/apropos">Qu’est-ce que
	FranceConnect ?</a>
</div>
<br>
<strong>Lien Method GET : </strong><div style="font-style:italic;"><?php echo $action; ?></div>
<br>
<?php
	if ($_SESSION['status'] == "connected")
	{
		$disconnection_link = $FC_URL."logout?id_token_hint=".$_SESSION['data']['tokens']['id_token']."&state=STATE&post_logout_redirect_uri=".urlencode($FC_URL);
		?>
			<?php echo "FirstName : ".$_SESSION['firstname']; ?>
			<br>
			<?php echo "LastName : ".$_SESSION['lastname']; ?>
			<br>
			<?php echo "Email : ".$_SESSION['email']; ?>
			<br><br>
		<div id="fconnect-profile" data-fc-logout-url="<?php echo $disconnection_link; ?>">
		 <a href="#">Disconnection (<?php echo "FirstName : ".$_SESSION['firstname']; ?>)</a>
		</div>

		<script src="http://fcp.integ01.dev-franceconnect.fr/js/franceconnect.js"></script>

		<?php
	}
	echo "<br><br><h2>Status user connectivity to France Connect : ".$_SESSION['status']."</h2><br>"; 
	echo "<h3>Credits : Thomas LE MIGNAN - offered to you by BioDeploy.com</h3>";
?>