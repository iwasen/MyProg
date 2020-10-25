<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:事務局からのお知らせ登録変更画面
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/select.php");

// 有効/無効選択肢
function select_cs_status($sel) {
	echo "<option ", value_selected('0', $sel), ">有効</option>";
	echo "<option ", value_selected('9', $sel), ">無効</option>";
}
//表示check
function cs_checked($data) {
	if ($data) {
		if ($data == 't') {
			print "checked";
		}
	}
}
// メイン処理
set_global('etc', 'その他管理', '事務局からのお知らせ', BACK_TOP);

$sql = "SELECT * FROM t_center_msg WHERE cs_seq_no=$cs_no";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	system_error('メッセージNo.が不正', __FILE__);
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
function check_date(y, m, d) {
	var dt = new Date(y, m - 1, d);
	if (isNaN(dt))
		return false;
	else {
		if (dt.getYear() == y && dt.getMonth() + 1 == m && dt.getDate() == d)
		return true;
	else
		return false;
	}
}

function update_check(f) {
	if (f.cs_title.value == "") {
		alert("タイトルを入力してください。");
		f.cs_title.focus();
		return false;
	}
	if (!check_date(f.start_year.value, f.start_month.value, f.start_day.value)) {
		alert("日付の指定が正しくありません。");
		f.start_day.focus();
		return(false);
	}
	if (!check_date(f.end_year.value, f.end_month.value, f.end_day.value)) {
		alert("日付の指定が正しくありません。");
		f.end_day.focus();
		return(false);
	}
	return confirm("事務局からのお知らせ情報を更新します。よろしいですか？");
}
function delete_check(f) {
	return confirm("事務局からのお知らせを削除します。よろしいですか？");
}
//-->
</script>
</head>
<body>
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update.php" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="100%">
	<tr>
		<td class="m0" colspan=2>■事務局からのお知らせ情報を入力してください</td>
	</tr>
	<tr>
		<td class="m1">タイトル<?=MUST_ITEM?></td>
		<td class="n1">
			<input class="kanji" type="text" name="cs_title" size=75 maxlength=100 <?=value($fetch->cs_title)?>>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">メッセージ<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="cs_message" cols=70 rows=15><?=htmlspecialchars($fetch->cs_message)?></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">表示ページ<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="checkbox" name="cs_monitor_top" value='t' <? cs_checked($fetch->cs_monitor_top) ?>><font size=-1> モニタートップページ</font><br>
			<input type="checkbox" name="cs_monitor_mypage" value='t' <? cs_checked($fetch->cs_monitor_mypage) ?>><font size=-1> モニターMyページ</font><br>
			<input type="checkbox" name="cs_marketer_top" value='t' <? cs_checked($fetch->cs_marketer_top) ?>><font size=-1> マーケタートップページ</font><br>
			<input type="checkbox" name="cs_marketer1_mypage" value='t' <? cs_checked($fetch->cs_marketer1_mypage) ?>><font size=-1> マーケター無料Myページ</font><br>
			<input type="checkbox" name="cs_marketer2_mypage" value='t' <? cs_checked($fetch->cs_marketer2_mypage) ?>><font size=-1> マーケター有料Myページ</font><br>
			<input type="checkbox" name="cs_agent_top" value='t' <? cs_checked($fetch->cs_agent_top) ?>><font size=-1> エージェントトップページ</font><br>
			<input type="checkbox" name="cs_agent_mypage" value='t' <? cs_checked($fetch->cs_agent_mypage) ?>><font size=-1> エージェントMyページ</font><br>
		</td>
	</tr>
	<tr>
		<td class="m1">表示期間</td>
		<td class="n1">開始日
			<select name="start_year"><? select_year('2000',' ', get_datepart('Y', $fetch->cs_start_date)) ?></select>月
			<select name="start_month"><? select_month(' ', get_datepart('M', $fetch->cs_start_date)) ?></select>月
			<select name="start_day"><? select_day(' ', get_datepart('D', $fetch->cs_start_date)) ?></select>日
			<br>終了日
			<select name="end_year"><? select_year('2000',' ', get_datepart('Y', $fetch->cs_end_date)) ?></select>月
			<select name="end_month"><? select_month(' ', get_datepart('M', $fetch->cs_end_date)) ?></select>月
			<select name="end_day"><? select_day(' ', get_datepart('D', $fetch->cs_end_date)) ?></select>日
		</td>
	</tr>
	<tr>
		<td class="m1">状態<?=MUST_ITEM?></td>
		<td class="n1"><select name="cs_status"><? select_cs_status($fetch->cs_status) ?></select></td>
	</tr>
</table>
<br>
<input type="hidden" name="cs_no" <?=value($cs_no)?>>
<input type="hidden" name="next_action">
<input type="submit" value="　更新　" onclick="document.form1.next_action.value='update'">
<input type="button" value="　戻る　" onclick="history.back()">
</form>
</div>

<? page_footer() ?>
</body>
</html>
