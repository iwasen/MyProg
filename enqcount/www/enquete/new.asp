<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/header.asp"-->
<!--#INCLUDE FILE="../inc/login_check.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:アンケート新規登録
'******************************************************

'=== メイン処理 ===
Dim enquete_id, sql, rs, title, memo

set_global "アンケートデータアップロード", BACK_TOP

enquete_id = Request.QueryString("enquete_id")
If enquete_id <> "" Then
	sql = "SELECT en_title,en_memo FROM t_enquete WHERE en_enquete_id=" & sql_number(enquete_id)
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	If Not rs.EOF Then
		title = rs("en_title")
		memo = rs("en_memo")
	End If
End If
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../css/main.css">
<script type="text/javascript">
<!--
function onSubmit_form1(f) {
<%
If enquete_id = "" Then
%>
	if (f.answer_file.value == "") {
		alert("回答データファイルを入力してください。");
		f.answer_file.focus();
		return false;
	}
<%
End If
%>
	if (f.title.value == "") {
		alert("タイトルを入力してください。");
		f.title.focus();
		return false;
	}
	return true;
}
function onclick_delete(id) {
	if (confirm("このアンケートを削除します。よろしいですか？"))
		location.href = "delete.asp?enquete_id=<%=Request.QueryString("enquete_id")%>";
}
//-->
</script>
</head>
<body>
<% page_header() %>

<div align="center">
<form method="post" name="form1" action="upload.asp" ENCtype="multipart/form-data" onsubmit="return onSubmit_form1(this)">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0" colspan=2>■集計するデータを入力してください</td>
	</tr>
	<tr>
		<td class="m1">回答データファイル<%If enquete_id = "" Then Response.Write MUST_ITEM%></td>
		<td class="n1">
			<input type="file" name="answer_file" size=80>
		</td>
	</tr>
	<tr>
		<td class="m1" width="20%">設問項目ファイル</td>
		<td class="n1" width="80%">
			<input type="file" name="question_file" size=80>
		</td>
	</tr>
	<tr>
		<td class="m1">タイトル<%=MUST_ITEM%></td>
		<td class="n1">
			<input type="text" name="title" size=80 <%=value(title)%>>
		</td>
	</tr>
	<tr>
		<td class="m1">備考欄</td>
		<td class="n1">
			<textarea name="memo" cols=60 rows=5><%=HTMLEncode(memo)%></textarea>
		</td>
	</tr>
</table>
<br>
<input type="submit" value="　登録　" onclick="document.form1.next_action.value='new'">
<%
If enquete_id <> "" Then
%>
<input type="button" value="　削除　" onclick="onclick_delete()">
<%
End If
%>
<input type="button" value="キャンセル" onclick="history.back()">
<input type="hidden" name="next_action">
<input type="hidden" name="enquete_id" value="<%=enquete_id%>">
</form>
</div>

<% page_footer() %>
</body>
</html>
