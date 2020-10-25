<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="kaiyaku_inc.asp"-->
<%
'******************************************************
' System :‚ß‚é‚ß‚éEnetŽ––±‹Ç—pƒy[ƒW
' Content:–{“o˜^‰ðœˆ—
'******************************************************

SetHeaderTitle "–{“o˜^‰ðœŒ‹‰Ê", TC_HAKKOUSHA
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim mag_id, msg

'ƒpƒ‰ƒ[ƒ‰Žæ“¾
mag_id = Request.Form("mag_id")
If Not IsEmpty(mag_id) Then
  '‰ð–ñˆ—
  If Kaiyaku(mag_id) Then
    msg = "Žw’è‚µ‚½ƒƒ‹ƒ}ƒK‚Ì“o˜^‚ð‰ðœ‚µ‚Ü‚µ‚½B"
  Else
    msg = "‰ðœ’Ê’mƒ[ƒ‹‚Ì‘—M‚ÉŽ¸”s‚µ‚Ü‚µ‚½B"
  End If
End If
%>

<center>
<p><%=msg%></p>
<input type="button" value=" OK " onClick="location.href='cancel.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
