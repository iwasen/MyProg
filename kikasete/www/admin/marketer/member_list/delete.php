<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:���С��ꥹ��
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$monitor_id = get_multi_data($delete);
if ($monitor_id != '') {
	db_begin_trans();

	$sql = "DELETE FROM t_myenq_member_list WHERE mml_group_id=$group_id AND mml_monitor_id IN ($monitor_id)";
	db_exec($sql);

	$sql = "UPDATE t_myenq_member_grp SET mmg_update_date=CURRENT_TIMESTAMP WHERE mmg_group_id=$group_id";
	db_exec($sql);

	db_commit_trans();
}

redirect("m_list.php?group_id=$group_id");
?>