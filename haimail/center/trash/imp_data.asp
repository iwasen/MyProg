<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�S�z�M���z���f�[�^��͉��
'******************************************************

'=== ���C������ ===
Dim job_id

SetHeaderTitle "�S�z�M ���z���f�[�^���"

job_id = Request.QueryString("job_id")
If IsEmpty(job_id) Then
  SystemError "job_id is empty"
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1">
  <table border=0 cellspacing=3 cellpadding=0 width='30%'>
    <tr>
      <td bgcolor="#EEEEFF" width="50%">���b�r�u</td>
      <td bgcolor="#FFEEEE" width="50%">���O���t</td>
    </tr>
    <tr>
      <td><input type="button" name="send_csv" value="���M�f�[�^" onclick="location.href='imp_csv_send.asp?job_id=<%=job_id%>'"></td>
			<td><input type="button" name="send_graph" value="���M�ҍ\��" onclick="alert('�H�����I')"></td>
	  </tr>
    <tr>
			<td><input type="button" name="recv_csv" value="��M�f�[�^" onclick="location.href='imp_csv_recv.asp?job_id=<%=job_id%>'"></td>
			<td><input type="button" name="recv_graph" value="��M�ҍ\��" onclick="alert('�H�����I')"></td>
    </tr>
  </table>

  <br>
  <input type="button" value=" �߂� " onclick="location.href='index.asp'">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
