<?php
include("../inc/com_db_func.inc");
include("../sub/head.inc");
include("../sub/csub.inc");

/****************************************************************
 Main
****************************************************************/
//$gConn = db_conxxxt();

if ($mail_addr != "") {pass_check($gConn,trim($mail_addr),trim($reader_pswd));}
if ($mail_addr == "") {
	$mail_addr = $cookie_mail_addr;
	$reader_pswd = $cookie_reader_pswd;
	$reader_id = $cookie_reader_id;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>海外発行者さんのリレー連載</title>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<META NAME="author" content="株式会社ドゥ･ハウス">
<META NAME="description" content="１５人の海外在住メルマガ発行者さんによるリレー連載で、世界を巡るこのコーナー。住んでいなくちゃわからない（！）楽しい記事を世界各地からお届けしています。">
<META NAME="keywords" content=" 海外生活,海外,メルマガ,連載,リレー,世界旅行,旅,メールマガジン"> 
<style type="text/css">
<!--
.comment {  font-size: 12px; line-height: 125%; color: #000000}
.hakkousya {  font-size: 12px; line-height: 115%}
a:link {  text-decoration: none; color: #0000FF}
a:hover {  color: #FF0000; text-decoration: underline}
a:active {  color: #FF0000; text-decoration: underline}
.travel {  font-size: 14px; line-height: 125%; font-weight: bold; color: #68a01e}
-->
</style>
<script language="JavaScript">
<!--
function subWin(wURL) { //v2.0
  window.open(wURL,'relay','width=420,height=500,scrollbars=yes,resizable=yes');
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_displayStatusMsg(msgStr) { //v1.0
  status=msgStr;
  document.MM_returnValue = true;
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('img/ny_2.gif','img/ca_2.gif','img/at_2.gif','img/holland_2.gif','img/manche_2.gif','img/london_2.gif','img/france_2.gif','img/hangary_2.gif','img/china_2.gif','img/au_2.gif','img/nz_2.gif','img/hawaii_2.gif','img/canada_2.gif')">
<?php draw_head("..");	?>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="25"><img src="img/spacer.gif" width="25" height="10"></td>
    <td width="725">　</td>
  </tr>
  <tr> 
    <td width="25">　</td>
    <td width="725"> 
      <div align="center"><img src="img/title.gif" width="310" height="82"> </div>
    </td>
  </tr>
  <tr> 
    <td width="25"><img src="img/spacer.gif" width="25" height="10"></td>
    <td width="725">　</td>
  </tr>
  <tr> 
    <td width="25">　</td>
    <td width="725"> 
      <table width="500" border="0" cellspacing="5" cellpadding="2" align="center" class="comment">
        <tr> 
          <td align="center" class="comment" colspan="2"> 
            <p><b>１５人の海外在住発行者さんによるリレー連載で、世界を巡るこのコーナー。<br>
              住んでいなくちゃわからない（！）楽しい記事を世界各地からお届けしています。</b></p>
          </td>
        </tr>
        <tr> 
          <td class="comment" nowrap valign="top"> 
            <div align="right">※</div>
          </td>
          <td class="comment">この連載は、公式マガジン『めろんぱんレター』の中で掲載しております。<br>
            めろんぱんレターのご購読は<a href="http://www.melonpan.net/letter/letterbaqn.php" target="_blank">こちら</a>から 
          </td>
        </tr>
        <tr> 
          <td align="left" class="comment" colspan="2"> 
            <p><font color="#FFFF99">●</font>→　この発行者さんが発行しているメルマガです。<br><font color="#e2f0c9">●</font>→　リレー連載記事のバックナンバーです。</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td width="25"><img src="img/spacer.gif" width="25" height="20"></td>
    <td width="725"> 
      <hr noshade>
    </td>
  </tr>
  <tr> 
    <td width="25">　</td>
    <td width="725">　</td>
  </tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25"><img src="img/spacer.gif" width="25" height="8"></td>
    <td width="200"> 
      <div align="center"><img src="img/travel.gif" width="194" height="29"></div>
    </td>
    <td width="62"> 
      <div align="center"></div>
    </td>
    <td width="200"> 
      <table width="200" border="0" cellpadding="0" class="hakkousya" cellspacing="0">
        <tr valign="middle"> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ny','','img/ny_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_usa.gif" width="37" height="25" border="0"></a></td>
          <td width="163">アメリカ・ニューヨーク</td>
          <td>　</td>
        </tr>
        <tr> 
          <td bgcolor="#FFFF99" width="37"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td bgcolor="#FFFF99" width="163"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001019" target="_blank">ニューヨーク生活情報<br>
            ◆◆◆ハーレム日記◆◆◆</a></td>
          <td bgcolor="#FFFF99"><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td bgcolor="#e2f0c9" width="37"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay1.html')">1月15日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
    </td>
    <td width="63"> 
      <div align="center"><img src="img/next1.gif" width="26" height="15" align="middle"></div>
    </td>
    <td width="200"> 
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ca','','img/ca_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_usa.gif" width="37" height="25" border="0"></a></td>
          <td width="163">アメリカ・カリフォルニア</td>
          <td>　</td>
        </tr>
        <tr> 
          <td valign="middle" bgcolor="#FFFF99" width="37"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"> 
            </div>
          </td>
          <td width="163" bgcolor="#FFFF99"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002148" target="_blank">カリフォルニア通信</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td bgcolor="#e2f0c9" width="37"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay2.html')">1月18日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td width="25">　</td>
    <td width="200">　</td>
    <td width="62"><img src="img/spacer.gif" width="62" height="1"></td>
    <td width="200">　</td>
    <td width="63"><img src="img/spacer.gif" width="63" height="8"></td>
    <td width="200">
      <div align="center"><img src="img/next2.gif" width="15" height="26" vspace="5"></div>
    </td>
  </tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="25"><img src="img/spacer.gif" width="25" height="1"></td>
    <td width="200"> <img src="img/spacer.gif" width="200" height="1"></td>
    <td width="325"><img src="img/spacer.gif" width="325" height="1"></td>
    <td width="200"><img src="img/spacer.gif" width="200" height="1"></td>
  </tr>
  <tr>
    <td width="25">　</td>
    <td width="200" valign="bottom"> 
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('canada','','img/canada_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_canada.gif" width="37" height="25" border="0"></a></td>
          <td width="163"><a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('canada','','img/canada_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"></a>カナダ</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000416" target="_blank">国際結婚☆バカップル日記</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9">
            <div align="right"><a href="javascript:subWin('relay15.html')">3月5日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
      <div align="center"> <img src="img/next4.gif" width="16" height="27" vspace="4"><br>
      </div>
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('hawaii','','img/hawaii_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_usa.gif" width="37" height="25" border="0"></a></td>
          <td width="163">アメリカ・ハワイ</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001124" target="_blank">Jasminが毎日使っている英語</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay14.html')">3月1日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
      <div align="center"> <img src="img/next4.gif" width="16" height="27" vspace="4"> 
        <br>
      </div>
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('hawaii','','img/hawaii_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_usa.gif" width="37" height="25" border="0"></a></td>
          <td width="163"><a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('hawaii','','img/hawaii_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"></a>アメリカ・ハワイ</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001309" target="_blank">アロハフリーク</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay13.html')">2月26日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
      <div align="center"> <img src="img/next4.gif" width="16" height="27" vspace="4"> 
        <br>
      </div>
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('nz','','img/nz_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_nz.gif" width="37" height="25" border="0"></a></td>
          <td width="163">ニュージーランド</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002040" target="_blank">怪しいニュージーランド</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay12.html')">2月22日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
      <div align="center"><img src="img/next4.gif" width="16" height="27" vspace="4">
        <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
          <tr> 
            <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('au','','img/au_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_au.gif" width="37" height="25" border="0"></a></td>
            <td width="163">オーストラリア</td>
            <td>　</td>
          </tr>
          <tr> 
            <td width="37" bgcolor="#FFFF99"> 
              <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
            </td>
            <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001912" target="_blank">***Live 
              in Sydney***</a></td>
            <td><img src="img/spacer.gif" width="1" height="32"></td>
          </tr>
          <tr> 
            <td width="37" bgcolor="#e2f0c9"> 
              <div align="center"></div>
            </td>
            <td width="163" bgcolor="#e2f0c9"> 
              <div align="right"><a href="javascript:subWin('relay11.html')">2月19日連載記事を読む</a></div>
            </td>
            <td>　</td>
          </tr>
        </table>
      </div>
    </td>
    <td width="325" valign="middle"> 
      <div align="center"><span class="comment"><b>▼国旗にマウスを乗せるとその国の場所がわかります。</b></span><br>
      </div>
      <table border="0" cellpadding="0" cellspacing="0" width="325">
        <tr> 
          <td><img src="img/spacer.gif" width="61" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="10" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="13" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="6" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="4" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="14" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="5" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="12" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="6" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="8" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="15" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="2" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="8" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="14" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="3" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="14" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="27" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="14" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="3" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="14" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="17" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="17" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="38" height="1" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="1" border="0"></td>
        </tr>
        <tr> 
          <td colspan="23"><img src="img/no_point_r1_c1.gif" width="325" height="41" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="41" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="2" colspan="18"><img src="img/no_point_r2_c1.gif" width="236" height="20" border="0"></td>
          <td rowspan="2" colspan="2"><img name="canada" border="0" src="img/canada_1.gif" width="17" height="20"></td>
          <td colspan="3"><img src="img/no_point_r2_c21.gif" width="72" height="7" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="7" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="2"><img src="img/no_point_r3_c21.gif" width="17" height="15" border="0"></td>
          <td rowspan="4"><img name="ny" border="0" src="img/ny_1.gif" width="17" height="20"></td>
          <td rowspan="22"><img src="img/no_point_r3_c23.gif" width="38" height="324" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="13" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="2" colspan="17"><img src="img/no_point_r4_c1.gif" width="222" height="6" border="0"></td>
          <td rowspan="4" colspan="2"><img name="ca" border="0" src="img/ca_1.gif" width="17" height="20"></td>
          <td rowspan="21"><img src="img/no_point_r4_c20.gif" width="14" height="311" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="2" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="5"><img name="at" border="0" src="img/at_1.gif" width="17" height="20"></td>
          <td><img src="img/spacer.gif" width="1" height="4" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="11" colspan="6"><img src="img/no_point_r6_c1.gif" width="108" height="159" border="0"></td>
          <td rowspan="5" colspan="2"><img name="china" border="0" src="img/china_1.gif" width="17" height="20"></td>
          <td rowspan="3" colspan="9"><img src="img/no_point_r6_c9.gif" width="97" height="15" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="1" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="18"><img src="img/no_point_r7_c22.gif" width="17" height="304" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="13" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="17" colspan="2"><img src="img/no_point_r8_c18.gif" width="17" height="291" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="1" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="4" colspan="6"><img src="img/no_point_r9_c9.gif" width="53" height="25" border="0"></td>
          <td rowspan="3" colspan="2"><img name="hawaii" border="0" src="img/hawaii_1.gif" width="17" height="20"></td>
          <td rowspan="16"><img src="img/no_point_r9_c17.gif" width="27" height="290" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="1" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="15"><img src="img/no_point_r10_c21.gif" width="17" height="289" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="4" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="7" colspan="2"><img src="img/no_point_r11_c7.gif" width="17" height="154" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="15" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="2" colspan="2"><img src="img/no_point_r12_c15.gif" width="17" height="20" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="5" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="7" colspan="2"><img src="img/no_point_r13_c9.gif" width="14" height="156" border="0"></td>
          <td rowspan="2" colspan="2"><img name="au" border="0" src="img/au_1.gif" width="17" height="20"></td>
          <td colspan="2"><img src="img/no_point_r13_c13.gif" width="22" height="15" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="15" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="11"><img src="img/no_point_r14_c13.gif" width="8" height="250" border="0"></td>
          <td rowspan="2" colspan="2"><img name="nz" border="0" src="img/nz_1.gif" width="17" height="20"></td>
          <td rowspan="11"><img src="img/no_point_r14_c16.gif" width="14" height="250" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="5" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="5" colspan="2"><img src="img/no_point_r15_c11.gif" width="17" height="136" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="15" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="9" colspan="2"><img src="img/no_point_r16_c14.gif" width="17" height="230" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="84" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="8"><img src="img/no_point_r17_c1.gif" width="61" height="146" border="0"></td>
          <td rowspan="2" colspan="2"><img name="manche" border="0" src="img/manche_1.gif" width="23" height="23"></td>
          <td colspan="3"><img src="img/no_point_r17_c4.gif" width="24" height="15" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="15" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="4"><img src="img/no_point_r18_c4.gif" width="6" height="31" border="0"></td>
          <td rowspan="3" colspan="3"><img name="holland" border="0" src="img/holland_1.gif" width="23" height="23"></td>
          <td rowspan="7"><img src="img/no_point_r18_c8.gif" width="12" height="131" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="8" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="3" colspan="2"><img name="london" border="0" src="img/london_1.gif" width="23" height="23"></td>
          <td><img src="img/spacer.gif" width="1" height="14" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="5"><img src="img/no_point_r20_c9.gif" width="6" height="109" border="0"></td>
          <td rowspan="3" colspan="2"><img name="hangary" border="0" src="img/hangary_1.gif" width="23" height="23"></td>
          <td rowspan="5"><img src="img/no_point_r20_c12.gif" width="2" height="109" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="1" border="0"></td>
        </tr>
        <tr> 
          <td colspan="3"><img src="img/no_point_r21_c5.gif" width="23" height="8" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="8" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="3"><img src="img/no_point_r22_c2.gif" width="10" height="100" border="0"></td>
          <td rowspan="2" colspan="3"><img name="france" border="0" src="img/france_1.gif" width="23" height="23"></td>
          <td rowspan="3" colspan="2"><img src="img/no_point_r22_c6.gif" width="19" height="100" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="14" border="0"></td>
        </tr>
        <tr> 
          <td rowspan="2" colspan="2"><img src="img/no_point_r23_c10.gif" width="23" height="86" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="9" border="0"></td>
        </tr>
        <tr> 
          <td colspan="3"><img src="img/no_point_r24_c3.gif" width="23" height="77" border="0"></td>
          <td><img src="img/spacer.gif" width="1" height="77" border="0"></td>
        </tr>
      </table>
    </td>
    <td width="200" valign="top"> 
      <div align="center">
        <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
          <tr> 
            <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('at','','img/at_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_usa.gif" width="37" height="25" border="0"></a></td>
            <td width="163">アメリカ ・アトランタ</td>
            <td>　</td>
          </tr>
          <tr> 
            <td width="37" bgcolor="#FFFF99"> 
              <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
            </td>
            <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001898" target="_blank">English 
              for idiots to experts to idiots</a></td>
            <td><img src="img/spacer.gif" width="1" height="32"></td>
          </tr>
          <tr> 
            <td width="37" bgcolor="#e2f0c9"> 
              <div align="center"></div>
            </td>
            <td width="163" bgcolor="#e2f0c9"> 
              <div align="right"><a href="javascript:subWin('relay3.html')">1月22日連載記事を読む</a></div>
            </td>
            <td>　</td>
          </tr>
        </table>
        <img src="img/next2.gif" width="15" height="26" vspace="5"> 
      </div>
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('holland','','img/holland_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_holland.gif" width="37" height="25" border="0"></a></td>
          <td width="163">オランダ</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001989" target="_blank">「オランダや」通信　from 
            オランダ</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay4.html')">1月25日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
      <div align="center"><img src="img/next2.gif" width="15" height="26" vspace="5"><br>
      </div>
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('manche','','img/manche_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_uk.gif" width="37" height="25" border="0"></a></td>
          <td width="163">イギリス・マンチェスター</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001467" target="_blank">uplift 
            mail magazine アートから世界へ、諦めより行動を！</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay5.html')">1月29日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
      <div align="center"><img src="img/next2.gif" width="15" height="26" vspace="5"> 
        <br>
      </div>
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('london','','img/london_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_uk.gif" width="37" height="25" border="0"></a></td>
          <td width="163">イギリス・ロンドン</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000638" target="_blank">【情報と感動の交換　EiiE.】</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay6.html')">2月1日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
      <div align="center"><img src="img/next2.gif" width="15" height="26" vspace="5"> 
        <br>
      </div>
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('france','','img/france_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_france.gif" width="37" height="25" border="0"></a></td>
          <td width="163"><a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('france','','img/france_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"></a>フランス・ニース</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000975" target="_blank">フランスの片隅から</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay7.html')">2月5日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
      <div align="center"></div>
      </td>
  </tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="25">　</td>
    <td width="200">
      <div align="center"><img src="img/next4.gif" width="16" height="27" vspace="4"></div>
    </td>
    <td width="62"><img src="img/spacer.gif" width="62" height="1"></td>
    <td width="200">　</td>
    <td width="63"><img src="img/spacer.gif" width="63" height="8"></td>
    <td width="200"> 
      <div align="center"><img src="img/next2.gif" width="15" height="26" vspace="5"></div>
    </td>
  </tr>
  <tr> 
    <td width="25"><img src="img/spacer.gif" width="25" height="8"></td>
    <td width="200"> 
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('china','','img/china_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_china.gif" width="37" height="25" border="0"></a></td>
          <td width="163"><a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('china','','img/china_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"></a>中華人民共和国</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=001340" target="_blank">＜＜ 
            WEB_熱線 ＞＞<br>
            ＝市井から見た中国＝</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay10.html')">2月15日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
    </td>
    <td width="62"> 
      <div align="center"><img src="img/next3.gif" width="27" height="16" align="middle"></div>
    </td>
    <td width="200"> 
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('hangary','','img/hangary_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_hangary.gif" width="37" height="25" border="0"></a></td>
          <td width="163"><a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('hangary','','img/hangary_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"></a>ハンガリー</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"> <a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=000685" target="_blank">我が愛すべきハンガリーのジレンマ</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay9.html')">2月12日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
    </td>
    <td width="63"> 
      <div align="center"><img src="img/next3.gif" width="27" height="16" align="middle"></div>
    </td>
    <td width="200"> 
      <table width="200" border="0" cellspacing="0" cellpadding="0" class="hakkousya">
        <tr> 
          <td width="37"> <a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('france','','img/france_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"><img src="img/f_france.gif" width="37" height="25" border="0"></a></td>
          <td width="163"><a href="javascript:;" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('france','','img/france_2.gif',1);MM_displayStatusMsg('地図に場所を示します。');return document.MM_returnValue"></a>フランス・パリ</td>
          <td>　</td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#FFFF99"> 
            <div align="center"><img src="img/mag.gif" width="32" height="24"></div>
          </td>
          <td width="163" bgcolor="#FFFF99"><a href="http://www.melonpan.net/melonpa/mag-detail.php?mag_id=002001" target="_blank">フランス食いかじり☆現地発！パリのおいしい生活☆</a></td>
          <td><img src="img/spacer.gif" width="1" height="32"></td>
        </tr>
        <tr> 
          <td width="37" bgcolor="#e2f0c9"> 
            <div align="center"></div>
          </td>
          <td width="163" bgcolor="#e2f0c9"> 
            <div align="right"><a href="javascript:subWin('relay8.html')">2月7日連載記事を読む</a></div>
          </td>
          <td>　</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="25"><img src="img/spacer.gif" width="25" height="30"></td>
    <td>　</td>
  </tr>
  <tr> 
    <td width="25">　</td>
    <td class="comment"> 
      <div align="center">※<a href="../index.php">めろんぱん</a>は、（株）○○○○○○の事業理念「発信する生活者を支援する」に基づいて運営されています。</div>
    </td>
  </tr>
  <tr> 
    <td width="25">　</td>
    <td class="comment"> 
      <hr noshade width="75%">
    </td>
  </tr>
  <tr>
    <td width="25">　</td>
    <td class="comment">
      <div align="center">Copyright(c)2001 xxxxxxx, Co., Ltd. All rights reserved.<br>
        <a href="http://www.xxxxxxx.co.jp/guide/xxxxxxx.html" target="_blank"><img src="../common/do_rogo.gif" width="40" height="40" border="0"></a></div>
    </td>
  </tr>
</table>
