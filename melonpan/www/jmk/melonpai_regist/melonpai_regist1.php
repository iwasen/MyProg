<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:���ޥ��ʥ���Ͽ
'******************************************************/

$title_text = $TT_melonpai_regist;
$title_color = $TC_DOKUSHA;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><?= $title_text ?></title>
<link rel="stylesheet" type="text/css" href="../css/melonpan.css">
<SCRIPT LANGUAGE=javascript>
<!--
function num_chk(v1) {  
	var p1 = v1;  
	for (i=0; i<p1.length; i++){  
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {  
			return(false);  
		}  
	}  
	return true;  
}
function isA(val) {
	var numstring ="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ{}./+-*=~!#$%^&_?@";
	for(var i = 0; i < val.length; i++) {
		if(numstring.indexOf(val.charAt(i)) == -1)
			return false;
	}
	return true;
}
function OnSubmit_form1() {
 with (document.form1) {
    if (name.value == "") {
      alert("��̾�����Ϥ��Ƥ���������");
      name.focus();
      return false;
    }
    if (mail_addr.value == "") {
      alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
      mail_addr.focus();
      return false;
    }
		if (!isA(mail_addr.value)) {
			alert("�᡼�륢�ɥ쥹��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			mail_addr.focus();
			return false;
		}
		if (pswd.value == "") {
			alert("�ѥ���ɤ����Ϥ��Ƥ���������");
			pswd.focus();
			return false;
		}
		if (!isA(pswd.value)) {
			alert("�ѥ���ɤ�Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			pswd.focus();
			return false;
		}
		if (!isA(zip1.value)) {
			alert("͹���ֹ��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			zip1.focus();
			return false;
		}
		if (!isA(zip2.value)) {
			alert("͹���ֹ��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			zip2.focus();
			return false;
		}
		if (!isA(tel.value)) {
			alert("�����ֹ��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			tel.focus();
			return false;
		}		str=pro.value;
		if (str.length > 50) {
			alert("�ץ�ե����뤬Ĺ�����ޤ���");
			pro.focus();
			return(false);
		}
    if (choice_num.value == "") {
      alert("�ߤĤ�������ͤ����Ϥ��Ƥ���������");
      choice_num.focus();
      return false;
    }
		if (!num_chk(choice_num.value)){  
			alert("�ߤĤ�������ͤ�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");  
			choice_num.focus();  
			return(false);  
		} 
    if (recom_num.value == "") {
      alert("����ʸ����ͤ����Ϥ��Ƥ���������");
      recom_num.focus();
      return false;
    }
		if (!num_chk(recom_num.value)){  
			alert("����ʸ����ͤ�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");  
			recom_num.focus();  
			return(false);  
		} 
    if (osusume_num.value == "") {
      alert("�����������ͤ����Ϥ��Ƥ���������");
      osusume_num.focus();
      return false;
    }
		if (!num_chk(osusume_num.value)){  
			alert("�����������ͤ�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");  
			osusume_num.focus();  
			return(false);  
		}
    if (abunai_num.value == "") {
      alert("���֤ʤ������ͤ����Ϥ��Ƥ���������");
      abunai_num.focus();
      return false;
    }
		if (!num_chk(abunai_num.value)){  
			alert("���֤ʤ������ͤ�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");  
			abunai_num.focus();  
			return(false);  
		} 
    if (point.value == "") {
      alert("�ݥ���Ƚ���ͤ����Ϥ��Ƥ���������");
      point.focus();
      return false;
    }
		if (!num_chk(point.value)){  
			alert("�ݥ���Ƚ���ͤ�Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");  
			point.focus();  
			return(false);  
		} 

  }
	return confirm("��Ͽ���ޤ���������Ǥ�����");
}
//-->
</SCRIPT>
</head>
<body>

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" action="melonpai_regist3.php" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">�����ޥ��ʥ���Ͽ����</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=1 cellpadding=3 width="100%">
					<tr>
						<td class="m5">��ư����</td>
						<td class="n5">
							<input type="radio" name="m_flg" value="0" checked><font size="-1"> ��ư�� </font>
							<input type="radio" name="m_flg" value="1"><font size="-1"> �ٻ��� </font>
							<input type="radio" name="m_flg" value="2"><font size="-1"> ��� </font>
						</td>
					</tr>
					<tr>
						<td class="m5">�ᡡ��̾</td>
						<td class="n5"><input size=50 name="name" maxlength=25><font class="note">�����ѡ�</font></td>
					</tr>
					<tr>
						<td class="m5">�˥å��͡���</td>
						<td class="n5"><input size=50 name="nic" maxlength=10><font class="note">��Ⱦ�ѱѿ���</font></td>
					</tr>
					<tr>
						<td class="m5" width="25%">�᡼�륢�ɥ쥹</td>
						<td class="n5"><input size=50 name="mail_addr" maxlength=50><font class="note">��Ⱦ�ѱѿ���</font><BR></td>
					</tr>
					<tr>
						<td class="m5">�ѥ����</td>
						<td class="n5"><input size=20 name="pswd" maxlength=8><font class="note">��Ⱦ�ѱѿ���8ʸ�������</font></td>
					</tr>
					<tr>
						<td class="m5">͹���ֹ�</td>
						<td class="n5"><input size=3 name="zip1" maxlength=3>-<input size=4 name="zip2" maxlength=4> <font class="note">��Ⱦ�ѿ��͡�</font></td>
					</tr>
					<tr>
						<td class="m5">��������</td>
						<td class="n5"><input size=80 name="add" maxlength=50><font class="note">�����ѡ�</font></td>
					</tr>
					<tr>
						<td class="m5">�����ֹ�</td>
						<td class="n5"><input size=20 name="tel" maxlength=15><font class="note">��Ⱦ�ѿ��͡�</font></td>
					</tr>
					<tr>
						<td class="m5">�ץ�ե�����</td>
						<td class="n5"><textarea name="pro" cols="40" rows="4"></textarea><font class="note">�����ѡ�50ʸ�������</font></td>
					</tr>
					<tr>
						<td class="m5">�ߤĤ�����½����</td>
						<td class="n5"><input size=10 DIR=rtl name="choice_num" maxlength=4 value=0><font class="note">��Ⱦ�ѿ��͡�</font></td>
					</tr>
					<tr>
						<td class="m5">����ʸ��½����</td>
						<td class="n5"><input size=10 DIR=rtl name="recom_num" maxlength=4 value=0><font class="note">��Ⱦ�ѿ��͡�</font></td>
					</tr>
					<tr>
						<td class="m5">���������½����</td>
						<td class="n5"><input size=10 DIR=rtl name="osusume_num" maxlength=4 value=0><font class="note">��Ⱦ�ѿ��͡�</font></td>
					</tr>
					<tr>
						<td class="m5">���֤ʤ����½����</td>
						<td class="n5"><input size=10 DIR=rtl name="abunai_num" maxlength=4 value=0><font class="note">��Ⱦ�ѿ��͡�</font></td>
					</tr>
					<tr>
						<td class="m5">�ݥ���Ƚ����</td>
						<td class="n5"><input size=10 DIR=rtl name="point" maxlength=10 value=0><font class="note">��Ⱦ�ѿ��͡�</font></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" ��Ͽ ">
	<input type="reset" value="�ꥻ�å�">
	<input type="button" value=" ��� " onclick="history.back()">

</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
