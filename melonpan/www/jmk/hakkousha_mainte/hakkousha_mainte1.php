<?
include ("../inc/header_jmk.php");
include ("../inc/footer_jmk.php");
include ("../inc/common.php");
include ("../inc/database_inc.php");
include ("../inc/admin_inc.php");
include ("../inc/sub_func_inc.php");

/******************************************************
' System :����Ѥ��̳���ѥڡ���
' Content:ȯ�ԼԾ�����ƥʥ�
'******************************************************/

$title_text = $TT_hakkousha_mainte;
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
  document.form1.search_addr.focus();
}
function OnSubmit_form1(form) {
  if (form.search_addr.value == "") {
    alert("�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������");
    form.search_addr.focus();
    return false;
  }
  return true;
}
//-->
</SCRIPT>
</head>
<body onLoad="OnLoad_body()">

<? header_jmk($title_text,$title_color,0); ?>

<center>
<form method="post" name="form1" action="hakkousha_mainte1_2.php" onSubmit="return OnSubmit_form1(this)" method="post">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
	  <tr>
	    <td class="m0">��ȯ�ԼԤΥ᡼�륢�ɥ쥹�����Ϥ��Ƥ�������</td>
		</tr>
	</table>
  <table border=0 cellspacing=2 cellpadding=3 width='700'>
    <tr>
      <td class="h3" width="25%">�᡼�륢�ɥ쥹</td>
      <td class="n4"><input type="text" size="40" maxlength="50" name="search_addr"></td>
    </tr>
  </table><br>
  <input type="submit" value=" ���� ">
	<input type="button" value=" ��� " onclick="location.href='../menu.php'">
</form>
</center>

<? footer_jmk(0); ?>

</body>
</html>
