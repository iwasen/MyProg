<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:��Фå�����Ͽ
'******************************************************/

$title_text = $TT_riba_regist;
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
function OnLoad_body() {
  document.form1.mail_add_main.focus();
}
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}
function num_chk(val) {
	var numstring ="0123456789./+-";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}	return true;
}
function OnSubmit_form1() {
 with (document.form1) {
    if (mail_add_main.value == "") {
      alert("���ܥ᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
      mail_add_main.focus();
      return false;
    }
		if (!isA(mail_add_main.value)) {
			alert("���ܥ᡼�륢�ɥ쥹��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			mail_add_main.focus();
			return false;
		}
    if (mail_add_sub.value == "") {
      alert("���ܥ᡼�륢�ɥ쥹�������Ϥ��Ƥ���������");
      mail_add_sub.focus();
      return false;
    }
		if (!isA(mail_add_sub.value)) {
			alert("���ܥ᡼�륢�ɥ쥹����Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			mail_add_sub.focus();
			return false;
		}
    if (owner_pswd.value == "") {
      alert("�ѥ���ɤ����Ϥ��Ƥ���������");
      owner_pswd.focus();
      return false;
    }
    if (owner_nm_kanj.value == "") {
      alert("ȯ�Լ�̾�����Ϥ��Ƥ���������");
      owner_nm_kanj.focus();
      return false;
    }
    if (owner_nm_kana.value == "") {
      alert("ȯ�Լ�̾[����]�����Ϥ��Ƥ���������");
      owner_nm_kana.focus();
      return false;
    }
    if (owner_zip1.value == "") {
      alert("͹���ֹ�����Ϥ��Ƥ���������");
      owner_zip1.focus();
      return false;
    }
		if (!num_chk(owner_zip1.value)){
			alert("͹���ֹ��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			owner_zip1.focus();
			return(false);
		}
    if (owner_zip2.value == "") {
      alert("͹���ֹ�����Ϥ��Ƥ���������");
      owner_zip2.focus();
      return false;
    }
		if (!num_chk(owner_zip2.value)){
			alert("͹���ֹ��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			owner_zip2.focus();
			return(false);
		}
    if (owner_add.value == "") {
      alert("��������Ϥ��Ƥ���������");
      owner_add.focus();
      return false;
    }
    if (owner_tel.value == "") {
      alert("�����ֹ�����Ϥ��Ƥ���������");
      owner_tel.focus();
      return false;
    }
		if (!isA(owner_tel.value)){
			alert("�����ֹ��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			owner_tel.focus();
			return(false);
		}
    if (pt_melonpan.value == "") {
      alert("�ݥ���Ȥ����Ϥ��Ƥ���������");
      pt_melonpan.focus();
      return false;
    }
		if (!num_chk(pt_melonpan.value)){
			alert("�ݥ���Ȥ�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			pt_melonpan.focus();
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
<form method="post" action="riba_regist6.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">���ڤ�Фå����ȯ�ԼԾ�����Ͽ</td>
		</tr>
	<table border=0 cellspacing=1 cellpadding=3 width="700">
		<tr>
			<td class="m5" width="25%">���ܥ᡼�륢�ɥ쥹</td>
			<td class="n5"><input size=50 name=mail_add_main maxlength=50><font class="note">��Ⱦ�ѱѿ���</font><BR></td>
		</tr>
		<tr>
			<td class="m5" width="25%">���ܥ᡼�륢�ɥ쥹��</td>
			<td class="n5"><input size=50 name=mail_add_sub maxlength=50><font class="note">��Ⱦ�ѱѿ���</font><BR></td>
		</tr>
		<tr>
			<td class="m5">�ѥ����</td>
			<td class="n5"><input size=20 name=owner_pswd maxlength=20><font class="note">��Ⱦ�ѱѿ���4ʸ���ʾ�8ʸ�������</font></td>
		</tr>
		<tr>
			<td class="m5">ȯ�Լ�[����]</td>
			<td class="n5"><input size=40 name=owner_nm_kanj maxlength=25><font class="note">�����ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">ȯ�Լ�[����]</td>
			<td class="n5"><input size=40 name=owner_nm_kana maxlength=25><font class="note">�����ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">͹���ֹ�</td>
			<td class="n5"><input size=3 name=owner_zip1 maxlength=3>-<input size=4 name=owner_zip2 maxlength=4> <font class="note">��Ⱦ�ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">Ϣ���轻��</td>
			<td class="n5"><select name="owner_prefec"><? SelectKen(); ?></select>
			<input size=50 name=owner_add maxlength=100 value="<?= $owner_add ?>"><font class="note">�����ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">Ϣ���������ֹ�</td>
			<td class="n5"><input size=20 name=owner_tel maxlength=14><font class="note">��Ⱦ�ѡ�</font></td>
		</tr>
		<tr>
			<td class="m5">�ݥ����</td>
			<td class="n5"><input size=9 DIR=rtl name=pt_melonpan maxlength=14 value=0><font class="note">��Ⱦ�ѿ��͡�</font></td>
		</tr>
	</table><br>
	<input type="hidden" name="publisher_id" value=<?= $publisher_id ?>>
	<input type="submit" value=" ��Ͽ ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="history.back()">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
