#!/usr/local/bin/php4
<?
/******************************************************
' System :fletteスタッフページ
' Content:部屋情報変更画面
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");

$inc = "$top/inc";
include("$inc/common.php");
include("$inc/login_check.php");
include("$inc/header.php");

$sql = "SELECT * FROM m_room WHERE rm_room_id='$room_id'";
$result = db_exec($sql);
if (pg_numrows($result) != 0)
	$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title>flette</title>
<link rel="stylesheet" type="text/css" href="/css/staff.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	if (f.room_name.value == "") {
		alert("部屋名称を入力してください。");
		f.room_name.focus();
		return false;
	}
	return confirm("部屋情報を更新します。よろしいですか？");
}
//-->
</script>
</head>
<body>

<? page_header('部屋情報修正', true, true) ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■部屋情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%">部屋ID</td>
		<td class="n1"><?=$fetch->rm_room_id?></td>
	</tr>
	<tr>
		<td class="m1" width="20%">所属店舗</td>
		<td class="n1"><?=decode_shop($fetch->rm_shop_id)?></td>
	</tr>
	<tr>
		<td class="m1">部屋コード<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="room_cd" size=2 maxlength=1 <?=value($fetch->rm_room_cd)?>>
			<span class="note">（全角１文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">部屋名称<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="room_name" size=30 maxlength=30 <?=value($fetch->rm_room_name)?>>
			<span class="note">（全角・半角３０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">リンクURL</td>
		<td class="n1">
			<input class="alpha" type="text" name="link_url" size=80 maxlength=80 <?=value($fetch->rm_link_url)?>>
			<span class="note">（半角８０文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">ステータス<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="radio" name="status" <?=value_checked('1', $fetch->rm_status)?>>有効
			<input type="radio" name="status" <?=value_checked('9', $fetch->rm_status)?>>無効
		</td>
	</tr>
	<tr>
		<td class="m1">表示順序</td>
		<td class="n1">
			<input class="number" type="text" name="display_order" size=5 maxlength=5 <?=value($fetch->rm_display_order)?>>
			<span class="note">（半角数字５文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">登録日時</td>
		<td class="n1"><?=format_datetime($fetch->rm_regist_date)?></td>
	</tr>
	<tr>
		<td class="m1">最終更新日時</td>
		<td class="n1"><?=format_datetime($fetch->rm_update_date)?></td>
	</tr>
</table>
<br>
<input type="hidden" name="shop_id" <?=value($shop_id)?>>
<input type="hidden" name="room_id" <?=value($room_id)?>>
<input type="submit" value="　更新　">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>

</body>
</html>
