<?
/******************************************************
' System :�֤�����Factory�ץ��󥯥롼�ɥե�����
' Content:�ե����फ��ǡ�������
'******************************************************/

function get_name($s) {
	return strip_blank($s);
}

function get_name_kana($s) {
	return strip_blank(mb_convert_kana($s, 'KCV'));
}

function get_birthday($s) {
	return strip_blank(mb_convert_kana($s, 'n'));
}

function get_mail_addr($s) {
	return strip_blank($s);
}

function get_password($s) {
	return strip_blank($s);
}

function get_zip($s) {
	return strip_blank(mb_convert_kana($s, 'n'));
}

function get_tel($s) {
	return strip_blank(mb_convert_kana($s, 'n'));
}

function get_number($s) {
	return strip_blank(mb_convert_kana($s, 'n'));
}

function get_hankaku_alpha($s) {
	return strip_blank(mb_convert_kana($s, 'r'));
}
?>