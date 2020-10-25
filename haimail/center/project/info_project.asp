<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:プロジェクト情報表示処理
'******************************************************

SetHeaderTitle "プロジェクト情報"

Dim sql, ds, project_id

project_id= Request.QueryString("id")
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
sql = "SELECT * FROM T_PROJECT,T_DAIRITEN,T_COMPANY,T_TANTOUSHA" & vbCrLf & _
			"WHERE PJ_project_id=" & project_id & vbCrLf & _
			" AND PJ_tantousha_id=TS_tantousha_id AND TS_company_id=CM_company_id AND CM_dairiten_id=DA_dairiten_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
%>
	<table border=0 cellspacing=0 cellpadding=0 width='500'>
		<tr>
			<td class="m0">■プロジェクト情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="30%">プロジェクトＩＤ　</td>
						<td class="n3"><%=ds("PJ_project_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">プロジェクト名</td>
						<td class="n3"><%=ReplaceTag(ds("PJ_project_name").Value)%></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td class="m0">■代理店情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="30%">代理店ＩＤ　</td>
						<td class="n3"><%=ds("DA_dairiten_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">代理店名称</td>
						<td class="n3"><%=ReplaceTag(ds("DA_name").Value)%></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td class="m0">■企業／担当者情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="30%">企業ＩＤ　</td>
						<td class="n3"><%=ds("CM_company_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">企業名</td>
						<td class="n3"><%=ReplaceTag(ds("CM_name").Value)%></td>
					</tr>
					<tr>
						<td class="m3" width="30%">担当者ＩＤ　</td>
						<td class="n3"><%=ds("TS_tantousha_id").Value%></td>
					</tr>
					<tr>
						<td class="m3">担当者名</td>
						<td class="n3"><%=ReplaceTag(ds("TS_name1").Value & " " & ds("TS_name2").Value)%></td>
					</tr>
					<tr>
						<td class="m3" width="30%">e-mailアドレス</td>
						<td class="n3"><%=ds("TS_mail_adr").Value%></td>
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
