<!--#INCLUDE FILE="../../inc/common.asp"-->
<!--#INCLUDE FILE="../../inc/sub.asp"-->
<!--#INCLUDE FILE="../../inc/database.asp"-->
<!--#INCLUDE FILE="../../inc/login.asp"-->
<!--#INCLUDE FILE="../../inc/search.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����ǁE�c�Ƌ��ʃy�[�W
' Content:�l��������ʏ���
'******************************************************

'=== �����`�F�b�N�{�b�N�X�ꊇ�擾�[ ===
Private Function GetMultiFlag(name, n)
	Redim a(n)
	Dim i, c, data

	For i = 0 To n - 1
		a(i) = "0"
	Next

	Set data = Request.Form(name)
	If Not IsEmpty(data.Item) Then
		For Each c In data
			a(c - 1) = "1"
		Next
	End If

	GetMultiFlag = Join(a, "")
End Function

'=== ���C������ ===
SetHeaderTitle "�l������"

Dim search, sql, ds, find, all, condition, job_id, teikei_qno, teikei_andor, teikei_ans, ct_id

job_id = Request.Form("job_id")
If IsEmpty(job_id) Then
	SystemError "job_id is empty"
End If

If CheckPermission(PM_REGIST_JOB_A) Then
	sql = "SELECT COUNT(*) FROM T_MEMBER WHERE MM_status='0'"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If ds.EOF Then
		all = 0
	Else
		all = ds(0).Value
	End If
End If

Set search = New CSearch
search.GetData
condition = search.GetConditionText

teikei_qno = Request.Form("teikei_qno")
teikei_andor = Request.Form("teikei_andor")
teikei_ans = GetMultiFlag("teikei_ans", 15)
ct_id = JoinItem(Request.Form("ct_id"))

'�C���v���X�𔭐M�ΏۊO�Ƃ���i�Q�����܂ł̌��菈�u�j====
'If system_id = "sales" Then
'	If search.jogai_baitai <> "" Then
'		search.jogai_baitai = search.jogai_baitai & ","
'	End If
'	search.jogai_baitai = search.jogai_baitai & "30"
'End If
'====================================================

sql = "SELECT COUNT(*) FROM T_MEMBER WHERE " & search.MakeSQL2(job_id, teikei_qno, teikei_andor, teikei_ans, ct_id)
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	find = 0
Else
	find = ds(0).Value
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

<!--#INCLUDE FILE="../../inc/header.asp"-->

<center>
<table border=0 cellspacing=0 cellpadding=0 width='700'>
	<tr>
		<td class="m0">����������</td>
	</tr>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr> 
            <td class="m4" width="25%">���������</td>
            <td class="n4"><%=NumFormat(find)%></td>
          </tr>
          <%If CheckPermission(PM_REGIST_JOB_A) Then%>
          <tr> 
            <td class="m4">�S�L�������</td>
            <td class="n4"><%=NumFormat(all)%></td>
          </tr>
          <tr> 
            <td class="m4">�������ꂽ����</td>
            <td class="n4"><%=FormatNumber(CDbl(find) / all * 100, 1, -1, 0, 0)%>%</td>
          </tr>
          <%End If%>
          <tr> 
            <td class="m4">��������</td>
            <td class="n4"><font size="-1"><%=Replace(condition, vbCrLF, "<br>")%></font></td>
          </tr>
          </table>
		</td>
	</tr>
</table>

<p>
<input type="button" value="�@����@" onclick="window.close()">
</p>
</center>

<!--#INCLUDE FILE="../../inc/footer.asp"-->

</body>
</html>
