<?
/******************************************************
' System :お酒と買物のアンケートモニター事務局用ページ
' Content:調査結果変更画面
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// メイン処理
set_global('etc', 'その他管理', '調査結果アップロード', BACK_TOP);

$sql = "SELECT * FROM t_report WHERE rp_report_id=$report_id";
$result = db_exec($sql);
if (pg_num_rows($result) == 0)
	system_error("レポートIDが不正", __FILE__);
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
	if (f.chain_or_gyoutai[0].checked && f.chain_cd.value == "") {
		alert("チェーンを選択してください。");
		f.chain_cd.focus();
		return false;
	}
	if (f.chain_or_gyoutai[1].checked && f.gyoutai_cd.value == "") {
		alert("業態を選択してください。");
		f.chain_cd.focus();
		return false;
	}
	if (f.title.value == "") {
		alert("タイトルを入力してください。");
		f.title.focus();
		return false;
	}
	if (f.from_month.value == "") {
		alert("調査期間を入力してください。");
		f.from_month.focus();
		return false;
	}
	if (f.from_day.value == "") {
		alert("調査期間を入力してください。");
		f.from_day.focus();
		return false;
	}
	if (f.to_month.value == "") {
		alert("調査期間を入力してください。");
		f.to_month.focus();
		return false;
	}
	if (f.to_day.value == "") {
		alert("調査期間を入力してください。");
		f.to_day.focus();
		return false;
	}
	if (document.form1.next_action.value=='delete')
	return confirm("調査結果を削除します。よろしいですか？");
	return confirm("調査結果をアップロードします。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" enctype="multipart/form-data" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■調査結果を入力してください。</td>
	</tr>
	<tr>
		<td class="m1" width="20%"><input type="radio" name="chain_or_gyoutai" value="1" <?=checked($fetch->rp_chain_cd)?>>チェーン<?=MUST_ITEM?></td>
		<td class="n1" width="80%"><select name="chain_cd"><? select_chain('- 選択してください -', $fetch->rp_chain_cd) ?></select></td>
	</tr>
	<tr>
		<td class="m1" width="20%"><input type="radio" name="chain_or_gyoutai" value="2" <?=checked($fetch->rp_gyoutai_cd)?>>業態別<?=MUST_ITEM?></td>
		<td class="n1" width="80%"><select name="gyoutai_cd"><? select_gyoutai('- 選択してください -', $fetch->rp_gyoutai_cd) ?></select></td>
	</tr>
	<tr>
		<td class="m1">タイトル<?=MUST_ITEM?></td>
		<td class="n1"><input type="text" name="title" size=80 <?=value($fetch->rp_title)?>></td>
	</tr>
	<tr>
		<td class="m1">調査期間<?=MUST_ITEM?></td>
		<td class="n1">
			<select name="from_year"><? select_year(2004, '', get_datepart('Y', $fetch->rp_from_date)) ?></select>年
			<select name="from_month"><? select_month('', get_datepart('M', $fetch->rp_from_date)) ?></select>月
			<select name="from_day"><? select_day('', get_datepart('D', $fetch->rp_from_date)) ?></select>日
			〜
			<select name="to_year"><? select_year(2004, '', get_datepart('Y', $fetch->rp_to_date)) ?></select>年
			<select name="to_month"><? select_month('', get_datepart('M', $fetch->rp_to_date)) ?></select>月
			<select name="to_day"><? select_day('', get_datepart('D', $fetch->rp_to_date)) ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">レポートファイル</td>
		<td class="n1">
			<input type="file" name="report_file" size=60>
<?
if ($fetch->rp_report_file_name) {
?>
			<span class="note">（設定済みです。変更する場合のみ入力してください）</span>
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">集計結果ファイル</td>
		<td class="n1">
			<input type="file" name="sum_file" size=60>
<?
if ($fetch->rp_sum_file_name) {
?>
			<span class="note">（設定済みです。変更する場合のみ入力してください）</span>
<?
}
?>
		</td>
	</tr>
	<tr>
		<td class="m1">調査票ファイル</td>
		<td class="n1">
			<input type="file" name="enquete_file" size=60>
<?
if ($fetch->rp_enquete_file_name) {
?>
			<span class="note">（設定済みです。変更する場合のみ入力してください）</span>
<?
}
?>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="submit" value="　削除　" onclick="document.form1.next_action.value='delete'">
<input type="button" value="　戻る　" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="report_id" <?=value($report_id)?>>
</form>
</div>

<? page_footer() ?>
</body>
</html>
