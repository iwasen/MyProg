<?php

function mm_chk_tbody($mag_titl, $mag_body) {
	$msg = "OK";
	if (strlen($mag_titl) == 0) {
		$msg = "・「タイトル」が記入されていないようです。<br><br>\n";
	}
	if (strlen($mag_body) == 0) {
		$msg = $msg . "・「本文」が記入されていないようです。<br><br>\n";
	}
	return $msg;
}

?>
