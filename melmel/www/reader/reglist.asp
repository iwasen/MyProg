<!--#INCLUDE file="mama.inc"-->
<HTML>

<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=shift_jis">
<META HTTP-EQUIV="Content-Language" CONTENT="ja">
<META NAME="GENERATOR" CONTENT="Microsoft FrontPage 4.0">
<META NAME="ProgId" CONTENT="FrontPage.Editor.Document">
<TITLE>応募状況</TITLE>
</HEAD>
<%

dim cn, rs, cnt
'--

'--
	Set cn=Server.CreateObject("ADODB.Conxxxtion")
	cn.OPEN "Driver=" & ADO_PROVIDER & "; DBQ=" & ADO_DATABASE
        Set rs=Server.CreateObject("ADODB.Recordset")
        rs.Open "Select * From Q_present",cn,,2

        rs.movefirst
        cnt = 0

DO until rs.EOF

cnt = cnt + 1

rs.MoveNext

LOOP

rs.MoveFirst

%>
<BODY BGCOLOR="#FF9999">
<TABLE WIDTH="600" BORDER="0" CELLPADDING="3" ALIGN="CENTER" BGCOLOR="#FFFFFF" CELLSPACING="0">
  <TR BGCOLOR="#FF9999">
    <TD><A HREF="http://www.melmel.net"><IMG SRC="images/head_logo.gif" BORDER="0" ALT="めるめる・net ロゴ" VSPACE="1" HSPACE="1" WIDTH="252" HEIGHT="96"></A></TD>
  </TR>
  <TR BGCOLOR="#FFFF66">
    <TD><IMG SRC="images/spacer.gif" WIDTH="600" HEIGHT="1"></TD>
  </TR>
  <TR ALIGN="CENTER">
    <TD>　 </TD>
  </TR>
  <TR ALIGN="CENTER" VALIGN="MIDDLE">
    <TD>
      <P><IMG SRC="images/spacer.gif" WIDTH="1" HEIGHT="50" ALIGN="LEFT"><FONT SIZE="4">応募状況</FONT>
        <BR><font size=3>ただ今の応募件数は【<%=cnt%>件】です。</font><BR>
        <BR>
    </TD>
  </TR>
  <tr>
    <td>
      <table BORDER=1>
        <tr>
          <TH width=100><FONT SIZE=2>受け付け番号</FONT></TH><TH width=150>お名前</TH><TH width=150><FONT SIZE=2>購読誌数</FONT></TH><TH width=200><FONT SIZE=2>メッセージ</FONT></TH>
        </tr>
<%
DO until RS.EOF
%>
    <TR>
        <TD><% =RS("ID") %></TD>
        <TD><% =RS("nm") %></TD>
        <TD><% =RS("magazine") %></TD>
        <td><% =RS("message") & "　" %></td>
    </TR>
<%
RS.moveNext
LOOP
%>
</table>

    </td>
  </tr>

  <TR ALIGN="CENTER" VALIGN="MIDDLE">
    <TD>
        <A HREF="http://www.melmel.net/reader/">読者ページトップへ戻る </A></P>
      </TD>
  </TR>
  <TR ALIGN="CENTER">
    <TD><FONT SIZE="2">□ めるめる・net事務局 □<BR>
      <A HREF="mailto:info@melmel.net"><FONT SIZE="2">info@melmel.net</FONT></A>
      </FONT></TD>
  </TR>
  <TR BGCOLOR="#0099FF">
    <TD><IMG SRC="images/spacer.gif" WIDTH="600" HEIGHT="1"></TD>
  </TR>
</TABLE>


<!----  外枠・ブルー/END  ----> <!-----  bottom logo・(c)  ----->
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" ALIGN="CENTER">
  <TR>
<TD ROWSPAN="2" ALIGN="center" VALIGN="bottom" WIDTH="135"><A HREF="http://www.melmel.net"><IMG SRC="images/cara_s.gif" BORDER="0" ALT="めるめる イメージ" WIDTH="66" HEIGHT="49"></A></TD>
<TD VALIGN="bottom" WIDTH="300" HEIGHT="42" ALIGN="center"><A HREF="http://www.melmel.net"><IMG SRC="images/logo_s.gif" BORDER="0" ALT="めるめる・net ロゴ" WIDTH="155" HEIGHT="42"></A></TD>
<TR><TD VALIGN="top" HEIGHT="22" ALIGN="center"><FONT SIZE="1">Copyright (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</FONT></TD></TR>
</TABLE>
<!-----  bottom logo・(c)/END  ----->
</BODY>

</HTML>
