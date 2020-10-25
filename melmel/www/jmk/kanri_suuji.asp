<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:最新管理数字表示画面
'******************************************************

SetHeaderTitle "最新管理数字表示", TC_OTHER
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
Dim DB, RS, SQL, line
Dim h_cnt, h_busuu, h_wbusuu, k_cnt, hakkousha, point

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SQL = "SELECT COUNT(*) AS cnt,SUM(MG_new_busuu) AS busuu,SUM(MG_new_busuu * MG_shuu_keisuu) AS wbusuu" & vbCrLf & _
      "FROM T_MELMAG INNER JOIN T_MELMEL ON MG_mel_id=MM_mel_id" & vbCrLf & _
      "WHERE MM_status=0 AND MG_status=0"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
  h_cnt = RS("cnt")
  h_busuu = RS("busuu")
  h_wbusuu = Int(RS("wbusuu"))
End If
RS.Close

SQL = "SELECT MAX(PR_seq_no) AS cnt FROM T_PROVREG"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
  k_cnt = RS("cnt")
End If
RS.Close

SQL = "SELECT COUNT(*) AS cnt,SUM(MM_point) AS point FROM T_MELMEL WHERE MM_status=0"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
If Not RS.EOF Then
  hakkousha = RS("cnt")
  point = RS("point")
End If
RS.Close

%>

<center>
<table border=0 cellspacing=0 cellpadding=0 width='700'>
  <tr>
    <td>■<%=TimeFormat(Now)%> 現在</td>
  </tr>
  <tr>
    <td>
      <table border=0 cellspacing=2 cellpadding=3 width='100%'>
        <tr>
          <td class="m4" width="28%">仮登録メルマガ数</td>
          <td class="n4"><%=NumFormat(k_cnt)%> 誌</td>
        </tr>
        <tr>
          <td class="m4">本登録メルマガ数</td>
          <td class="n4"><%=NumFormat(h_cnt)%> 誌</td>
        </tr>
        <tr>
          <td class="m4">総発行部数</td>
          <td class="n4"><%=NumFormat(h_busuu)%> 部</td>
        </tr>
        <tr>
          <td class="m4">１週間あたり発行部数</td>
          <td class="n4"><%=NumFormat(h_wbusuu)%> 部</td>
        </tr>
        <tr>
          <td class="m4" width="28%">登録発行者数</td>
          <td class="n4"><%=NumFormat(hakkousha)%></td>
        </tr>
        <tr>
          <td class="m4">総発行者ポイント数</td>
          <td class="n4"><%=NumFormat(point)%></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
