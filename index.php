<?php
	session_start();
	// Parameters
	include 'init.php';
	$_SESSION['status'] = "disconnected";
	/*** Loading OpenID ***/
	include 'Auth/OpenID.php';
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
	$action = $FC_URL."authorize?response_type=code&client_id=".$CLIENT_ID."&redirect_uri=".urlencode($FS_URL.$FS_CALLBACK)."&scope=SCOPES&state=STATE&nonce=NONCE";
?>
<form method="get" action="<?php echo $action; ?>" style="text-align:center;">

<input type="submit" type="image" value="" style="margin:0 auto;width:300px;height:100px;background:url('bouton.png'); background-size: 100% 100%;" />
<br>

<a href="https://fcp.integ01.dev-franceconnect.fr/apropos">Qu’est-ce que
FranceConnect ?</a>
</form>


<br><br><br>
Lien Method GET : <?php echo $action; ?>
<br><br><br>
<?php


	if ($_SESSION['status'] == "connected")
	{
		echo "Connected";
		$disconnection_link = $FC_URL."logout?id_token_hint=".$_SESSION['data']['tokens']['id_token']."&state=STATE&post_logout_redirect_uri=".urlencode($FC_URL);
		?>

		Your very name is : FirstName, Lastname

		

		<div id="fconnect-profile" data-fc-logout-url="<?php echo $disconnection_link; ?>">
		 <a href="#">Disconnection (place here the name of the user)</a>
		</div>


		<script src="http://fcp.integ01.dev-franceconnect.fr/js/franceconnect.js"></script>

		<?php
	}
	else
		echo "<br><br>Disconnected from France Connect";


	echo "<br><br>Status user connectivity to France Connect : ".$_SESSION['status']."<br><br><br>"; 
	echo "<br><br><br>Credits : Thomas LE MIGNAN - BioDeploy.com";

	// to see the details
	print_r($_SESSION['data']);