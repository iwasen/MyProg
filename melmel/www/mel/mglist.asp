<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
If Session("mel_id") = "" then
	Response.Redirect "./false.html"
Else
	Dim DB, RS, SQL, status

	Set DB = OpenDB()
	Set RS = Server.CreateObject("ADODB.RecordSet")

  SQL = "SELECT MM_status FROM T_MELMEL WHERE MM_mel_id='" & Session("mel_id") & "'"
	RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
	If RS.EOF Then
	'--- No Entry ---
		Response.Redirect "./false.html"
	End If
  status = RS(0)
  RS.Close

	SQL = "SELECT MG_mag_id,MG_m_name,MG_busuu,MG_hindo" & vbCrLf & _
        "FROM T_MELMAG" & vbCrLf & _
        "WHERE MG_mel_id = '" & Session("mel_id") & "' AND (MG_status=0 OR MG_status=1)"
	RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

	If RS.EOF Then
	'--- No Entry ---
		Response.Redirect "./false.html"
	Else
%>
<html>
<head>
<title>めるめる・net メルマガ情報の更新</title>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<LINK rel="stylesheet" href="../melmel.css">
<script language="JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
</head>
<body bgcolor="#ff9999" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('../images/tab_koukoku1b.gif','../images/tab_top1.gif')">
<TABLE width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <TR>
    <TD colspan="3">
      <TABLE bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <TR><!-- Shim row, height 1. -->
          <TD><IMG border=0 height=1 src="../images/shim.gif" width=252></TD>
          <TD><IMG border=0 height=1 src="../images/shim.gif" width=348></TD>
          <TD><IMG border=0 height=1 src="../images/shim.gif" width=1></TD>
        </TR>
        <TR valign="top"><!-- row 1 -->
          <TD rowspan="3"><A HREF="../index.html"><IMG alt="めるめる・net ロゴ" border=0 height=96 name=head_logo src="../images/head_logo.gif" width=252 ></A></TD>
          <TD><IMG border=0 height=26 src="../images/shim.gif" width=348></TD>
          <TD><IMG border=0 height=26 src="../images/shim.gif" width=1></TD>
        </TR>
        <TR valign="top"><!-- row 2 -->
          <TD><IMG alt=発行者向けページ border=0 height=52 name=head_t1 src="../images/head_t1.gif" width=348></TD>
          <TD><IMG border=0 height=52 src="../images/shim.gif" width=1></TD>
        </TR>
        <TR valign="top"><!-- row 3 -->
          <TD><IMG border=0 height=18 src="../images/shim.gif" width=348></TD>
          <TD><IMG border=0 height=18 src="../images/shim.gif" width=1></TD>
        </TR>
      </TABLE>
    </TD>
  </TR><!--  <TR>
    <TD valign="top" colspan="3" align="left">
      <TABLE width="405" border="0" cellspacing="0" cellpadding="0">
        <TR>
          <TD><A href="../index.html" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image45','','../images/tab_top1.gif',1)"><IMG name="Image45" border="0" src="../images/tab_top1b.gif" width="130" height="25" alt="トップページ"></A></TD>
          <TD><A href="pubtop.asp"><IMG src="../images/tab_hakko1b.gif" width="130" height="25" alt="発行者向けトップページ" border="0"></A></TD>
          <TD><A href="../agenttop.html" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image44','','../images/tab_koukoku1b.gif',1)"><IMG name="Image44" border="0" src="../images/tab_koukoku1.gif" width="130" height="25" alt="広告代理店向けトップページ"></A></TD>
          <TD><IMG src="../images/tab_tale1.gif" width="15" height="25"></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>-->
  <TR>
    <TD valign="top" bgcolor="#ffff00"><IMG height=10 src="../images/shim.gif" width=130></TD>
    <TD valign="top" bgcolor="#ffff00"><IMG height=10 src="../images/shim.gif" width=461></TD>
    <TD valign="top"><IMG height=10 src="../images/shadow5.gif" width=9></TD>
  </TR>
  <TR>
    <TD valign="top" bgcolor="#ffff00" width="130">
      <TABLE width="130" border="0" cellspacing="0" cellpadding="0">
        <TR align="middle">
          <TD><IMG alt=MENU height=20 src="images/menu.gif" width=130></TD>
        </TR>
        <TR align="middle">
          <TD><A href="pubtop.asp"><IMG alt=ポイント精算確認 border=0 height=34 src="images/b_memb2.gif" width=112></A></TD>
        </TR>
        <tr align="center">
          <td><a href="pointhst.asp"><img src="images/b_memb10.gif" width="112" height="34" alt="ポイント履歴" border="0"></a></td>
        </tr>
        <TR align="middle">
          <TD><A href="regedit.asp"><IMG alt=登録情報修正 border=0 height=34 src="images/b_memb1.gif" width=112></A></TD>
        </TR>
        <TR align="middle">
          <TD><IMG alt=メルマガ情報修正 height=34 src="images/b_memb3b.gif" width=112></TD>
        </TR>
        <TR align="middle">
          <TD><A href="rexxxheck.asp"><IMG alt=休会申し込み border=0 height=34 src="images/b_memb4.gif" width=112></A></TD>
        </TR>
        <TR align="middle">
          <TD><A href="backcheck.asp"><IMG alt=復会申し込み border=0 height=34 src="images/b_memb5.gif" width=112></A></TD>
        </TR>
        <TR align="middle">
          <TD><A href="resign.asp"><IMG alt=退会申し込み border=0 height=34 src="images/b_memb6.gif" width=112></A></TD>
        </TR>
        <tr align="center">
          <td><a href="../mpub/provreg_1.asp?id=<%=Session("mel_id")%>"><img src="images/b_memb7.gif" width="112" height="34" alt="仮登録申し込み" border="0"></a></td>
        </tr>
        <TR align="middle">
          <TD height = "34" ><A href="index.asp"><img src="images/b_memb8.gif" width="112" height="34" alt="ログインページ" border="0"></A></TD>
        </TR>
        <TR align="middle">
          <TD height = "34" ><A href="http://www.melmel.net/"><img src="images/b_memb9.gif" width="112" height="34" alt="トップページ" border="0"></A></TD>
        </TR>
      </TABLE>
    </TD>
    <TD valign="top" bgcolor="#ffffff"><!------------------ 本文textboxテーブル・ここから ------------------------------>
      <TABLE width="461" border="0" cellspacing="5" cellpadding="0">
        <TR>
          <TD>
            <DIV align="center">
              <P><IMG alt=メールマガジン登録情報 height=24 src="images/t_mgcheck.gif" width=196><BR>
<%
If status = 0 Then
%>
                <IMG alt=キャラクターイメージ border=0 height=61 name=chara01 src="../images/chara01.gif" width=83>
              </P>


            <FONT size="3" class="midashi1">修正するメルマガを選択してください。</FONT>
            <P></P>
            <TABLE width="451" border="1" cellspacing="0" cellpadding="3">
              <TR>
                <TD><FONT size="3" class="midashi1">メルマガID・マガジン名</FONT></TD>
                <TD ALIGN="middle"><FONT size="3" class="midashi1">発行部数</FONT></TD>
                <TD ALIGN="middle"><FONT size="3" class="midashi1">発行頻度</FONT></TD>
              </TR>

	<%
		While Not RS.EOF

            Response.Write "<TR>" & vbcrlf
            Response.Write "<TD><FONT size=3 class=""kihon"">"
			      Response.Write "<A HREF=""mgedit.asp?p=" & RS("MG_mag_id") & """>"
            Response.Write RS("MG_mag_id") & " " & RS("MG_m_name")
			      Response.Write "</A>"
            Response.Write "</FONT></TD>" & vbcrlf
            Response.Write "<TD><FONT size=3 class=""kihon"" ALIGN=""right"">"
            Response.Write RS("MG_busuu")
            Response.Write "</FONT></TD>" & vbcrlf
            Response.Write "<TD><FONT size=3 class=""kihon"" ALIGN=""right"">"
            Response.Write RS("MG_hindo")
            Response.Write "</FONT></TD>" & vbcrlf

            Response.Write "</TR>" & vbcrlf
			RS.MoveNext
		Wend
	%>
            </TABLE>
<%
ElseIf status = 8 Then
%>
                <img name="chara09" src="../images/chara07.gif" width="83" height="62" border="0" alt="キャラクターイメージ">
              </p>
              <p><font size="3" class="kihon">現在、めるめる・netを休会中です。<br>
              （メルマガ情報を修正されたい場合は復会申し込みを行ってください）</font></p>
<%
End If
%>
            <P>　 </P></DIV>

          </TD>
        </TR>
      </TABLE><!------------------ 本文textboxテーブル・ここまで ------------------------------>
      <P>　 </P>
      </TD>
    <TD background="../images/shadow2.gif" ><IMG height=300 src="../images/shim.gif" width=9></TD>
  </TR>
  <TR>
    <TD bgcolor="#ffff00"><IMG height=10 src="../images/shim.gif" width=130></TD>
    <TD bgcolor="#ffff00"><IMG height=10 src="../images/shim.gif" width=461></TD>
    <TD background="../images/shadow2.gif" ><IMG height=10 src="../images/shim.gif" width=9></TD>
  </TR>
  <TR>
    <TD height="9" colspan="3"><IMG height=9 src="../images/shadow6.gif" width=600></TD>
  </TR>
  <TR>
    <TD colspan="3">
      <TABLE bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <TR><!-- Shim row, height 1. -->
          <TD><IMG border=0 height=1 src="../images/shim.gif" width=147></TD>
          <TD><IMG border=0 height=1 src="../images/shim.gif" width=66></TD>
          <TD><IMG border=0 height=1 src="../images/shim.gif" width=28></TD>
          <TD><IMG border=0 height=1 src="../images/shim.gif" width=155></TD>
          <TD><IMG border=0 height=1 src="../images/shim.gif" width=204></TD>
          <TD><IMG border=0 height=1 src="../images/shim.gif" width=1></TD>
        </TR>
        <TR valign="top"><!-- row 1 -->
          <TD colspan="3"><IMG border=0 height=15 src="../images/shim.gif" width=241></TD>
          <TD rowspan="2"><IMG alt="めるめる・net ロゴ" border=0 height=42 name=logo_s src="../images/logo_s.gif" width=155 ></TD>
          <TD rowspan="2"><IMG border=0 height=42 src="../images/shim.gif" width=204></TD>
          <TD><IMG border=0 height=15 src="../images/shim.gif" width=1></TD>
        </TR>
        <TR valign="top"><!-- row 2 -->
          <TD rowspan="2"><IMG border=0 height=49 src="../images/shim.gif" width=147></TD>
          <TD rowspan="2"><IMG alt="めるめる イメージ" border=0 height=49 name=cara_s src="../images/cara_s.gif" width=66 ></TD>
          <TD><IMG border=0 height=27 src="../images/shim.gif" width=28></TD>
          <TD><IMG border=0 height=27 src="../images/shim.gif" width=1></TD>
        </TR>
        <TR valign="top"><!-- row 3 -->
          <TD colspan="3" height="22" valign="center"> <FONT size="2" class="komoji">Copyright
            (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</FONT></TD>
          <TD><IMG border=0 height=22 src="../images/shim.gif" width=1></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</body>

</html>
<%
	End if
End if
%>
