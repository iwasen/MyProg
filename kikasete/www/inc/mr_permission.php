<?
/******************************************************
' System :�������ơ�net����
' Content:�ޡ�����������
'******************************************************/

define('PMR_MY_ENQUETE', 0);
define('PMR_MY_MONITOR', 1);
define('PMR_MY_SEARCH', 2);
define('PMR_MY_AGENT', 3);
define('PMR_ENQ_RESULT', 4);
define('PMR_REGIST_INF', 5);
define('PMR_ASSISTANT', 6);
define('PMR_CHG_AGENT', 7);

define('PMR_DEF_MARKETER', '1111111111');
define('PMR_DEF_ASSISTANT', '1111100000');

define('PMR_LEN', 10);

function check_permission($permission) {
	return $_SESSION['ss_permission']{$permission} == '1';
}

function input_permission($permission) {
?>
<input type="checkbox" name="pmr[]" value="<?=PMR_MY_ENQUETE?>" <?=checked($permission{PMR_MY_ENQUETE})?>>�ͣ����󥱡��ȡ��У�異�󥱡��Ȥκ���������
<br><input type="checkbox" name="pmr[]" value="<?=PMR_ENQ_RESULT?>" <?=checked($permission{PMR_ENQ_RESULT})?>>�������߼»ܤΥ��󥱡��Ⱦܺٱ��������������
<br><input type="checkbox" name="pmr[]" value="<?=PMR_MY_AGENT?>" <?=checked($permission{PMR_MY_AGENT})?>>����������Ȥؤ��䤤��碌
<br><input type="checkbox" name="pmr[]" value="<?=PMR_MY_MONITOR?>" <?=checked($permission{PMR_MY_MONITOR})?>>�ͣ��ѡ��ȥʡ��μ»�
<br><input type="checkbox" name="pmr[]" value="<?=PMR_MY_SEARCH?>" <?=checked($permission{PMR_MY_SEARCH})?>>�ͣ������������ꡦ����������
<?
}

function show_permission($permission) {
	if ($permission{PMR_MY_ENQUETE})
		echo '�ͣ����󥱡��ȡ��У�異�󥱡��Ȥκ���������<br>';
	if ($permission{PMR_ENQ_RESULT})
		echo '�������߼»ܤΥ��󥱡��Ⱦܺٱ��������������<br>';
	if ($permission{PMR_MY_AGENT})
		echo '����������Ȥؤ��䤤��碌<br>';
	if ($permission{PMR_MY_MONITOR})
		echo '�ͣ��ѡ��ȥʡ��μ»�<br>';
	if ($permission{PMR_MY_SEARCH})
		echo '�ͣ������������ꡦ����������<br>';
}
?>