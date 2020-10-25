<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:テンプレート更新
'******************************************************

'=== メイン処理 ===
Dim b, basp, sql, template_id, filename, p

set_global "テンプレート更新", BACK_TOP

b = Request.BinaryRead(Request.TotalBytes)
Set basp = Server.CreateObject("basp21")

BeginTrans

template_id = basp.Form(b, "template_id")

sql = "UPDATE t_template SET " & _
			"te_title=" & sql_char(basp.Form(b, "title")) & "," & _
			"te_memo=" & sql_char(basp.Form(b, "memo")) & " WHERE te_template_id=" & sql_number(template_id)
ExecuteSQL sql

filename = basp.FormFileName(b, "template_file")
If filename <> "" Then
	p = InStrRev(filename, "\")
	filename = Mid(filename, p + 1)

	sql = "UPDATE t_template SET " & _
				"te_filename=" & sql_char(filename) & " WHERE te_template_id=" & sql_number(template_id)
	ExecuteSQL sql

	basp.BinaryWrite basp.FormBinary(b, "template_file"), Server.MapPath(filename)
End If

CommitTrans
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../css/main.css">
</head>
<body>
<% page_header() %>

<div align="center">
<p class="msg">テンプレートを更新しました。</p>
<p><input type="button" id="ok" value="　戻る　" onclick="location.href='../enquete/template.asp?enquete_id=<%=basp.Form(b, "enquete_id")%>'"></p>
</div>

<% page_footer() %>
</body>
</html>
