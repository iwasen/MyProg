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

SetHeaderTitle "�S�z�M ���z����M�f�[�^CSV�o��"

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
<form method="post" name="form1" action="imp_csv_recv2.asp">
	<input type="hidden" name="job_id" value="<%=job_id%>">
  <table border=0 cellspacing=0 cellpadding=0 width='80%'>
    <tr>
      <td class="m0">���o�͏��I��</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="20%">�o�͍���</td>
            <td class="n3">
							<input type="checkbox" name="csv_seq" value="1" checked>SEQ<br>
							<input type="checkbox" name="csv_member_id" value="1" checked>�͂��߁[��ID<br>
							<input type="checkbox" name="csv_seibetsu" value="1" checked>����<br>
							<input type="checkbox" name="csv_nenrei" value="1" checked>�N��<br>
							<input type="checkbox" name="csv_nendai" value="1" checked>�N��<br>
							<input type="checkbox" name="csv_shokugyou" value="1" checked>�E��<br>
							<input type="checkbox" name="csv_mikikon" value="1" checked>������<br>
							<input type="checkbox" name="csv_adr_kyojuu" value="1" checked>�s���{���i�Z���j<br>
							<input type="checkbox" name="csv_adr_kinmu" value="1" checked>�s���{���i�Ζ��n�j<br>
							<input type="checkbox" name="csv_zip_kyojuu" value="1" checked>�X�֔ԍ��i�Z���j<br>
							<input type="checkbox" name="csv_zip_kinmu" value="1" checked>�X�֔ԍ��i�Ζ��n�j<br>
							<input type="checkbox" name="csv_ans_date" value="1" checked>�񓚓�<br>
							<input type="checkbox" name="csv_ans_data" value="1" checked>�񓚓��e<br>
							<input type="checkbox" name="csv_kaiseki" value="1" checked>��͌���<br>
						</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" �o�� ">
  <input type="button" value=" �߂� " onclick="location.href='imp_data.asp?job_id=<%=job_id%>'">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
