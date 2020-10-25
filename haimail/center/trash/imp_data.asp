<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信感想文データ解析画面
'******************************************************

'=== メイン処理 ===
Dim job_id

SetHeaderTitle "全配信 感想文データ解析"

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
      <td bgcolor="#EEEEFF" width="50%">■ＣＳＶ</td>
      <td bgcolor="#FFEEEE" width="50%">■グラフ</td>
    </tr>
    <tr>
      <td><input type="button" name="send_csv" value="送信データ" onclick="location.href='imp_csv_send.asp?job_id=<%=job_id%>'"></td>
			<td><input type="button" name="send_graph" value="送信者構成" onclick="alert('工事中！')"></td>
	  </tr>
    <tr>
			<td><input type="button" name="recv_csv" value="受信データ" onclick="location.href='imp_csv_recv.asp?job_id=<%=job_id%>'"></td>
			<td><input type="button" name="recv_graph" value="受信者構成" onclick="alert('工事中！')"></td>
    </tr>
  </table>

  <br>
  <input type="button" value=" 戻る " onclick="location.href='index.asp'">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
