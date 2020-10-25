<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
Dim DB, RS, SQL, Cmd, mel_id, h_name, Point, status, i

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

If Session("mel_id") = "" Then
	SQL = "SELECT MM_mel_id,MM_h_name,MM_point,MM_status FROM V_MELMEL_LOGIN WHERE MM_mel_id='" & Request.Form("mel_id") & "' AND MM_password='" & Request.Form("pwd") & "'"
Else
	SQL = "SELECT MM_mel_id,MM_h_name,MM_point,MM_status FROM V_MELMEL_LOGIN WHERE MM_mel_id='" & Session("mel_id") & "'"
End If
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

If RS.EOF Then
	'--- No Entry ---
	Response.Redirect "./false.html"
End If

If Session("mel_id") = "" Then
  Session("mel_id") = Request.Form("mel_id")
End If

mel_id = RS("MM_mel_id")
h_name = RS("MM_h_name")
Point = Number(RS("MM_point"))
status = RS("MM_status")
RS.Close

%>
<html>
<head>
<title>めるめる・net 発行者向けトップページ</title>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<link rel="stylesheet" href="../melmel.css">
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
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td colspan="3">
      <table bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <tr> <!-- Shim row, height 1. -->
          <td><img src="../images/shim.gif" width="252" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="348" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="1" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 1 -->
          <td rowspan="3"><a HREF="../index.html"><img name="head_logo" src="../images/head_logo.gif" width="252" height="96" border="0" alt="めるめる・net ロゴ"></a></td>
          <td><img src="../images/shim.gif" width="348" height="26" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="26" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 2 -->
          <td><img name="head_t1" src="../images/head_t1.gif" width="348" height="52" border="0" alt="発行者向けページ"></td>
          <td><img src="../images/shim.gif" width="1" height="52" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 3 -->
          <td><img src="../images/shim.gif" width="348" height="18" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="18" border="0"></td>
        </tr>
      </table>
    </td>
  </tr>
<!--  <TR>     <TD valign="top" colspan="3" align="left">       <TABLE width="405" border="0" cellspacing="0" cellpadding="0">        <TR>           <TD><A href="../index.html" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image45','','../images/tab_top1.gif',1)"><IMG name="Image45" border="0" src="../images/tab_top1b.gif" width="130" height="25" alt="トップページ"></A></TD>          <TD><IMG src="../images/tab_hakko1b.gif" width="130" height="25" alt="発行者向けトップページ"></TD>          <TD><A href="../agenttop.asp" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image44','','../images/tab_koukoku1b.gif',1)"><IMG name="Image44" border="0" src="../images/tab_koukoku1.gif" width="130" height="25" alt="広告代理店向けトップページ"></A></TD>          <TD><IMG src="../images/tab_tale1.gif" width="15" height="25"></TD>        </TR>      </TABLE>    </TD>  </TR>-->
  <tr>
    <td valign="top" bgcolor="#FFFF00"><img src="../images/shim.gif" width="130" height="10"></td>
    <td valign="top" bgcolor="#FFFF00"><img src="../images/shim.gif" width="461" height="10"></td>
    <td valign="top"><img src="../images/shadow5.gif" width="9" height="10"></td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#FFFF00" width="130">
      <table width="130" border="0" cellspacing="0" cellpadding="0">
        <tr align="center">
          <td><img src="images/menu.gif" width="130" height="20" alt="MENU"></td>
        </tr>
        <tr align="center">
          <td><a href="pubtop.asp"><img src="images/b_memb2.gif" width="112" height="34" alt="ポイント精算確認" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="regedit.asp"><img src="images/b_memb1.gif" width="112" height="34" alt="登録情報修正" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="mglist.asp"><img src="images/b_memb3.gif" width="112" height="34" alt="メルマガ情報修正" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="rexxxheck.asp"><img src="images/b_memb4.gif" width="112" height="34" alt="休会申し込み" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="backcheck.asp"><img src="images/b_memb5.gif" width="112" height="34" alt="復会申し込み" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="resign.asp"><img src="images/b_memb6.gif" width="112" height="34" alt="退会申し込み" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><a href="../mpub/provreg_1.asp?id=<%=mel_id%>"><img src="images/b_memb7.gif" width="112" height="34" alt="仮登録申し込み" border="0"></a></td>
        </tr>
        <tr align="center">
          <td height="34"><a href="index.asp"><img src="images/b_memb8.gif" width="112" height="34" alt="ログインページ" border="0"></a></td>
        </tr>
        <tr align="center">
          <td height="34"><a href="../index.html"><img src="images/b_memb9.gif" width="112" height="34" alt="トップページ" border="0"></a></td>
        </tr>
      </table>
    </td>
    <td valign="top" bgcolor="#FFFFFF">
      <table width="461" border="0" cellspacing="5" cellpadding="0">
        <tr>
          <td>
            <p align="center"><img src="images/t_pubtop.gif" width="279" height="22" alt="めるめるさん専用ページへようこそ"><br>
              <img name="chara06" src="../images/chara06.gif" width="78" height="64" border="0" alt="キャラクターイメージ">
            </p>
            <p align="center"><font size="4" class="ohmidashi"><%=h_name%>さん、こんにちは</font><br><br>
              <font size="3" class="kihon">メールマガジンの執筆、ご苦労様です。<br>
              なにかお気づきのことがありましたら、<br><a HREF="mailto:office@melmel.net">めるめる・net事務局</a>までお知らせください。</font></p>
            <table border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td>
                  <div align="right"><font size="3" class="kihon">現在の<%=h_name%>さんの交換可能なポイント</font></div>
                </td>
                <td align="right" WIDTH="80"><font size="3" class="midashi1">
<%
if (Point >= 5000) Then
  Response.Write ("<FORM name='form1' METHOD='POST' ACTION='pcheck.asp'>")
  Response.Write ("<SELECT NAME='ssn'>")
  i=0
  DO while i < Int(Point/1000)
    i = i+1
    if i >= 5 Then
      Response.Write ("<OPTION VALUE=")
      Response.Write ( FormatNumber(i * 1000,0))
      if i = Int(Point/1000) Then
        Response.Write " SELECTED"
      End If
      Response.Write (">")
      Response.Write ( FormatNumber(i * 1000,0))
    End If
  Loop				
  Response.Write "</SELECT>"
Else
  Response.Write(FormatNumber(0,0))
End If
%>
                </font></td>
              </tr>

              <tr>
                <td>
                  <div align="right"><font size="3" class="kihon">未精算累計ポイント</font></div>
                </td>
                <td WIDTH="80"><div ALIGN="right"><font size="3" class="midashi1"><%=FormatNumber(Point,0)%></font></div></td>
              </tr>

              <tr>
                <td>　</td>
                <td align="right">▼　</td>
              </tr>
              <tr>
                <td>
                  <div align="right"><font size="3" class="kihon">精算可能金額　</font></div>
                </td>
                <td align="right"><font size="3" class="midashi1">
<%
if (Point >= 5000) Then
  Response.Write(FormatNumber((Int(Point/1000)) * 1000,0))
Else
  Response.Write(FormatNumber(0,0))
End If				
%>
                </font></td>
              </tr>
            </table>
<%
if (Point >= 5000) Then
%>
	            <div align="center"> <font size="3" class="kihon">
                <input type="submit" name="Submit" value="精算">
                </font> </div>
	            </form>
<%
End If
%>
            <p>　</p>
<%
If status = 0 Then
%>
            <div ALIGN="center">
            <img name="chara02" src="../images/chara02.gif" width="82" height="55" border="0" alt="キャラクターイメージ"><br>
            </div>
            <p align="center"><img src="../images/border_y.gif" width="300" height="3"></p>
            <font size="3" class="midashi1"><%=h_name%>さんの登録メールマガジン</font>
            </p>
            <table width="451" border="1" cellspacing="0" cellpadding="3">
              <tr>
                <td><font size="3" class="midashi1">メルマガID・マガジン名</font></td>
                <td ALIGN="center"><font size="3" class="midashi1">発行部数</font></td>
                <td ALIGN="center"><font size="3" class="midashi1">発行頻度</font></td>
                <td ALIGN="center"><font size="3" class="midashi1">活動状態</font></td>
              </tr>

<%
SQL = "SELECT MG_mag_id,MG_m_name,MG_busuu,MG_hindo,MG_status" & vbCrLf & _
      "FROM T_MELMAG" & vbCrLf & _
      "WHERE MG_mel_id='" & mel_id & "' AND (MG_status=0 OR MG_status=1)"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
While Not RS.EOF

  Response.Write "<TR>" & vbcrlf
  Response.Write "<TD><FONT size=3 class='kihon'>"
  Response.Write RS("MG_mag_id") & " " & RS("MG_m_name")
  Response.Write "</FONT></TD>" & vbcrlf
  Response.Write "<TD><FONT size=3 class='kihon'>"
  Response.Write RS("MG_busuu")
  Response.Write "</FONT></TD>" & vbcrlf
  Response.Write "<TD><FONT size=3 class='kihon'>"
  Response.Write RS("MG_hindo")
  Response.Write "</FONT></TD>" & vbcrlf
  Response.Write "<TD align='center'><FONT size=3 class='kihon'>"
  If RS("MG_status") = 0 Then
    Response.Write "活動中"
  Else
    Response.Write "休止中"
  End If
  Response.Write "</FONT></TD>" & vbcrlf
  Response.Write "</TR>" & vbcrlf

	RS.MoveNext
Wend
%>
            </table>
<div align="right"><form><input type="button" value="活動状態変更" onClick="location.href='./mgstatus.asp'"></form></div>
<%
Else
%>
            <div ALIGN="center">
            <img name="chara02" src="../images/chara07.gif" width="82" height="55" border="0" alt="キャラクターイメージ"><br>
            </div>
            <p align="center"><img src="../images/border_y.gif" width="300" height="3"></p>
            <font size="3" class="midashi1"><center><%=h_name%>さんは現在めるめる・netを休会中です。<br>
            （復会されたい場合はメニューの「復会申し込み」をクリックしてください）</center></font>
<%
End If
%>
            <p>　 </p>
          </td>
        </tr>
      </table>
    </td>
    <td background="../images/shadow2.gif"><img src="../images/shim.gif" width="9" height="300"></td>
  </tr>
  <tr>
    <td bgcolor="#FFFF00"><img src="../images/shim.gif" width="130" height="10"></td>
    <td bgcolor="#FFFF00"><img src="../images/shim.gif" width="461" height="10"></td>
    <td background="../images/shadow2.gif"><img src="../images/shim.gif" width="9" height="10"></td>
  </tr>
  <tr>
    <td height="9" colspan="3"><img src="../images/shadow6.gif" width="600" height="9"></td>
  </tr>
  <tr>
    <td colspan="3">
      <table bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <tr> <!-- Shim row, height 1. -->
          <td><img src="../images/shim.gif" width="147" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="66" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="28" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="155" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="204" height="1" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="1" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 1 -->
          <td colspan="3"><img src="../images/shim.gif" width="241" height="15" border="0"></td>
          <td rowspan="2"><img name="logo_s" src="../images/logo_s.gif" width="155" height="42" border="0" alt="めるめる・net ロゴ"></td>
          <td rowspan="2"><img src="../images/shim.gif" width="204" height="42" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="15" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 2 -->
          <td rowspan="2"><img src="../images/shim.gif" width="147" height="49" border="0"></td>
          <td rowspan="2"><img name="cara_s" src="../images/cara_s.gif" width="66" height="49" border="0" alt="めるめる イメージ"></td>
          <td><img src="../images/shim.gif" width="28" height="27" border="0"></td>
          <td><img src="../images/shim.gif" width="1" height="27" border="0"></td>
        </tr>
        <tr valign="top"><!-- row 3 -->
          <td colspan="3" height="22" valign="middle"> <font size="2" class="komoji">Copyright
            (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</font></td>
          <td><img src="../images/shim.gif" width="1" height="22" border="0"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>

</html>
