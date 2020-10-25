<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:発行者マスタＤＢ更新処理
'******************************************************

SetHeaderTitle "発行者マスタ更新", TC_MASTER

Dim DB, RS, SQL, mel_id

mel_id = Request.Form("mel_id")
If Not IsEmpty(mel_id) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  SQL = "SELECT * FROM T_MELMEL WHERE MM_mel_id='" & mel_id & "'"
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
  If Not RS.EOF Then
    RS("MM_mail_adr") = Request.Form("mail_adr")
    RS("MM_h_name") = Request.Form("h_name")
    RS("MM_c_name") = Request.Form("c_name")
    RS("MM_password") = Request.Form("password")
    RS("MM_url1") = Request.Form("url1")
    RS("MM_url2") = Request.Form("url2")
    RS("MM_url3") = Request.Form("url3")
    RS("MM_bk_acc") = Request.Form("bk_acc")
    RS("MM_bk_nm") = Request.Form("bk_nm")
    RS("MM_bk_st") = Request.Form("bk_st")
    RS("MM_bk_knd") = Request.Form("bk_knd")
    RS("MM_bk_knm") = Request.Form("bk_knm")
    RS("MM_last_update") = Now()
    RS("MM_status") = Request.Form("status")
    RS("MM_point") = Request.Form("point")
    RS.Update
  End If
  RS.Close
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

<center>
<p>更新しました。</p>
<input type="button" value=" ＯＫ " onClick="location.href='mel_master.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
<%
Else
  Response.Redirect("index.asp")
End If
%>