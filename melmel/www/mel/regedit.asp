<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
If Session("mel_id") = "" then
	Response.Redirect "./false.html"
Else
	Dim DB, RS, SQL

	Set DB = OpenDB()
	SQL = "SELECT * FROM T_MELMEL WHERE MM_mel_id = '" & Session("mel_id") & "'"
	Set RS = Server.CreateObject("ADODB.RecordSet")
	RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

	If RS.EOF Then
		'--- No Entry ---
		Response.Write ("エラーが発生しました。<BR>")
		Response.Write (OFFICE_ADDRESS)
		Response.Write ("<BR> まで連絡下さい。")

	Else

%>
<html>
<head>
<title>めるめる・net 登録情報修正</title>
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
<!--  <TR>     <TD valign="top" colspan="3" align="left">       <TABLE width="405" border="0" cellspacing="0" cellpadding="0">        <TR>           <TD><A href="../index.html" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image45','','../images/tab_top1.gif',1)"><IMG name="Image45" border="0" src="../images/tab_top1b.gif" width="130" height="25" alt="トップページ"></A></TD>          <TD><A href="pubtop.asp"><IMG src="../images/tab_hakko1b.gif" width="130" height="25" alt="発行者向けトップページ" border="0"></A></TD>          <TD><A href="../agenttop.html" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image44','','../images/tab_koukoku1b.gif',1)"><IMG name="Image44" border="0" src="../images/tab_koukoku1.gif" width="130" height="25" alt="広告代理店向けトップページ"></A></TD>          <TD><IMG src="../images/tab_tale1.gif" width="15" height="25"></TD>        </TR>      </TABLE>    </TD>  </TR>-->
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
          <td><a href="pointhst.asp"><img src="images/b_memb10.gif" width="112" height="34" alt="ポイント履歴" border="0"></a></td>
        </tr>
        <tr align="center">
          <td><img src="images/b_memb1b.gif" width="112" height="34" alt="登録情報修正"></td>
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
          <td><a href="../mpub/provreg_1.asp?id=<%=Session("mel_id")%>"><img src="images/b_memb7.gif" width="112" height="34" alt="仮登録申し込み" border="0"></a></td>
        </tr>
        <tr align="center">
          <td height="34"><a href="index.asp"><img src="images/b_memb8.gif" width="112" height="34" alt="ログインページ" border="0"></a></td>
        </tr>
        <tr align="center">
          <td height="34"><a href="http://www.melmel.net/"><img src="images/b_memb9.gif" width="112" height="34" alt="トップページ" border="0"></a></td>
        </tr>
      </table>
    </td>
    <td valign="top" bgcolor="#FFFFFF">
<!------------------ 本文textboxテーブル・ここから ------------------------------>
      <table width="461" border="0" cellspacing="5" cellpadding="0">
        <tr>
          <td>
            <p align="center"><img src="images/t_regedit.gif" width="216" height="22" alt="めるめるさん登録情報修正"><br>
              <img name="chara04" src="../images/chara04.gif" width="102" height="56" border="0" alt="キャラクターイメージ">
            </p>
            <p align="center"><font size="3" class="kihon"><img src="../images/border_y.gif" width="100" height="3">
              登録表示項目</font> <font size="3" class="kihon"><img src="../images/border_y.gif" width="100" height="3"></font>
            </p>
            <form name="form1" METHOD="POST" ACTION="./regcheck.asp">
              <table border="0" cellspacing="0" cellpadding="2" align="center">
                <tr>
                  <td><font size="3" class="kihon">●氏名</font></td>
                  <td class="kihon"> <font size="3">
                    <input type="text" name="h_name" value="<%=RS("MM_h_name")%>" MAXLENGTH="50" size=40>
                    </font></td>
                </tr>
                <tr>
                  <td><font size="3" class="kihon">●メールアドレス</font></td>
                  <td class="kihon"> <font size="3">
                    <input type="text" name="madd" value="<%=RS("MM_mail_adr")%>" MAXLENGTH="100" size=40>
                    </font></td>
                </tr>
                <tr>
                  <td><font size="3" class="kihon">●会社名</font></td>
                  <td class="kihon"> <font size="3">
                    <input type="text" name="c_name" value="<%=RS("MM_c_name")%>" MAXLENGTH="100" size=40>
                    </font></td>
                </tr>
                <tr>
                  <td><font size="3" class="kihon">●パスワード</font></td>
				  <td><font size="3" class="kihon"><input TYPE="checkbox" NAME="passchk" VALUE="-1">変更する場合はチェック　</font></td>
                </tr>
                <tr>
                  <td><font size="3" class="kihon"></font></td>
                  <td class="kihon"> <font size="3">
                    <input type="password" name="pwd1" MAXLENGTH="15">
                    </font></td>
                </tr>
                <tr>
                  <td ALIGN="right"><font size="3" class="kihon">確認</font></td>
                  <td class="kihon"> <font size="3">
                    <input type="password" name="pwd2" MAXLENGTH="15">
                    </font></td>
                </tr>
              </table>
              <br>
              <div align="center">
                <input type="submit" name="Submit" value=" 更新 ">
                <input type="reset" name="Submit2" value="リセット">
              </div>
            </form>
            <p>　</p>
              <table align=center width=300>
                <TR>
                  <TD>　
                  </TD>
                  <TD>　
                  </TD>
                </TR>
                <TR>
                  <TD width=100><FONT size="3" class="kihon" valign="top">●振込用銀行口座</FONT></TD>
                  <TD class="kihon" valign="top" width=200><FONT size="3" class="kihon">確認・更新されたい方は<A HREF="mailto:office@melmel.net">事務局までメール</A>でお問い合わせください。　</FONT>
                    </TD>
                </TR>
              </table>
            </td>
        </tr>
      </table>
<!------------------ 本文textboxテーブル・ここまで ------------------------------>
      <p>　 </p>
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
<%
	End if
End if
%>
