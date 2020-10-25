<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
Session.abandon
%>

<html>
<head>
<title>めるめる・net 発行者向けトップページ</title>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
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
<body bgcolor="#ff9999" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/tab_koukoku1b.gif','images/tab_top1.gif')">
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td colspan="3">
      <table bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <tr><!-- Shim row, height 1. -->
          <td><img border="0" height="1" src="images/shim.gif" width="252"></td>
          <td><img border="0" height="1" src="images/shim.gif" width="348"></td>
          <td><img border="0" height="1" src="images/shim.gif" width="1"></td>
        </tr>
        <tr valign="top"><!-- row 1 -->
          <td rowspan="3"><a href="../index.html"><img alt="めるめる・net ロゴ" border="0" height="96" name="head_logo" src="../images/head_logo.gif" width="252"></a></td>
          <td><img border="0" height="26" src="../images/shim.gif" width="348"></td>
          <td><img border="0" height="26" src="../images/shim.gif" width="1"></td>
        </tr>
        <tr valign="top"><!-- row 2 -->
          <td><img alt="発行者向けページ" border="0" height="52" name="head_t1" src="../images/head_t1.gif" width="348"></td>
          <td><img border="0" height="52" src="../images/shim.gif" width="1"></td>
        </tr>
        <tr valign="top"><!-- row 3 -->
          <td><img border="0" height="18" src="../images/shim.gif" width="348"></td>
          <td><img border="0" height="18" src="../images/shim.gif" width="1"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td valign="top" colspan="3" align="left">
    </td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#ffff00"><img height="10" src="../images/shim.gif" width="130"></td>
    <td valign="top" bgcolor="#ffff00"><img height="10" src="../images/shim.gif" width="461"></td>
    <td valign="top"><img height="10" src="../images/shadow5.gif" width="9"></td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#ffffff" colspan="2">
      <p align="center"><br>
        <img alt="ログイン" id="IMG1" src="images/t_login.gif" WIDTH="72" HEIGHT="23"><br>
      </p>
      <form name="form1" action="pubtop.asp" method="post">
        <table border="0" cellspacing="5" cellpadding="0" align="center">
          <tr>
            <td>
              <div align="right"><font size="3" class="kihon">
            <div align="right"><font class="kihon" size="3">発行者ID（半角数字５桁）：</font></div></font></div>
            </td>
            <td>
              <input name="mel_id">
            </td>
            <td rowspan="3"><img alt="キャラクターイメージ" border="0" height="154" name="chara10" src="../images/chara13.gif" width="100"></td>
          </tr>
          <tr>
            <td>
              <div align="right"><font size="3" class="kihon">パスワード：</font></div>
            </td>
            <td>
              <input type="password" name="pwd">
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <div align="center">
                <p><span class="kihon"><font size="3" class="kihon">
                  <input type="submit" name="Submit2" value="ログイン">
                  <input type="reset" name="Submit" value="リセット">
                  </font></span></p>
              </div>
            </td>
          </tr>
        </table>
        <div align="center">
          <p>　 </p>
        </div>
      </form>
      <p></p>
    </td>
    <td background="images/shadow2.gif"><img height="300" src="../images/shim.gif" width="9"></td>
  </tr>
  <tr>
    <td bgcolor="#ffff00"><img height="10" src="../images/shim.gif" width="130"></td>
    <td bgcolor="#ffff00"><img height="10" src="../images/shim.gif" width="461"></td>
    <td background="images/shadow2.gif"><img height="10" src="../images/shim.gif" width="9"></td>
  </tr>
  <tr>
    <td height="9" colspan="3"><img height="9" src="../images/shadow6.gif" width="600"></td>
  </tr>
  <tr>
    <td colspan="3">
      <table bgcolor="#ff9999" border="0" cellpadding="0" cellspacing="0" width="600" align="center">
        <tr><!-- Shim row, height 1. -->
          <td><img border="0" height="1" src="../images/shim.gif" width="147"></td>
          <td><img border="0" height="1" src="../images/shim.gif" width="66"></td>
          <td><img border="0" height="1" src="../images/shim.gif" width="28"></td>
          <td><img border="0" height="1" src="../images/shim.gif" width="155"></td>
          <td><img border="0" height="1" src="../images/shim.gif" width="204"></td>
          <td><img border="0" height="1" src="../images/shim.gif" width="1"></td>
        </tr>
        <tr valign="top"><!-- row 1 -->
          <td colspan="3"><img border="0" height="15" src="../images/shim.gif" width="241"></td>
          <td rowspan="2"><img alt="めるめる・net ロゴ" border="0" height="42" name="logo_s" src="../images/logo_s.gif" width="155"></td>
          <td rowspan="2"><img border="0" height="42" src="../images/shim.gif" width="204"></td>
          <td><img border="0" height="15" src="../images/shim.gif" width="1"></td>
        </tr>
        <tr valign="top"><!-- row 2 -->
          <td rowspan="2"><img border="0" height="49" src="../images/shim.gif" width="147"></td>
          <td rowspan="2"><img alt="めるめる イメージ" border="0" height="49" name="cara_s" src="../images/cara_s.gif" width="66"></td>
          <td><img border="0" height="27" src="../images/shim.gif" width="28"></td>
          <td><img border="0" height="27" src="../images/shim.gif" width="1"></td>
        </tr>
        <tr valign="top"><!-- row 3 -->
          <td colspan="3" height="22" valign="center"> <font size="2" class="komoji">Copyright
            (C) <%=Year(Date)%> xx xxxxx Inc. All rights reserved.</font></td>
          <td><img border="0" height="22" src="images/shim.gif" width="1"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>

</html>
