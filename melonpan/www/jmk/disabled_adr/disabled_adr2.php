<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:���ɶػߥ᡼�륢�ɥ쥹��Ͽ
'******************************************************/

$title_text = $TT_disabled_adr;
$title_color = $TC_DOKUSHA;

$mail_addr = stripslashes($mail_addr);
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
  document.form1.new_mail_addr.focus();
}
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}
function OnSubmit_form1(mail_addr) {
  with (document.form1) {
    if (new_mail_addr.value == "") {
      alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
      new_mail_addr.focus();
      return false;
    }
		if (!isA(new_mail_addr.value)) {
			alert("�᡼�륢�ɥ쥹��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			new_mail_addr.focus();
			return false;
		}
		if (new_mail_addr.value == mail_addr.value) {
			alert("�᡼�륢�ɥ쥹���ѹ�����Ƥ��ʤ��褦�Ǥ���");
			new_mail_addr.focus();
			return false;
		}
  }
  return confirm("�������ޤ���������Ǥ�����");
}
function OnClick_sakujo() {
	mail_addr=document.form1.new_mail_addr.value;
	if (confirm("������ޤ���������Ǥ�����")) {
    location.href = "disabled_adr5.php?mail_addr=" + mail_addr;
	}
}

//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="disabled_adr4.php" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="mt_id" value="<?= $mt_id ?>">
  <input type="hidden" name="guide" value="<?= $guide ?>">
	<table border=0 cellspacing=0 cellpadding=0 width='750'>
    <tr>
      <td class="m0">�����ɶػߥ᡼�륢�ɥ쥹����</td>
    </tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width="750">
    <tr>
      <td class="m4" width="20%">�᡼�륢�ɥ쥹</td>
      <td class="n4"><input type="text" name="new_mail_addr" size="50" maxlength=50 value="<?= $mail_addr ?>"><font class="note">��Ⱦ�ѱѿ���</font></td>
    </tr>
  </table><br>
	<input type="hidden" name="mail_addr" value="<?= $mail_addr ?>">
  <input type="submit" value=" ���� ">
  <input type="reset" value="�ꥻ�å�">
  <input type="button" value=" ��� " onclick="OnClick_sakujo()">
  <input type="button" value=" ��� " onclick="history.back()">
</form>
</center>


<? footer_jmk(0); ?>

</body>
</html>
