<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:��ƒS���ҏ��\������
'******************************************************

SetHeaderTitle "��ƒS���ҏ��"

Dim sql, ds, tantousha_id

tantousha_id = Request.QueryString("id")
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
sql = "SELECT * FROM T_TANTOUSHA,T_COMPANY WHERE TS_tantousha_id='" & tantousha_id & "' AND TS_company_id=CM_company_id"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If Not ds.EOF Then
%>
	<table border=0 cellspacing=0 cellpadding=0 width='500'>
		<tr>
			<td class="m0">����Ə��</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m2" width="30%">��Ƃh�c</td>
						<td class="n2"><%=ds("CM_company_id").Value%></td>
					</tr>
					<tr>
						<td class="m2" width="30%">��Ɩ�</td>
						<td class="n2"><%=ds("CM_name").Value%></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><br></td>
		</tr>
		<tr>
			<td class="m0">���S���ҏ��</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m2" width="30%">�S���҂h�c</td>
						<td class="n2"><%=ds("TS_tantousha_id").Value%></td>
					</tr>
					<tr>
						<td class="m2" width="30%">�S���Җ�</td>
						<td class="n2"><%=ReplaceTag(ds("TS_name1").Value & " " & ds("TS_name2").Value)%></td>
					</tr>
					<tr>
						<td class="m2" width="30%">e-mail�A�h���X</td>
						<td class="n2"><%=ds("TS_mail_adr").Value%></td>
					</tr>
					<tr>
						<td class="m2" width="30%">�d�b�ԍ�</td>
						<td class="n2"><%=ds("TS_tel_no").Value%></td>
					</tr>
					<tr>
						<td class="m2" width="30%">FAX�ԍ�</td>
						<td class="n2"><%=ds("TS_fax_no").Value%></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<%
End If
%>
	<br>
	<input type="button" value="�@����@" onclick="window.close()">
</center>
</body>
</html>
