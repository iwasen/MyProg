<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:スペシャルパネル管理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

switch ($_REQUEST['next_action']) {
case 'add':
	db_begin_trans();

	$ary = explode(",", $monitor_id_ary);
	foreach ($ary as $monitor_id) {
		$rec['pnm_panel_id'] = sql_number($_REQUEST['panel_id']);
		$rec['pnm_monitor_id'] = sql_number($monitor_id);
		db_insert('t_panel_monitor_list', $rec);
	}

	$rec['pnl_monitor_update_date'] = 'current_timestamp';
	db_update('t_panel', $rec, sprintf("pnl_panel_id=%s", $_REQUEST['panel_id']));

	db_commit_trans();
	break;
case 'delete':
	$monitor_id = get_multi_data($_REQUEST['delete']);
	if ($monitor_id != '') {
		db_begin_trans();

		db_delete('t_panel_monitor_list', sprintf("pnm_panel_id=%s AND pnm_monitor_id IN ($monitor_id)", sql_number($_REQUEST['panel_id'])));

		$rec['pnl_monitor_update_date'] = 'current_timestamp';
		db_update('t_panel', $rec, sprintf("pnl_panel_id=%s", $_REQUEST['panel_id']));

		db_commit_trans();
	}
	break;
}

redirect("monitor_list.php?category_id={$_REQUEST['category_id']}&panel_id={$_REQUEST['panel_id']}");
?>
