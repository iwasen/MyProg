<html>

<%
Dim sKomokuNo(10)
Dim sKomokuName(10)
Dim nItem
Dim nPageSize, nPageCount
Dim sDaibunrui
Dim conDB
Dim rsGazou
%>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>�������ʈꗗ</title>



<meta name="Microsoft Theme" content="mstheme22243 011, default"><meta name="Microsoft Border" content="t, default"></head>
<%Initialize%>
<body background="_themes/mstheme22243/sumtextb.jpg" bgcolor="#FFFFFF" text="#000066" link="#3333CC" vlink="#3333CC" alink="#990099"><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><!--mstheme--><font face="�l�r �o�S�V�b�N">

<p align="left"><font size="6"><strong><img src="_derived/ichiran.asp_cmp_mstheme22243010_bnr.gif" width="600" height="60" border="0" alt="�������ʈꗗ"></strong></font></p>

<!--mstheme--></font></td></tr><!--msnavigation--></table><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><!--msnavigation--><td valign="top"><!--mstheme--><font face="�l�r �o�S�V�b�N"><!--mstheme--></font><table border="1" align="right" bordercolordark="#003366" bordercolorlight="#C0C0C0">
<%OutputPage%>
</table><!--mstheme--><font face="�l�r �o�S�V�b�N">
<form action="ichiran.asp" method="POST">
<%SetPageButton%>
</form>

<!--mstheme--></font><table border="1" width="100%" bordercolordark="#003366" bordercolorlight="#C0C0C0">
<%OutputData%>
</table><!--mstheme--><font face="�l�r �o�S�V�b�N">
<%
	If rsGazou.PageCount = 0 Then
		Response.Write "<br><a href=""JavaScript:history.back()"">�Y������f�[�^�͂P��������܂���B</a>"
	End If
%>
<!--msthemeseparator--><p align="left"><img src="_themes/mstheme22243/sumhorsa.gif" width="600" height="10"></p>
<a href="default.asp"><img border="0" src="images/b11_036.gif" WIDTH="90" HEIGHT="22"></a>
<!--mstheme--></font><!--msnavigation--></td></tr><!--msnavigation--></table></body>

<%
Private Sub OutputPage()
	Response.Write "<tr>" & vbcrlf
	Response.Write "<td width=60 align=""right"">" & rsGazou.RecordCount & "��</td>" & vbvrlf
	Response.Write "<td width=100 align=""right"">" & nPageCount & "/" & rsGazou.PageCount & "�߰��</td>" & vbcrlf
	Response.Write "</tr>" & vbcrlf
End Sub

Private Sub Initialize()

	GetParameter

	GetItemInfo

	if Not IsObject(rsGazou) Then
		CreateIchiranRS
	End If

End Sub

Private Sub GetParameter()
	nPageSize = 10

	'��i�c�a�I�u�W�F�N�g�擾
	Set conDB = Session("xxxNDB")

	Select Case Request("PageMove")
	Case "�O�y�[�W"
		Set rsGazou = Session("IchiranRS")
		nPageCount = Session("PageCount") - 1
		sDaibunrui = Session("Daibunrui")
	Case "���y�[�W"
		Set rsGazou = Session("IchiranRS")
		nPageCount = Session("PageCount") + 1
		sDaibunrui = Session("Daibunrui")
	Case Else
		nPageCount = 1
		sDaibunrui = Request("Daibunrui")
		Session("Daibunrui") = sDaibunrui
	End Select

	Session("PageCount") = nPageCount

End Sub

Private Sub GetItemInfo()
	'�R���g���[���e�[�u������ꗗ�\�����e���擾SQL
	Sql = "SELECT ���ڔԍ�,���ږ���,�ꗗ�\���t���O FROM M_CONTROL " & _
		"WHERE �啪�ރR�[�h='" & sDaibunrui & "' " & _
		"ORDER BY ���ڔԍ�"
	Set rsControl = conDB.Execute(Sql)

	'�R���g���[���e�[�u������ꗗ�\�����e���擾
	nItem = 0
	Do While (Not rsControl.Eof)
		If rsControl.Fields("�ꗗ�\���t���O") = True Then
			nItem = nItem + 1
			sKomokuNo(nItem) = rsControl.Fields("���ڔԍ�")
			sKomokuName(nItem) = rsControl.Fields("���ږ���")
		End If
		rsControl.MoveNext
	Loop
	rsControl.Close
End Sub

Private Sub CreateIchiranRS()
	'SQL���o���ڕҏW
	SqlSelect = "�摜�t�H���_ID,�摜ID"
	For i = 1 To nItem
		SqlSelect = SqlSelect + ",����" & sKomokuNo(i)
	Next

	'SQL���������ҏW
	SqlWhere = "�啪�ރR�[�h='" & Request("Daibunrui") & "'"
	If Request("Chubunrui") <> "" then
		SqlWhere = SqlWhere & " AND �����ރR�[�h='" & Request("Chubunrui") & "'"
	End If
	If Request("Shobunrui") <> "" then
		SqlWhere = SqlWhere & " AND ������='" & Request("Shobunrui") & "'"
	End If
	For i = 1 To 10
		sZokusei = "Zokusei" & i
		If Request(sZokusei) <> "" Then
			sField = "����" & Right("0" & i, 2)
			SqlWhere = SqlWhere & " AND " & sField & "='" & Request(sZokusei) & "'"
		End If
	Next
	If Request("Zokusei2F") <> "" Then
		'SqlWhere = SqlWhere & " AND " & "CDate(����02)>=CDate('" & Request("Zokusei2F") & "')"
		SqlWhere = SqlWhere & " AND " & "����02>='" & Request("Zokusei2F") & "'"
	End If
	If Request("Zokusei2T") <> "" Then
		'SqlWhere = SqlWhere & " AND " & "CDate(����02)<=CDate('" & Request("Zokusei2T") & "')"
		SqlWhere = SqlWhere & " AND " & "����02<='" & Request("Zokusei2T") & "'"
	End If

	'SQL�I�[�_�ҏW
	SqlOrder = "����01,�摜�t�H���_ID,�摜ID"

	'SQL���ҏW�����s
	Sql = "SELECT " & SqlSelect & " FROM F_GAZOU WHERE " & SqlWhere & " ORDER BY " & SqlOrder
	Set rsGazou = CreateObject("ADODB.Recordset")
	rsGazou.CursorLocation = 3'adUseClient
	rsGazou.PageSize = nPageSize
	rsGazou.Open Sql, conDB, adOpenKeyset
	Set Session("IchiranRS") = rsGazou
End Sub

Private Sub OutputData()
	On Error Resume Next
	
	'�\�̃w�b�_�i���o���j���o��
	Response.Write "<tr>" & vbcrlf
	For i = 1 To nItem
		Response.Write "<th>" & sKomokuName(i) & "</th>" & vbcrlf
	Next
	Response.Write "</tr>" & vbcrlf

	rsGazou.AbsolutePage = nPageCount

	'�������ʏo��
	For j = 1 To nPageSize
		If rsGazou.Eof Then
			Exit For
		End If

		Response.Write "<tr>" & vbcrlf
		For i = 1 To nItem
			Response.Write "<td>"
			If i = 1 Then
				Response.Write "<a href=shosai.asp?folder=" & rsGazou.Fields("�摜�t�H���_ID")& "&id=" & rsGazou.Fields("�摜ID") & ">"
			End If
			sData = rsGazou.Fields("����" & sKomokuNo(i))
			If IsNull(sData) Then
				If i = 1 Then
					sData = "*���ݒ�*"
				Else
					sData = "�@"
				End If
			End If
			Response.Write sData & "</td>" & vbcrlf
		Next
		Response.Write "</tr>" & vbcrlf
		rsGazou.MoveNext
	Next

End Sub

Private Sub SetPageButton()
	If nPageCount < rsGazou.PageCount Then
		sDisabled = ""
	Else
		sDisabled = " disabled"
	End If
	Response.Write "<input type=""submit"" value=""���y�[�W"" name=""PageMove""" & sDisabled & ">" & vbcrlf

	If nPageCount > 1 Then
		sDisabled = ""
	Else
		sDisabled = " disabled"
	End If
	Response.Write "<input type=""submit"" value=""�O�y�[�W"" name=""PageMove""" & sDisabled & ">" & vbcrlf

	Response.Write "<input type=""submit"" value=""�T���l�C��"" name=""PageMove"">" & vbcrlf
End Sub

%>

</html>
