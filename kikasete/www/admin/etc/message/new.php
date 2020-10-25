<?
/******************************************************
' System :きかせて・net事務局用ページ
' Content:事務局からのお知らせ新規登録画面
'******************************************************/

$top = '../..';
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/select.php");

set_global('etc', 'その他管理', '事務局からのお知らせ', BACK_TOP);
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<script type="text/javascript">
<!--
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

function onSubmit_form1(f) {
	if (f.cs_title.value == "") {
		alert("タイトルを入力してください。");
		f.cs_title.focus();
		return false;
	}
	if (f.cs_message.value == "") {
		alert("メッセージを入力してください。");
		f.cs_message.focus();
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
	return confirm("事務局からのお知らせを登録します。よろしいですか？");
}
//-->
</script>
</head>
<body onload="document.form1.cs_title.focus()">
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
			<input class="kanji" type="text" name="cs_title" size=75 maxlength=100>
			<span class="note">（全角１００文字まで）</span>
		</td>
	</tr>
	<tr>
		<td class="m1">メッセージ<?=MUST_ITEM?></td>
		<td class="n1">
			<textarea class="kanji" name="cs_message" cols=70 rows=15></textarea>
		</td>
	</tr>
	<tr>
		<td class="m1">表示ページ<?=MUST_ITEM?></td>
		<td class="n1">
			<input type="checkbox" name="cs_monitor_top" value=true><font size=-1> モニタートップページ</font><br>
			<input type="checkbox" name="cs_monitor_mypage" value=true><font size=-1> モニターMyページ</font><br>
			<input type="checkbox" name="cs_marketer_top" value=true><font size=-1> マーケタートップページ</font><br>
			<input type="checkbox" name="cs_marketer1_mypage" value=true><font size=-1> マーケター無料Myページ</font><br>
			<input type="checkbox" name="cs_marketer2_mypage" value=true><font size=-1> マーケター有料Myページ</font><br>
			<input type="checkbox" name="cs_agent_top" value=true><font size=-1> エージェントトップページ</font><br>
			<input type="checkbox" name="cs_agent_mypage" value=true><font size=-1> エージェントMyページ</font><br>
		</td>
	</tr>
	<tr>
		<td class="m1">表示期間</td>
		<td class="n1">開始日
			<select name="start_year"><? select_year(2002, ' ') ?></select>月
			<select name="start_month"><? select_month(' ') ?></select>月
			<select name="start_day"><? select_day(' ') ?></select>日
			<br>終了日
			<select name="end_year"><? select_year(2002, ' ') ?></select>月
			<select name="end_month"><? select_month(' ') ?></select>月
			<select name="end_day"><? select_day(' ') ?></select>日
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　">
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action" value="new">
</form>
</div>

<? page_footer() ?>
</body>
</html>
