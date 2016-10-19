<?php 
	// FC ID
	$key_FC = "634481cb288709102a6d938ee887d7ca51b064d4fa051cc7f18505e827a1eb6a";
	$secret_FC = "cfa06852cb0d7503aef5786c24737e1c263e5b4097fd56c3cdaebd77ab331d03";
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
	echo "<br>Key FC => ".$key_FC."<br>";
	echo "<br>Secret FC => ".$secret_FC."<br>";
?>
<br><br><br>
<a href=\"\"><img src="bouton.png" /></a><br>
<a href="https://fcp.integ01.dev-franceconnect.fr/apropos">Qu’est-ce que
FranceConnect ?</a>
<?php
	echo "<br><br>Status FC : ".$status."<br><br><br>"; 
	echo "Data : "."<br>";
	echo "Firstname : "."<br>";
	echo "LastName : "."<br>";

	echo "<br><br><br>Credits : Thomas LE MIGNAN - BioDeploy.com";
?>