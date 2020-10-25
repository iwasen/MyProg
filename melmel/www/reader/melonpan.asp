<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
Dim DB, RS, SQL, WHERE

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MG_m_name,MG_melonpan_id FROM T_MELMAG WHERE MG_mag_id='" & Request.QueryString("id") & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
%>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title>めろんぱんで購読</title>
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form() {
	if (navigator.appName.charAt(0) == "M")
		window.close();
	return true;
}
//-->
</SCRIPT>
</HEAD>
<BODY onload="document.form1.email.focus()">
<center>
<table width="400" border="0" cellspacing="0" cellpadding="2" height="60">
 <tr bgcolor="#669933">
  <td height="25" align="center" colspan="2">
   <font size="-1" color="#ffffff">
    <b>『<%=ReplaceTag(RS(0))%>』</b> を購読しませんか？
   </font>
  </td>
 </tr>
 <tr>
  <td width="100" bgcolor="#fff9c2" align="left">
   <a href="http://www.melonpan.net/" target="_blank">
   <img src="http://www.melonpan.net/img/banner.gif" border="0"
    alt="めろんぱん" width="135" height="45">
   </a>
  </td>
   <form name="form1" action="http://www.melonpan.net/melonpa/register.php3" method="post" target="_blank" onsubmit="OnSubmit_form()">
  <td  bgcolor="#FFF9C2" align="right">
   <font size="-1" color="#006600">メールアドレス</font>
    <input type="hidden" name="mag_id" value="<%=RS(1)%>">
    <input type="text" size="22" name="email">
    <input type="submit" value="購読" name="regist">
  </td>
   </form>
 </tr>
</table>
<form>
<input type="button" value="閉じる" onclick="window.close()">
</form>
</center>
</BODY>
</HTML>
