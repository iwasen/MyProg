<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:��ƒS���ҏ��폜����
'******************************************************

'=== ���C������ ===
SetHeaderTitle "��ƒS���ҏ��폜"

Dim sql, tantousha_id

tantousha_id = Request.QueryString("id")
If IsEmpty(tantousha_id) Then
	SystemError "empty tantousha_id"
End If

sql = "DELETE T_TANTOUSHA WHERE TS_tantousha_id='" & tantousha_id & "'"
ExecuteSQL(sql)
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
<p class="np">�S���ҏ����폜���܂����B<br>
		�V�K�œo�^�����ۂɂ́A�u���/�S���ғo�^�v���j���[TOP�y�[�W�ŁA���M��Ɩ����N���b�N���Ă��������B</p>
<input type="button" value=" �n�j " onclick="location.href='index.asp'">
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
