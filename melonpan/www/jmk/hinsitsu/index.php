<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:�ʼ������å�
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
			alert("���դλ��꤬����������ޤ���");
			s_day.focus();
			return(false);
		}
		if (!check_date(e_year.value, e_month.value, e_day.value)) {
			alert("���դλ��꤬����������ޤ���");
			e_day.focus();
			return(false);
		}
	}
}
function OnSubmit_form3() {
  with (document.form3) {
    if (keyword.value == "") {
      alert("����������Ϥ��Ƥ���������");
      keyword.focus();
      return false;
    }
	}
}function OnSubmit_form4() {
  with (document.form4) {
    if (keyword.value == "") {
      alert("����������Ϥ��Ƥ���������");
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
			<td>���ʼ������å�</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=5 width='80%'>
	<form method="post" name="form1" action="hinsitsu1.php" onSubmit="return OnSubmit_form1();">
		<tr>
			<td align="center" class="m6s">��</td>
			<td class="m6s"><nobr>���������</nobr></td>
			<td class="h2">
				<select name="s_year"><? select_year(2000, '', date("Y")); ?></select>ǯ
				<select name="s_month"><? select_month('', date("m")); ?></select>��
				<select name="s_day"><? select_day('', date("d")); ?></select>����
				<select name="e_year"><? select_year(2000, '', date("Y")); ?></select>ǯ
				<select name="e_month"><? select_month('', date("m")); ?></select>��
				<select name="e_day"><? select_day('', date("d")); ?></select>�� ����
				<select name="mag_count">
					<option value=10 selected>10��</option>
					<option value=20>20��</option>
					<option value=30>30��</option>
				</select><br>
				<nobr><font size=-1>����д��֤ȷ������ꤷ�ơ����������Ф��ޤ���</font></nobr>
			</td>
			<td align="center" class="h2"><input type="submit" name="date_set" value=" ���� "></td>
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
			<td align="center" class="m6s">��</td>
			<td class="m6s"><nobr>Ǥ�ո����</nobr></td>
			<td class="h2">
				<textarea  cols="60" rows="5" name="ninigo"><?= $word ?></textarea>
				<input type="submit" name="ninigo_regist" value="Ǥ�ո���Ͽ"><br>
				<nobr><font size=-1>�����餫������Ͽ����Ƥ��륭����ɤ򸵤���Ф��ޤ����ʻ��֤�������ޤ�����</font></nobr>
			</td>
			<td align="center" class="h2"><input type="button" value=" ���� " onclick="location.href='hinsitsu2.php'"></td>
		</tr>
	</form>
	<form method="post" name="form3" action="hinsitsu3.php" onSubmit="return OnSubmit_form3();">
		<tr>
			<td align="center" class="m6s">��</td>
			<td class="m6s"><nobr>������ɸ���</nobr></td>
			<td class="h2">
				<input size="60" name=keyword maxlength=60>
				<select name="term">
					<option value="all">����</option>
					<option value="1m">������</option>
					<option value="3m">������</option>
					<option value="6m">������</option>
				</select>
				<nobr>
				<input type="radio" name="chk_flg" value="subject" checked><font size=-1>Subject�Τ�</font>
				<input type="radio" name="chk_flg" value="honbun"><font size=-1>��ʸ�Τ�</font>
				<input type="radio" name="chk_flg" value="both"><font size=-1>ξ��</font></nobr><br>
				<nobr><font size=-1>��������ɤ����Ϥ�����Ф��ޤ���(���ˤ�äƻ��֤�������ޤ���)</font></nobr>
			</td>
			<td align="center" class="h2"><input type="submit" name="keyword_set" value=" ���� "></td>
		</tr>
	</form>
	</table><br>
	<input type="button" value=" ��� " onclick="location.href='../menu.php'">
</center>

<? footer_jmk(0); ?>

</body>
</html>
