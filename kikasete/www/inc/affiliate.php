<?
/******************************************************
' System :�������ơ�net����
' Content:���ե��ꥨ���ȴ�Ϣ
'******************************************************/

// ���ե��ꥨ���ȼ���
define('AFFILIATE_WEB', 1);			// Web
define('AFFILIATE_MAIL', 2);		// �᡼��

// ���ե��ꥨ���ȼ���̾����
function decode_affiliate_type($code) {
	switch ($code) {
	case AFFILIATE_WEB:
		return 'Web';
	case AFFILIATE_MAIL:
		return '�᡼��';
	}
}
?>