<?php

function mm_chk_tbody($mag_titl, $mag_body, $mag_type) {
	$msg = "OK";
	if (strlen($mag_titl) == 0) {
		$msg = "���֥����ȥ�פ���������Ƥ��ʤ��褦�Ǥ���<br><br>\n";
	}
	if (strlen($mag_body) == 0) {
		$msg = "������ʸ�פ���������Ƥ��ʤ��褦�Ǥ���<br><br>\n";
	}
	/* 01.07.31�ɲ� */
	if (strlen($mag_titl) > 80) {
		$msg = "�������ȥ뤬Ĺ�����ޤ���<br><br>\n";
	}
	if ( ( $mag_type == "01" and strlen($mag_body) > 50000) or
		 ( $mag_type == "02" and strlen($mag_body) >   400) ){
		$msg = "����ʸ��Ĺ�����ޤ���<br><br>\n";
	}
	return $msg;
}

?>