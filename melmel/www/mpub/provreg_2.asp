<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
Dim day_time,madd,h_name,m_name,c_name,busuu,hindo,mel_id
Dim mms_melonpan, mms_mag, mms_pubz, mms_melma, mms_macky, mms_kapu, mms_emag, mms_tengoku, mms_tiara, mms_merubox, mms_dokuji, mms_other
Dim cat_int, cat_pcc, cat_soft, cat_mvl, cat_bns, cat_seiji, cat_kabu, cat_fsn, cat_lif, cat_shumi, cat_res, cat_tvl, cat_spt, cat_gamble, cat_hlt, cat_art, cat_shp, cat_ent, cat_game, cat_movie, cat_uranai, cat_std, cat_shikaku, cat_gogaku, cat_nws, cat_zatsu, cat_kenshou, cat_homepage, cat_hitokoto, cat_other
Dim age_10m,age_10f,age_20m,age_20f,age_30m,age_30f,age_40m,age_40f,age_50m,age_50f,shk_stu,shk_enp,shk_syf,shk_other
Dim are_ht,are_kt,are_cb,are_kk,are_cs,are_ks,are_fr,are_zk,email,melonpan_id,mgmg_id
Dim DB, RS, SQL

'POSTデータ取得
mel_id = Request.Form("mel_id")
madd = Request.Form("madd")
h_name = Request.Form("h_name")
m_name = Request.Form("m_name")
c_name = Request.Form("c_name")
busuu = Request.Form("busuu")
hindo = Request.Form("hindo")
melonpan_id = Request.Form("melonpan_id")
mgmg_id = Request.Form("mgmg_id")
mms_melonpan = Request.Form("mms_melonpan")
mms_mag = Request.Form("mms_mag")
mms_pubz = Request.Form("mms_pubz")
mms_melma = Request.Form("mms_melma")
mms_macky = Request.Form("mms_macky")
mms_kapu = Request.Form("mms_kapu")
mms_emag = Request.Form("mms_emag")
mms_tengoku = Request.Form("mms_tengoku")
mms_tiara = Request.Form("mms_tiara")
mms_merubox = Request.Form("mms_merubox")
mms_dokuji = Request.Form("mms_dokuji")
mms_other = Request.Form("mms_other")
cat_int = Request.Form("cat_int")
cat_pcc = Request.Form("cat_pcc")
cat_soft = Request.Form("cat_soft")
cat_mvl = Request.Form("cat_mvl")
cat_bns = Request.Form("cat_bns")
cat_seiji = Request.Form("cat_seiji")
cat_kabu = Request.Form("cat_kabu")
cat_fsn = Request.Form("cat_fsn")
cat_lif = Request.Form("cat_lif")
cat_shumi = Request.Form("cat_shumi")
cat_res = Request.Form("cat_res")
cat_tvl = Request.Form("cat_tvl")
cat_spt = Request.Form("cat_spt")
cat_gamble = Request.Form("cat_gamble")
cat_hlt = Request.Form("cat_hlt")
cat_art = Request.Form("cat_art")
cat_shp = Request.Form("cat_shp")
cat_ent = Request.Form("cat_ent")
cat_game = Request.Form("cat_game")
cat_movie = Request.Form("cat_movie")
cat_uranai = Request.Form("cat_uranai")
cat_std = Request.Form("cat_std")
cat_shikaku = Request.Form("cat_shikaku")
cat_gogaku = Request.Form("cat_gogaku")
cat_nws = Request.Form("cat_nws")
cat_zatsu = Request.Form("cat_zatsu")
cat_kenshou = Request.Form("cat_kenshou")
cat_homepage = Request.Form("cat_homepage")
cat_hitokoto = Request.Form("cat_hitokoto")
cat_other = Request.Form("cat_other")
age_10m = Request.Form("age_10m")
age_10f = Request.Form("age_10f")
age_20m = Request.Form("age_20m")
age_20f = Request.Form("age_20f")
age_30m = Request.Form("age_30m")
age_30f = Request.Form("age_30f")
age_40m = Request.Form("age_40m")
age_40f = Request.Form("age_40f")
age_50m = Request.Form("age_50m")
age_50f = Request.Form("age_50f")
shk_stu = Request.Form("shk_stu")
shk_enp = Request.Form("shk_enp")
shk_syf = Request.Form("shk_syf")
shk_other = Request.Form("shk_other")
are_ht = Request.Form("are_ht")
are_kt = Request.Form("are_kt")
are_cb = Request.Form("are_cb")
are_kk = Request.Form("are_kk")
are_cs = Request.Form("are_cs")
are_ks = Request.Form("are_ks")
are_fr = Request.Form("are_fr")
are_zk = Request.Form("are_zk")

If "Insert" = Request("ActionType") Then  '送信するボタンが押された場合
  Set DB = OpenDB()
  SQL = "SELECT * FROM T_PROVREG WHERE PR_m_name = '" & Request.Form("m_name") & "' AND PR_mail_adr = '" & Request.Form("madd") & "'"
  Set RS = DB.Execute(SQL)

  If Not RS.EOF Then
    RS.Close
    DB.Close
    Response.Redirect("return.asp")
  Else
    RS.Close

    '既に登録されている発行者か調べる
    If mel_id = "" Then
      SQL = "SELECT MM_mel_id FROM T_MELMEL WHERE REPLACE(REPLACE(MM_h_name,' ', ''),'　','')='" & Replace(Replace(h_name, " ", ""), "　", "") & "' AND MM_mail_adr='" & madd & "' AND MM_status<>9"
      Set RS = DB.Execute(SQL)
      If Not RS.EOF Then
        mel_id = RS("MM_mel_id")
      End If
      RS.Close
    End If

    '新規発行者の場合はmel_idはNULLとする
    If mel_id = "" Then
      mel_id = "NULL"
    Else
      mel_id = "'" & mel_id & "'"
    End If

    SQL = "INSERT INTO T_PROVREG (PR_mail_adr,PR_m_name,PR_h_name,PR_c_name,PR_busuu,PR_hindo,PR_mms_melonpan,PR_mms_mag,PR_mms_pubz,PR_mms_melma,PR_mms_macky,PR_mms_kapu,PR_mms_emag,PR_mms_tengoku,PR_mms_tiara,PR_mms_merubox,PR_mms_dokuji,PR_mms_other,PR_cat_int,PR_cat_pcc,PR_cat_soft,PR_cat_mvl,PR_cat_bns,PR_cat_seiji,PR_cat_kabu,PR_cat_fsn,PR_cat_lif,PR_cat_shumi,PR_cat_res,PR_cat_tvl,PR_cat_spt,PR_cat_gamble,PR_cat_hlt,PR_cat_art,PR_cat_shp,PR_cat_ent,PR_cat_game,PR_cat_movie,PR_cat_uranai,PR_cat_std,PR_cat_shikaku,PR_cat_gogaku,PR_cat_nws,PR_cat_zatsu,PR_cat_kenshou,PR_cat_homepage,PR_cat_hitokoto,PR_cat_other,PR_age_10m,PR_age_10f,PR_age_20m,PR_age_20f,PR_age_30m,PR_age_30f,PR_age_40m,PR_age_40f,PR_age_50m,PR_age_50f,PR_shk_stu,PR_shk_enp,PR_shk_syf,PR_shk_other,PR_are_ht,PR_are_kt,PR_are_cb,PR_are_kk,PR_are_cs,PR_are_ks,PR_are_fr,PR_are_zk,PR_hp_own,PR_url1,PR_url2,PR_url3,PR_melonpan_id,PR_mgmg_id,PR_regist_date,PR_last_update,PR_status,PR_koudoku_flag,PR_mel_id) " & _
          "VALUES ('" &  madd & "','" & m_name & "','" & h_name & "','" & c_name & "','" & busuu & "','" & hindo & "','" & mms_melonpan & "','" & mms_mag & "','" & mms_pubz & "','" & mms_melma & "','" & mms_macky & "','" & mms_kapu & "','" & mms_emag & "','" & mms_tengoku & "','" & mms_tiara & "','" & mms_merubox & "','" & mms_dokuji & "','" & mms_other & "','" & cat_int & "','" & cat_pcc & "','" & cat_soft & "','" & cat_mvl & "','" & cat_bns & "','" & cat_seiji & "','" & cat_kabu & "','" & cat_fsn & "','" & cat_lif & "','" & cat_shumi & "','" & cat_res & "','" & cat_tvl & "','" & cat_spt & "','" & cat_gamble & "','" & cat_hlt & "','" & cat_art & "','" & cat_shp & "','" & cat_ent & "','" & cat_game & "','" & cat_movie & "','" & cat_uranai & "','" & cat_std & "','" & cat_shikaku & "','" & cat_gogaku & "','" & cat_nws & "','" & cat_zatsu & "','" & cat_kenshou & "','" & cat_homepage & "','" & cat_hitokoto & "','" & cat_other & "','" & age_10m & "','" & age_10f & "','" & age_20m & "','" & age_20f & "','" & age_30m & "','" & age_30f & "','" & age_40m & "','" & age_40f & "','" & age_50m & "','" & age_50f & "','" & shk_stu & "','" & shk_enp & "','" & shk_syf & "','" & shk_other & "','" & are_ht & "','" & are_kt & "','" & are_cb & "','" & are_kk & "','" & are_cs & "','" & are_ks & "','" & are_fr & "','" & are_zk & "',0,NULL,NULL,NULL,'" & melonpan_id & "','" & mgmg_id & "',{fn now()},NULL,0,0," & mel_id & ")"
    DB.Execute SQL
    DB.Close

    '仮登録完了メール送信
    If madd <> "" Then
      Dim subject, mailfrom, body

      Call GetSendMailInfo("KREG", subject, mailfrom, body)
      body = Replace(body, "%HAKKOUSHA%", h_name)
      Call SendMail(madd, mailfrom, subject, body)
    End If

		'ありがとうメッセージasp
    Response.Redirect("thanks.asp")
  End If
End If
%>

<html>

<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title>入力情報確認ページ</title>
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
    <td width="370" height="7"><font color="#000080"><span style="font-size: 11pt"> <%=Request.Form("h_name")%> </span></font></td>
  </tr>
  <tr>
    <td width="170" height="7"><span style="font-size: 11pt"><small>■発行者のe-mailアドレス：</small></span></td>
    <td width="370" height="7"><font color="#000080"><span style="font-size: 11pt"> <%=Request.Form("madd")%> </span></font></td>
  </tr>
  <tr>
    <td width="170" height="7"><small><span style="font-size: 11pt">■</span></small><span style="font-size: 11pt"><small>メルマガ名：</small></span></td>
    <td width="370" height="7"><font color="#000080"><span style="font-size: 11pt"> <%=Request.Form("m_name")%> </span></font></td>
  </tr>
  <tr>
    <td width="170" height="7"><small><span style="font-size: 11pt">■</span></small><span style="font-size: 11pt"><small>会社名（法人の場合）：</small></span></td>
    <td width="370" height="7"><font color="#000080"><span style="font-size: 11pt"> <%=Request.Form("c_name")%> </span></font></td>
  </tr>
  <tr>
    <td width="170" height="7"><span style="font-size: 11pt"><small>■発行部数：</small></span></td>
    <td width="370" height="7"><font color="#000080"><span style="font-size: 11pt"> <%=Request.Form("busuu")%></span></font></td>
  </tr>
  <tr>
    <td width="170" height="6"><span style="font-size: 11pt"><small>■発行頻度：</small></span></td>
    <td width="370" height="6"><font color="#000080"><span style="font-size: 11pt"> <%=Request.Form("hindo")%></span></font></td>
  </tr>
</table>
</center></div>

<p align="center"><small>上記の内容を確認し、よければ下の”送信”ボタンをクリックしてください。</small></p>
<div align="center">

<p align="center"><small>訂正する場合はブラウザのバックボタンで戻って修正してください。</small></p>

<form method="POST" ACTION="provreg_2.asp">
  <input type="hidden" name="mel_id" value="<%=Request.Form("mel_id")%>">
  <input type="hidden" name="madd" value="<%=Request.Form("madd")%>">
  <input type="hidden" name="h_name" value="<%=Request.Form("h_name")%>">
  <input type="hidden" name="m_name" value="<%=Request.Form("m_name")%>">
  <input type="hidden" name="c_name" value="<%=Request.Form("c_name")%>">
  <input type="hidden" name="busuu" value="<%=Request.Form("busuu")%>">
  <input type="hidden" name="hindo" value="<%=Request.Form("hindo")%>">
  <input type="hidden" name="melonpan_id" value="<%=Request.Form("melonpan_id")%>">
  <input type="hidden" name="mgmg_id" value="<%=Request.Form("mgmg_id")%>">
  <input type="hidden" name="mms_melonpan" value="<%=Request.Form("mms_melonpan")%>">
  <input type="hidden" name="mms_mag" value="<%=Request.Form("mms_mag")%>">
  <input type="hidden" name="mms_pubz" value="<%=Request.Form("mms_pubz")%>">
  <input type="hidden" name="mms_melma" value="<%=Request.Form("mms_melma")%>">
  <input type="hidden" name="mms_macky" value="<%=Request.Form("mms_macky")%>">
  <input type="hidden" name="mms_kapu" value="<%=Request.Form("mms_kapu")%>">
  <input type="hidden" name="mms_emag" value="<%=Request.Form("mms_emag")%>">
  <input type="hidden" name="mms_tengoku" value="<%=Request.Form("mms_tengoku")%>">
  <input type="hidden" name="mms_tiara" value="<%=Request.Form("mms_tiara")%>">
  <input type="hidden" name="mms_merubox" value="<%=Request.Form("mms_merubox")%>">
  <input type="hidden" name="mms_dokuji" value="<%=Request.Form("mms_dokuji")%>">
  <input type="hidden" name="mms_other" value="<%=Request.Form("mms_other")%>">
  <input type="hidden" name="cat_int" value="<%=Request.Form("cat_int")%>">
  <input type="hidden" name="cat_pcc" value="<%=Request.Form("cat_pcc")%>">
  <input type="hidden" name="cat_soft" value="<%=Request.Form("cat_soft")%>">
  <input type="hidden" name="cat_mvl" value="<%=Request.Form("cat_mvl")%>">
  <input type="hidden" name="cat_bns" value="<%=Request.Form("cat_bns")%>">
  <input type="hidden" name="cat_seiji" value="<%=Request.Form("cat_seiji")%>">
  <input type="hidden" name="cat_kabu" value="<%=Request.Form("cat_kabu")%>">
  <input type="hidden" name="cat_fsn" value="<%=Request.Form("cat_fsn")%>">
  <input type="hidden" name="cat_lif" value="<%=Request.Form("cat_lif")%>">
  <input type="hidden" name="cat_shumi" value="<%=Request.Form("cat_shumi")%>">
  <input type="hidden" name="cat_res" value="<%=Request.Form("cat_res")%>">
  <input type="hidden" name="cat_tvl" value="<%=Request.Form("cat_tvl")%>">
  <input type="hidden" name="cat_spt" value="<%=Request.Form("cat_spt")%>">
  <input type="hidden" name="cat_gamble" value="<%=Request.Form("cat_gamble")%>">
  <input type="hidden" name="cat_hlt" value="<%=Request.Form("cat_hlt")%>">
  <input type="hidden" name="cat_art" value="<%=Request.Form("cat_art")%>">
  <input type="hidden" name="cat_shp" value="<%=Request.Form("cat_shp")%>">
  <input type="hidden" name="cat_ent" value="<%=Request.Form("cat_ent")%>">
  <input type="hidden" name="cat_game" value="<%=Request.Form("cat_game")%>">
  <input type="hidden" name="cat_movie" value="<%=Request.Form("cat_movie")%>">
  <input type="hidden" name="cat_uranai" value="<%=Request.Form("cat_uranai")%>">
  <input type="hidden" name="cat_std" value="<%=Request.Form("cat_std")%>">
  <input type="hidden" name="cat_shikaku" value="<%=Request.Form("cat_shikaku")%>">
  <input type="hidden" name="cat_gogaku" value="<%=Request.Form("cat_gogaku")%>">
  <input type="hidden" name="cat_nws" value="<%=Request.Form("cat_nws")%>">
  <input type="hidden" name="cat_zatsu" value="<%=Request.Form("cat_zatsu")%>">
  <input type="hidden" name="cat_kenshou" value="<%=Request.Form("cat_kenshou")%>">
  <input type="hidden" name="cat_homepage" value="<%=Request.Form("cat_homepage")%>">
  <input type="hidden" name="cat_hitokoto" value="<%=Request.Form("cat_hitokoto")%>">
  <input type="hidden" name="cat_other" value="<%=Request.Form("cat_other")%>">
  <input type="hidden" name="age_10m" value="<%=Request.Form("age_10m")%>">
  <input type="hidden" name="age_10f" value="<%=Request.Form("age_10f")%>">
  <input type="hidden" name="age_20m" value="<%=Request.Form("age_20m")%>">
  <input type="hidden" name="age_20f" value="<%=Request.Form("age_20f")%>">
  <input type="hidden" name="age_30m" value="<%=Request.Form("age_30m")%>">
  <input type="hidden" name="age_30f" value="<%=Request.Form("age_30f")%>">
  <input type="hidden" name="age_40m" value="<%=Request.Form("age_40m")%>">
  <input type="hidden" name="age_40f" value="<%=Request.Form("age_40f")%>">
  <input type="hidden" name="age_50m" value="<%=Request.Form("age_50m")%>">
  <input type="hidden" name="age_50f" value="<%=Request.Form("age_50f")%>">
  <input type="hidden" name="shk_stu" value="<%=Request.Form("shk_stu")%>">
  <input type="hidden" name="shk_enp" value="<%=Request.Form("shk_enp")%>">
  <input type="hidden" name="shk_syf" value="<%=Request.Form("shk_syf")%>">
  <input type="hidden" name="shk_other" value="<%=Request.Form("shk_other")%>">
  <input type="hidden" name="are_ht" value="<%=Request.Form("are_ht")%>">
  <input type="hidden" name="are_kt" value="<%=Request.Form("are_kt")%>">
  <input type="hidden" name="are_cb" value="<%=Request.Form("are_cb")%>">
  <input type="hidden" name="are_kk" value="<%=Request.Form("are_kk")%>">
  <input type="hidden" name="are_cs" value="<%=Request.Form("are_cs")%>">
  <input type="hidden" name="are_ks" value="<%=Request.Form("are_ks")%>">
  <input type="hidden" name="are_fr" value="<%=Request.Form("are_fr")%>">
  <input type="hidden" name="are_zk" value="<%=Request.Form("are_zk")%>">
  <input type="hidden" name="ActionType" value="Insert"><div align="center"><center><p><input type="submit" value="　送信　"></p>
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
