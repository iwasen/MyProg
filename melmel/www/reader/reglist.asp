<!--#INCLUDE file="mama.inc"-->
<HTML>

<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=shift_jis">
<META HTTP-EQUIV="Content-Language" CONTENT="ja">
<META NAME="GENERATOR" CONTENT="Microsoft FrontPage 4.0">
<META NAME="ProgId" CONTENT="FrontPage.Editor.Document">
<TITLE>�����</TITLE>
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
    <TD><A HREF="http://www.melmel.net"><IMG SRC="images/head_logo.gif" BORDER="0" ALT="�߂�߂�Enet ���S" VSPACE="1" HSPACE="1" WIDTH="252" HEIGHT="96"></A></TD>
  </TR>
  <TR BGCOLOR="#FFFF66">
    <TD><IMG SRC="images/spacer.gif" WIDTH="600" HEIGHT="1"></TD>
  </TR>
  <TR ALIGN="CENTER">
    <TD>�@ </TD>
  </TR>
  <TR ALIGN="CENTER" VALIGN="MIDDLE">
    <TD>
      <P><IMG SRC="images/spacer.gif" WIDTH="1" HEIGHT="50" ALIGN="LEFT"><FONT SIZE="4">�����</FONT>
        <BR><font size=3>�������̉��匏���́y<%=cnt%>���z�ł��B</font><BR>
        <BR>
    </TD>
  </TR>
  <tr>
    <td>
      <table BORDER=1>
        <tr>
          <TH width=100><FONT SIZE=2>�󂯕t���ԍ�</FONT></TH><TH width=150>�����O</TH><TH width=150><FONT SIZE=2>�w�ǎ���</FONT></TH><TH width=200><FONT SIZE=2>���b�Z�[�W</FONT></TH>
        </tr>
<%
DO until RS.EOF
%>
    <TR>
        <TD><% =RS("ID") %></TD>
        <TD><% =RS("nm") %></TD>
        <TD><% =RS("magazine") %></TD>
        <td><% =RS("message") & "�@" %></td>
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
        <A HREF="http://www.melmel.net/reader/">�ǎ҃y�[�W�g�b�v�֖߂� </A></P>
      </TD>
  </TR>
  <TR ALIGN="CENTER">
    <TD><FONT SIZE="2">�� �߂�߂�Enet������ ��<BR>
      <A HREF="mailto:info@melmel.net"><FONT SIZE="2">info@melmel.net</FONT></A>
      </FONT></TD>
  </TR>
  <TR BGCOLOR="#0099FF">
    <TD><IMG SRC="images/spacer.gif" WIDTH="600" HEIGHT="1"></TD>
  </TR>
</TABLE>


<!----  �O�g�E�u���[/END  ----> <!-----  bottom logo�E(c)  ----->
<TABLE BORDER="0" CELLPADDING="0" CELLSPACING="0" ALIGN="CENTER">
  <TR>
<TD ROWSPAN="2" ALIGN="center" VALIGN="bottom" WIDTH="135"><A HREF="http://www.melmel.net"><IMG SRC="images/cara_s.gif" BORDER="0" ALT="�߂�߂� �C���[�W" WIDTH="66" HEIGHT="49"></A></TD>
<TD VALIGN="bottom" WIDTH="300" HEIGHT="42" ALIGN="center"><A HREF="http://www.melmel.net"><IMG SRC="images/logo_s.gif" BORDER="0" ALT="�߂�߂�Enet ���S" WIDTH="155" HEIGHT="42"></A></TD>
<TR><TD VALIGN="top" HEIGHT="22" ALIGN="center"><FONT SIZE="1">Copyright (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</FONT></TD></TR>
</TABLE>
<!-----  bottom logo�E(c)/END  ----->
</BODY>

</HTML>
