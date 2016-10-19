<?php
	session_start();
	// Parameters
	include 'init.php';
	if (!isset($_SESSION['status']))
		$_SESSION['status'] = "disconnected";
	/*** Loading OpenID ***/
	//include 'Auth/OpenID.php';
	if (isset($_GET['callback']))
	{
		//echo "callback";
		include 'callback.php';
		exit();
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

	//$scope = array("openid", "given_name", "family_name", "email");
	//$scopeHttp = http_build_query($scope, ' ');

	$action = $FC_URL."authorize?response_type=code&client_id=".$CLIENT_ID."&redirect_uri=".urlencode($FS_URL.$FS_CALLBACK)."&scope=openid given_name family_name email&state=STATE&nonce=NONCE";
?>
<div style="text-align:center;">

<a href="<?php echo $action; ?>" style="display: block;margin:0 auto;width:300px;height:100px;background:url('bouton.png'); background-size: 100% 100%;"></a>
<br>

<a href="https://fcp.integ01.dev-franceconnect.fr/apropos">Qu’est-ce que
FranceConnect ?</a>

</div>


<br><br><br>
Lien Method GET : <?php echo $action; ?>
<br><br><br>
<?php

echo $_SESSION['status'];


	if ($_SESSION['status'] == "connected")
	{
		echo "Connected";
		$disconnection_link = $FC_URL."logout?id_token_hint=".$_SESSION['data']['tokens']['id_token']."&state=STATE&post_logout_redirect_uri=".urlencode($FC_URL);
		?>

			<?php echo "FirstName : ".$_SESSION['firstname']; ?>
			<?php echo "LastName : ".$_SESSION['lastname']; ?>
			<?php echo "Email : ".$_SESSION['email']; ?>
		
		<div id="fconnect-profile" data-fc-logout-url="<?php echo $disconnection_link; ?>">
		 <a href="#">Disconnection (place here the name of the user)</a>
		</div>

		<script src="http://fcp.integ01.dev-franceconnect.fr/js/franceconnect.js"></script>

		<?php
	}
	else
		echo "<br><br>Disconnected from France Connect";


	echo "<br><br>Status user connectivity to France Connect : ".$_SESSION['status']."<br><br><br>"; 
	echo "<br><br><br>Credits : Thomas LE MIGNAN - offered to you by BioDeploy.com<br><br><br>";

	// to see the details
	print_r($_SESSION);
?>