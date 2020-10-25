<!--#INCLUDE FILE="mama.inc"-->
<%


dim magazine,message
dim nm,email,add1,add2,tel

'--
'--表示用にデータを変数に格納
'--

magazine = request("magazine")
message = request("message")
nm = request("nm")
email = request("email")
add1 = request("add1")
add2 = request("add2")
tel = request("tel")



'--
'--それぞれをsessionに格納
'--

session("magazine")=magazine
session("message")=message
session("nm")=nm
session("email")=email
session("add1")=add1
session("add2")=add2
session("tel")=tel

%>

<HTML>

<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=shift_jis">
<META HTTP-EQUIV="Content-Language" CONTENT="ja">
<TITLE>応募フォーム確認画面</TITLE>
</HEAD>

<BODY BGCOLOR="#FF9999">
<TABLE WIDTH="600" BORDER="0" CELLPADDING="3" ALIGN="CENTER" BGCOLOR="#FFFFFF" CELLSPACING="0">
  <TR BGCOLOR="#FF9999">
    <TD><A HREF="http://www.melmel.net"><IMG SRC="images/head_logo.gif" BORDER="0" ALT="めるめる・net ロゴ" VSPACE="1" HSPACE="1" WIDTH="252" HEIGHT="96"></A></TD>
  </TR>
  <TR BGCOLOR="#FFFF66">
    <TD><IMG SRC="images/spacer.gif" WIDTH="600" HEIGHT="1"></TD>
  </TR>
  <TR ALIGN="CENTER">
    <TD> <FONT FACE="ＭＳ Ｐゴシック, Osaka" SIZE="4"><IMG SRC="images/kakunin.gif" WIDTH="179" HEIGHT="29">
      </FONT> </TD>
  </TR>
  <TR>
    <TD><!--　応募フォーム  -->
        <TABLE WIDTH="100%" BORDER="0" CELLPADDING="3" CELLSPACING="0">
          <TR BGCOLOR="#CCFFCC">
            <TD><IMG SRC="images/spacer.gif" WIDTH="600" HEIGHT="1"></TD>
          </TR>
          <TR>
            <TD><STRONG>メールマガジンを購読になりましたか？</STRONG> </TD>
          </TR>
          <TR>
            <TD><%=magazine%></TD>
          </TR>
          <TR BGCOLOR="#CCFFCC">
            <TD><IMG SRC="images/spacer.gif" WIDTH="600" HEIGHT="1"></TD>
          </TR>
          <TR>
            <TD><STRONG>何かご質問等があればご記入ください。 </STRONG></TD>
          </TR>
          <TR>
            <TD><STRONG>&nbsp;<%=message%> </STRONG></TD>
          </TR>
          <TR BGCOLOR="#CCFFCC">
            <TD><IMG SRC="images/spacer.gif" WIDTH="600" HEIGHT="1"></TD>
          </TR>
          <TR>
            <TD><STRONG>連絡先を入力してください</STRONG> </TD>
          </TR>
          <TR>
            <TD>
              <TABLE WIDTH="100%" BORDER="0" CELLPADDING="3" CELLSPACING="0">
                <TR>
                  <TD WIDTH="140" ALIGN="left"> お名前
                  <TD ALIGN="left"><%=nm%>
                </TR>
                <TR>
                  <TD WIDTH="140" ALIGN="left"> 電子メールアドレス
                  <TD ALIGN="left"><%=email%>
                </TR>
                <TR>
                  <TD WIDTH="140" ALIGN="left">郵便番号
                  <TD ALIGN="left"><%=add1%>
                </TR>
                <TR>
                  <TD WIDTH="140" ALIGN="left"> 住所
                  <TD ALIGN="left"><%=add2%>
                </TR>
                <TR>
                  <TD WIDTH="140" ALIGN="left"> 電話番号
                  <TD ALIGN="left"><%=tel%>
                </TR>
              </TABLE>
            </TD>
          </TR>
          <TR ALIGN="CENTER" BGCOLOR="#CCFFCC">
            <TD><IMG SRC="images/spacer.gif" WIDTH="600" HEIGHT="1"></TD>
          </TR>
          <TR ALIGN="CENTER">

          <TD> 　
            <TABLE WIDTH="100%" BORDER="0" CELLPADDING="3">
              <TR>
                <TD ALIGN="RIGHT" WIDTH="50%">
                  <FORM ACTION="present3.asp" METHOD="POST" >
                    <INPUT TYPE="submit" VALUE="プレゼントに応募" NAME="submit">
                    　
                  </FORM>
                </TD>
                <TD ALIGN="LEFT" WIDTH="50%">
                  <FORM ACTION="present3.asp" METHOD="POST" >
                    　
                    <INPUT TYPE="BUTTON" VALUE="キャンセル" onClick="history.back()"NAME="reset">
                    　　 　　　　
                  </FORM>
                </TD>
              </TR>
            </TABLE>
            　　　　　　　 </TD>
          </TR>
        </TABLE>

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
