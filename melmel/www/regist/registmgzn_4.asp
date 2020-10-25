<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
'=== Save Session ===
Session("h_name") = Request.Form("h_name")
Session("madd") = Request.Form("madd")
Session("m_name") = Request.Form("m_name")
Session("c_name") = Request.Form("c_name")
Session("busuu") = Request.Form("busuu")
Session("hindo") = Request.Form("hindo")
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
Session("password") = Request.Form("password")
Session("keisen_id") = Request.Form("keisen_id")
Session("moji_suu") = Request.Form("moji_suu")
Session("hpown") = Request.Form("hpown")
Session("koudoku_url") = Request.Form("koudoku_url")
Session("shousai_url") = Request.Form("shousai_url")
Session("shoukai_text") = Left(Request.Form("shoukai_text"), 250)
Session("shoukai_flag1") = Request.Form("shoukai_flag1")
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
Session("bk_acc") = Request.Form("bk_acc")
Session("bk_nm") = Request.Form("bk_nm")
Session("bk_st") = Request.Form("bk_st")
Session("bk_knd") = Request.Form("bk_knd")
Session("bk_knm") = Request.Form("bk_knm")
Session("melonpan_id") = Request.Form("melonpan_id")
Session("mgmg_id") = Request.Form("mgmg_id")
Session("seq") = Request.Form("seq")
Session("mel_id") = Request.Form("mel_id")
%>
<html>

<head>
<title>入力情報確認ページ</title>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
</head>

<body topmargin="0" bgcolor="#FFFFFF">

<div align="center">
  <center>
  <table border="0" cellpadding="0" cellspacing="0" width="100%" height="20" bgcolor="#FF80C0">
    <tr>
      <td>

<p align="center"><strong><big><font color="#FFFFFF">確認ページ</font></big></strong></p>
      </td>
    </tr>
  </table>
  </center>
</div>
<div align="center">　<div align="center"><center>

<table border="1" width="550" height="175" cellspacing="0" bordercolor="#FF80C0" bordercolorlight="#FF80C0" bordercolordark="#FF80C0" cellpadding="3">
  <tr>
    <td width="170" height="7"><span style="font-size: 11pt"><small>■発行者の名前：</small></span></td>
    <td width="370" height="7"><font color="#000080"><span style="font-size: 11pt"> <%=Session("h_name")%> </span></font></td>
  </tr>
  <tr>
    <td width="170" height="7"><span style="font-size: 11pt"><small>■発行者のe-mailアドレス：</small></span></td>
    <td width="370" height="7"><font color="#000080"><span style="font-size: 11pt"> <%=Session("madd")%> </span></font></td>
  </tr>
  <tr>
    <td width="170" height="7"><span style="font-size: 11pt"><small>■</small><small>メルマガ名：</small></span></td>
    <td width="370" height="7"><font color="#000080"><span style="font-size: 11pt"> <%=Session("m_name")%> </span></font></td>
  </tr>
  <tr>
    <td width="170" height="7"><span style="font-size: 11pt"><small>■会社名（法人の場合）：</small></span></td>
    <td width="370" height="7"><font color="#000080"><span style="font-size: 11pt"> <%=Session("c_name")%> </span></font></td>
  </tr>
</table>
</center></div>

<p align="center"><small>上記の内容を確認し、よければ下の”送信”ボタンをクリックしてください。</small></p>
<div align="center">

<p align="center"><small>訂正する場合はブラウザのバックボタンで戻って修正してください。</small></p>

<form method="POST" ACTION="registmgzn_5.asp">
  <div align="center"><center>
  <p><input type="submit" value="　送信　"></p>
  </center></div>
</form>

</div></div>
<div align="center">
  <center>
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td>
        <hr size="1" color="#FF80C0">
      </td>
    </tr>
    <tr>
      <td height="20">
        <p align="center"><font size="1" color="#000000">めるめる・net事務局</font></td>
    </tr>
  </table>
  </center>
</div>
</body>
</html>
