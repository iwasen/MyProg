<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:本登録解除メルマガの確認画面
'******************************************************

SetHeaderTitle "本登録解除確認", TC_HAKKOUSHA
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
Dim DB, RS, SQL, mag_id

mag_id = Request.Form("mag_id")
If IsEmpty(mag_id) Then
  Response.Redirect("index.asp")
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT * FROM V_MELMAG WHERE MG_mag_id='" & mag_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

If Not RS.EOF Then
%>
<center>
<form method="post" action="cancel3.asp" name="form1">
  <input type="hidden" name="mag_id" value="<%=RS("MG_mag_id")%>">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>■このメルマガの本登録を解除します。</td>
    </tr>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width='100%'>
          <tr>
            <td class="m1" width="23%">メルマガID</td>
            <td class="n1"><%=RS("MG_mag_id")%></td>
          </tr>
          <tr>
            <td class="m1">メルマガ名</td>
            <td class="n1"><%=ReplaceTag(RS("MG_m_name"))%></td>
          </tr>
          <tr>
            <td class="m1">発行者ID</td>
            <td class="n1"><%=RS("MM_mel_id")%></td>
          </tr>
          <tr>
            <td class="m1">発行者名</td>
            <td class="n1"><%=RS("MM_h_name")%></td>
          </tr>
          <tr>
            <td class="m1">e-mailアドレス</td>
            <td class="n1"><%=RS("MM_mail_adr")%></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <center>
  <input type="submit" value=" 実行 ">
  <input type="button" value=" ｷｬﾝｾﾙ " onClick="location.href='cancel.asp'">
  </center>
</form>
</center>

<%
Else
%>
<center>
<p>指定したメルマガIDは登録されていません。</p>
<input type="button" value=" 戻る " onClick="location.href='cancel.asp'">
</center>
<%
End If

RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
