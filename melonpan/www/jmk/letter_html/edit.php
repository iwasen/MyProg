<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:めろんぱんレター配信
'******************************************************/

$title_text = $TT_letter_html;
$title_color = $TC_MASTER;

$mag_id = "S00001";

$sql = "SELECT hl_subject,hl_body,hl_send_num,hl_index_id"
	. ", to_char(hl_send_date, 'YYYY') as reserve_year"
	. ", to_char(hl_send_date, 'MM') as reserve_month"
	. ", to_char(hl_send_date, 'DD') as reserve_day"
	. ", to_char(hl_send_date, 'HH24') as reserve_hour"
	. ", to_char(hl_send_date, 'MI') as reserve_minutes"
	. " FROM t_html_letter WHERE hl_letter_id=$letter_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	$subject = $fetch->hl_subject;
	$body = $fetch->hl_body;
	$reserve_year = $fetch->reserve_year;
	$reserve_month = $fetch->reserve_month;
	$reserve_day = $fetch->reserve_day;
	$reserve_hour = $fetch->reserve_hour;
	$reserve_minutes = $fetch->reserve_minutes;
	$index_id = $fetch->hl_index_id;
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
    if (body.value == "") {
      alert("本文を入力してください。");
      body.focus();
      return false;
    }
		if (!check_date(reserve_year.value, reserve_month.value, reserve_day.value)) {
			alert("日付の指定が正しくありません。");
			reserve_day.focus();
			return(false);
		}
		dd = new Date();
		dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate(), dd.getHours(), dd.getMinutes());
		var dt = new Date(reserve_year.value, reserve_month.value - 1, reserve_day.value, reserve_hour.value, reserve_minutes.value);
		if (dd > dt) {
			alert("過去の日時付は指定できません。");
			reserve_minutes.focus();
			return(false);
		}
  }
  return confirm("更新します。よろしいですか？");
}
function onTestSend() {
	with (document.form1) {
	    if (subject.value == "") {
	      alert("件名を入力してください。");
	      subject.focus();
	      return false;
	    }
	    if (body.value == "") {
	      alert("本文を入力してください。");
	      body.focus();
	      return false;
	    }

		if (confirm("テスト配信を行います。よろしいですか？")) {
			action = "test_send.php";
			submit();
		}
	}
}
function onDelete() {
	with (document.form1) {
		if (confirm("このメールを削除します。よろしいですか？")) {
				action = "delete.php";
				submit();
		}
	}
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" style="margin:0" action="update.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■メール情報</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4">件名</td>
      <td class="n6"><input type="text" name="subject" size=80 value="<?= htmlspecialchars($subject) ?>"></td>
    </tr>
    <tr>
      <td class="m4" rowspan="2">本文</td>
    </tr>
		<tr>
			<td class="n6"><textarea cols=83 rows=30 name="body" class="np" name="body"><?= htmlspecialchars($body) ?></textarea>
		</tr>
		<tr>
      <td class="m4">配信予約日時</td>
      <td class="n6">
				<select name="reserve_year"><? select_year(2007, '', $reserve_year); ?></select>年
				<select name="reserve_month"><? select_month('', $reserve_month); ?></select>月
				<select name="reserve_day"><? select_day('', $reserve_day); ?></select>日
				<select name="reserve_hour"><? select_hour('', $reserve_hour); ?></select>
				<select name="reserve_minutes"><? select_minute('', $reserve_minutes); ?></select>
			</td>
    </tr>
  </table>
	<br>
	<input type="hidden" name="letter_id" value="<?= $letter_id ?>">
	<input type="hidden" name="index_id" value="<?= $index_id ?>">
  <input type="submit" value=" 更新 ">
  <input type="button" value="テスト配信" onclick="onTestSend()">
  <input type="button" value=" 削除 " onclick="onDelete()">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="history.back()">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
