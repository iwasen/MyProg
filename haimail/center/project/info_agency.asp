<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:代理店情報表示処理
'******************************************************

SetHeaderTitle "代理店情報"

Dim sql, ds, dairiten_id

dairiten_id = Request.QueryString("id")
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
<%
sql = "SELECT * FROM T_DAIRITEN WHERE DA_dairiten_id='" & dairiten_id & "'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
%>
	<table border=0 cellspacing=0 cellpadding=0 width='500'>
		<tr>
			<td class="m0">■代理店情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m1" width="30%">代理店ＩＤ　</td>
						<td class="n1"><%=dairiten_id%></td>
					</tr>
					<tr>
						<td class="m1">代理店名称</td>
						<td class="n1"><%=ReplaceTag(ds("DA_name").Value)%></td>
					</tr>
					<tr>
						<td class="m1">代理店クラス</td>
						<td class="n1"><%=ds("DA_class").Value%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<%
End If
%>
	<br>
	<input type="button" value="　閉じる　" onclick="window.close()">
</center>
</body>
</html>
