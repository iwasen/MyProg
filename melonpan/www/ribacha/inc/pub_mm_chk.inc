<?php

function mm_chk_tbody($mag_titl, $mag_body) {
	$msg = "OK";
	if (strlen($mag_titl) == 0) {
		$msg = "���֥����ȥ�פ���������Ƥ��ʤ��褦�Ǥ���<br><br>\n";
	}
	if (strlen($mag_body) == 0) {
		$msg = $msg . "������ʸ�פ���������Ƥ��ʤ��褦�Ǥ���<br><br>\n";
	}
	return $msg;
}

?>