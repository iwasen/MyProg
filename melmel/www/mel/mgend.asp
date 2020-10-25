<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
If Session("mel_id") = "" then
	Response.Redirect "./false.html"
end if

if Session("mag_id") = "" then
	Response.Redirect "./false.html"
end if

'=== Save Session ===
Session("mms_melonpan") = Request.Form("mms_melonpan")
Session("mms_mag") = Request.Form("mms_mag")
Session("mms_pubz") = Request.Form("mms_pubz")
Session("mms_melma") = Request.Form("mms_melma")
Session("mms_macky") = Request.Form("mms_macky")
Session("mms_kapu") = Request.Form("mms_kapu")
Session("mms_emag") = Request.Form("mms_emag")
Session("mms_tengoku") = Request.Form("mms_tengoku")
Session("mms_tiara") = Request.Form("mms_tiara")
Session("mms_merubox") = Request.Form("mms_merubox")
Session("mms_dokuji") = Request.Form("mms_dokuji")
Session("mms_other") = Request.Form("mms_other")
Session("cat_int") = Request.Form("cat_int")
Session("cat_pcc") = Request.Form("cat_pcc")
Session("cat_soft") = Request.Form("cat_soft")
Session("cat_mvl") = Request.Form("cat_mvl")
Session("cat_bns") = Request.Form("cat_bns")
Session("cat_seiji") = Request.Form("cat_seiji")
Session("cat_kabu") = Request.Form("cat_kabu")
Session("cat_fsn") = Request.Form("cat_fsn")
Session("cat_lif") = Request.Form("cat_lif")
Session("cat_shumi") = Request.Form("cat_shumi")
Session("cat_res") = Request.Form("cat_res")
Session("cat_tvl") = Request.Form("cat_tvl")
Session("cat_spt") = Request.Form("cat_spt")
Session("cat_gamble") = Request.Form("cat_gamble")
Session("cat_hlt") = Request.Form("cat_hlt")
Session("cat_art") = Request.Form("cat_art")
Session("cat_shp") = Request.Form("cat_shp")
Session("cat_ent") = Request.Form("cat_ent")
Session("cat_game") = Request.Form("cat_game")
Session("cat_movie") = Request.Form("cat_movie")
Session("cat_uranai") = Request.Form("cat_uranai")
Session("cat_std") = Request.Form("cat_std")
Session("cat_shikaku") = Request.Form("cat_shikaku")
Session("cat_gogaku") = Request.Form("cat_gogaku")
Session("cat_nws") = Request.Form("cat_nws")
Session("cat_zatsu") = Request.Form("cat_zatsu")
Session("cat_kenshou") = Request.Form("cat_kenshou")
Session("cat_homepage") = Request.Form("cat_homepage")
Session("cat_hitokoto") = Request.Form("cat_hitokoto")
Session("cat_other") = Request.Form("cat_other")
Session("age_10m") = Request.Form("age_10m")
Session("age_10f") = Request.Form("age_10f")
Session("age_20m") = Request.Form("age_20m")
Session("age_20f") = Request.Form("age_20f")
Session("age_30m") = Request.Form("age_30m")
Session("age_30f") = Request.Form("age_30f")
Session("age_40m") = Request.Form("age_40m")
Session("age_40f") = Request.Form("age_40f")
Session("age_50m") = Request.Form("age_50m")
Session("age_50f") = Request.Form("age_50f")
Session("shk_stu") = Request.Form("shk_stu")
Session("shk_enp") = Request.Form("shk_enp")
Session("shk_syf") = Request.Form("shk_syf")
Session("shk_other") = Request.Form("shk_other")
Session("are_ht") = Request.Form("are_ht")
Session("are_kt") = Request.Form("are_kt")
Session("are_cb") = Request.Form("are_cb")
Session("are_kk") = Request.Form("are_kk")
Session("are_cs") = Request.Form("are_cs")
Session("are_ks") = Request.Form("are_ks")
Session("are_fr") = Request.Form("are_fr")
Session("are_zk") = Request.Form("are_zk")

Dim DB, RS, SQL
Dim TITLE, MSG, Result

Set DB = OpenDB()
SQL = "SELECT * FROM T_MELMAG WHERE MG_mag_id='" & Session("mag_id") & "'"
Set RS = Server.CreateObject("ADODB.RecordSet")
RS.Open SQL, DB, adOpenKeyset, adLockPessimistic

If RS.EOF Then
	'--- Repeated Entry ---
	Response.Write "エラーが発生しました。<BR>"
	Response.Write (OFFICE_ADDRESS)
	Response.Write "<BR>に問い合わせ下さい。"
	
Else

	RS("MG_m_name") = Session("m_name")
  RS("MG_busuu") = Number(Session("busuu"))
  RS("MG_hindo") = Session("hindo")
	RS("MG_mms_melonpan") = CheckBox(Session("mms_melonpan"))
	RS("MG_mms_mag") = CheckBox(Session("mms_mag"))
	RS("MG_mms_pubz") = CheckBox(Session("mms_pubz"))
	RS("MG_mms_melma") = CheckBox(Session("mms_melma"))
	RS("MG_mms_macky") = CheckBox(Session("mms_macky"))
	RS("MG_mms_kapu") = CheckBox(Session("mms_kapu"))
	RS("MG_mms_emag") = CheckBox(Session("mms_emag"))
	RS("MG_mms_tengoku") = CheckBox(Session("mms_tengoku"))
	RS("MG_mms_tiara") = CheckBox(Session("mms_tiara"))
	RS("MG_mms_merubox") = CheckBox(Session("mms_merubox"))
	RS("MG_mms_dokuji") = CheckBox(Session("mms_dokuji"))
	RS("MG_mms_other") = CheckBox(Session("mms_other"))
	RS("MG_cat_int") = CheckBox(Session("cat_int"))
	RS("MG_cat_pcc") = CheckBox(Session("cat_pcc"))
	RS("MG_cat_soft") = CheckBox(Session("cat_soft"))
	RS("MG_cat_mvl") = CheckBox(Session("cat_mvl"))
	RS("MG_cat_bns") = CheckBox(Session("cat_bns"))
	RS("MG_cat_seiji") = CheckBox(Session("cat_seiji"))
	RS("MG_cat_kabu") = CheckBox(Session("cat_kabu"))
	RS("MG_cat_fsn") = CheckBox(Session("cat_fsn"))
	RS("MG_cat_lif") = CheckBox(Session("cat_lif"))
	RS("MG_cat_shumi") = CheckBox(Session("cat_shumi"))
	RS("MG_cat_res") = CheckBox(Session("cat_res"))
	RS("MG_cat_tvl") = CheckBox(Session("cat_tvl"))
	RS("MG_cat_spt") = CheckBox(Session("cat_spt"))
	RS("MG_cat_gamble") = CheckBox(Session("cat_gamble"))
	RS("MG_cat_hlt") = CheckBox(Session("cat_hlt"))
	RS("MG_cat_art") = CheckBox(Session("cat_art"))
	RS("MG_cat_shp") = CheckBox(Session("cat_shp"))
	RS("MG_cat_ent") = CheckBox(Session("cat_ent"))
	RS("MG_cat_game") = CheckBox(Session("cat_game"))
	RS("MG_cat_movie") = CheckBox(Session("cat_movie"))
	RS("MG_cat_uranai") = CheckBox(Session("cat_uranai"))
	RS("MG_cat_std") = CheckBox(Session("cat_std"))
	RS("MG_cat_shikaku") = CheckBox(Session("cat_shikaku"))
	RS("MG_cat_gogaku") = CheckBox(Session("cat_gogaku"))
	RS("MG_cat_nws") = CheckBox(Session("cat_nws"))
	RS("MG_cat_zatsu") = CheckBox(Session("cat_zatsu"))
	RS("MG_cat_kenshou") = CheckBox(Session("cat_kenshou"))
	RS("MG_cat_homepage") = CheckBox(Session("cat_homepage"))
	RS("MG_cat_hitokoto") = CheckBox(Session("cat_hitokoto"))
	RS("MG_cat_other") = CheckBox(Session("cat_other"))
	RS("MG_age_10m") = CheckBox(Session("age_10m"))
	RS("MG_age_10f") = CheckBox(Session("age_10f"))
	RS("MG_age_20m") = CheckBox(Session("age_20m"))
	RS("MG_age_20f") = CheckBox(Session("age_20f"))
	RS("MG_age_30m") = CheckBox(Session("age_30m"))
	RS("MG_age_30f") = CheckBox(Session("age_30f"))
	RS("MG_age_40m") = CheckBox(Session("age_40m"))
	RS("MG_age_40f") = CheckBox(Session("age_40f"))
	RS("MG_age_50m") = CheckBox(Session("age_50m"))
	RS("MG_age_50f") = CheckBox(Session("age_50f"))
	RS("MG_shk_stu") = CheckBox(Session("shk_stu"))
	RS("MG_shk_enp") = CheckBox(Session("shk_enp"))
	RS("MG_shk_syf") = CheckBox(Session("shk_syf"))
	RS("MG_shk_other") = CheckBox(Session("shk_other"))
	RS("MG_are_ht") = CheckBox(Session("are_ht"))
	RS("MG_are_kt") = CheckBox(Session("are_kt"))
	RS("MG_are_cb") = CheckBox(Session("are_cb"))
	RS("MG_are_kk") = CheckBox(Session("are_kk"))
	RS("MG_are_cs") = CheckBox(Session("are_cs"))
	RS("MG_are_ks") = CheckBox(Session("are_ks"))
	RS("MG_are_fr") = CheckBox(Session("are_fr"))
	RS("MG_are_zk") = CheckBox(Session("are_zk"))
	RS("MG_last_update") = Now()
  RS("MG_keisen_id") = Session("keisen_id")
  RS("MG_moji_suu") = Session("moji_suu")
  RS("MG_koudoku_url") = Session("koudoku_url")
  RS("MG_shousai_url") = Session("shousai_url")
  RS("MG_shoukai_text") = Session("shoukai_text")
  RS("MG_shoukai_flag1") = Session("shoukai_flag1")
  RS("MG_status") = Session("status")
	RS.Update
%>

<html>
<head>
<title>めるめる・net メルマガ登録情報更新完了</title>
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
          <TD><A href="pubtop.html"><IMG src="../images/tab_hakko1b.gif" width="130" height="25" alt="発行者向けトップページ" border="0"></A></TD>
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
          <td><a href="pointhst.asp"><img src="images/b_memb10.gif" width="112" height="34" alt="ポイント履歴" border="0"></a></td>
        </tr>
        <TR align="center">
          <TD><A href="regedit.asp"><IMG src="images/b_memb1.gif" width="112" height="34" alt="登録情報修正" border="0"></A></TD>
        </TR>
        <TR align="center">
          <TD><IMG src="images/b_memb3b.gif" width="112" height="34" alt="メルマガ情報修正"></TD>
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
            <P align="center"><IMG src="images/t_mgend.gif" width="213" height="23" alt="メルマガ登録情報更新完了"><BR>
              <IMG name="chara06" src="../images/chara06.gif" width="78" height="64" border="0" alt="キャラクターイメージ">
            </P>
            <P align="center"><FONT size="4" class="ohmidashi">更新しました。ありがとうございました。</FONT></P>
            <P align="center"><FONT size="3" class="komoji"><A href="pubtop.asp">めるめるさん専用トップページに戻る</A></FONT></P>
          </TD>
        </TR>
      </TABLE>
<!------------------ 本文textboxテーブル・ここまで ------------------------------>
      <P>　 </P>
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
<%
End if
%>
