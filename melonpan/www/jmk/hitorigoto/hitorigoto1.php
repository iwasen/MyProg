<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:「今日のひとりごと」入力
'******************************************************/

$title_text = $TT_hitorigoto;
$title_color = $TC_MASTER;

$sql = "SELECT MAX(access_day) as access_day FROM T_ADMIN_MSG WHERE msg_id='2'";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, $i);
	$access_day = $fetch->access_day;
}

$info_year = substr($access_day, 0, 4);
$info_month = substr($access_day, 4, 2);
$info_day = substr($access_day, 6, 2);

$next_day = mktime (0,0,0,$info_month,$info_day + 1,$info_year);
$next_day = strftime ("%Y%m%d", $next_day);

if ($next_day < date("Ymd")) {
	$next_day_year = date("Y");
	$next_day_month = date("m");
	$next_day_day = date("d");
} else {
	$next_day_year = substr ($next_day, 0, 4);
	$next_day_month = substr ($next_day, 4, 2);
	$next_day_day = substr ($next_day, 6, 2);
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
  document.form1.admin_msg.focus();
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
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return(false);
		}
	}
	return true;
}
function OnSubmit_form1() {
  with (document.form1) {
		if (!check_date(info_year.value, info_month.value, info_day.value)) {
			alert("日付の指定が正しくありません。");
			info_day.focus();
			return(false);
		}
		dd = new Date();
		dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate());
		var dt = new Date(info_year.value, info_month.value - 1, info_day.value);
		if (dd > dt) {
			alert("過去の日付は指定できません。");
			info_day.focus();
			return(false);
		}
    if (admin_msg.value == "") {
      alert("メッセージを入力してください。");
      admin_msg.focus();
      return false;
    }
		str=admin_msg.value;
		if (str.length > 100) {
			alert("メッセージが長すぎます。");
			document.form1.admin_msg.focus();
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
<form method="post" action="hitorigoto3.php" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="mt_id" value="<?= $mt_id ?>">
  <input type="hidden" name="guide" value="<?= $guide ?>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">■「今日のひとりごと」情報</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4" width="20%">表示指定日</td>
      <td class="n6">
				<select name="info_year"><? select_year(2000, '', date($next_day_year)); ?></select>年
				<select name="info_month"><? select_month('', date($next_day_month)); ?></select>月
				<select name="info_day"><? select_day('', date($next_day_day)); ?></select>日			</td>
    </tr>
    <tr>
      <td class="m4">メッセージ<br><font size="-1">（100文字以内）</font></td>
      <td class="n6"><textarea cols=60 rows=4 name="admin_msg" class="np"></textarea></td>
    </tr>
  </table><br>
  <input type="submit" value=" 登録 ">
  <input type="reset" value="リセット">
  <input type="button" value=" 戻る " onclick="history.back()">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
