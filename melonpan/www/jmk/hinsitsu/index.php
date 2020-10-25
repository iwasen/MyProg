<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :めろんぱん事務局用ページ
' Content:品質チェック
'******************************************************/

$title_text = $TT_hinshitsu;
$title_color = $TC_HAKKOUSHA;

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
		if (!check_date(s_year.value, s_month.value, s_day.value)) {
			alert("日付の指定が正しくありません。");
			s_day.focus();
			return(false);
		}
		if (!check_date(e_year.value, e_month.value, e_day.value)) {
			alert("日付の指定が正しくありません。");
			e_day.focus();
			return(false);
		}
	}
}
function OnSubmit_form3() {
  with (document.form3) {
    if (keyword.value == "") {
      alert("キーワード入力してください。");
      keyword.focus();
      return false;
    }
	}
}function OnSubmit_form4() {
  with (document.form4) {
    if (keyword.value == "") {
      alert("キーワード入力してください。");
      keyword.focus();
      return false;
    }
	}
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center><br>
	<table border=0 cellspacing=0 cellpadding=0 width='80%'>
		<tr>
			<td>■品質チェック</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=5 width='80%'>
	<form method="post" name="form1" action="hinsitsu1.php" onSubmit="return OnSubmit_form1();">
		<tr>
			<td align="center" class="m6s">１</td>
			<td class="m6s"><nobr>ランダム抽出</nobr></td>
			<td class="h2">
				<select name="s_year"><? select_year(2000, '', date("Y")); ?></select>年
				<select name="s_month"><? select_month('', date("m")); ?></select>月
				<select name="s_day"><? select_day('', date("d")); ?></select>日〜
				<select name="e_year"><? select_year(2000, '', date("Y")); ?></select>年
				<select name="e_month"><? select_month('', date("m")); ?></select>月
				<select name="e_day"><? select_day('', date("d")); ?></select>日 から
				<select name="mag_count">
					<option value=10 selected>10件</option>
					<option value=20>20件</option>
					<option value=30>30件</option>
				</select><br>
				<nobr><font size=-1>※抽出期間と件数を指定して、ランダムに抽出します。</font></nobr>
			</td>
			<td align="center" class="h2"><input type="submit" name="date_set" value=" 検索 "></td>
		</tr>
	</form>
	<form method="post" name="form2" action="index.php">
<?php
if ($ninigo_regist) {
	$ninigo = replace_single($ninigo);
	$sql = "UPDATE M_REDWORD SET word='$ninigo' WHERE word_id=1";
	db_exec($sql);
}
$sql = "SELECT * FROM M_REDWORD WHERE word_id=1";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, $i);
	$word = htmlspecialchars($fetch->word);
}
?>
		<tr>
			<td align="center" class="m6s">２</td>
			<td class="m6s"><nobr>任意語抽出</nobr></td>
			<td class="h2">
				<textarea  cols="60" rows="5" name="ninigo"><?= $word ?></textarea>
				<input type="submit" name="ninigo_regist" value="任意語登録"><br>
				<nobr><font size=-1>※あらかじめ登録されているキーワードを元に抽出します。（時間がかかります。）</font></nobr>
			</td>
			<td align="center" class="h2"><input type="button" value=" 検索 " onclick="location.href='hinsitsu2.php'"></td>
		</tr>
	</form>
	<form method="post" name="form3" action="hinsitsu3.php" onSubmit="return OnSubmit_form3();">
		<tr>
			<td align="center" class="m6s">３</td>
			<td class="m6s"><nobr>キーワード検索</nobr></td>
			<td class="h2">
				<input size="60" name=keyword maxlength=60>
				<select name="term">
					<option value="all">全て</option>
					<option value="1m">過去１ヶ月</option>
					<option value="3m">過去３ヶ月</option>
					<option value="6m">過去６ヶ月</option>
				</select>
				<nobr>
				<input type="radio" name="chk_flg" value="subject" checked><font size=-1>Subjectのみ</font>
				<input type="radio" name="chk_flg" value="honbun"><font size=-1>本文のみ</font>
				<input type="radio" name="chk_flg" value="both"><font size=-1>両方</font></nobr><br>
				<nobr><font size=-1>※キーワードを入力して抽出します。(条件によって時間がかかります。)</font></nobr>
			</td>
			<td align="center" class="h2"><input type="submit" name="keyword_set" value=" 検索 "></td>
		</tr>
	</form>
	</table><br>
	<input type="button" value=" 戻る " onclick="location.href='../menu.php'">
</center>

<? footer_jmk(0); ?>

</body>
</html>
