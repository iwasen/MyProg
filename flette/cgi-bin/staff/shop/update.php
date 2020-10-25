#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:店舗情報更新処理
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

if ($shop_id) {
	$rec['sh_shop_name'] = sql_char($shop_name);
	$rec['sh_shop_address'] = sql_char($shop_address);
	$rec['sh_status'] = sql_number($status);
	$rec['sh_display_order'] = sql_number($display_order);
	$rec['sh_update_date'] = 'current_timestamp';
	db_update('m_shop', $rec, "sh_shop_id=" . sql_number($shop_id));
	$msg = '店舗情報を更新しました。';
} else {
	$rec['sh_shop_id'] = "COALESCE((select max(sh_shop_id) from m_shop),0)+1";
	$rec['sh_shop_name'] = sql_char($shop_name);
	$rec['sh_shop_address'] = sql_char($shop_address);
	$rec['sh_status'] = sql_number($status);
	$rec['sh_display_order'] = sql_number($display_order);
	db_insert('m_shop', $rec);
	$msg = '店舗を新規登録しました。';
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

<? page_header('店舗情報更新完了', true, true) ?>

<div align="center">
<p class="msg"><?=$msg?></p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='../index.php'"></p>
</div>

<? page_footer() ?>

</body>
</html>
