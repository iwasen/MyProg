<?
/******************************************************
' System :ICP���̥��󥯥롼�ɥե�����
' Content:�����å��ؿ�
'******************************************************/

// �᡼�륢�ɥ쥹�����å�
function check_mail_addr($addr) {
	return ereg("^[a-zA-Z0-9._/-]+@[a-zA-Z0-9_-]+\.[a-zA-Z0-9._-]+[^.]$", $addr);
}

// ���ͥ����å�
function check_num($num, $min=false, $max=false) {
	if (!is_numeric($num))
		return false;

	if ($min && $num < $min)
		return false;

	if ($max && $num > $max)
		return false;

	return true;
}

?>