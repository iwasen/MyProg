<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メンバーリスト
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$monitor_id = get_multi_data($delete);
if ($monitor_id != '') {
	db_begin_trans();

	$sql = "DELETE FROM t_member_list WHERE ml_group_id=$group_id AND ml_monitor_id IN ($monitor_id)";
	db_exec($sql);

	$sql = "UPDATE t_member_grp SET mg_update_date=CURRENT_TIMESTAMP WHERE mg_group_id=$group_id";
	db_exec($sql);

	db_commit_trans();
}

redirect("m_list.php?group_id=$group_id");
?>
