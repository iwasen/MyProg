#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:デフォルト予約可能枠時間更新処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

db_begin_trans();

db_delete('m_default_waku', "dw_room_id=$room_id");

$n = count($start_hour);
for ($i = 0; $i < $n; $i++) {
	if ($start_hour[$i] != '' && $start_minute[$i] != '' && $end_hour[$i] != '' && $end_minute[$i] != '') {
		$rec['dw_room_id'] = sql_number($room_id);
		$rec['dw_start_time'] = sql_date("{$start_hour[$i]}:{$start_minute[$i]}:00");
		$rec['dw_end_time'] = sql_date("{$end_hour[$i]}:{$end_minute[$i]}:00");

		$sql = sprintf("SELECT COUNT(*) FROM m_default_waku WHERE dw_room_id=%s AND dw_start_time=%s AND dw_end_time=%s",
				$rec['dw_room_id'], $rec['dw_start_time'], $rec['dw_end_time']);
		if (db_fetch1($sql) == 0)
			db_insert('m_default_waku', $rec);
	}
}

db_commit_trans();
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header('デフォルト予約可能枠更新完了', true, true) ?>

<div align="center">
<p class="msg">デフォルト予約可能枠を更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php?shop_id=<?=$shop_id?>'"></p>
</div>

<? page_footer() ?>

</body>
</html>
