<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:����Ѥ��̿��ۿ�
'******************************************************/

$title_text = $TT_letter_tsuushin;
$title_color = $TC_MASTER;

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
      alert("��̾�����Ϥ��Ƥ���������");
      subject.focus();
      return false;
    }
    if (from.value == "") {
      alert("from���ɥ쥹�����Ϥ��Ƥ���������");
      from.focus();
      return false;
    }
    if (body.value == "") {
      alert("��ʸ�����Ϥ��Ƥ���������");
      body.focus();
      return false;
    }
		if (!check_date(send_year.value, send_month.value, send_day.value)) {
			alert("���դλ��꤬����������ޤ���");
			send_day.focus();
			return(false);
		}
		dd = new Date();
		dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate(), dd.getHours(), dd.getMinutes());
		var dt = new Date(send_year.value, send_month.value - 1, send_day.value, send_hour.value, send_minutes.value);
		if (dd > dt) {
			alert("���������դϻ���Ǥ��ޤ���");
			send_minutes.focus();
			return(false);
		}
  }
  return confirm("��Ͽ���ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="letter_tsuushin3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">������Ѥ��̿�����</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4">��̾</td>
      <td class="n6"><input type="text" name="subject" size=80></td>
    </tr>
    <tr>
      <td class="m4">from���ɥ쥹</td>
      <td class="n6"><input type="text" name="from" size=80></td>
    </tr>
    <tr>
      <td class="m4">��ʸ</td>
			<td class="n6"><textarea cols=83 rows=30 name="body" class="np" name="body"><?= $body ?></textarea>
		</tr>
		<tr>
      <td class="m4">�ۿ�ͽ������</td>
      <td class="n6">
				<select name="send_year"><? select_year(2001, ' ', ''); ?></select>ǯ
				<select name="send_month"><? select_month(' ', ''); ?></select>��
				<select name="send_day"><? select_day(' ', ''); ?></select>��
				<select name="send_hour"><? select_hour(' ', ''); ?></select>��
				<select name="send_minutes"><? select_minute(' ', ''); ?></select>ʬ
			</td>
    </tr>
  </table>
  <input type="submit" value=" ��Ͽ ">
  <input type="reset" value="�ꥻ�å�">
  <input type="button" value=" ��� " onclick="location.href='index.php'">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
