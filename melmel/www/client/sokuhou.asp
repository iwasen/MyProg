<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
Dim client_id

client_id = Session("client_id")
If IsEmpty(client_id) Then
  Session("client_next") = "sokuhou.asp"
  Response.Redirect "login.asp"
End If
%>
<html>
<head>
<title>めるめる・net 広告主向け掲載速報ページ</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<LINK rel="stylesheet" href="../melmel.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnClick_koukoku_id(id) {
  var win;
  win = window.open("koukoku_info.asp?id=" + id, "koukoku_info", "resizable=yes,width=680,height=780");
  win.focus();
}
//-->
</SCRIPT>
</head>
<body bgcolor="#ff9999" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="">
<TABLE width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <TR>
    <TD colspan="3">
      <TABLE bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <TR> <!-- Shim row, height 1. -->
          <TD><IMG src="../images/shim.gif" width="252" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="348" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="1" border="0"></TD>
        </TR>
        <TR><!-- row 1 -->
          <TD rowspan="3" valign="top"><A href="../index.html"><IMG name="head_logo" src="../images/head_logo.gif" width="252" height="96" border="0" alt="めるめる・net ロゴ"></A></TD>
          <TD valign="top"><IMG src="../images/shim.gif" width="348" height="26" border="0"></TD>
          <TD valign="top"><IMG src="../images/shim.gif" width="1" height="26" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 2 -->
          <TD><IMG name="head_t1" src="../images/head_t1.gif" width="348" height="52" border="0" alt="Welcome to melmel-net !"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="52" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 3 -->
          <TD width="348" height="18">
            <DIV align="center"><IMG src="images/shim.gif" width="348" height="18"></DIV>
          </TD>
          <TD><IMG src="../images/shim.gif" width="1" height="18" border="0"></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
  <TR bgcolor="#666666">
    <TD valign="top" colspan="3" align="left"><IMG src="../images/shim.gif" width="600" height="7"></TD>
  </TR>
  <TR>
    <TD valign="top" colspan="3" align="left">
      <TABLE width="600" border="0" cellspacing="0" cellpadding="0">
        <TR>
          <TD bgcolor="#FFCCCC" valign="top">
            <TABLE border="0" cellpadding="0" cellspacing="0" width="130">
                <TR> <!-- Shim row, height 1. -->
                  <TD><IMG src="images/shim.gif" width="8" height="1" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="112" height="1" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="10" height="1" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="1" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 1 -->
                  <TD colspan="3"><IMG src="images/shim.gif" width="130" height="6" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="6" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 2 -->
                  <TD colspan="3"><IMG name="menu_4" src="../images/menu_4.gif" width="130" height="20" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="20" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 3 -->
                  <TD colspan="3"><IMG src="images/shim.gif" width="130" height="4" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="4" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 4 -->
                  <TD rowspan="7"><IMG src="images/shim.gif" width="8" height="154" border="0"></TD>

                <TD><IMG name="b_4top" src="images/b_4top2.gif" width="112" height="34" border="0" alt="広告を出したい！TopPage"></TD>
                  <TD rowspan="7"><IMG src="images/shim.gif" width="10" height="154" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="34" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 5 -->
                  <TD><IMG src="images/shim.gif" width="112" height="6" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="6" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 6 -->

                <TD><A href="type.html"><IMG name="b_4type" src="images/b_4type.gif" width="112" height="34" border="0" alt="広告タイプ"></A></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="34" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 7 -->
                  <TD><IMG src="images/shim.gif" width="112" height="6" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="6" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 8 -->

                <TD><A href="nagare.html"><IMG name="b_4nagare" src="images/b_4nagare.gif" width="112" height="34" border="0" alt="広告出稿からご報告までの流れ"></A></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="34" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 9 -->
                  <TD><IMG src="images/shim.gif" width="112" height="6" border="0"></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="6" border="0"></TD>
                </TR>
                <TR valign="top"><!-- row 10 -->

                <TD><A href="kijyun.html"><IMG name="b_4kijyun" src="images/b_4kijyun.gif" width="112" height="34" border="0" alt="登録メールマガジンの審査基準"></A></TD>
                  <TD><IMG src="images/shim.gif" width="1" height="34" border="0"></TD>
                </TR>
              </TABLE>

            <P align="center"><A href="yoyaku.asp">予約</A></P>
            <P align="center"><A href="sokuhou.asp">掲載状況速報</A></P>
            <P align="center"><A href="../index.html"><IMG src="../images/b_rt2.gif" width="112" height="25" alt="めるめる・netトップページに戻る" border="0"></A></P>
            <P align="center">　</P>
            <P align="center"><IMG src="../images/chara03r2.gif" width="63" height="97"></P>
          </TD>

          <TD valign="top" bgcolor="#FFFFFF">
            <TABLE width="470" border="0" cellspacing="5" cellpadding="0">
              <TR>
                <TD>
<center>
  <table width="100%" class="kihon">
    <tr>
      <td align="left">■掲載状況速報 <font size="-2">（広告IDをクリックすると詳細情報、依頼数をクリックすると個別掲載状況を表示します）</font></td>
    </tr>
  </table>

<table border=1 cellpadding=1 cellspacing=1 width="100%" align="center" class="kihon">
  <tr bgcolor="#FFE0E0">
    <th>広告ID</th>
    <th>広告名</th>
    <th>掲載部数</th>
    <th>保証部数</th>
    <th>依頼数</th>
    <th>ｸﾘｯｸ数</th>
  </tr>

<%
Dim DB, RS, SQL, SUB1, SUB2, line, keisai_busuu, hoshou_busuu, color, status

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SUB1 = "SELECT KS_koukoku_id,COUNT(*) AS S1_cnt FROM T_KEISAI GROUP BY KS_koukoku_id"
SUB2 = "SELECT CL_koukoku_id,COUNT(*) AS S2_cnt FROM T_CLICKLOG GROUP BY CL_koukoku_id"

SQL = "SELECT KK_koukoku_id,KK_koukoku_name,keisai_busuu,KK_busuu,S1_cnt,S2_cnt" & vbCrLf & _
      "FROM ((V_KOUKOKU LEFT OUTER JOIN V_KEISAI_BUSUU ON KK_koukoku_id=koukoku_id)" & vbCrLf & _
      " LEFT OUTER JOIN (" & SUB1 & ") AS SUB1 ON KK_koukoku_id=KS_koukoku_id)" & vbCrLf & _
      " LEFT OUTER JOIN (" & SUB2 & ") AS SUB2 ON KK_koukoku_id=CL_koukoku_id" & vbCrLf & _
      "WHERE KK_client_id='" & client_id & "' AND KK_status BETWEEN 1 AND 4" & vbCrLf & _
      "ORDER BY KK_koukoku_id DESC"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

line = 1
Do Until RS.EOF
  If line Mod 2 = 0 Then
    Writeln "<tr bgcolor='#FFFFFF'>"
  Else
    Writeln "<tr bgcolor='#E0FFE0'>"
  End If

  keisai_busuu = NVL(RS("keisai_busuu"), 0)
  hoshou_busuu = RS("KK_busuu")
  If keisai_busuu < hoshou_busuu Then
    color = "red"
  Else
    color = "blue"
  End If
  Writeln "<td align='center'><a href='JavaScript:OnClick_koukoku_id(""" & RS("KK_koukoku_id") & """)'>" & RS("KK_koukoku_id") & "</a></td>"
  Writeln "<td>" & ReplaceTag(RS("KK_koukoku_name")) & "</td>"
  Writeln "<td align='right'><font color='" & color & "'>" & NumFormat(keisai_busuu) & "</font></td>"
  Writeln "<td align='right'>" & NumFormat(hoshou_busuu) & "</td>"
  Writeln "<td align='right'><a href='sokuhou2.asp?id=" & RS("KK_koukoku_id") & "'>" & NumFormat(NVL(RS("S1_cnt"), 0)) & "</a></td>"
  Writeln "<td align='right'>" & NumFormat(NVL(RS("S2_cnt"), 0)) & "</td>"
  Writeln "</tr>"

  RS.MoveNext
  line = line + 1
Loop
RS.close
%>
</table>

</center>
                </TD>
              </TR>
            </TABLE>
          </TD>
          </TR>
        </TABLE>
    </TD>
  </TR>
  <TR bgcolor="#666666">
    <TD valign="top" colspan="3" align="left"><IMG src="../images/shim.gif" width="600" height="7"></TD>
  </TR>
  <TR>
    <TD colspan="3">
      <TABLE bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <TR> <!-- Shim row, height 1. -->
          <TD><IMG src="../images/shim.gif" width="147" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="66" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="28" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="155" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="204" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="1" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 1 -->
          <TD colspan="3"><IMG src="../images/shim.gif" width="241" height="15" border="0"></TD>
          <TD rowspan="2"><IMG name="logo_s" src="../images/logo_s.gif" width="155" height="42" border="0" alt="めるめる・net ロゴ"></TD>
          <TD rowspan="2"><IMG src="../images/shim.gif" width="204" height="42" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="15" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 2 -->
          <TD rowspan="2"><IMG src="../images/shim.gif" width="147" height="49" border="0"></TD>
          <TD rowspan="2"><IMG name="cara_s" src="../images/cara_s.gif" width="66" height="49" border="0" alt="めるめる イメージ"></TD>
          <TD><IMG src="../images/shim.gif" width="28" height="27" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="27" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 3 -->
          <TD colspan="3" height="22" valign="top"> <FONT size="2" class="komoji">Copyright
            (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</FONT></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="22" border="0"></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</body>

</html>
