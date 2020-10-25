#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:部屋情報更新処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

if ($room_id) {
	$rec['rm_room_cd'] = sql_char($room_cd);
	$rec['rm_room_name'] = sql_char($room_name);
	$rec['rm_link_url'] = sql_char($link_url);
	$rec['rm_status'] = sql_number($status);
	$rec['rm_display_order'] = sql_number($display_order);
	$rec['rm_update_date'] = 'current_timestamp';
	db_update('m_room', $rec, "rm_room_id=" . sql_number($room_id));
	$msg = '部屋情報を更新しました。';
} else {
	$rec['rm_room_id'] = "COALESCE((select max(rm_room_id) from m_room),0)+1";
	$rec['rm_room_cd'] = sql_char($room_cd);
	$rec['rm_room_name'] = sql_char($room_name);
	$rec['rm_link_url'] = sql_char($link_url);
	$rec['rm_shop_id'] = sql_number($shop_id);
	$rec['rm_status'] = sql_number($status);
	$rec['rm_display_order'] = sql_number($display_order);
	db_insert('m_room', $rec);
	$msg = '部屋を新規登録しました。';
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body onLoad="document.all.ok.focus()">

<? page_header('部屋情報更新完了', true, true) ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='list.php?shop_id=<?=$shop_id?>'"></p>
</div>

<? page_footer() ?>

</body>
</html>
