<!--#INCLUDE file="mama.inc"-->
<HTML>

<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=shift_jis">
<META HTTP-EQUIV="Content-Language" CONTENT="ja">
<META NAME="GENERATOR" CONTENT="Microsoft FrontPage 4.0">
<META NAME="ProgId" CONTENT="FrontPage.Editor.Document">
<TITLE>応募フォーム確認画面</TITLE>
</HEAD>
<%

dim cn, rsInsert
'--
'--登録情報をＤＢに格納
'--
	Set cn=Server.CreateObject("ADODB.Conxxxtion")
	cn.OPEN "Driver=" & ADO_PROVIDER & "; DBQ=" & ADO_DATABASE
        Set rsInsert=Server.CreateObject("ADODB.Recordset")
        rsInsert.Open "Select * From present",cn,,2

  rsInsert.AddNew

  rsInsert("magazine") = session("magazine")
  rsInsert("message") = session("message")
  rsInsert("nm") = session("nm")
  rsInsert("email") = session("email")
  rsInsert("add1") = session("add1")
  rsInsert("add2") = session("add2")
  rsInsert("tel") = session("tel")
  rsInsert("in_data") = now()

rsInsert.Update
rsInsert.Close

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
      <P><IMG SRC="images/spacer.gif" WIDTH="1" HEIGHT="300" ALIGN="LEFT"><FONT SIZE="4">ご応募ありがとうございました。</FONT>
        <BR>
        <BR>
        <A HREF="http://www.melmel.net/reader/">トップへ戻る </A></P>
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
