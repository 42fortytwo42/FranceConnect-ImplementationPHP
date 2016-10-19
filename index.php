<?php
	// Parameters
	include 'init.php';
	$status = "disconnected";
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
	$action = $FC_URL."authorize?response_type=code&client_id=".$CLIENT_ID."&redirect_uri=".urlencode($FS_URL.$FS_CALLBACK)."&scope=<SCOPES>&state=<STATE>&nonce=<NONCE>";
?>
<form method="GET" action="<?php echo $action; ?>" style="text-align:center;">

<input type="submit" type="image" value="" style="margin:0 auto;width:300px;height:100px;background:url('bouton.png'); background-size: 100% 100%;" />
<br>

<a href="https://fcp.integ01.dev-franceconnect.fr/apropos">Qu’est-ce que
FranceConnect ?</a>
</form>


<br><br><br>
Lien Method GET : <?php echo $action; ?>
<br><br><br>
<?php
	echo "<br><br>Status FC : ".$status."<br><br><br>"; 
	echo "Data : "."<br>";
	echo "Firstname : "."<br>";
	echo "LastName : "."<br>";

	echo "<br><br><br>Credits : Thomas LE MIGNAN - BioDeploy.com";

	if ($status == "connected")
	{
		echo "Connected";
		?>

		<script src="http://fcp.integ01.dev-franceconnect.fr/js/franceconnect.js"></script>

		<div id="fconnect-profile" data-fc-logout-url="/lien-deconnexion">
		 <a href="#">Disconnection</a>
		</div>

		<?php
	}
	else
		echo "<br><br>Disconnected from France Connect";