<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�����̳���ѥڡ���
' Content:������
'******************************************************/

define('MUST_ITEM', '<sup><font color="green">*</font></sup>');
define('LIST_TABLE', 'border=1 cellspacing=0 cellpadding=1 bordercolorlight="#f0f0f0" frame="box"');

// �����
define('BACK_NONE', 0);
define('BACK_LOGOFF', 1);
define('BACK_TOP', 2);
define('BACK_CLOSE', 3);

// �о��ʪ°��
define('PS_MONITOR', 1);			// ��˥���
define('PS_STAFF', 2);				// �����å�
define('PS_ADMIN', 3);				// ��̳��

// �����ƥ२�顼����
function system_error($msg, $fname) {
	global $top;
	redirect("$top/../common/system_error.php");
}
?>