<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:��ƒS���ҏ��X�V����
'******************************************************

'=== ���C������ ===
SetHeaderTitle "��ƒS���ҏ��X�V"

Dim ds, sql, tantousha_id, id_flag

tantousha_id = Request.Form("tantousha_id")
id_flag = Request.Form("id_flag")
If IsEmpty(tantousha_id) Then
	SystemError "empty tantousha_id"
End If

sql = "SELECT * FROM T_TANTOUSHA WHERE TS_tantousha_id='" & tantousha_id & "'"
Set ds = CreateDynaset(sql, ORADYN_DEFAULT)

If ds.EOF Then
	ds.Addnew
	ds("TS_tantousha_id").Value = Request.Form("tantousha_id")
	ds("TS_company_id").Value = Request.Form("company_id")
	ds("TS_regist_date").Value = Now
Else
	If id_flag = 1 Then
		Response.Redirect "tantou_id_error.asp"
	End If
		ds.Edit
End If
ds("TS_mail_adr").Value = Request.Form("mail_adr")
ds("TS_password").Value = Request.Form("password")
ds("TS_name1").Value = Request.Form("name1")
ds("TS_name2").Value = Request.Form("name2")
ds("TS_name1_kana").Value = Request.Form("name1_kana")
ds("TS_name2_kana").Value = Request.Form("name2_kana")
ds("TS_tel_no").Value = Request.Form("tel_no")
ds("TS_fax_no").Value = Request.Form("fax_no")
ds("TS_update_date").Value = Now
ds.Update
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<p class="np">���M��ƒS���ҏ����X�V���܂����B</p>
<input type="button" value=" �n�j " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
