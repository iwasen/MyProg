<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱん通信配信
'******************************************************/

$title_text = $TT_letter_tsuushin;
$title_color = $TC_MASTER;

$TS_id = $id;

$sql = "SELECT TS_subject, TS_from, TS_body"
	. ", to_char(TS_send_date, 'YYYY') as send_year"
	. ", to_char(TS_send_date, 'MM') as send_month"
	. ", to_char(TS_send_date, 'DD') as send_day"
	. ", to_char(TS_send_date, 'HH24') as send_hour"
	. ", to_char(TS_send_date, 'MI') as send_minutes"
	. " FROM T_TSUUSHIN"
	. " WHERE TS_id=$TS_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$subject = $fetch->ts_subject;
	$from = $fetch->ts_from;
	$body = $fetch->ts_body;
	$send_year = $fetch->send_year;
	$send_month = $fetch->send_month;
	$send_day = $fetch->send_day;
	$send_hour = $fetch->send_hour;
	$send_minutes = $fetch->send_minutes;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnLoad_body() {
  document.form1.subject.focus();
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

function OnSubmit_form1() {
  with (document.form1) {
    if (subject.value == "") {
      alert("件名を入力してください。");
      subject.focus();
      return false;
    }
    if (from.value == "") {
      alert("fromアドレスを入力してください。");
      from.focus();
      return false;
    }
    if (body.value == "") {
      alert("本文を入力してください。");
      body.focus();
      return false;
    }
		if (!check_date(send_year.value, send_month.value, send_day.value)) {
			alert("日付の指定が正しくありません。");
			send_day.focus();
			return(false);
		}
		dd = new Date();
		dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate(), dd.getHours(), dd.getMinutes());
		var dt = new Date(send_year.value, send_month.value - 1, send_day.value, send_hour.value, send_minutes.value);
		if (dd > dt) {
			alert("過去の日時付は指定できません。");
			send_minutes.focus();
			return(false);
		}
  }
  return confirm("更新します。よろしいですか？");
}
function OnClick_sakujo() {
	if (confirm("削除します。よろしいですか？")) {
    location.href = "letter_tsuushin5.php?id=<?= $TS_id ?>";
	}
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="letter_tsuushin4.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■めろんぱん通信情報</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4">件名</td>
      <td class="n6"><input type="text" name="subject" size=80 value="<?= htmlspecialchars($subject) ?>"></td>
    </tr>
    <tr>
      <td class="m4">fromアドレス</td>
      <td class="n6"><input type="text" name="from" size=80 value="<?= htmlspecialchars($from) ?>"></td>
    </tr>
    <tr>
      <td class="m4">本文</td>
			<td class="n6"><textarea cols=83 rows=30 name="body" class="np" name="body"><?= htmlspecialchars($body) ?></textarea>
		</tr>
		<tr>
      <td class="m4">配信予約日時</td>
      <td class="n6">
				<select name="send_year"><? select_year(2001, '', $send_year); ?></select>年
				<select name="send_month"><? select_month('', $send_month); ?></select>月
				<select name="send_day"><? select_day('', $send_day); ?></select>日
				<select name="send_hour"><? select_hour('', $send_hour); ?></select>時
				<select name="send_minutes"><? select_minute('', $send_minutes); ?></select>分
			</td>
    </tr>
  </table>
	<input type ="hidden" name="id" value=<?= $TS_id ?>>
  <input type="submit" value=" 更新 ">
  <input type="reset" value="リセット">
		<input type="button" value="削除" onclick="OnClick_sakujo()">
  <input type="button" value=" 戻る " onclick="location.href='index.php'">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
