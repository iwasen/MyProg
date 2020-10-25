<!--#INCLUDE FILE="inc/common.asp"-->
<!--#INCLUDE FILE="inc/sub.asp"-->
<!--#INCLUDE FILE="inc/database.asp"-->
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">
</HEAD>
<BODY>

<P>&nbsp;</P>
<%
Response.Write SendMail("aizawa@linux.xxxxx-xxxx.co.jp" & vbtab & ">Return-Path: <sys@xxxxx-xxxx.co.jp>", "test@xxxxx-xxxx.co.jp", "test", "test")
%>

</BODY>
</HTML>
