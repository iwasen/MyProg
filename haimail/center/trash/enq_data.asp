<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:全配信アンケートデータ解析画面
'******************************************************

'=== メイン処理 ===
Dim job_id

SetHeaderTitle "全配信 アンケートデータ解析"

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
      <td class="m0">■出力情報選択</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m3" width="20%">ＣＳＶ</td>
            <td class="n3">
							<input type="button" name="send_csv" value="送信データ">
							<input type="button" name="recv_csv" value="受信データ">
						</td>
          </tr>
          <tr>
            <td class="m3">グラフ</td>
            <td class="n3">
							<input type="button" name="send_graph" value="送信者構成">
							<input type="button" name="recv_graph" value="受信者構成">
						</td>
          </tr>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="button" value=" 戻る " onclick="location.href='index.asp'">

</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
