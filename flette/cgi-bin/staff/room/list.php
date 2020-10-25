#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:登録部屋情報一覧表示
'******************************************************/

$top = '..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");
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

<? page_header('登録済み部屋一覧', true, true) ?>

<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td align="right">
			<input type="button" value="部屋新規登録" onclick="location.href='new.php?shop_id=<?=$shop_id?>'">
			<input type="button" value="　戻る　" onclick="location.href='../index.php'">
		</td>
	</tr>
</table>

■登録部屋情報一覧　（店舗：<?=decode_shop($shop_id)?>）
<table <?=LIST_TABLE?> width="100%">
	<tr class="tch">
		<th>ID</th>
		<th>部屋名称</th>
		<th>表示順序</th>
		<th>ステータス</th>
		<th>部屋情報修正</th>
		<th>ﾃﾞﾌｫﾙﾄ予約可能枠</th>
	</tr>
<?
$sql = "SELECT rm_room_id,rm_room_name,rm_status,rm_display_order"
		. " FROM m_room"
		. " WHERE rm_shop_id=$shop_id"
		. " ORDER BY rm_display_order";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$room_id = $fetch->rm_room_id;
?>
	<tr class="tc<?=$i % 2?>">
		<td align="center"><?=$fetch->rm_room_id?></td>
		<td><?=htmlspecialchars($fetch->rm_room_name)?></td>
		<td align="center"><?=$fetch->rm_display_order?></td>
		<td align="center"><?=decode_room_status($fetch->rm_status)?></td>
		<td align="center"><input type="button" value="部屋修正" onclick="location.href='edit.php?shop_id=<?=$shop_id?>&room_id=<?=$room_id?>'"></td>
		<td align="center"><input type="button" value="ﾃﾞﾌｫﾙﾄ枠設定" onclick="location.href='waku.php?shop_id=<?=$shop_id?>&room_id=<?=$room_id?>'"></td>
	</tr>
<?
}
?>
</table>

<? page_footer() ?>

</body>
</html>
