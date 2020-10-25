<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<%
SetHeaderTitle "メルマガマスタ更新", TC_MASTER
'******************************************************
' System :めるめる・net事務局用ページ
' Content:メルマガマスタＤＢ更新処理
'******************************************************

Dim DB, RS, SQL, mag_id

mag_id = Request.Form("mag_id")
If Not IsEmpty(mag_id) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  SQL = "SELECT * FROM T_MELMAG WHERE MG_mag_id='" & mag_id & "'"
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
  If Not RS.EOF Then
    RS("MG_m_name") = Request.Form("m_name")
    RS("MG_busuu") = Number(Request.Form("busuu"))
    RS("MG_hindo") = Request.Form("hindo")
		RS("MG_mms_melonpan") = CheckBox(Request.Form("mms_melonpan"))
		RS("MG_mms_mag") = CheckBox(Request.Form("mms_mag"))
		RS("MG_mms_pubz") = CheckBox(Request.Form("mms_pubz"))
		RS("MG_mms_melma") = CheckBox(Request.Form("mms_melma"))
		RS("MG_mms_macky") = CheckBox(Request.Form("mms_macky"))
		RS("MG_mms_kapu") = CheckBox(Request.Form("mms_kapu"))
		RS("MG_mms_emag") = CheckBox(Request.Form("mms_emag"))
		RS("MG_mms_tengoku") = CheckBox(Request.Form("mms_tengoku"))
		RS("MG_mms_tiara") = CheckBox(Request.Form("mms_tiara"))
		RS("MG_mms_merubox") = CheckBox(Request.Form("mms_merubox"))
		RS("MG_mms_dokuji") = CheckBox(Request.Form("mms_dokuji"))
		RS("MG_mms_other") = CheckBox(Request.Form("mms_other"))
		RS("MG_cat_int") = CheckBox(Request.Form("cat_int"))
		RS("MG_cat_pcc") = CheckBox(Request.Form("cat_pcc"))
		RS("MG_cat_soft") = CheckBox(Request.Form("cat_soft"))
		RS("MG_cat_mvl") = CheckBox(Request.Form("cat_mvl"))
		RS("MG_cat_bns") = CheckBox(Request.Form("cat_bns"))
		RS("MG_cat_seiji") = CheckBox(Request.Form("cat_seiji"))
		RS("MG_cat_kabu") = CheckBox(Request.Form("cat_kabu"))
		RS("MG_cat_fsn") = CheckBox(Request.Form("cat_fsn"))
		RS("MG_cat_lif") = CheckBox(Request.Form("cat_lif"))
		RS("MG_cat_shumi") = CheckBox(Request.Form("cat_shumi"))
		RS("MG_cat_res") = CheckBox(Request.Form("cat_res"))
		RS("MG_cat_tvl") = CheckBox(Request.Form("cat_tvl"))
		RS("MG_cat_spt") = CheckBox(Request.Form("cat_spt"))
		RS("MG_cat_gamble") = CheckBox(Request.Form("cat_gamble"))
		RS("MG_cat_hlt") = CheckBox(Request.Form("cat_hlt"))
		RS("MG_cat_art") = CheckBox(Request.Form("cat_art"))
		RS("MG_cat_shp") = CheckBox(Request.Form("cat_shp"))
		RS("MG_cat_ent") = CheckBox(Request.Form("cat_ent"))
		RS("MG_cat_game") = CheckBox(Request.Form("cat_game"))
		RS("MG_cat_movie") = CheckBox(Request.Form("cat_movie"))
		RS("MG_cat_uranai") = CheckBox(Request.Form("cat_uranai"))
		RS("MG_cat_std") = CheckBox(Request.Form("cat_std"))
		RS("MG_cat_shikaku") = CheckBox(Request.Form("cat_shikaku"))
		RS("MG_cat_gogaku") = CheckBox(Request.Form("cat_gogaku"))
		RS("MG_cat_nws") = CheckBox(Request.Form("cat_nws"))
		RS("MG_cat_zatsu") = CheckBox(Request.Form("cat_zatsu"))
		RS("MG_cat_kenshou") = CheckBox(Request.Form("cat_kenshou"))
		RS("MG_cat_homepage") = CheckBox(Request.Form("cat_homepage"))
		RS("MG_cat_hitokoto") = CheckBox(Request.Form("cat_hitokoto"))
		RS("MG_cat_other") = CheckBox(Request.Form("cat_other"))
    RS("MG_age_10m") = CheckBox(Request.Form("age_10m"))
    RS("MG_age_10f") = CheckBox(Request.Form("age_10f"))
    RS("MG_age_20m") = CheckBox(Request.Form("age_20m"))
    RS("MG_age_20f") = CheckBox(Request.Form("age_20f"))
    RS("MG_age_30m") = CheckBox(Request.Form("age_30m"))
    RS("MG_age_30f") = CheckBox(Request.Form("age_30f"))
    RS("MG_age_40m") = CheckBox(Request.Form("age_40m"))
    RS("MG_age_40f") = CheckBox(Request.Form("age_40f"))
    RS("MG_age_50m") = CheckBox(Request.Form("age_50m"))
    RS("MG_age_50f") = CheckBox(Request.Form("age_50f"))
    RS("MG_shk_stu") = CheckBox(Request.Form("shk_stu"))
    RS("MG_shk_enp") = CheckBox(Request.Form("shk_enp"))
    RS("MG_shk_syf") = CheckBox(Request.Form("shk_syf"))
    RS("MG_shk_other") = CheckBox(Request.Form("shk_other"))
    RS("MG_are_ht") = CheckBox(Request.Form("are_ht"))
    RS("MG_are_kt") = CheckBox(Request.Form("are_kt"))
    RS("MG_are_cb") = CheckBox(Request.Form("are_cb"))
    RS("MG_are_kk") = CheckBox(Request.Form("are_kk"))
    RS("MG_are_cs") = CheckBox(Request.Form("are_cs"))
    RS("MG_are_ks") = CheckBox(Request.Form("are_ks"))
    RS("MG_are_fr") = CheckBox(Request.Form("are_fr"))
    RS("MG_are_zk") = CheckBox(Request.Form("are_zk"))
    RS("MG_melonpan_id") = Request.Form("melonpan_id")
    RS("MG_last_update") = Now()
    RS("MG_status") = Request.Form("status")
    RS("MG_shuu_keisuu") = CDbl(Request.Form("shuu_keisuu"))
    RS("MG_shuu_keisuu2") = KeisuuInt(Request.Form("shuu_keisuu"))
    RS("MG_magmag_keisuu") = CDbl(Request.Form("magmag_keisuu")) / 100
    RS("MG_new_busuu") = Request.Form("new_busuu")
    RS("MG_keisen_id") = Request.Form("keisen_id")
	  RS("MG_koudoku_url") = Request.Form("koudoku_url")
	  RS("MG_shousai_url") = Request.Form("shousai_url")
    RS("MG_shoukai_text") = Request.Form("shoukai_text")
    RS("MG_shoukai_flag1") = CheckBox(Request.Form("shoukai_flag1"))
    RS("MG_shoukai_flag2") = CheckBox(Request.Form("shoukai_flag2"))
    If Request.Form("moji_suu") = "" Then RS("MG_moji_suu") = Null Else RS("MG_moji_suu") = CInt(Request.Form("moji_suu"))
    RS.Update
  End If
  RS.Close
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta name="GENERATOR" Content="Microsoft Visual Studio 6.0">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>

<!--#INCLUDE FILE="admin_header.asp"-->

<center>
<p>更新しました。</p>
<input type="button" value=" ＯＫ " onClick="location.href='mag_master.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
<%
Else
  Response.Redirect("index.asp")
End If
%>