<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
If Session("mel_id") = "" then
	Response.Redirect "./false.html"
End If

Dim DB, RS, SQL, SUB0, SUB1, SUB2, SUB3

Set DB = OpenDB()
Set RS = Server.CreateObject("ADODB.RecordSet")

SUB0 = "SELECT CL_koukoku_id,CL_mag_id,COUNT(*) AS click FROM T_CLICKLOG GROUP BY CL_koukoku_id,CL_mag_id"
SUB1 = "SELECT JP_date AS date,'広告：'+KK_koukoku_name AS naiyou,MG_m_name AS m_name,JP_point AS point,JP_busuu AS busuu,click,ISNULL(KK_koukoku_url,'') AS koukoku_url" & vbCrLf & _
       "FROM (((T_JPOINT LEFT OUTER JOIN (" & SUB0 & ") AS SUB0 ON JP_mag_id=CL_mag_id AND JP_koukoku_id=CL_koukoku_id)" & vbCrLf & _
       " INNER JOIN V_KOUKOKU ON JP_koukoku_id=KK_koukoku_id)" & vbCrLf & _
       " INNER JOIN T_MELMAG ON JP_mag_id=MG_mag_id)" & vbCrLf & _
       " INNER JOIN T_MELMEL ON MG_mel_id=MM_mel_id" & vbCrLf & _
       "WHERE MM_mel_id='" & Session("mel_id") & "'"
SUB2 = "SELECT SP_req_date AS date,'ポイント精算' AS naiyou,'<br>' AS m_name,-SP_req_point AS point,NULL AS busuu,NULL AS click,NULL AS koukoku_url" & vbCrLf & _
       "FROM T_SPOINT" & vbCrLf & _
       "WHERE SP_mel_id='" & Session("mel_id") & "'"
SUB3 = "SELECT MP_date AS date,MP_comment AS naiyou,'<br>' AS m_name,MP_point AS point,NULL AS busuu,NULL AS click,NULL AS koukoku_url" & vbCrLf & _
       "FROM T_MPOINT" & vbCrLf & _
       "WHERE MP_mel_id='" & Session("mel_id") & "'"
SQL = SUB1 & " UNION ALL " & SUB2 & " UNION ALL " & SUB3 & " ORDER BY date DESC"
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

%>
<html>
<head>
<title>めるめる・net ポイント履歴</title>
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
        <TR> <!-- Shim row, height 1. -->
          <TD><IMG src="../images/shim.gif" width="252" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="348" height="1" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="1" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 1 -->
          <TD rowspan="3"><A HREF="../index.html"><IMG name="head_logo" src="../images/head_logo.gif" width="252" height="96" border="0" alt="めるめる・net ロゴ"></A></TD>
          <TD><IMG src="../images/shim.gif" width="348" height="26" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="26" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 2 -->
          <TD><IMG name="head_t1" src="../images/head_t1.gif" width="348" height="52" border="0" alt="発行者向けページ"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="52" border="0"></TD>
        </TR>
        <TR valign="top"><!-- row 3 -->
          <TD><IMG src="../images/shim.gif" width="348" height="18" border="0"></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="18" border="0"></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
<!--  <TR>
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
    <TD valign="top" bgcolor="#FFFF00"><IMG src="../images/shim.gif" width="130" height="10"></TD>
    <TD valign="top" bgcolor="#FFFF00"><IMG src="../images/shim.gif" width="461" height="10"></TD>
    <TD valign="top"><IMG src="../images/shadow5.gif" width="9" height="10"></TD>
  </TR>
  <TR>
    <TD valign="top" bgcolor="#FFFF00" width="130">
      <TABLE width="130" border="0" cellspacing="0" cellpadding="0">
        <TR align="center">
          <TD><IMG src="images/menu.gif" width="130" height="20" alt="MENU"></TD>
        </TR>
        <TR align="center">
          <TD><A href="pubtop.asp"><IMG src="images/b_memb2.gif" width="112" height="34" alt="ポイント精算確認" border="0"></A></TD>
        </TR>
        <tr align="center">
          <td><a href="pointhst.asp"><img src="images/b_memb10b.gif" width="112" height="34" alt="ポイント履歴" border="0"></a></td>
        </tr>
        <TR align="center">
          <TD><A href="regedit.asp"><IMG src="images/b_memb1.gif" width="112" height="34" alt="登録情報修正" border="0"></A></TD>
        </TR>
        <TR align="center">
          <td><a href="mglist.asp"><img src="images/b_memb3.gif" width="112" height="34" alt="メルマガ情報修正" border="0"></a></td>
        </TR>
        <TR align="center">
          <TD><A href="rexxxheck.asp"><IMG src="images/b_memb4.gif" width="112" height="34" alt="休会申し込み" border="0"></A></TD>
        </TR>
        <TR align="center">
          <TD><A href="backcheck.asp"><IMG src="images/b_memb5.gif" width="112" height="34" alt="復会申し込み" border="0"></A></TD>
        </TR>
        <TR align="center">
          <TD><A href="resign.asp"><IMG src="images/b_memb6.gif" width="112" height="34" alt="退会申し込み" border="0"></A></TD>
        </TR>
        <tr align="center">
          <td><a href="../mpub/provreg_1.asp?id=<%=Session("mel_id")%>"><img src="images/b_memb7.gif" width="112" height="34" alt="仮登録申し込み" border="0"></a></td>
        </tr>
        <TR align="center">
          <TD height = "34" ><A href="index.asp"><img src="images/b_memb8.gif" width="112" height="34" alt="ログインページ" border="0"></a></TD>
        </TR>
        <TR align="center">
          <TD height = "34" ><A href="http://www.melmel.net/"><img src="images/b_memb9.gif" width="112" height="34" alt="トップページ" border="0"></a></TD>
        </TR>
      </TABLE>
    </TD>
    <TD valign="top" bgcolor="#FFFFFF">
<!------------------ 本文textboxテーブル・ここから ------------------------------>
      <TABLE width="461" border="0" cellspacing="5" cellpadding="0">
        <TR>
          <TD>
            <DIV align="center">
              <P><!--<IMG src="images/t_mgstatus.gif" width="196" height="24" alt="メルマガ活動状態変更">--><b><%=Session("h_name")%>さんのポイント履歴</b><BR>
                <IMG name="chara01" src="../images/chara01.gif" width="83" height="61" border="0" alt="キャラクターイメージ">
              </P>

            </DIV>
            <TABLE width="550" border="1" cellspacing="0" cellpadding="3">
              <TR>
                <TD ALIGN="center"><FONT size="3" class="midashi1">日付</FONT></TD>
                <TD ALIGN="center"><FONT size="3" class="midashi1">内容</FONT></TD>
                <TD ALIGN="center"><FONT size="3" class="midashi1">対象メルマガ名</FONT></TD>
                <TD ALIGN="center"><FONT size="3" class="midashi1">ﾎﾟｲﾝﾄ数</FONT></TD>
                <TD ALIGN="center"><FONT size="3" class="midashi1">発行部数</FONT></TD>
                <TD ALIGN="center"><FONT size="3" class="midashi1">ｸﾘｯｸ数</FONT></TD>
              </TR>
<%
Do Until RS.EOF
  Writeln "<tr>"
  Writeln "<td align='center'><font size=3 class='kihon'>" & DateFormat(RS("date")) & "</font></td>"
  Writeln "<td><font size=3 class='kihon'>" & RS("naiyou") & "</font></td>"
  Writeln "<td><font size=3 class='kihon'>" & RS("m_name") & "</font></td>"
  Writeln "<td align='right'><font size=3 class='kihon'>" & NumFormat(RS("point")) & "</font></td>"
  If IsNull(RS("busuu")) Then
    Writeln "<td align='right'><font size=3 class='kihon'><br></font></td>"
  Else
    Writeln "<td align='right'><font size=3 class='kihon'>" & NumFormat(RS("busuu")) & "</font></td>"
  End If
  If IsNull(RS("koukoku_url")) Then
    Writeln "<td align='right'><font size=3 class='kihon'><br></font></td>"
  ElseIf RS("koukoku_url") = "" Then
    Writeln "<td align='right'><font size=3 class='kihon'>-</font></td>"
  Else
    Writeln "<td align='right'><font size=3 class='kihon'>" & NumFormat(RS("click")) & "</font></td>"
  End If
  Writeln "</tr>"
  RS.MoveNext
Loop
RS.Close
%>
            </TABLE>
          </TD>
        </TR>
      </TABLE>
      <center><font size="3" class="kihon"><p>※ご注意：上記のポイント数は小数以下を四捨五入して表示しているため、<br>合計が現ポイント数に一致しない場合があります。</p></font></center><br>
<!------------------ 本文textboxテーブル・ここまで ------------------------------>
      </TD>
    <TD background="../images/shadow2.gif" ><IMG src="../images/shim.gif" width="9" height="300"></TD>
  </TR>
  <TR>
    <TD bgcolor="#FFFF00"><IMG src="../images/shim.gif" width="130" height="10"></TD>
    <TD bgcolor="#FFFF00"><IMG src="../images/shim.gif" width="461" height="10"></TD>
    <TD background="../images/shadow2.gif" ><IMG src="../images/shim.gif" width="9" height="10"></TD>
  </TR>
  <TR>
    <TD height="9" colspan="3"><IMG src="../images/shadow6.gif" width="600" height="9"></TD>
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
          <TD colspan="3" height="22" valign="middle"> <FONT size="2" class="komoji">Copyright
            (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</FONT></TD>
          <TD><IMG src="../images/shim.gif" width="1" height="22" border="0"></TD>
        </TR>
      </TABLE>
    </TD>
  </TR>
</TABLE>
</body>

</html>
