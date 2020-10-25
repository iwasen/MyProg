<?
/******************************************************
' System :ICP会議室ページ
' Contentスレッド移動実行
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/remark.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");
include("$inc/err_msg.php");

// 発言ID取得
function get_remark_id($job_id, $disp_type, $seq_no) {
	if ($disp_type == 1 && $_SESSION[SCD]['room_type'] == ROOM_PIR) {
		$where = sprintf("rm_job_id=%s AND rm_disp_type=%s AND (rm_disp_member_id=%s OR rm_disp_member_id=0) AND rm_seq_no=%s AND rm_status=1",
				sql_number($job_id),
				sql_number($disp_type),
				sql_number($_SESSION[SCD]['disp_member_id']),
				sql_number($seq_no));
	} else {
		$where = sprintf("rm_job_id=%s AND rm_disp_type=%s AND rm_seq_no=%s AND rm_status=1",
				sql_number($job_id),
				sql_number($disp_type),
				sql_number($seq_no));
	}

	$sql = "SELECT rm_remark_id FROM l_remark WHERE $where";

	return db_fetch1($sql);
}

// 親子関係チェック
function check_parent_child($job_id, $disp_type, $from_id, $to_id) {
	$sql = sprintf(
				"SELECT rm_parent_remark_id"
			. " FROM l_remark"
			. " WHERE rm_job_id=%s AND rm_disp_type=%s AND rm_remark_id=%s AND rm_status=1",
			sql_number($job_id),
			sql_number($disp_type),
			sql_number($to_id));
	$parent_remark_id = db_fetch1($sql);

	if ($parent_remark_id == 0)
		return true;
	elseif ($parent_remark_id == $from_id)
		return false;
	else
		return check_parent_child($job_id, $disp_type, $from_id, $parent_remark_id);
}

// 入力パラメータ
$move_from = $_POST['move_from'];
$move_to = $_POST['move_to'];
$move_target = $_POST['move_target'];
$disp_type = $_POST['disp_type'];
$job_id = $_SESSION[SCD]['job_id'];

// 移動元IDチェック
$from_id = get_remark_id($job_id, $disp_type, $move_from);
if (!$from_id)
	$msg[] = "入力された移動元ID $move_from は存在しません。";

// 移動先IDチェック
if ($move_to == 0)
	$to_id = 0;
else {
	$to_id = get_remark_id($job_id, $disp_type, $move_to);
	if (!$to_id)
		$msg[] = "入力された移動先ID $move_to は存在しません。";
}

// 親子関係チェック
if (!$msg) {
	if ($move_target == 2 && $to_id != 0) {
		if (!check_parent_child($job_id, $disp_type, $from_id, $to_id))
			$msg[] = 'スレッド全体をそのスレッドの子の位置に移動させることはできません。';
	}
}

if (!$msg) {
	db_begin_trans();

	// 指定した発言のみの場合はその子を自分の親に接続
	if ($move_target == 1) {
		// 対象の親発言IDを取得
		$rec['rm_parent_remark_id'] = "(SELECT rm_parent_remark_id FROM l_remark WHERE rm_remark_id=$from_id)";
		db_update('l_remark', $rec, "rm_parent_remark_id=$from_id");

		// 子発言有無フラグ更新
		set_child_flag($from_id, false);
	}

	// スレッドの移動
	$rec['rm_parent_remark_id'] = sql_number($to_id);
	db_update('l_remark', $rec, "rm_remark_id=$from_id");

	// 子発言有無フラグ更新
	set_child_flag($to_id, true);

	db_commit_trans();

	redirect('move.php?reload_subject=1');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ja">

<head>
	<meta http-equiv="content-type" content="text/html; charset=EUC-JP">
	<meta http-equiv="content-style-type" content="text/css">
	<meta http-equiv="content-script-type" content="text/javascript">
	<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="../css/default.css">
	<title>スレッド移動エラー</title>
</head>

<body bgcolor="#ffffff" leftmargin="3" marginwidth="3" topmargin="3" marginheight="3" rightmargin="3" bottommargin="3">

<? disp_err('スレッド移動でエラーがありました。', $msg) ?>

</body>

</html>
