<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�S�z�M�A���P�[�g�f�[�^��͉��
'******************************************************

'=== ���C������ ===
Dim job_id

SetHeaderTitle "�S�z�M �A���P�[�g�f�[�^���"

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
  <table border=0 cellspacing=0 cellpadding=0 width='80%'>
    <tr>
      <td class="m0">���o�͏��I��</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="20%">�b�r�u</td>
            <td class="n3">
							<input type="button" name="send_csv" value="���M�f�[�^">
							<input type="button" name="recv_csv" value="��M�f�[�^">
						</td>
          </tr>
          <tr>
            <td class="m3">�O���t</td>
            <td class="n3">
							<input type="button" name="send_graph" value="���M�ҍ\��">
							<input type="button" name="recv_graph" value="��M�ҍ\��">
						</td>
          </tr>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="button" value=" �߂� " onclick="location.href='index.asp'">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
