<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :Ν’ί[ιEnet±Ηpy[W
' Content:SzM΄zΆf[^πΝζΚ
'******************************************************

'=== C ===
Dim job_id

SetHeaderTitle "SzM ΄zΆMf[^CSVoΝ"

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
<form method="post" name="form1" action="imp_csv_send2.asp">
	<input type="hidden" name="job_id" value="<%=job_id%>">
  <table border=0 cellspacing=0 cellpadding=0 width='80%'>
    <tr>
      <td class="m0">‘oΝξρIπ</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="20%">oΝΪ</td>
            <td class="n3">
							<input type="checkbox" name="csv_seq" value="1" checked>SEQ<br>
							<input type="checkbox" name="csv_member_id" value="1" checked>Ν’ί[ιID<br>
							<input type="checkbox" name="csv_seibetsu" value="1" checked>«Κ<br>
							<input type="checkbox" name="csv_nenrei" value="1" checked>Nξ<br>
							<input type="checkbox" name="csv_nendai" value="1" checked>Nγ<br>
							<input type="checkbox" name="csv_shokugyou" value="1" checked>EΖ<br>
							<input type="checkbox" name="csv_mikikon" value="1" checked>’ω₯<br>
							<input type="checkbox" name="csv_adr_kyojuu" value="1" checked>sΉ{§iZj<br>
							<input type="checkbox" name="csv_adr_kinmu" value="1" checked>sΉ{§iΞ±nj<br>
							<input type="checkbox" name="csv_zip_kyojuu" value="1" checked>XΦΤiZj<br>
							<input type="checkbox" name="csv_zip_kinmu" value="1" checked>XΦΤiΞ±nj<br>
						</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" oΝ ">
  <input type="button" value=" ίι " onclick="location.href='imp_data.asp?job_id=<%=job_id%>'">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
