<?php 
	// FC ID
	$CLIENT_ID = "634481cb288709102a6d938ee887d7ca51b064d4fa051cc7f18505e827a1eb6a";
	$CLIENT_SECRET = "cfa06852cb0d7503aef5786c24737e1c263e5b4097fd56c3cdaebd77ab331d03";
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
?>
<br><br><br>
<?php
$action = "https://fcp.dev.dev-franceconnect.fr/api/v1/authorize?response_type=code&client_id=<?php echo $CLIENT_ID; ?>&redirect_uri=https%3A%2F%2Fbiodeploy.com%2api%2index.php?callback";
?>
<form method="GET" action="<?php echo $action; ?>">

<input type="submit" src="bouton.png" type="image" style="width:150px;height:100px;" />
</form>
<br>
<a href="https://fcp.integ01.dev-franceconnect.fr/apropos">Qu’est-ce que
FranceConnect ?</a>

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