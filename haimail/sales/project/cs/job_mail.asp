<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:���[���R���e���c�\������
'******************************************************

'=== �N���b�J�u��URL���� ===
Private Sub Clickable(body)
	Dim arry_ct_id, arry_jump_url
	Dim p1, p2, p3, p4, disp_url, jump_url, click_url, ct_id, i

	arry_ct_id = Split(Request.Form("ct_id"), "||")
	arry_jump_url = Split(Request.Form("jump_url"), "||")

	p1 = 1
	Do While True
		p1 = InStr(p1, body, "http://")
		If p1 = 0 Then
			Exit Do
		End If

		p2 = SearchUrlEnd(p1, body)
		disp_url = Mid(body, p1, p2 - p1)

		p3 = InStr(disp_url, "/ct/c.asp?")
		If p3 = 0 Then
			jump_url = disp_url
		Else
			jump_url = ""
			p4 = InStr(disp_url, "-%CLICK%")
			If p4 > 0 Then
				ct_id = Mid(disp_url, p3 + 10, p4 - p3 - 10)
				For i = 0 To Ubound(arry_ct_id)
					If arry_ct_id(i) = ct_id Then
						jump_url = arry_jump_url(i)
						Exit For
					End If
				Next
				If jump_url = "" Then
					jump_url = disp_url
				End If
			End If
		End If

		If jump_url <> "" Then
			click_url = "<a href=""" & jump_url & """ target=""_blank"">" & disp_url & "</a>"
			body = Left(body, p1 - 1) & click_url & Mid(body, p2)
			p1 = p1 + Len(click_url)
		Else
			p1 = p1 + Len(disp_url)
		End If
	Loop
		
End Sub

'=== URL�̏I���ʒu���� ===
Private Function SearchUrlEnd(pos, body)
	SearchUrlEnd = FindStr(pos, body, " �@""'>" & vbCrLf)
	If SearchUrlEnd = 0 Then
		SearchUrlEnd = Len(body) + 1
	End If
End Function

'=== �������� ===
Private Function FindStr(pos, str, find)
	Dim i, sz

	FindStr = 0
	sz = Len(str)
	For i = pos To sz
		If InStr(find, Mid(str, i, 1)) > 0 Then
			FindStr = i
			Exit For
		End If
	Next
End Function

'=== HTML�摜�\������ ===
Private Sub HtmlImage(body)
	Dim sql, ds, site_url

	site_url = GetControlInfo("CT_site_url")

	sql = "SELECT AT_file_name,AT_content_type FROM T_ATTACHMENT,T_JOB" & vbCrLf & _
				" WHERE JB_job_id=" & job_id & " AND AT_mail_id=JB_mail_id AND AT_content_type IS NOT NULL"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		body = Replace(body, ds("AT_file_name").Value, site_url & "pict.asp?cid=" & ds("AT_content_type").Value)
		ds.MoveNext
	Loop
End Sub

'=== ���C������ ===
SetHeaderTitle "���[���R���e���c�\��"

Dim job_id, sql, ds, subject, body, header, impression, footer, mail_format

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

sql = "SELECT SM_subject,SM_body,SM_header,SM_impression,SM_footer,SM_mail_format" & vbCrLf & _
			"FROM T_JOB,T_SEND_MAIL" & vbCrLf & _
			"WHERE JB_job_id=" & job_id & " AND SM_mail_id=JB_mail_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
	subject = ds("SM_subject").Value
	body = ReadCLOB(ds("SM_body").Value)
	header = ds("SM_header").Value
	impression =ds("SM_impression").Value
	footer = ds("SM_footer").Value
	mail_format = ds("SM_mail_format").Value

	'�w�b�_�t��
	If Len(header) > 0 Then
		body = LaxxxrLf(header) & vbCrLf & body
	End If

	'���z���t��
	If Len(impression) > 0 Then
		body = body & vbCrLf & LaxxxrLf(impression)
	End If

	'�t�b�^�t��
	If Len("footer") > 0 Then
		body = body & vbCrLf & LaxxxrLf(footer)
	End If
End If

If mail_format = "0" Then
	'�N���b�J�u��URL�Ή�
	Clickable body
Else
	'HTML�摜�\������
	HtmlImage body
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../../haimail.css">
</head>
<body>

<center>
<table border=0 cellspacing=1 cellpadding=0>
	<tr>
		<td class="m0">��<%=subject%></td>
	</tr>
	<tr>
		<td colspan=2>
			<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
				<tr>
					<td>
<%
If mail_format = "0" Then
	Writeln	"<pre class='np' style='margin:0'>" & body & "</pre>"
Else
	Writeln	body
End If
%>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</center>

<center>
<input type="button" value=" ���� " onclick="window.close()">
</center>

</body>
</html>
