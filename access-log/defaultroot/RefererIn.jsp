<%@ page contentType="text/html; charset=Shift_JIS" %>
<%@ page import="base.*" %>
<jsp:useBean id="SearchDbBeanId" scope="request" class="xxx.RefererInBean" />
<jsp:useBean id="SelectCondBeanId" scope="session" class="base.SelectCondBean" />
<jsp:useBean id="db" scope="request" class="base.DataBase" />
<html>
<head>
<title>xxx Business Analyst 発見くん</title>
<meta name="author" content="Kuni Tojima/tojima@xxxx.co.jp">
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Pragma" content="no-cache">
<script src='Script.js'></script>
<link rel="stylesheet" href="table.css" type="text/css">
<link rel="stylesheet" href="size.css" type="text/css">
<!---　スワップイメージJS　--->
<script language="JavaScript">
<!--
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
// -->

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
//-->
</script>
<!---　スワップイメージJS　ここまで　--->
</head>

<body bgcolor="#FFFFFF" text="#000000"  leftmargin="10" topmargin="10" onLoad="document.form1.reset();MM_preloadImages('images/btn_viewB.gif','images/btn_tmenu_kiteichiB.gif','images/btn_tmenu_gyou_co_bunpuB.gif','images/btn_tmenu_gyou_suiiB.gif','images/btn_tmenu_gyou_graphB.gif','images/btn_tmenu_bumon_gyou_suiiB.gif','images/btn_tmenu_bumon_gyou_graphB.gif','images/btn_tmenu_bumon_gyou_suco_bunpuB.gif','images/btn_tmenu_ac_saki_graphB.gif','images/btn_tmenu_co_gyou_bunpuB.gif','images/btn_tmenu_co_suiiB.gif','images/btn_tmenu_co_graphB.gif','images/btn_tmenu_bumon_toku_suiiB.gif','images/btn_tmenu_bumon_toku_graphB.gif','images/btn_tmenu_streamB.gif','images/btn_tmenu_kaishi_pageB.gif','images/btn_tmenu_syuuryou_pageB.gif')">
<form method="post" action="AllAccess" name="form1">
<!---　ヘッダ部分　--->
<!---　ナビゲーターテーブル　ここから　--->
<%=NavigatorGenerate.getNavigator(SelectCondBeanId)%>
<!---　ナビゲーターテーブル　ここまで　--->
<table width="720" border="0" cellspacing="0" cellpadding="0" height="5">
<tr>
<td> </td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_header_home.gif" height="6">
<tr>
<td> </td>
</tr>
</table>
<!---　ヘッダ部分 ここまで　--->
<!---　表示部分　--->
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left" class="size20"><b><font color="#333333" face="ＭＳ Ｐゴシック, Osaka">アクセス元ランキンググラフ</font></b></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="720" border="0" cellpadding="0" cellspacing="0">
<tr>
<td class="size09">このページでは指定したコーナーやページに来る直前のページや検索エンジンをアクセス数順にグラフ表示します。<br>
<%
// 説明文の表示
if ((SelectCondBeanId.getMode() & SelectCondBeanId.modeCondition) != 0) {
%>
表示は日単位・時間単位で行えますので、それぞれの表示ボタンをクリックして下さい。
<%
} // 説明文の表示 ここまで
%>
</td>
<td width="70" align="right" class="size09">　</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left"><img src="images/txt_taisyo_sentaku.gif"></td>
<td align="left">
<%=CornerSelect.getMoveToCornerSelect(db, SelectCondBeanId, "moveToCorner")%>
</td>
<td width="20" align="left" class="size09">　</td>
<td width="600" align="left" class="size09">
<%
// 対象コーナー(URL)の表示期限
if (SelectCondBeanId.getMoveToCorner().length() != 0) {
%>
<b>左記対象の表示期限は<%=SelectCondBeanId.getMoveToLimit(db)%>までです。</b>
<%
} // 対象コーナー(URL)の表示期限 ここまで
%>
</td>
</tr>
</table>
<%
// 対象コーナー(URL)選択済みチェック
if (SelectCondBeanId.getMoveToCorner().length() != 0) {
%>
<%
// 条件指定部分の表示
if ((SelectCondBeanId.getMode() & SelectCondBeanId.modeCondition) != 0) {
%>
<%@ include file="CondDateMWD.jsp" %>
<%
} // 条件指定部分の表示 ここまで
%>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<%
// 結果テーブル部分の表示
if ((SelectCondBeanId.getMode() & SelectCondBeanId.modeResult) != 0) {
%>
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_footer_home.gif" height="3">
<tr>
<td> </td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_taisyou_sentakuS.gif" width="65" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getMoveToCornerName(db)%></b></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_kikan_shiteiS.gif" width="65" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><b><%=SelectCondBeanId.getDateSpec()%>で表示</b>┃期間：<%=SelectCondBeanId.getDateSpan()%></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_tatejjiku.gif" width="65" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><b>コーナー名･ＵＲＬ</b></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#00156C">
<jsp:getProperty name="SearchDbBeanId" property="dbdata" />
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td class="size08" align="left">※青ボールド文字はリンクです。</td>
</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="70">
<input type="submit" name="Back" value="前の20件" style="width:70"<jsp:getProperty name="SearchDbBeanId" property="disableBackPage" />>
</td>
<td width="10">　</td>
<td width="70">
<input type="submit" name="Foward" value="次の20件" style="width:70"<jsp:getProperty name="SearchDbBeanId" property="disableForwardPage" />>
</td>
</tr>
</table>
<%
} // 結果テーブル部分の表示 ここまで
%>
<%
} // 対象コーナー(URL)選択済みチェック ここまで
%>
<table width="720" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="5"> </td>
</tr>
</table>
<!---　表示部分　ここまで　--->
<!---　フッタ部分　--->
<div align="center">
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="images/tile_footer_home.gif" height="6">
<tr>
<td> </td>
</tr>
</table>
</div>
<!---　フッタ部分　ここまで　--->
<!-- hidden部分 -->
<input type="hidden" name="page" value="<jsp:getProperty name="SelectCondBeanId" property="page" />">
<input type="hidden" name="mode" value="<jsp:getProperty name="SelectCondBeanId" property="mode" />">
<input type="hidden" name="init" value="0">
<!-- hidden部分 ここまで -->
</form>
</body>
</html>