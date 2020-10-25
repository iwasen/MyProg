<?php

function mm_chk_tbody($mag_titl, $mag_body, $mag_type) {
	$msg = "OK";
	if (strlen($mag_titl) == 0) {
		$msg = "・「タイトル」が記入されていないようです。<br><br>\n";
	}
	if (strlen($mag_body) == 0) {
		$msg = "・「本文」が記入されていないようです。<br><br>\n";
	}
	/* 01.07.31追加 */
	if (strlen($mag_titl) > 80) {
		$msg = "・タイトルが長すぎます。<br><br>\n";
	}
	if ( ( $mag_type == "01" and strlen($mag_body) > 50000) or
		 ( $mag_type == "02" and strlen($mag_body) >   400) ){
		$msg = "・本文が長すぎます。<br><br>\n";
	}
	return $msg;
}

?>
