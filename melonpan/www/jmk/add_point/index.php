<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");


/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:ȯ�Լԡ��ɼ�/�ݥ������Ϳ
'******************************************************/

$title_text = $TT_add_point;
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
    if (search_id1.value == "") {
      alert("ID�����Ϥ��Ƥ���������");
      search_id1.focus();
      return false;
    }
		if (!num_chk(search_id1.value)){
			alert("ID��Ⱦ�Ѥο��ͤ����Ϥ��Ƥ���������");
			search_id1.focus();
			return(false);
		}
	}
}
function OnSubmit_form2() {
  with (document.form2) {
    if (search_mail_adr1.value == "") {
      alert("ID�����Ϥ��Ƥ���������");
      search_mail_adr1.focus();
      return false;
    }
		if (!isA(search_mail_adr1.value)) {
			alert("�᡼�륢�ɥ쥹��Ⱦ�ѱѿ������Ϥ��Ƥ���������");
			search_mail_adr1.focus();
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
			<td>��ȯ�Լԡ��ɼԸ���</td>
		</tr>
	</table>
	<table border=1 cellspacing=0 cellpadding=5 width='80%'>
	<form method="post" name="form1" action="add_point1.php" onSubmit="return OnSubmit_form1();">
		<tr>
			<td align="center" class="m6s">��</td>
			<td class="m6s"><nobr>ID����</nobr></td>
			<td class="h2"><nobr>
				<input type="text" name="search_id1" size=40>
				<input type="radio" name="search_id2" value="hakkousha" checked><font size=-1>ȯ�Լ�</font>
				<input type="radio" name="search_id2" value="dokusha"><font size=-1>�ɼ�</nobr></font>
				<input type="radio" name="search_id2" value="navi"><font size=-1>���ޥ��ʥ�</font></nobr>
			</td>
			<td align="center" class="h2"><input type="submit" value=" ���� "></td>
		</tr>
	</form>
	<form method="post" name="form2" action="add_point1.php" onSubmit="return OnSubmit_form2();">
		<tr>
			<td align="center" class="m6s">��</td>
			<td class="m6s"><nobr>��Ͽ�᡼�륢�ɥ쥹����</nobr></td>
			<td class="h2">
				<input type="text" name="search_mail_adr1" size=40>
				<input type="radio" name="search_mail_adr2" value="hakkousha" checked><font size=-1>ȯ�Լ�</font>
				<input type="radio" name="search_mail_adr2" value="dokusha"><font size=-1>�ɼ�</font>
				<input type="radio" name="search_mail_adr2" value="navi"><font size=-1>���ޥ��ʥ�</font>
			</td>
			<td align="center" class="h2"><input type="submit" value=" ���� "></td>
		</tr>
	</form>
	<form method="post" name="form3" action="add_point_list.php">
		<tr>
			<td align="center" class="m6s">��</td>
			<td class="m6s"><nobr>����ɽ��</nobr></td>
			<td class="h2">
				<input type="radio" name="regist_list" value="hakkousha" checked><font size=-1>ȯ�Լ�</font>
				<input type="radio" name="regist_list" value="dokusha" ><font size=-1>�ɼ�</font>
				<input type="radio" name="regist_list" value="navi" ><font size=-1>���ޥ��ʥ�</font>
			</td>
			<td align="center" class="h2"><input type="submit" value=" ���� "></td>
		</tr>
	</form>
	</table><br>
	<input type="button" value=" ��� " onclick="location.href='../menu.php'">
</center>

<? footer_jmk(0); ?>

</body>
</html>
