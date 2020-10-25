<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/header.asp"-->
<!--#INCLUDE FILE="../../inc/list.asp"-->
<%
'******************************************************
' System :�A���P�[�g�W�v�T�[�r�X
' Content:�J�������ݒ�
'******************************************************

'=== �ݖ��ʃf�R�[�h ===
Function decode_question_type(question_type)
	Select Case question_type
	Case "SA"
		decode_question_type = "�P���(SA)"
	Case "MA"
		decode_question_type = "������(MA)"
	Case "FA"
		decode_question_type = "���R��(FA)"
	Case "DT"
		decode_question_type = "���t(DT)"
	Case "ID"
		decode_question_type = "�񓚎�ID(ID)"
	End Select
End Function

'=== ����J�����I�� ===
Sub set_column(question_type, name, col_no, sel_no)
	Dim checked

	If question_type = "SA" Or question_type = "MA" Then
		If col_no = sel_no Then
			checked = " checked"
		End If

		Response.Write "<input type='radio' name='" & name & "'" & " value=" & col_no & checked & ">"
	End If
End Sub

'=== �W�v�o�̓J�����I�� ===
Sub set_output(question_type, question_text, col_no)
	Dim checked

	If question_type = "SA" Or question_type = "MA" Or question_type = "FA" Then
		If Left(question_text, 1) = "�p" Or Left(question_text, 1) = "Q" Then
			checked = " checked"
		End If
		
		Response.Write "<input type='checkbox' name='output'" & " value=" & col_no & checked & ">"
	End If
End Sub

'=== ����J�����擾 ===
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

'=== �J�����I�� ===
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

'=== ���C������ ===
Dim enquete_id, template_id, displine, page, order_by, sql, rs, nrow, n, checked, column_no, question_type, question_text
Dim sex, age, mikion, shokugyou, kyojuu

set_global "�W�v�o�͏��ݒ�", BACK_TOP

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
		alert("�\���P��I�����Ă��������B");
		return false;
	}
	if (!check_radio(f, "side2")) {
		alert("�\���Q��I�����Ă��������B");
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
		<td class="lt">���J�������</td>
		<td class="lb">
			<input type="button" value="�@�߂�@" onclick="location.href='../list.asp'">
		</td>
	</tr>
</table>

<form method="post" name="form1" action="output.asp" onsubmit="return onsubmit_form1(this)">
<table <%=LIST_TABLE%> width="100%" class="small">
	<tr class="tch">
		<th rowspan=2>���No.</th>
		<th rowspan=2>�J������</th>
		<th rowspan=2>���</th>
		<th rowspan=2>�ݖ╶</th>
		<th colspan=5>�v���t�@�C��</th>
		<th colspan=2>�W�v�\</th>
		<th rowspan=2>FA<br>�{��</th>
		<th rowspan=2>�o��</th>
	</tr>
	<tr class="tch">
		<th>����</th>
		<th>�N��</th>
		<th>������</th>
		<th>�E��</th>
		<th>���Z�n</th>
		<th>�\���P</th>
		<th>�\���Q</th>
	</tr>
<%
sex = find_column(enquete_id, "����")
age = find_column(enquete_id, "�N��")
If age = "" Then
	age = find_column(enquete_id, "�N��")
End If
mikion = find_column(enquete_id, "������")
shokugyou = find_column(enquete_id, "�E��")
kyojuu = find_column(enquete_id, "���Z�n��")

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
<input type="submit" value="�@�o�́@">
<input type="reset" value="���Z�b�g">
<input type="hidden" name="enquete_id" value="<%=enquete_id%>">
<input type="hidden" name="template_id" value="<%=template_id%>">
</form>
</div>

<% page_footer() %>
</body>
</html>
