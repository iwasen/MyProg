<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
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
define('PS_MONITOR_VISITOR', 0);		// �ӥ������ʥ�˥�����
define('PS_MONITOR', 1);						// ��˥���
define('PS_MARKETING_PARTNER', 2);	// �ꥵ�����ѡ��ȥʡ�
define('PS_MARKETER_VISITOR', 3);		// �ӥ������ʥޡ���������
define('PS_MARKETER_MAILNEWS', 4);	// �᡼��˥塼�����ɼ�
define('PS_MARKETER_FREE', 5);			// �ޡ���������̵����
define('PS_MARKETER_PAY', 6);				// �ޡ���������ͭ����
define('PS_MARKETER_GUEST', 7);			// �����ȡ�ͭ����Ͽ�ԤΡ�
define('PS_MARKETER_ASSISTANT', 8);	// �����������
define('PS_AGENT', 9);							// �����������
define('PS_AGENT_SUB', 10);					// ����������ȡʥ��֡�
define('PS_ADMIN', 11);							// ��̳��

// �����ƥ२�顼����
function system_error($msg, $fname) {
	global $top;
	redirect("$top/../common/system_error.php");
}
?>