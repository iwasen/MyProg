<?php

/* Using:

	<?php
	session_start();
	?>
	<form action="./" method="post">
	<p>Enter text shown below:</p>
	<p><img src="PATH-TO-THIS-SCRIPT?<?php echo session_name()?>=<?php echo session_id()?>"></p>
	<p><input type="text" name="keystring"></p>
	<p><input type="submit" value="Check"></p>
	</form>
	<?php
	if(count($_POST)>0){
		if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] ==  $_POST['keystring']){
			echo "Correct";
		}else{
			echo "Wrong";
		}
	}
	unset($_SESSION['captcha_keystring']);
	?>

*/

include('kcaptcha.php');

$keys = array_keys($_GET);
$session_name = $keys[0];
session_name($session_name);
session_id($_GET[$session_name]);
session_start();

$captcha = new KCAPTCHA();

$_SESSION['captcha_keystring'] = $captcha->getKeyString();
