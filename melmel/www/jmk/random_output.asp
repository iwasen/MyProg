<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:メルマガランダムアウトプット部数入力
'******************************************************

SetHeaderTitle "ランダムアウトプット", TC_HAKKOUSHA
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
  with (document.form1) {
    if (busuu.value == "" || isNaN(Number(busuu.value)) || Number(busuu.value) <= 0) {
      alert("印刷する部数を入力してください。");
      busuu.focus();
      return false;
    }
    if (isNaN(Date.parse(start_date.value))) {
      alert("正しい開始日付を入力してください。");
      start_date.focus();
      return false;
    }
    if (isNaN(Date.parse(end_date.value))) {
      alert("正しい終了日付を入力してください。");
      end_date.focus();
      return false;
    }
  }
  return true;
}
function OnClick_saihyouji() {
  document.form1.action = "random_output.asp";
  document.form1.submit();
}
//-->
</SCRIPT>
</head>
<body onLoad="document.form1.busuu.focus();document.form1.busuu.select()">

<!--#INCLUDE FILE="admin_header.asp"-->

<%
Dim DB, RS, SQL, count, busuu, start_date, end_date

start_date = Request.Form("start_date")
If IsEmpty(start_date) Or Not IsDate(start_date) Then
  start_date = DateAdd("m", -1, Date)
End If

end_date = Request.Form("end_date")
If IsEmpty(end_date) Or Not IsDate(end_date) Then
  end_date = Date
End If

busuu = Request.Form("busuu")
If IsEmpty(busuu) Then
  busuu = 100
End If

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = "SELECT COUNT(*)" & vbCrLf & _
      "FROM T_HMAILLOG" & vbCrLf & _
      "WHERE HM_print_flag=0 AND HM_date BETWEEN '" & start_date & "' AND '" & end_date & "'"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
  count = RS(0)
Else
  count = 0
End If
RS.Close

If count < 100 Then
  busuu = count
End If
%>

<form action="random_output2.asp" method="post" name="form1" onSubmit="return OnSubmit_form1();">

<center>
  <table border=1 cellpadding=3>
    <tr>
      <td bgcolor='#FFE0E0'>印刷可能部数</td>
      <td bgcolor='#E0FFE0'>
        <table border=0 cellspacing=0 cellpadding=0 width="100%">
          <tr>
            <td><%=count%></td>
            <td bgcolor='#E0FFE0' align="right"><input type="button" value="再表示" onClick="OnClick_saihyouji()"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td bgcolor='#FFE0E0'>印刷する部数</td>
      <td bgcolor='#E0FFE0'><input type="text" name="busuu" value=<%=busuu%>></td>
    </tr>
    <tr>
      <td bgcolor='#FFE0E0'>受信日付範囲</td>
      <td bgcolor='#E0FFE0'><input type="text" name="start_date" value=<%=DateFormat(start_date)%>> ～
      <input type="text" name="end_date" value=<%=DateFormat(end_date)%>></td>
    </tr>
  </table>
  <br>
  <input type="submit" value=" 印刷 ">
</center>

</form>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
