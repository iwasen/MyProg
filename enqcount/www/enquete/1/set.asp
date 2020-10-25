<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/header.asp"-->
<!--#INCLUDE FILE="../../inc/list.asp"-->
<%
'******************************************************
' System :アンケート集計サービス
' Content:カラム情報設定
'******************************************************

'=== 設問種別デコード ===
Function decode_question_type(question_type)
	Select Case question_type
	Case "SA"
		decode_question_type = "単一回答(SA)"
	Case "MA"
		decode_question_type = "複数回答(MA)"
	Case "FA"
		decode_question_type = "自由回答(FA)"
	Case "DT"
		decode_question_type = "日付(DT)"
	Case "ID"
		decode_question_type = "回答者ID(ID)"
	End Select
End Function

'=== 特定カラム選択 ===
Sub set_column(question_type, name, col_no, sel_no)
	Dim checked

	If question_type = "SA" Or question_type = "MA" Then
		If col_no = sel_no Then
			checked = " checked"
		End If

		Response.Write "<input type='radio' name='" & name & "'" & " value=" & col_no & checked & ">"
	End If
End Sub

'=== 集計出力カラム選択 ===
Sub set_output(question_type, question_text, col_no)
	Dim checked

	If question_type = "SA" Or question_type = "MA" Or question_type = "FA" Then
		If Left(question_text, 1) = "Ｑ" Or Left(question_text, 1) = "Q" Then
			checked = " checked"
		End If
		
		Response.Write "<input type='checkbox' name='output'" & " value=" & col_no & checked & ">"
	End If
End Sub

'=== 特定カラム取得 ===
Function find_column(enquete_id, name)
	Dim sql, rs

	sql = "SELECT qu_column_no,qu_question_name,qu_question_text FROM t_question WHERE qu_enquete_id=" & enquete_id
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	Do Until rs.EOF
		If rs("qu_question_name") = name Or rs("qu_question_text") = name Then
			find_column = rs("qu_column_no")
			Exit Do
		End IF

		If InStr(rs("qu_question_name"), name) > 0 Or InStr(rs("qu_question_text"), name) > 0 Then
			find_column = rs("qu_column_no")
		End If
	
		rs.MoveNext
	Loop
End Function

'=== カラム選択 ===
Function select_column(col_no)
	Dim s

	sql = "SELECT qu_column_no,qu_question_name FROM t_question WHERE qu_question_type='SA' ORDER BY qu_column_no"
	Set rs = CreateRecordset(sql, adOpenForwardOnly, adLockReadOnly)
	Do Until rs.EOF
		s = s & "<option " & value_selected(rs("qu_column_no"), col_no) & ">" & rs("qu_question_name") & "</option>"
		rs.MoveNext
	Loop
	select_column = s
End Function

'=== メイン処理 ===
Dim enquete_id, template_id, displine, page, order_by, sql, rs, nrow, n, checked, column_no, question_type, question_text
Dim sex, age, mikion, shokugyou, kyojuu

set_global "集計出力情報設定", BACK_TOP

enquete_id = Request.QueryString("enquete_id")
template_id = Request.QueryString("template_id")

sql = "SELECT qu_enquete_id,qu_column_no,qu_question_name,qu_question_type,qu_question_text" & _
			" FROM t_question" & _
			" WHERE qu_enquete_id=" & enquete_id & _
			" ORDER BY qu_column_no"
Set rs = CreateRecordset(sql, adOpenKeyset, adLockReadOnly)
%>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<title><%=g_title%></title>
<link rel="stylesheet" type="text/css" href="../../css/main.css">
<script type="text/javascript">
<!--
function onsubmit_form1(f) {
	if (!check_radio(f, "side1")) {
		alert("表側１を選択してください。");
		return false;
	}
	if (!check_radio(f, "side2")) {
		alert("表側２を選択してください。");
		return false;
	}
	return true;
}
function check_radio(f, nm) {
	if (f[nm]) {
		if (f[nm].length) {
			for (i = 0; i < f[nm].length; i++) {
				if (f[nm][i].checked)
					return true;
			}
		} else
			return f[nm].checked;
	}
	return false;
}
//-->
</script>
</head>
<body>
<% page_header() %>

<div align="center">
<table border=0 cellspacing=0 cellpadding=1 width="100%">
	<tr>
		<td class="lt">■カラム情報</td>
		<td class="lb">
			<input type="button" value="　戻る　" onclick="location.href='../list.asp'">
		</td>
	</tr>
</table>

<form method="post" name="form1" action="output.asp" onsubmit="return onsubmit_form1(this)">
<table <%=LIST_TABLE%> width="100%" class="small">
	<tr class="tch">
		<th rowspan=2>ｶﾗﾑNo.</th>
		<th rowspan=2>カラム名</th>
		<th rowspan=2>種別</th>
		<th rowspan=2>設問文</th>
		<th colspan=5>プロファイル</th>
		<th colspan=2>集計表</th>
		<th rowspan=2>FA<br>＋α</th>
		<th rowspan=2>出力</th>
	</tr>
	<tr class="tch">
		<th>性別</th>
		<th>年代</th>
		<th>未既婚</th>
		<th>職業</th>
		<th>居住地</th>
		<th>表側１</th>
		<th>表側２</th>
	</tr>
<%
sex = find_column(enquete_id, "性別")
age = find_column(enquete_id, "年代")
If age = "" Then
	age = find_column(enquete_id, "年齢")
End If
mikion = find_column(enquete_id, "未既婚")
shokugyou = find_column(enquete_id, "職業")
kyojuu = find_column(enquete_id, "居住地域")

n = 0
Do Until rs.EOF
	column_no = rs("qu_column_no")
	question_type = rs("qu_question_type")
	question_text = rs("qu_question_text")
%>
	<tr class="tc<%=n Mod 2%>">
		<td align="center"><%=column_no%></td>
		<td><%=HTMLEncode(rs("qu_question_name"))%></td>
		<td><%=decode_question_type(rs("qu_question_type"))%></td>
		<td><%=HTMLEncode(question_text)%></td>
		<td align="center"><%set_column question_type, "sex", column_no, sex%></td>
		<td align="center"><%set_column question_type, "age", column_no, age%></td>
		<td align="center"><%set_column question_type, "mikikon", column_no, mikion%></td>
		<td align="center"><%set_column question_type, "shokugyou", column_no, shokugyou%></td>
		<td align="center"><%set_column question_type, "kyojuu", column_no, kyojuu%></td>
		<td align="center"><%set_column question_type, "side1", column_no, sex%></td>
		<td align="center"><%set_column question_type, "side2", column_no, age%></td>
		<td align="center"><%set_column question_type, "fa", column_no, 0%></td>
		<td align="center"><%set_output question_type, question_text, column_no%></td>
	</tr>
<%
	n = n + 1
	rs.MoveNext
Loop
%>
</table>
<br>
<input type="submit" value="　出力　">
<input type="reset" value="リセット">
<input type="hidden" name="enquete_id" value="<%=enquete_id%>">
<input type="hidden" name="template_id" value="<%=template_id%>">
</form>
</div>

<% page_footer() %>
</body>
</html>
