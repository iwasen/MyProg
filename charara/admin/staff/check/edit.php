<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:似顔絵チェックリスト 
'******************************************************/

$top = "../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
include("$inc/decode.php");
include("$inc/format.php");

// 優先度選択
function select_priority($selected) {
		echo '<option ', value_selected('', $selected), '>通常</option>', "\n";
		echo '<option ', value_selected(1, $selected), '>優先する</option>', "\n";
}

// 写真画像URL取得
$ur_photo = get_system_info('url_photo');

// 似顔絵URL取得
$ur_nigaoe = get_system_info('url_nigaoe');

// メイン処理
set_global('staff', 'スタッフ情報管理', '似顔絵チェックリスト', BACK_TOP);

$sql = "SELECT nd_nigaoe_id,nd_status,nd_random_id,nd_campaign_cd,nd_photo_date,nd_assign_date,nd_creation_date,nd_completion_date,nd_nouki_date"
		. ",nd_priority, nd_staff_id,nd_sex_code,nd_brushwork,nd_age,nd_photo_file,nd_nigaoe_file,to_char(nd_nouki_date - now(),'dd日 HH24時間MI分') AS limit_nouki"
		. " FROM t_nigaoe_data WHERE nd_nigaoe_id=$id";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("所属名IDが不正", __FILE__);
$fetch = pg_fetch_object($result, 0);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
	switch (f.next_action.value) {
	case "OK":
		return ok_check(f);
	case "NG":
		return ng_check(f);
	}
	return false;
}

function ok_check(f) {
	return confirm("ユーザにメールを送信します。よろしいですか？");
}
function ng_check(f) {
	return confirm("担当者に修正依頼します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" width=30%>■似顔絵を確認して下さい</td>
		<td align="right" width=70%><input type="button" value="　戻る　" onclick="history.back()"></td>
	</tr>
</table>
<table class="n2" border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td align="center" width=50%>
			<img class="photo" src="<?=$ur_photo?><?=$fetch->nd_photo_file?>" alt="顔写真" width=180>
		</td>
		<td align="center" width=50%>
			<img class="photo" src="<?=$ur_nigaoe?><?=$fetch->nd_nigaoe_file?>" alt="似顔絵" width=180>
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m2" align="center" width=10%>似顔絵ID</td>
		<td class="n3" width=23%><?=$fetch->nd_random_id?></td>
		<td class="m2" align="center" width=10%>性別</td>
		<td class="n3" width=23%><?=decode_sex($fetch->nd_sex_code)?></td>
		<td class="m2" align="center" width=10%>年齢</td>
		<td class="n3" width=23%><?=$fetch->nd_age != '' ? $fetch->nd_age : '-'?>才
		</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m2" width=30%>担当作画スタッフ</td>
		<td class"=n1" width=70%><?=decode_staff_name($fetch->nd_staff_id)?>
			&lt;<a href='mailto:<?=decode_staff_mail($fetch->nd_staff_id)?>' title="スタッフにメールします"><?=decode_staff_mail($fetch->nd_staff_id)?></a>&gt;</td>
	</tr>
</table><br>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td bgcolor="#ffddff" width=30%>納期</td>
		<td class="n1" width=70%><?=format_datetime($fetch->nd_nouki_date)?>&nbsp;&nbsp;[&nbsp;納期まで：<?=$fetch->limit_nouki?>&nbsp;]</td>
	</tr>
</table>
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m3" width=30%>ユーザからの写真受信日時</td>
		<td class="n3" width=70%><?=format_datetime($fetch->nd_photo_date)?>&nbsp;&nbsp;<?=$fetch->photo != '' ? "[経過時間：$fetch->photo]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">作画スタッフ割当日時</td>
		<td class="n3"><?=format_datetime($fetch->nd_assign_date)?>&nbsp;&nbsp;<?=$fetch->assign != '' ? "[経過時間：$fetch->assign]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">似顔絵作成開始日時</td>
		<td class="n3"><?=format_datetime($fetch->nd_creation_date)?>&nbsp;&nbsp;<?=$fetch->creation != '' ? "[経過時間：$fetch->creation]":''?>
		</td>
	</tr>
	<tr>
		<td class="m3">似顔絵完成日時</td>
		<td class="n4"><?=format_datetime($fetch->nd_completion_date)?>&nbsp;&nbsp;<?=$fetch->completion != '' ? "[経過時間：$fetch->completion]":''?>
		</td>
	</tr>
</table>
<br>
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<input type="hidden" name="id" <?=value($id)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　ＯＫ　" onclick="document.form1.next_action.value='OK'">
<input type="submit" value=" ＮＧ " onclick="document.form1.next_action.value='NG'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
