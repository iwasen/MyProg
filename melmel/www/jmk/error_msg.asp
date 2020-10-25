<%@ Language=VBScript %>
<%
Option Explicit
Response.Clear
%>

<html>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<META name="GENERATOR" Content="Microsoft Visual Studio 6.0">
</HEAD>
<body>

<table width="100%" border=0 cellpadding=5 align="center">
  <tr bgcolor="orange">
    <td><font color="white" size="+1"><STRONG>エラーメッセージ</STRONG></font></td>
  </tr>
  <tr>
    <td><%=Session("err_msg")%></td>
  </tr>
  <tr>
    <td><form><input type="button" value=" 戻る " onClick="history.back()"></form></td>
  </tr>
</table>
</body>
</html>
