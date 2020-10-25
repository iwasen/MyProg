<%@ page contentType="text/html; charset=Shift_JIS" %>
<%@ page import="base.*" %>
<jsp:useBean id="SearchDbBeanId" scope="request" class="xxx.ClickStreamBean" />
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
<!---　リンク文字スタイル定義　--->
<style>
.linkcolor_blue { color:blue;font-weight:bold;text-decoration:none; }
span a:hover { color:red }
</style>
<!---　リンク文字スタイル定義　ここまで　--->
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
<!---　コーナー選択JS　--->
<script language="Javascript">
<!---
function init_disp() {
  for (var i = 0; i < 5; i++)
    change_corner(i);
<%
String errMsg = SelectCondBeanId.getStreamErrMsg();
if (errMsg.length() != 0) {
%>
  alert("<%=errMsg%>");
<%
}
%>
<%
if (SelectCondBeanId.getStreamCounting() != 0) {
%>
  if (navigator.userAgent.indexOf("MSIE") != -1)
    window.showModelessDialog("ClickStreamFC.jsp", window, "dialogWidth:550px;dialogHeight:200px");
  else {
    var gWindowWidth = 550;
    var gWindowHeight = 200;
    var st = 'width=' + gWindowWidth;
    st += ',';
    st += 'height=' + gWindowHeight;
    st += ',';
    var cw = (screen.width / 2) - (gWindowWidth / 2);
    var ch = (screen.height / 2) - (gWindowHeight / 2);
    st += 'screenX=' + cw;
    st += ',';
    st += 'screenY=' + ch;
    window.open("ClickStreamFC.jsp", "ClickStreamFC", st);
  }
<%
}
%>
}
function translate_corner(n) {
  var f = document.form1;
  if (f["streamUrl" + n].value == "") {
    alert("URLを入力してください。");
    f["streamUrl" + n].focus();
    return;
  }
  f.transCorner.value = n;
  f.submit();
}
function change_corner(n) {
  var f = document.form1;
  var corner = f["streamCorner" + n].value;
  var ary = corner.split(":");
  f["lowerDisp" + n].disabled = (ary[0] == "1" || ary[1] == "0");
  f["upperDisp" + n].disabled = (ary[1] <= "1");
}
function lower_corner(n) {
  var f = document.form1;
  if (f["streamCorner" + n].value == "") {
    alert("コーナーを選択してください。");
    f["streamCorner" + n].focus();
    return;
  }
  f.lowerCorner.value = n;
  f.submit();
}
function upper_corner(n) {
  var f = document.form1;
  f.upperCorner.value = n;
  f.submit();
}
function check_disp() {
  var f = document.form1;
  var sel = false;
  for (var i = 0; i < 5; i++) {
    if (f["streamCorner" + i].selectedIndex != 0)
      sel = true;
  }
  if (!sel) {
    alert("コーナーを選択してください。");
    f.streamCorner0.focus();
    return false;
  }
  if (f.streamCorner0.selectedIndex == 0 && f.streamCorner4.selectedIndex == 0) {
    sel = false;
    for (var i = 1; i < 4; i++) {
      if (f["streamCorner" + i].selectedIndex != 0) {
        if (sel) {
          alert("始点・終点を指定しない場合は、中間点を複数指定することはできません。");
          f["streamCorner" + i].focus();
          return false;
        }
        sel = true;
      }
    }
  }
  if (!checkDay())
    return false;
  f.mode.value = 3;
  return true;
}
function check_table_id() {
  var f = document.form1;
  if (f.streamTableID.value == "") {
    alert("処理番号を入力してください。");
    f.streamTableID.focus();
    return false;
  }
  if (!num_chk(f.streamTableID.value)) {
    alert("処理番号には数字のみを入力してください。");
    f.streamTableID.focus();
    return false;
  }
  f.mode.value = 3;
  return true;
}

function to_stream_history() {
  var gWindowWidth = 350;
  var gWindowHeight = 500;
  var st = 'width=' + gWindowWidth;
  st += ',';
  st += 'height=' + gWindowHeight;
  st += ',';
  var cw = (screen.width / 2) - (gWindowWidth / 2);
  var ch = (screen.height / 2) - (gWindowHeight / 2);
  if (navigator.userAgent.indexOf("MSIE") != -1) {
    st += 'left=' + cw;
  } else {
    st += 'screenX=' + cw;
  }
  st += ',';
  if (navigator.userAgent.indexOf("MSIE") != -1) {
    st += 'top=' + ch;
  } else {
    st += 'screenY=' + ch;
  }
  st += ',';
  st += 'scrollbars=1';
  window.open("ClickStreamHistory.jsp", "ClickStreamHistory", st);
}

function change_level() {
  document.form1.submit();
}
function num_chk(v1) {
	var p1 = v1;
	for (i=0; i<p1.length; i++){
		if (p1.substring(i,i+1)<"0" || p1.substring(i,i+1)>"9") {
			return false;
		}
	}
	return true;
}
//--->
</script>
<!---　コーナー選択　ここまで　--->
</head>

<body bgcolor="#FFFFFF" text="#000000"  leftmargin="10" topmargin="10" onLoad="document.form1.reset();init_disp();MM_preloadImages('images/btn_viewB.gif','images/btn_tmenu_kiteichiB.gif','images/btn_tmenu_gyou_co_bunpuB.gif','images/btn_tmenu_gyou_suiiB.gif','images/btn_tmenu_gyou_graphB.gif','images/btn_tmenu_bumon_gyou_suiiB.gif','images/btn_tmenu_bumon_gyou_graphB.gif','images/btn_tmenu_bumon_gyou_suco_bunpuB.gif','images/btn_tmenu_ac_moto_graphB.gif','images/btn_tmenu_ac_saki_graphB.gif','images/btn_tmenu_co_gyou_bunpuB.gif','images/btn_tmenu_co_suiiB.gif','images/btn_tmenu_co_graphB.gif','images/btn_tmenu_bumon_toku_suiiB.gif','images/btn_tmenu_bumon_toku_graphB.gif','images/btn_tmenu_kaishi_pageB.gif','images/btn_tmenu_syuuryou_pageB.gif')">
<form method="post" action="AllAccess" name="form1">
<!-- hidden部分 -->
<input type="hidden" name="page" value="<jsp:getProperty name="SelectCondBeanId" property="page" />">
<input type="hidden" name="mode" value="<jsp:getProperty name="SelectCondBeanId" property="mode" />">
<input type="hidden" name="table" value="<jsp:getProperty name="SelectCondBeanId" property="table" />">
<input type="hidden" name="init" value="0">
<input type="hidden" name="transCorner" value="">
<input type="hidden" name="lowerCorner" value="">
<input type="hidden" name="upperCorner" value="">
<!-- hidden部分 ここまで -->
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
<td align="left" class="size20"><b><font color="#333333" face="ＭＳ Ｐゴシック, Osaka">クリックストリームランキング</font></b></td>
</tr>
</table>
<%
// 条件指定部分の表示
if ((SelectCondBeanId.getMode() & SelectCondBeanId.modeCondition) != 0) {
%>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table width="720" border="0" cellpadding="0" cellspacing="0">
<tr>
<td class="size09">このページではあるコーナーからあるコーナーまでの遷移パターンを順位順に表示します。<br>
表示は日単位で行いますので、表示設定が終わりましたら表示ボタンをクリックして下さい。</td>
<td width="70" align="right" class="size09">　</td>
</tr>
</table>
<%@ include file="CondAttribute.jsp" %>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left" valign="middle"><img src="images/txt_shiten_sentaku.gif" width="71" height="20"></td>
<td width="30">　 </td>
<td width="40"><img src="images/txt_url.gif" width="40" height="20"> </td>
<td width="150">
<input type="text" name="streamUrl0" style="width:150px" value="<%=CommonFunc.escapeHtml(SelectCondBeanId.getStreamUrl(0))%>">
</td>
<td width="10">　 </td>
<td width="50">
<input type="button" value="変換→" onClick="translate_corner(0)" style="width:50px">
</td>
<td width="10">　 </td>
<td align="left" width="150">
<%=CornerSelect.getStreamCornerSelect(db, SelectCondBeanId, 0)%>
</td>
<td align="left" width="10">　 </td>
<td align="left">
<input type="button" name="lowerDisp0" value="下位を表示" onClick="lower_corner(0)" style="width:70px">
<input type="button" name="upperDisp0" value="上位を表示" onClick="upper_corner(0)" style="width:70px">
</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left" valign="middle"><img src="images/txt_chuukanten_sentaku.gif" width="85" height="20"></td>
<td width="30"><img src="images/txt_1.gif" width="30" height="20"></td>
<td width="40"><img src="images/txt_url.gif" width="40" height="20"> </td>
<td width="150">
<input type="text" name="streamUrl1" style="width:150px" value="<%=CommonFunc.escapeHtml(SelectCondBeanId.getStreamUrl(1))%>">
</td>
<td width="10">　 </td>
<td width="50">
<input type="button" value="変換→" onClick="translate_corner(1)" style="width:50px">
</td>
<td width="10">　 </td>
<td align="left" width="150">
<%=CornerSelect.getStreamCornerSelect(db, SelectCondBeanId, 1)%>
</td>
<td align="left" width="10">　 </td>
<td align="left">
<input type="button" name="lowerDisp1" value="下位を表示" onClick="lower_corner(1)" style="width:70px">
<input type="button" name="upperDisp1" value="上位を表示" onClick="upper_corner(1)" style="width:70px">
</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left" valign="middle">　</td>
<td width="30"><img src="images/txt_2.gif" width="30" height="20">
</td>
<td width="40"><img src="images/txt_url.gif" width="40" height="20"> </td>
<td width="150">
<input type="text" name="streamUrl2" style="width:150px" value="<%=CommonFunc.escapeHtml(SelectCondBeanId.getStreamUrl(2))%>">
</td>
<td width="10">　 </td>
<td width="50">
<input type="button" value="変換→" onClick="translate_corner(2)" style="width:50px">
</td>
<td width="10">　 </td>
<td align="left" width="150">
<%=CornerSelect.getStreamCornerSelect(db, SelectCondBeanId, 2)%>
</td>
<td align="left" width="10">　 </td>
<td align="left">
<input type="button" name="lowerDisp2" value="下位を表示" onClick="lower_corner(2)" style="width:70px">
<input type="button" name="upperDisp2" value="上位を表示" onClick="upper_corner(2)" style="width:70px">
</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left" valign="middle">　</td>
<td width="30"><img src="images/txt_3.gif" width="30" height="20">
</td>
<td width="40"><img src="images/txt_url.gif" width="40" height="20"> </td>
<td width="150">
<input type="text" name="streamUrl3" style="width:150px" value="<%=CommonFunc.escapeHtml(SelectCondBeanId.getStreamUrl(3))%>">
</td>
<td width="10">　 </td>
<td width="50">
<input type="button" value="変換→" onClick="translate_corner(3)" style="width:50px">
</td>
<td width="10">　 </td>
<td align="left" width="150">
<%=CornerSelect.getStreamCornerSelect(db, SelectCondBeanId, 3)%>
</td>
<td align="left" width="10">　 </td>
<td align="left">
<input type="button" name="lowerDisp3" value="下位を表示" onClick="lower_corner(3)" style="width:70px">
<input type="button" name="upperDisp3" value="上位を表示" onClick="upper_corner(3)" style="width:70px">
</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left" valign="middle"><img src="images/txt_syuten_sentaku.gif" width="71" height="20"></td>
<td width="30">　 </td>
<td width="40"><img src="images/txt_url.gif" width="40" height="20"> </td>
<td width="150">
<input type="text" name="streamUrl4" style="width:150px" value="<%=CommonFunc.escapeHtml(SelectCondBeanId.getStreamUrl(4))%>">
</td>
<td width="10">　 </td>
<td width="50">
<input type="button" value="変換→" onClick="translate_corner(4)" style="width:50px">
</td>
<td width="10">　 </td>
<td align="left" width="150">
<%=CornerSelect.getStreamCornerSelect(db, SelectCondBeanId, 4)%>
</td>
<td align="left" width="10">　 </td>
<td align="left">
<input type="button" name="lowerDisp4" value="下位を表示" onClick="lower_corner(4)" style="width:70px">
<input type="button" name="upperDisp4" value="上位を表示" onClick="upper_corner(4)" style="width:70px">
</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left"><img src="images/txt_kikan_shitei.gif"> </td>
<td width="55" align="left">
<%= DateSelect.getSelectYear(db, "DATE", SelectCondBeanId.getDayFromY(), "dayFromY", 55) %>
</td>
<td width="24" align="left"><img src="images/txt_nen.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectMonth(SelectCondBeanId.getDayFromM(), "dayFromM", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_tsuki1.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectDay(SelectCondBeanId.getDayFromD(), "dayFromD", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_nichi1.gif"></td>
<td width="24" align="left"><img src="images/txt_kara.gif"></td>
<td width="55" align="left">
<%= DateSelect.getSelectYear(db, "DATE", SelectCondBeanId.getDayToY(), "dayToY", 55) %>
</td>
<td width="24" align="left"><img src="images/txt_nen.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectMonth(SelectCondBeanId.getDayToM(), "dayToM", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_tsuki1.gif"></td>
<td width="40" align="left">
<%= DateSelect.getSelectDay(SelectCondBeanId.getDayToD(), "dayToD", 40) %>
</td>
<td width="24" align="left"><img src="images/txt_nichi1.gif"></td>
<td width="72" class="size09" align="right">・・・・・・・・・・・・</td>
<td align="right" width="70">
<input type="submit" name="ToDay" value="表　示" style="width:90px" onClick="return check_disp()">
</td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100"></td>
<td class="size09"><input type="checkbox" name="streamRecycle" value="1" checked>同一条件の集計結果が存在する場合、それを利用する。</td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="100" align="left" valign="middle"><img src="images/txt_syoribangou_shitei.gif" width="85" height="20"></td>
<td class="size09"><input type="text" name="streamTableID" size="10" value="<%=SelectCondBeanId.getStreamTableID()%>"></td>
<td width="72" class="size09" align="right">・・・・・・・・・・・・</td>
<td align="right" width="200">
<input type="submit" name="ToStreamTableID" value="表　示" style="width:90px" onClick="return check_table_id()">　<input type="button" value="履歴参照" style="width:90px" onClick="to_stream_history();">
</td>
</tr>
</table>
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
<td width="65" align="left" class="size09"><img src="images/txt_kigyou_zokuseiS.gif" width="65" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left" width="340"><%=SelectCondBeanId.getCondAttribute(db)%></td>
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
<td class="size09" align="left">期間：<%=SelectCondBeanId.getDateSpan()%></td>
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
<td class="size09" align="left"><b>順位</b></td>
</tr>
</table>
<table width="720" height="5" border="0" cellpadding="0" cellspacing="0">
<tr>
<td> </td>
</tr>
</table>
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="65" align="left" class="size09"><img src="images/txt_yokojjiku.gif" width="65" height="18"></td>
<td width="5">　</td>
<td class="size09" align="left"><b>コーナー毎</b></td>
<td width="250"> </td>
<td width="65"><img src="images/txt_hyouji_levelS.gif" width="65" height="18"></td>
<td width="5"> </td>
<td>
<%=LevelSelect.getLevelSelect(SelectCondBeanId.getStreamDispLevel(), "streamDispLevel")%>
</td>
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
</form>
</body>
</html>