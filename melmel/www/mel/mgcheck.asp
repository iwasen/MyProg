<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
If Session("mel_id") = "" then
	Response.Redirect "./false.html"
end if

if Session("mag_id") = "" then
	Response.Redirect "./false.html"
end if

Session("m_name") = Request.Form("m_name")
Session("busuu") = Request.Form("busuu")
Session("hindo") = Request.Form("hindo")

if Session("m_name") = "" or IsNumeric(Session("busuu")) = false or Session("hindo") = "" then
	Response.Redirect "./mailfalse.html"
End if

Session("keisen_id") = Request.Form("keisen_id")
Session("moji_suu") = Request.Form("moji_suu")
Session("koudoku_url") = Request.Form("koudoku_url")
Session("shousai_url") = Request.Form("shousai_url")
Session("shoukai_text") = Left(Request.Form("shoukai_text"), 250)
Session("shoukai_flag1") = Request.Form("shoukai_flag1")

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
Session("melonpan_id") = Request.Form("melonpan_id")
Session("mgmg_id") = Request.Form("mgmg_id")
Session("status") = Request.Form("status")

%>

<html>
<head>
<title>めるめる・net 更新内容確認</title>
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
function OnSubmit_form1(form) {
  var cnt = 0;
  if (form.cat_int.checked)
    cnt++;
  if (form.cat_pcc.checked)
    cnt++;
  if (form.cat_soft.checked)
    cnt++;
  if (form.cat_mvl.checked)
    cnt++;
  if (form.cat_bns.checked)
    cnt++;
  if (form.cat_seiji.checked)
    cnt++;
  if (form.cat_kabu.checked)
    cnt++;
  if (form.cat_fsn.checked)
    cnt++;
  if (form.cat_lif.checked)
    cnt++;
  if (form.cat_shumi.checked)
    cnt++;
  if (form.cat_res.checked)
    cnt++;
  if (form.cat_tvl.checked)
    cnt++;
  if (form.cat_spt.checked)
    cnt++;
  if (form.cat_gamble.checked)
    cnt++;
  if (form.cat_hlt.checked)
    cnt++;
  if (form.cat_art.checked)
    cnt++;
  if (form.cat_shp.checked)
    cnt++;
  if (form.cat_ent.checked)
    cnt++;
  if (form.cat_game.checked)
    cnt++;
  if (form.cat_movie.checked)
    cnt++;
  if (form.cat_uranai.checked)
    cnt++;
  if (form.cat_std.checked)
    cnt++;
  if (form.cat_shikaku.checked)
    cnt++;
  if (form.cat_gogaku.checked)
    cnt++;
  if (form.cat_nws.checked)
    cnt++;
  if (form.cat_zatsu.checked)
    cnt++;
  if (form.cat_kenshou.checked)
    cnt++;
  if (form.cat_homepage.checked)
    cnt++;
  if (form.cat_hitokoto.checked)
    cnt++;
  if (form.cat_other.checked)
    cnt++;
  if (cnt > 2) {
    alert("メルマガカテゴリは２つ以内で選択してください。")
    return false;
  }
	return true;
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
            <P align="center"><IMG src="images/t_mgcheck.gif" width="196" height="24" alt="メールマガジン登録情報"><BR>
              <IMG name="chara04" src="../images/chara04.gif" width="102" height="56" border="0" alt="キャラクターイメージ">
            </P>
            <P align="center"><font class="kihon">以下の通り、登録情報を更新します。<BR>
              <IMG src="../images/border_y.gif" width="200" height="3"> </FONT></P>
            <FORM method="post" action="./mgend.asp" onSubmit="return OnSubmit_form1(this)">
              <TABLE width="451" border="0" cellspacing="0" cellpadding="3">
                <TR>
                  <TD>
                    <TABLE width="451" border="0" cellspacing="0" cellpadding="0">
                      <TR>
                        <TD width=100><font class="kihon"><SPAN class="kihon">●メルマガID</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon"><%=Session("mag_id")%></SPAN></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●メルマガ名</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon"><%=Session("m_name")%></SPAN></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●発行頻度</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon"><%=Session("hindo")%></SPAN></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●発行部数</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon"><%=Session("busuu")%></SPAN></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●広告罫線</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon">
<%
Dim DB, SQL, RS
Set DB = OpenDB()
SQL = "SELECT KE_start_line FROM T_KEISEN WHERE KE_keisen_id='" & Session("keisen_id") & "'"
Set RS = DB.Execute(SQL)
If Not RS.EOF Then
  Response.Write ReplaceTag(ZenkakuRight(RS(0), 22))
End If
RS.Close
%>
                        </SPAN></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●１行の文字数</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon">全角<%=Session("moji_suu")%>文字</SPAN></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●メルマガ紹介文</SPAN></FONT></TD>
                        <TD><font class="kihon"><textarea name="shoukai_text" cols=44 rows=5 readonly><%=Session("shoukai_text")%></textarea></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●購読用ＵＲＬ</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon"><%=Session("koudoku_url")%></SPAN></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●詳細説明用ＵＲＬ</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon"><%=Session("shousai_url")%></SPAN></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●読者向けページ</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon"><%If Session("shoukai_flag1") = "1" Then Response.Write "掲載を希望します" Else Response.Write "掲載を希望しません"%></SPAN></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●めろんぱんID</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon"><%=Session("melonpan_id")%></SPAN></FONT></TD>
                      </TR>
                      <TR>
                        <TD><font class="kihon"><SPAN class="kihon">●まぐまぐID</SPAN></FONT></TD>
                        <TD><font class="kihon"><SPAN class="kihon"><%=Session("mgmg_id")%></SPAN></FONT></TD>
                      </TR>
                    </TABLE>
                    <font class="kihon">●発行スタンド<BR></FONT>
                    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width="100%">
                      <TR>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_melonpan" value="1"<%=Checked(Session("mms_melonpan"))%>>めろんぱん</td>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_mag" value="1"<%=Checked(Session("mms_mag"))%>>まぐまぐ</td>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_pubz" value="1"<%=Checked(Session("mms_pubz"))%>>Pubzine</td>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_melma" value="1"<%=Checked(Session("mms_melma"))%>>melma!</td>
                      </TR>
                      <TR>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_macky" value="1"<%=Checked(Session("mms_macky"))%>>Macky</td>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_kapu" value="1"<%=Checked(Session("mms_kapu"))%>>カプライト</td>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_emag" value="1"<%=Checked(Session("mms_emag"))%>>E-MAGAZINE</td>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_tengoku" value="1"<%=Checked(Session("mms_tengoku"))%>>メルマガ天国</td>
                      </TR>
                      <TR>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_tiara" value="1"<%=Checked(Session("mms_tiara"))%>>ﾃｨｱﾗｵﾝﾗｲﾝ</td>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_merubox" value="1"<%=Checked(Session("mms_merubox"))%>>MERU-BOX</td>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_dokuji" value="1"<%=Checked(Session("mms_dokuji"))%>>独自配信</td>
                        <td width="25%" class="kihon"><input type="checkbox" name="mms_other" value="1"<%=Checked(Session("mms_other"))%>>その他</td>
                      </TR>
                    </TABLE><BR>
                    <font class="kihon">●メルマガカテゴリ<BR></FONT>
                    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 width="100%">
                      <TR>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_int" value="1"<%=Checked(Session("cat_int"))%>>インターネット</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_pcc" value="1"<%=Checked(Session("cat_pcc"))%>><nobr>パソコン/コンピュータ全般</nobr></td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_soft" value="1"<%=Checked(Session("cat_soft"))%>><nobr>ソフトウェア/プログラミング</nobr></td>
                      </TR>
                      <TR>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_mvl" value="1"<%=Checked(Session("cat_mvl"))%>>携帯/モバイル</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_bns" value="1"<%=Checked(Session("cat_bns"))%>>ビジネス全般</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_seiji" value="1"<%=Checked(Session("cat_seiji"))%>>政治/経済</td>
                      </TR>
                      <TR>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_kabu" value="1"<%=Checked(Session("cat_kabu"))%>>株式/投資</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_fsn" value="1"<%=Checked(Session("cat_fsn"))%>>ファッション/美容</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_lif" value="1"<%=Checked(Session("cat_lif"))%>>暮らし/日記</td>
                      </TR>
                      <TR>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_shumi" value="1"<%=Checked(Session("cat_shumi"))%>>趣味</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_res" value="1"<%=Checked(Session("cat_res"))%>>グルメ/レストラン</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_tvl" value="1"<%=Checked(Session("cat_tvl"))%>>旅行/地域</td>
                      </TR>
                      <TR>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_spt" value="1"<%=Checked(Session("cat_spt"))%>>スポーツ/レジャー</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_gamble" value="1"<%=Checked(Session("cat_gamble"))%>>ギャンブル</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_hlt" value="1"<%=Checked(Session("cat_hlt"))%>>健康/医療/育児</td>
                      </TR>
                      <TR>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_art" value="1"<%=Checked(Session("cat_art"))%>>アート/文芸</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_shp" value="1"<%=Checked(Session("cat_shp"))%>>ショッピング</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_ent" value="1"<%=Checked(Session("cat_ent"))%>>エンターテイメント</td>
                      </TR>
                      <TR>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_game" value="1"<%=Checked(Session("cat_game"))%>>ゲーム/クイズ</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_movie" value="1"<%=Checked(Session("cat_movie"))%>>映画/音楽/芸能</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_uranai" value="1"<%=Checked(Session("cat_uranai"))%>>占い</td>
                      </TR>
                      <TR>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_std" value="1"<%=Checked(Session("cat_std"))%>>学習/教育</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_shikaku" value="1"<%=Checked(Session("cat_shikaku"))%>>資格</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_gogaku" value="1"<%=Checked(Session("cat_gogaku"))%>>語学</td>
                      </TR>
                      <TR>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_nws" value="1"<%=Checked(Session("cat_nws"))%>>ニュース</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_zatsu" value="1"<%=Checked(Session("cat_zatsu"))%>>雑学</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_kenshou" value="1"<%=Checked(Session("cat_kenshou"))%>>懸賞/プレゼント</td>
                      </TR>
                      <TR>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_homepage" value="1"<%=Checked(Session("cat_homepage"))%>>ホームページ紹介</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_hitokoto" value="1"<%=Checked(Session("cat_hitokoto"))%>>一日ひとこと系</td>
                        <td width="33%" class="kihon"><input type="checkbox" name="cat_other" value="1"<%=Checked(Session("cat_other"))%>>その他</td>
                      </TR>
                    </TABLE><BR>
                    <font class="kihon">●読者層（年代）<BR></FONT>
                    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                      <TR>
                        <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_10m" value="1"<%=Checked(Session("age_10m"))%>>10代男</td>
                        <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_20m" value="1"<%=Checked(Session("age_20m"))%>>20代男</td>
                        <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_30m" value="1"<%=Checked(Session("age_30m"))%>>30代男</td>
                        <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_40m" value="1"<%=Checked(Session("age_40m"))%>>40代男</td>
                        <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_50m" value="1"<%=Checked(Session("age_50m"))%>>50代以上男</td>
                      </TR>
                      <TR>
                        <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_10f" value="1"<%=Checked(Session("age_10f"))%>>10代女</td>
                        <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_20f" value="1"<%=Checked(Session("age_20f"))%>>20代女</td>
                        <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_30f" value="1"<%=Checked(Session("age_30f"))%>>30代女</td>
                        <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_40f" value="1"<%=Checked(Session("age_40f"))%>>40代女</td>
                        <td width=90 class="kihon"><INPUT TYPE="checkbox" NAME="age_50f" value="1"<%=Checked(Session("age_50f"))%>>50代以上女</td>
                      </TR>
                    </TABLE><BR>
                    <font class="kihon">●読者層（職業）<BR></FONT>
                    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                      <TR>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="shk_stu" VALUE="1"<%=Checked(Session("shk_stu"))%>>学生</td>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="shk_enp" VALUE="1"<%=Checked(Session("shk_enp"))%>>社会人</td>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="shk_syf" VALUE="1"<%=Checked(Session("shk_syf"))%>>主婦</td>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="shk_other" VALUE="1"<%=Checked(Session("shk_other"))%>>その他</td>
                      </TR>
                    </TABLE><BR>

                    <font class="kihon">●読者層（エリア）<BR></FONT>

                    <TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0>
                      <TR>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_ht" value="1"<%=Checked(Session("are_ht"))%>>北海道/東北</td>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_kt" value="1"<%=Checked(Session("are_kt"))%>>関東</td>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_cb" value="1"<%=Checked(Session("are_cb"))%>>中部</td>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_kk" value="1"<%=Checked(Session("are_kk"))%>>近畿</td>
                      </TR>
                      <TR>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_cs" value="1"<%=Checked(Session("are_cs"))%>>中国/四国</td>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_ks" value="1"<%=Checked(Session("are_ks"))%>>九州</td>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_fr" value="1"<%=Checked(Session("are_fr"))%>>海外</td>
                        <td width=100 class="kihon"><INPUT TYPE="checkbox" NAME="are_zk" value="1"<%=Checked(Session("are_zk"))%>>全国</td>
                      </TR>
                    </TABLE>
				          </TD>
                </TR>
                <TR>
                  <TD><font class="kihon"><SPAN class="kihon">●メルマガ活動状態</SPAN></FONT>&nbsp;
                  <font class="kihon"><SPAN class="kihon">
<%
Select Case Session("status")
Case 0
  Response.Write "活動中"
Case 1
  Response.Write "休止中"
Case 2
  Response.Write "廃刊"
End Select
%></SPAN></FONT></TD>
                </TR>
              </TABLE>
              <DIV align="center"><BR>
                <font class="kihon">
                <INPUT type="submit" name="Submit" value=" ＯＫ ">
                <INPUT type="button" value="戻る" onClick="history.back()">
                </FONT></DIV>
            </FORM>
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
