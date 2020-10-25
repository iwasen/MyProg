<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:メンバーリスト
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$monitor_id = get_multi_data($select);
if ($monitor_id != '') {
	db_begin_trans();

	$sql = "INSERT INTO t_myenq_member_list (mml_group_id,mml_monitor_id)"
			. " SELECT $group_id,mn_monitor_id FROM t_monitor WHERE mn_monitor_id IN ($monitor_id)"
			. " AND NOT EXISTS (SELECT * FROM t_myenq_member_list WHERE mml_group_id=$group_id AND mml_monitor_id=mn_monitor_id)";
	db_exec($sql);

	$sql = "UPDATE t_myenq_member_grp SET mmg_update_date=CURRENT_TIMESTAMP WHERE mmg_group_id=$group_id";
	db_exec($sql);

	db_commit_trans();
} elseif ($monitor_id_ary != '') {
	db_begin_trans();

	$ary = explode(",", $monitor_id_ary);
	foreach ($ary as $monitor_id) {
		$sql = "INSERT INTO t_myenq_member_list (mml_group_id,mml_monitor_id) VALUES ($group_id,$monitor_id)";
		db_exec($sql);
	}

	$sql = "UPDATE t_myenq_member_grp SET mmg_update_date=CURRENT_TIMESTAMP WHERE mmg_group_id=$group_id";
	db_exec($sql);

	db_commit_trans();
}

redirect("m_list.php?group_id=$group_id");
?>
