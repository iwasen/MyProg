<?php
function err_msg ($msg_code, $p_titl) {
	switch ($msg_code) {
	case "PP001":	//publisher_id �������顼
		$l_msg = "�ǡ����١�������³�Ǥ��ޤ���Ǥ���";
		break;
	case "PP002":	//publisher�ޥ����������������顼
		$l_msg = "�ǡ����١�������³�Ǥ��ޤ���Ǥ���";
		break;
	case "PM001":	//magazine_id �������顼
		$l_msg = "�ǡ����١�������³�Ǥ��ޤ���Ǥ���";
		break;
	case "PM002":	//magazine�ޥ����������������顼
		$l_msg = "�ǡ����١�������³�Ǥ��ޤ���Ǥ���";
		break;
	default:
		$l_msg = "���顼";
		break;
	}
	$print_msg =  $l_msg . "(" . $msg_code . ")";
	include ("err-msg.html");
}
?>