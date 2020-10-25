<!--#INCLUDE FILE="../melmel_inc.asp"-->

<%
'******************************************************
' System :‚ß‚é‚ß‚éEnetŽ––±‹Ç—pƒy[ƒW
' Content:L“üe‚c‚aXVˆ—
'******************************************************

Dim DB, SQL, RS, koukoku_id, koukoku_msg, irai, mag_id

koukoku_id = Request.Form("koukoku_id")
If Not IsEmpty(koukoku_id) Then
  'L–{•¶‚ÌÅŒã‚Ì‰üs‚ðíœ
  koukoku_msg = Request.Form("koukoku_msg")
  Do While Right(koukoku_msg, 2) = vbCrLf
    koukoku_msg = Left(koukoku_msg, Len(koukoku_msg) - 2)
  Loop

  'Lƒ}ƒXƒ^ƒI[ƒvƒ“
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")

  If koukoku_id = "" Then
    SQL = "SELECT * FROM T_KOUKOKU WHERE 0=1"
    RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
    RS.AddNew
    RS("KK_regist_date") = Now()
  Else
    SQL = "SELECT * FROM V_KOUKOKU WHERE KK_koukoku_id='" & koukoku_id & "'"
    RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
    RS("KK_last_update") = Now()
  End If

  'Lƒ}ƒXƒ^‚É“o˜^
  RS("KK_koukoku_name") = Request.Form("koukoku_name")
  RS("KK_client_name") = Request.Form("client_name")
  RS("KK_company") = Request.Form("company")
  RS("KK_tantousha") = Request.Form("tantousha")
  RS("KK_mail_adr") = Request.Form("mail_adr")
  RS("KK_tel_no") = Request.Form("tel_no")
  RS("KK_koukoku_shuu") = Request.Form("koukoku_shuu")
  RS("KK_pub_start") = Request.Form("pub_start")
  RS("KK_pub_end") = Request.Form("pub_end")
  RS("KK_busuu") = Request.Form("busuu")
  RS("KK_keisai_irai") = Request.Form("keisai_irai")
  RS("KK_koukoku_line") = Mid(Request.Form("koukoku_type"), 2)
  RS("KK_koukoku_msg") = koukoku_msg
  RS("KK_jogai_id") = Request.Form("jogai_id")
  RS("KK_koukoku_url") = Request.Form("koukoku_url")
	RS("KK_mms_melonpan") = Checkbox(Request.Form("mms_melonpan").Item)
	RS("KK_mms_mag") = Checkbox(Request.Form("mms_mag").Item)
	RS("KK_mms_pubz") = Checkbox(Request.Form("mms_pubz").Item)
	RS("KK_mms_melma") = Checkbox(Request.Form("mms_melma").Item)
	RS("KK_mms_macky") = Checkbox(Request.Form("mms_macky").Item)
	RS("KK_mms_kapu") = Checkbox(Request.Form("mms_kapu").Item)
	RS("KK_mms_emag") = Checkbox(Request.Form("mms_emag").Item)
	RS("KK_mms_tengoku") = Checkbox(Request.Form("mms_tengoku").Item)
	RS("KK_mms_tiara") = Checkbox(Request.Form("mms_tiara").Item)
	RS("KK_mms_merubox") = Checkbox(Request.Form("mms_merubox").Item)
	RS("KK_mms_dokuji") = Checkbox(Request.Form("mms_dokuji").Item)
	RS("KK_mms_other") = Checkbox(Request.Form("mms_other").Item)
	RS("KK_cat_int") = Checkbox(Request.Form("cat_int").Item)
	RS("KK_cat_pcc") = Checkbox(Request.Form("cat_pcc").Item)
	RS("KK_cat_soft") = Checkbox(Request.Form("cat_soft").Item)
	RS("KK_cat_mvl") = Checkbox(Request.Form("cat_mvl").Item)
	RS("KK_cat_bns") = Checkbox(Request.Form("cat_bns").Item)
	RS("KK_cat_seiji") = Checkbox(Request.Form("cat_seiji").Item)
	RS("KK_cat_kabu") = Checkbox(Request.Form("cat_kabu").Item)
	RS("KK_cat_fsn") = Checkbox(Request.Form("cat_fsn").Item)
	RS("KK_cat_lif") = Checkbox(Request.Form("cat_lif").Item)
	RS("KK_cat_shumi") = Checkbox(Request.Form("cat_shumi").Item)
	RS("KK_cat_res") = Checkbox(Request.Form("cat_res").Item)
	RS("KK_cat_tvl") = Checkbox(Request.Form("cat_tvl").Item)
	RS("KK_cat_spt") = Checkbox(Request.Form("cat_spt").Item)
	RS("KK_cat_gamble") = Checkbox(Request.Form("cat_gamble").Item)
	RS("KK_cat_hlt") = Checkbox(Request.Form("cat_hlt").Item)
	RS("KK_cat_art") = Checkbox(Request.Form("cat_art").Item)
	RS("KK_cat_shp") = Checkbox(Request.Form("cat_shp").Item)
	RS("KK_cat_ent") = Checkbox(Request.Form("cat_ent").Item)
	RS("KK_cat_game") = Checkbox(Request.Form("cat_game").Item)
	RS("KK_cat_movie") = Checkbox(Request.Form("cat_movie").Item)
	RS("KK_cat_uranai") = Checkbox(Request.Form("cat_uranai").Item)
	RS("KK_cat_std") = Checkbox(Request.Form("cat_std").Item)
	RS("KK_cat_shikaku") = Checkbox(Request.Form("cat_shikaku").Item)
	RS("KK_cat_gogaku") = Checkbox(Request.Form("cat_gogaku").Item)
	RS("KK_cat_nws") = Checkbox(Request.Form("cat_nws").Item)
	RS("KK_cat_zatsu") = Checkbox(Request.Form("cat_zatsu").Item)
	RS("KK_cat_kenshou") = Checkbox(Request.Form("cat_kenshou").Item)
	RS("KK_cat_homepage") = Checkbox(Request.Form("cat_homepage").Item)
	RS("KK_cat_hitokoto") = Checkbox(Request.Form("cat_hitokoto").Item)
	RS("KK_cat_other") = Checkbox(Request.Form("cat_other").Item)
  RS("KK_age_10m") = CheckBox(Request.Form("age_10m").Item)
  RS("KK_age_10f") = CheckBox(Request.Form("age_10f").Item)
  RS("KK_age_20m") = CheckBox(Request.Form("age_20m").Item)
  RS("KK_age_20f") = CheckBox(Request.Form("age_20f").Item)
  RS("KK_age_30m") = CheckBox(Request.Form("age_30m").Item)
  RS("KK_age_30f") = CheckBox(Request.Form("age_30f").Item)
  RS("KK_age_40m") = CheckBox(Request.Form("age_40m").Item)
  RS("KK_age_40f") = CheckBox(Request.Form("age_40f").Item)
  RS("KK_age_50m") = CheckBox(Request.Form("age_50m").Item)
  RS("KK_age_50f") = CheckBox(Request.Form("age_50f").Item)
  RS("KK_shk_stu") = CheckBox(Request.Form("shk_stu").Item)
  RS("KK_shk_enp") = CheckBox(Request.Form("shk_enp").Item)
  RS("KK_shk_syf") = CheckBox(Request.Form("shk_syf").Item)
  RS("KK_shk_other") = CheckBox(Request.Form("shk_other").Item)
  RS("KK_are_ht") = CheckBox(Request.Form("are_ht").Item)
  RS("KK_are_kt") = CheckBox(Request.Form("are_kt").Item)
  RS("KK_are_cb") = CheckBox(Request.Form("are_cb").Item)
  RS("KK_are_kk") = CheckBox(Request.Form("are_kk").Item)
  RS("KK_are_cs") = CheckBox(Request.Form("are_cs").Item)
  RS("KK_are_ks") = CheckBox(Request.Form("are_ks").Item)
  RS("KK_are_fr") = CheckBox(Request.Form("are_fr").Item)
  RS("KK_are_zk") = CheckBox(Request.Form("are_zk").Item)
  RS("KK_limit_date") = Request.Form("limit_date")
  RS("KK_koukoku_type") = Left(Request.Form("koukoku_type"), 1)
  RS("KK_client_id") = Request.Form("client_id")
  If Request.Form("tsuika") <> "" Then RS("KK_tsuika_no") = CLng(Request.Form("tsuika"))
  RS("KK_status") = 1
  RS.Update

  '‚±‚ÌL‚ÉŠÖ‚·‚éŠù‘¶‚ÌˆË—Šƒf[ƒ^‚ðíœ
  SQL = "DELETE T_KEISAI WHERE KS_koukoku_id='" & koukoku_id & "'"
  DB.Execute SQL

  Set irai = Request.Form("irai")
  For Each mag_id IN irai
    SQL = "INSERT INTO T_KEISAI VALUES ('" & Right("00000" & RS("KK_seq_no"), 6) & "','" & mag_id & "',0)"
    DB.Execute SQL
  Next

  RS.Close
End If

If Request.Form("tsuika") = "" Then
  Response.Redirect("nyuukou.asp")
Else
  Response.Redirect("tsuika.asp")
End If
%>
