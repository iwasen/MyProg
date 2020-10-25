<?
/******************************************************
' System :「きゃららFactory」管理者用ページル
' Content:マスタメンテナンス｜似顔絵関連マスタ｜キャンペーンマスタ
'******************************************************/

$top = "../../..";
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// メイン処理
set_global('master_st', 'マスタ管理｜似顔絵関連マスタ', 'キャンペーンマスタ', BACK_TOP);

$sql = "SELECT * FROM m_campaign WHERE cp_campaign_cd = '$cp_cd'";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error("キャンペーンIDが不正", __FILE__);
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
	case "update":
		return update_check(f);
	case "delete":
		return delete_check(f);
	}
	return false;
}

function update_check(f) {
	if (f.cp_server_id.value == "") {
		alert("課金サーバを選択してください。");
		f.cp_server_id.focus();
		return false;
	}
	if (f.cp_name.value == "") {
		alert("キャンペーン名を入力してください。");
		f.cp_name.focus();
		return false;
	}
	return confirm("キャンペーン情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("キャンペーン情報を削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■登録するキャンペーン情報を入力してください。</td>
	</tr>
	<tr>
		<td class="m1">キャンペーンコード<?=MUST_ITEM?></td>
		<td class="n1">
			<?=htmlspecialchars($fetch->cp_campaign_cd)?>
		</td>
	</tr>
	<tr>
		<td class="m1">課金サーバID<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="cp_server_id"><?=select_ac_server('', $fetch->cp_server_id)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">キャンペーン名<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cp_name" size=60 <?=value($fetch->cp_name)?>>
		</td>
	</tr>
	<tr>
		<td class="m1">キャンペーン詳細</td>
		<td class="n1">
			<textarea class="kanji" name="cp_explain" cols=50 rows=5><?=$fetch->cp_explain?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">画風<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="cp_brushwork"><?=select_brushwork('', $fetch->cp_brushwork)?></select>
		</td>
	</tr>
	<tr>
		<td class="m1">納期（日数）<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="cp_nouki"><?=select_day('', $fetch->cp_nouki)?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">キャンペーン開始日</td>
		<td class="n1">
			<select name="cp_from_date_y"><? select_year('2003', '未設定', get_datepart('Y', $fetch->cp_from_date)) ?></select>年
			<select name="cp_from_date_m"><? select_month('未設定', get_datepart('M', $fetch->cp_from_date)) ?></select>月
			<select name="cp_from_date_d"><? select_day('未設定', get_datepart('D', $fetch->cp_from_date)) ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">キャンペーン終了日</td>
		<td class="n1">
			<select name="cp_to_date_y"><? select_year('2003', '未設定', get_datepart('Y', $fetch->cp_to_date)) ?></select>年
			<select name="cp_to_date_m"><? select_month('未設定', get_datepart('M', $fetch->cp_to_date)) ?></select>月
			<select name="cp_to_date_d"><? select_day('未設定', get_datepart('D', $fetch->cp_to_date)) ?></select>日
		</td>
	</tr>
</table>
<br>
<input type="hidden" name="cp_cd" <?=value($cp_cd)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
