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

$title_text = $TT_letter_melonpan;
$title_color = $TC_MASTER;

$mag_id = "000049";

$sql = "SELECT TB.index_id, TB.mag_id, TB.body_id, TB.mag_titl, TB.mag_body, TB.modefy_dt"
	. ", to_char(TR.resrv_hour, 'YYYY') as reserve_year"
	. ", to_char(TR.resrv_hour, 'MM') as reserve_month"
	. ", to_char(TR.resrv_hour, 'DD') as reserve_day"
	. ", to_char(TR.resrv_hour, 'HH24') as reserve_hour"
	. ", to_char(TR.resrv_hour, 'MI') as reserve_minutes"
	. " FROM T_BODY TB, T_PUB_RESERVE TR"
	. " WHERE TB.index_id='$index_id' AND TB.index_id=TR.index_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$index_id = $fetch->index_id;
	$mag_id = $fetch->mag_id;
	$body_id = $fetch->body_id;
	$mag_titl = $fetch->mag_titl;
	$mag_body = $fetch->mag_body;
	$modefy_dt = $fetch->modefy_dt;
	$reserve_year = $fetch->reserve_year;
	$reserve_month = $fetch->reserve_month;
	$reserve_day = $fetch->reserve_day;
	$reserve_hour = $fetch->reserve_hour;
	$reserve_minutes = $fetch->reserve_minutes;
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
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" style="margin:0" action="letter_melonpan5.php" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="mt_id" value="<?= $mt_id ?>">
  <input type="hidden" name="guide" value="<?= $guide ?>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■めろんぱんレター情報</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4">件名</td>
      <td class="n6"><input type="text" name="subject" size=80 value="<?= htmlspecialchars($mag_titl) ?>"></td>
    </tr>
    <tr>
      <td class="m4" rowspan="2">本文</td>
    </tr>
		<tr>
			<td class="n6"><textarea cols=83 rows=30 name="body" class="np" name="body"><?= htmlspecialchars($mag_body) ?></textarea>
		</tr>
<?php
$sql = "SELECT admin_msg FROM T_MAG_FOOTER WHERE access_day=TO_CHAR(now(), 'YYYYMMDD') AND bakyun_footer=1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$admin_msg = $fetch->admin_msg;
	$msg = "<xmp>" . $admin_msg . "</xmp>";
} else {
	$msg = "<font color=\"gray\" size=-1>--本日はありません--</font>";
}
?>
		<tr>
      <td class="m4">自動挿入フッタ</td>
      <td class="n6"><?= $msg ?></td>
    </tr>
		<tr>
      <td class="m4">配信予約日時</td>
      <td class="n6">
				<select name="reserve_year"><? select_year(2000, '', $reserve_year); ?></select>年
				<select name="reserve_month"><? select_month('', $reserve_month); ?></select>月
				<select name="reserve_day"><? select_day('', $reserve_day); ?></select>日
				<select name="reserve_hour"><? select_hour('', $reserve_hour); ?></select>
				<select name="reserve_minutes"><? select_minute('', $reserve_minutes); ?></select>
			</td>
    </tr>
  </table>
	<input type="hidden" name="mag_id" value="<?= $mag_id ?>">
	<input type="hidden" name="index_id" value="<?= $index_id ?>">
  <input type="submit" value=" 更新 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="history.back()">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
