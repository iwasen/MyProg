<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:����Ѥ�쥿���ۿ�
'******************************************************/

$title_text = $TT_letter_html;
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
    if (body.value == "") {
      alert("��ʸ�����Ϥ��Ƥ���������");
      body.focus();
      return false;
    }
		if (!check_date(reserve_year.value, reserve_month.value, reserve_day.value)) {
			alert("���դλ��꤬����������ޤ���");
			reserve_day.focus();
			return(false);
		}
		dd = new Date();
		dd = new Date(dd.getYear(), dd.getMonth(), dd.getDate(), dd.getHours(), dd.getMinutes());
		var dt = new Date(reserve_year.value, reserve_month.value - 1, reserve_day.value, reserve_hour.value, reserve_minutes.value);
		if (dd > dt) {
			alert("���������դϻ���Ǥ��ޤ���");
			reserve_minutes.focus();
			return(false);
		}
  }
  return confirm("��Ͽ���ޤ���������Ǥ�����");
}
function onTestSend() {
	with (document.form1) {
	    if (subject.value == "") {
	      alert("��̾�����Ϥ��Ƥ���������");
	      subject.focus();
	      return false;
	    }
	    if (body.value == "") {
	      alert("��ʸ�����Ϥ��Ƥ���������");
	      body.focus();
	      return false;
	    }

		if (confirm("�ƥ����ۿ���Ԥ��ޤ���������Ǥ�����")) {
			action = "test_send.php";
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
<form method="post" style="margin:0" action="add.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">���᡼�����</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4">��̾</td>
      <td class="n6"><input type="text" name="subject" size=80 value=""></td>
    </tr>
    <tr>
      <td class="m4">��ʸ</td>
      <td class="n6"><textarea cols=80 rows=30 name="body" class="np"></textarea></td>
    </tr>
	<tr>
      <td class="m4">�ۿ�ͽ������</td>
      <td class="n6">
				<select name="reserve_year"><? select_year(2007, ' ', ''); ?></select>ǯ
				<select name="reserve_month"><? select_month(' ', ''); ?></select>��
				<select name="reserve_day"><? select_day(' ', ''); ?></select>��
				<select name="reserve_hour"><? select_hour(' ', ''); ?></select>
				<select name="reserve_minutes"><? select_minute(' ', ''); ?></select>
		</td>
    </tr>
  </table>
	<br>
  <input type="submit" value=" ��Ͽ ">
  <input type="button" value="�ƥ����ۿ�" onclick="onTestSend()">
  <input type="reset" value="�ꥻ�å�">
  <input type="button" value=" ��� " onclick="history.back()">
</form>
</cente>

<? footer_jmk(0); ?>

</body>
</html>
