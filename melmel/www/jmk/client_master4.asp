<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:クライアントマスタ更新処理
'******************************************************

SetHeaderTitle "クライアントマスタ更新", TC_MASTER

Dim DB, RS, SQL, client_id, msg

client_id = Request.Form("client_id")
If Not IsEmpty(client_id) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  SQL = "SELECT * FROM T_CLIENT WHERE CM_client_id='" & client_id & "'"
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
  If RS.EOF Then
    RS.AddNew
    RS("CM_client_id") = client_id
    RS("CM_regist_date") = Now
  Else
    If Request.Form("new") = "1" Then
      msg = "クライアントID " & client_id & " はすでに登録されています。"
    End If
  End If
  If IsEmpty(msg) Then
    RS("CM_client_name") = Request.Form("client_name")
    RS("CM_company") = Request.Form("company")
    RS("CM_tantousha") = Request.Form("tantousha")
    RS("CM_password") = Request.Form("password")
		RS("CM_mail_adr") = Request.Form("mail_adr")
		RS("CM_tel_no") = Request.Form("tel_no")
    RS("CM_last_update") = Now
    RS.Update
    msg = "更新しました。"
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
<p><%=msg%></p>
<input type="button" value=" ＯＫ " onClick="location.href='client_master.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
<%
Else
  Response.Redirect("client_master.asp")
End If
%>