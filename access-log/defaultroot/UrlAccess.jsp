<%@ page contentType="text/html; charset=Shift_JIS" %>
<jsp:useBean id="UrlAccessBeanId" scope="page" class="xxx.UrlAccessBean" />
<% UrlAccessBeanId.SearchDB(request); %>
<html>
<head>
<title>xxx Business Analyst ��������</title>
<meta name="author" content="Kuni Tojima/tojima@xxxx.co.jp">
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<link rel="stylesheet" href="table.css" type="text/css">
<link rel="stylesheet" href="size.css" type="text/css">
<!---�@�����N�����X�^�C����`�@--->
<style>
.linkcolor_blue { color:blue;font-weight:bold;text-decoration:none; }
span a:hover { color:red }
</style>
<!---�@�����N�����X�^�C����`�@�����܂Ł@--->
<!---�@�ʃE�B���h�E�I�[�v��JS�@--->
<script language="JavaScript">
<!--
function open_url(url) {
  window.open(url, "_blank");
}
//-->
</script>
<!---�@�ʃE�B���h�E�I�[�v��JS�@�����܂Ł@--->
</head>

<body bgcolor="#FFFFFF" text="#000000"  leftmargin="10" topmargin="10">
<form method="post">
<!---�@�w�b�_�����@--->
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left"><img src="images/lgo_top.jpg" width="100" height="51"></td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0" height="5">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_header_home.gif" height="6">
<tr>
<td> </td>
</tr>
</table>
<!---�@�w�b�_���� �����܂Ł@--->
<!--�@�\�������@-->
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left" class="size20"><b><font color="#333333" face="�l�r �o�S�V�b�N, Osaka">��ʖ��̃A�N�Z�X�ƕ\��</font></b></td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_footer_home.gif" height="3">
<tr>
<td> </td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_corner_meiS.gif" width="65" height="18"></td>
<td width="5" align="center">�@</td>
<td class="size09" align="left"><jsp:getProperty name="UrlAccessBeanId" property="cornerName" /></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_gyousyu_mei.gif" width="65" height="18"></td>
<td width="5" align="center">�@</td>
<td class="size09" align="left"><jsp:getProperty name="UrlAccessBeanId" property="categoryName" /></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#00156C">
<tr align="center" bgcolor="#FFDFB0" class="size10">
<td>����</td>
<td>�t�q�k</td>
<td valign="middle">�A�N�Z�X</td>
</tr>
<jsp:getProperty name="UrlAccessBeanId" property="dbdata" />
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="size08" align="left">���R�[�i�[���������͂t�q�k���N���b�N����Ǝ��ۂ̃y�[�W���\������܂��B</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="70">
<input type="submit" name="Back" value="�O��20��" style="width:70"<jsp:getProperty name="UrlAccessBeanId" property="disableBackPage" />>
</td>
<td width="10">�@</td>
<td width="70">
<input type="submit" name="Forward" value="����20��" style="width:70"<jsp:getProperty name="UrlAccessBeanId" property="disableForwardPage" />>
</td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<!---�@�\�������@�����܂Ł@--->
<!---�@�t�b�^�����@--->
<div align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_footer_home.gif" height="6">
<tr>
<td> </td>
</tr>
</table>
</div>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center">
<input type="button" name="back" value="����" onClick="window.close()">
</td>
</tr>
</table>
<!---�@�t�b�^�����@�����܂Ł@--->
<!--- hidden���� --->
<input type="hidden" name="date" value="<jsp:getProperty name="UrlAccessBeanId" property="date" />">
<input type="hidden" name="corner" value="<jsp:getProperty name="UrlAccessBeanId" property="cornerCode" />">
<input type="hidden" name="category" value="<jsp:getProperty name="UrlAccessBeanId" property="categoryCode" />">
<input type="hidden" name="pageno" value="<jsp:getProperty name="UrlAccessBeanId" property="pageNo" />">
<!--- hidden���� �����܂� --->
</form>
</body>
</html>
