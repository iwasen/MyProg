<?
/******************************************************
' System :�������ơ�net��˥����ڡ���
' Content:�Ƽ�ɽ������
'******************************************************/

// ��˥���̾ɽ��
function disp_monitor_name() {
	$sql = "SELECT mn_name1 FROM t_monitor WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
	echo htmlspecialchars(db_fetch1($sql));
}
?>