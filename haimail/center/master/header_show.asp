<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局・営業共通ページ
' Content:HTML用ヘッダ、感想文、フッタ表示処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "ヘッダ、感想文、フッタ表示"

Dim body, header, impression, footer

header = Request.Form("header")
impression =Request.Form("impression")
footer = Request.Form("footer")

body = "<br><center><h1><font color='blue'>本 文</font></h1></center><br>"
body = header & body & impression & footer
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<center>
<table border=0 cellspacing=1 cellpadding=0>
	<tr>
		<td colspan=2>
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td><%=body%></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<input type="button" value=" 閉じる " onclick="window.close()">
</center>

</body>
</html>
