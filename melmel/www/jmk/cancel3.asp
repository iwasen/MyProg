<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="kaiyaku_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:�{�o�^��������
'******************************************************

SetHeaderTitle "�{�o�^��������", TC_HAKKOUSHA
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim mag_id, msg

'�p�����[���擾
mag_id = Request.Form("mag_id")
If Not IsEmpty(mag_id) Then
  '��񏈗�
  If Kaiyaku(mag_id) Then
    msg = "�w�肵�������}�K�̓o�^���������܂����B"
  Else
    msg = "�����ʒm���[���̑��M�Ɏ��s���܂����B"
  End If
End If
%>

<center>
<p><%=msg%></p>
<input type="button" value=" OK " onClick="location.href='cancel.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
