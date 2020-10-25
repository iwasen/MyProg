<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:バ・キューン配信
'******************************************************/

$title_text = $TT_letter_bakyun;
$title_color = $TC_MASTER;

$mag_id = '000013';

if ($letter_header) {
	$mag_header_tmpl = replace_single($mag_header_tmpl);
	$mag_footer_tmpl = replace_single($mag_footer_tmpl);
	$sql = "UPDATE M_MAGAZINE SET mag_header_tmpl='$mag_header_tmpl', mag_footer_tmpl='$mag_footer_tmpl' WHERE mag_id='000013'";
	db_exec($sql);
	$mag_header_tmpl = str_replace("''", "'", $mag_header_tmpl);
	$mag_footer_tmpl = str_replace("''", "'", $mag_footer_tmpl);
} else {
	$sql = "SELECT mag_header_tmpl, mag_footer_tmpl FROM M_MAGAZINE WHERE mag_id='000013'";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, $i);
		$mag_header_tmpl = $fetch->mag_header_tmpl;
		$mag_footer_tmpl = $fetch->mag_footer_tmpl;
	}
}
if (trim($mag_footer_tmpl) != "") { 
	$body = $mag_header_tmpl . "\n\n\n\n\n\n\n\n" . $mag_footer_tmpl;
} else {
	$body = $mag_header_tmpl;
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
  return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="letter_bakyun3.php" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="mt_id" value="<?= $mt_id ?>">
  <input type="hidden" name="guide" value="<?= $guide ?>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■バ･キューン情報</td>
      <td align="right"><input type="button" value="ヘッダ・フッタ編集" onclick="location.href='letter_header.php'"></td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4">件名</td>
      <td class="n6"><input type="text" name="subject" size=80 value="<?= htmlspecialchars($subject) ?>"></td>
    </tr>
    <tr>
      <td class="m4">本文</td>
			<td class="n6"><textarea cols=83 rows=30 name="body" class="np" name="body"><?= htmlspecialchars($body) ?></textarea>
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
				<select name="reserve_year"><? select_year(2001, ' ', ''); ?></select>年
				<select name="reserve_month"><? select_month(' ', ''); ?></select>月
				<select name="reserve_day"><? select_day(' ', ''); ?></select>日
				<select name="reserve_hour"><? select_hour(' ', ''); ?></select>時
				<select name="reserve_minutes"><? select_minute(' ', ''); ?></select>分
			</td>
    </tr>
  </table>
	<input type="hidden" name="mag_id" value="<?= $mag_id ?>">
  <input type="submit" value=" 登録 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="location.href='index.php'">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
