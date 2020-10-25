<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:ポイント管理入力画面
'******************************************************

SetHeaderTitle "ポイント入力", TC_HAKKOUSHA

Dim mel_id

mel_id = Request.Form("mel_id")
If IsEmpty(mel_id) Then
  Response.Redirect("index.asp")
End If
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
  if (document.form1.point.value == "") {
    alert("ポイント数を入力してください。");
    document.form1.point.focus();
    return false;
  } else if (isNaN(Number(document.form1.point.value))) {
    alert("正しいポイント数を入力してください。");
    document.form1.point.focus();
    return false;
  }
  if (document.form1.comment.value == "") {
    alert("コメントを入力してください。");
    document.form1.comment.focus();
    return false;
  }
  return confirm("登録します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT MM_h_name,MM_point FROM T_MELMEL WHERE MM_mel_id='" & mel_id & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
%>
<center>
<form method="post" action="point3.asp" name="form1" onSubmit="return OnSubmit_form1();">
  <input type="hidden" name="mel_id" value="<%=mel_id%>">
  <table border=0 cellspacing=0 cellpadding=0 width='700'>
    <tr>
      <td>
        <table border=0 cellspacing=2 cellpadding=3 width="100%">
          <tr>
            <td class="m1" width="22%">発行者</td>
            <td class="n1"><%=mel_id%>&nbsp;<%=ReplaceTag(RS("MM_h_name"))%></td>
          </tr>
          <tr>
            <td class="m1" width="22%">現ポイント数</td>
            <td class="n1"><%=FormatNumber(NVL(RS("MM_point"), 0), 1, -1, 0, 0)%></td>
          </tr>
          <tr>
            <td class="m1">ポイント数</td>
            <td class="n1"><input type="text" name="point">&nbsp;<font size="-1">（ポイントを引く場合はマイナスの数値を入力してください）</font></td>
          </tr>
          <tr>
            <td class="m1">コメント</td>
            <td class="n1"><input type="text" name="comment" size=80></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <br>
  <input type="submit" value=" ＯＫ ">
  <input type="button" value=" 戻る " onClick="history.back()">

</form>
</center>

<%
Else
%>
<center>
  <p>指定した発行者IDは登録されていません。</p>
  <input type="button" value=" 戻る " onClick="history.back()">
</center>
<%
End If
RS.Close
%>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
