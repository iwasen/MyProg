<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:事務局からのお知らせ情報更新処理
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");

// ブール型データチェック
function sql_bool_check($data) {
	if ($data) {
		return 't';
 	} else
		return 'f';
}

set_global('etc', 'その他管理', '事務局からのお知らせ', BACK_TOP);

switch ($next_action) {
case 'new':
	$sql = "INSERT INTO t_center_msg (cs_status,cs_date,cs_monitor_top,cs_monitor_mypage,cs_marketer_top,cs_marketer1_mypage,cs_marketer2_mypage,cs_agent_top,cs_agent_mypage,cs_title,cs_message,cs_start_date,cs_end_date) VALUES (" .
					"0," .
					"'now'" . "," .
					sql_bool(sql_bool_check($cs_monitor_top)) . "," .
					sql_bool(sql_bool_check($cs_monitor_mypage)) . "," .
					sql_bool(sql_bool_check($cs_marketer_top)) . "," .
					sql_bool(sql_bool_check($cs_marketer1_mypage)) . "," .
					sql_bool(sql_bool_check($cs_marketer2_mypage)) . "," .
					sql_bool(sql_bool_check($cs_agent_top)) . "," .
					sql_bool(sql_bool_check($cs_agent_mypage)) . "," .
					sql_char($cs_title) . "," .
					sql_char($cs_message) . "," .
					sql_date("$start_year/$start_month/$start_day") . "," .
					sql_date("$end_year/$end_month/$end_day") . ")";
	db_exec($sql);
	$msg = '事務局からのお知らせを登録しました。';
	$ret = 'location.href=\'list.php\'';
	break;
case 'update':
	$sql = "UPDATE t_center_msg SET" .
				" cs_status=" . sql_char($cs_status) .
				",cs_date='now'" .
				",cs_monitor_top=" . sql_bool(sql_bool_check($cs_monitor_top)) .
				",cs_monitor_mypage=" . sql_bool(sql_bool_check($cs_monitor_mypage)) .
				",cs_marketer_top=" . sql_bool(sql_bool_check($cs_marketer_top)) .
				",cs_marketer1_mypage=" . sql_bool(sql_bool_check($cs_marketer1_mypage)) .
				",cs_marketer2_mypage=" . sql_bool(sql_bool_check($cs_marketer2_mypage)) .
				",cs_agent_top=" . sql_bool(sql_bool_check($cs_agent_top)) .
				",cs_agent_mypage=" . sql_bool(sql_bool_check($cs_agent_mypage)) .
				",cs_message=" . sql_char($cs_message) .
				",cs_title=" . sql_char($cs_title) .
				",cs_start_date=" . sql_date("$start_year/$start_month/$start_day") .
				",cs_end_date=" . sql_date("$end_year/$end_month/$end_day") .
				" WHERE cs_seq_no=$cs_no";
	db_exec($sql);
	$msg = '事務局からのお知らせ情報を更新しました。';
	$ret = 'location.href=\'list.php\'';
	break;
case 'delete':
	$sql = "DELETE FROM t_center_msg WHERE cs_seq_no=$cs_no";
	db_exec($sql);
	$msg = '事務局からのお知らせを削除しました。';
	$ret = 'location.href=\'list.php\'';
	break;
default:
	redirect('list.php');
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body onLoad="document.all.ok.focus()">
<? page_header() ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="<?=$ret?>"></p>
</div>

<? page_footer() ?>
</body>
</html>
