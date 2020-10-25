#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:スタッフトップページ
'******************************************************/

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

if ($s_staff_auth == 1)
	$title = '管理者スタッフページ';
else
	$title = '店舗スタッフページ';
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
</head>
<body>

<? page_header($title, false, true) ?>

<?
if ($s_staff_auth == 1) {
?>
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td align="right">
			<input type="button" value="店舗新規登録" onclick="location.href='shop/new.php'">
			<input type="button" value="スタッフ管理" onclick="location.href='staff/list.php'">
			<input type="button" value="送信メールテンプレート管理" onclick="location.href='mail_temp/list.php'">
		</td>
	</tr>
</table>
<?
}
?>
■店舗一覧
<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th>ID</th>
		<th>店舗名</th>
		<th>住所</th>
<?
if ($s_staff_auth == 1) {
?>
		<th>店舗情報修正</th>
		<th>部屋情報修正</th>
<?
}
?>
		<th>予約設定画面表示</th>
	</tr>
<?
$sql = "SELECT sh_shop_id,sh_shop_name,sh_shop_address FROM m_shop ORDER BY sh_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$shop_id = $fetch->sh_shop_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$shop_id?></td>
		<td><?=htmlspecialchars($fetch->sh_shop_name)?></td>
		<td><?=htmlspecialchars($fetch->sh_shop_address)?></td>
<?
if ($s_staff_auth == 1) {
?>

		<td align="center"><input type="button" value="店舗修正" onclick="location.href='shop/edit.php?shop_id=<?=$shop_id?>'"></td>
		<td align="center"><input type="button" value="部屋管理" onclick="location.href='room/list.php?shop_id=<?=$shop_id?>'"></td>
<?
}
?>
		<td align="center"><input type="button" value="予約設定" onclick="location.href='reserve/monthly.php?shop_id=<?=$shop_id?>'"></td>
	</tr>
<?
}
?>
</table>

<? page_footer() ?>

</body>
</html>
