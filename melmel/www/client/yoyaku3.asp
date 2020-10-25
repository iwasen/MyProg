<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="waku_kanri_inc.asp"-->
<%
'******************************************************
' System :‚ß‚é‚ß‚éEnetLŽå—pƒy[ƒW
' Content:—\–ñ‚c‚a“o˜^ˆ—
'******************************************************

Dim client_id

client_id = Session("client_id")
If IsEmpty(client_id) Then
  Session("client_next") = "yoyaku.asp"
  Response.Redirect "login.asp"
End If

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

Dim DB, RS, SQL
Dim type_a, type_b, akibusuu, akibusuu1, akibusuu2
Dim koukoku_id, koukoku_msg

Set DB = OpenDB()
koukoku_id = Request.Form("koukoku_id")
Call GetTotalBusuu(koukoku_id, type_a, type_b)

Set RS = Server.CreateObject("ADODB.RecordSet")
SQL = GetUmariWaku(Left(Request.Form("koukoku_shuu"), 6))
RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

Select Case Left(Request.Form("koukoku_type"), 1)
Case "A", "Q"
  akibusuu1 = type_a
  akibusuu2 = type_a * 2 - RS("type_a")
Case "B", "R"
  akibusuu1 = type_b
  akibusuu2 = type_b * 2 - RS("type_b")
End Select

If akibusuu1 < akibusuu Then
  akibusuu = akibusuu1
Else
  akibusuu = akibusuu2
End If

If CLng(Request.Form("busuu")) > akibusuu Then
  Response.Redirect "yoyaku5.asp?p=" & akibusuu
End If

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
  RS("KK_client_id") = client_id
  RS("KK_client_name") = Session("client_name")
  RS("KK_company") = Session("company")
  RS("KK_tantousha") = Request.Form("tantousha")
  RS("KK_mail_adr") = Request.Form("mail_adr")
  RS("KK_tel_no") = Request.Form("tel_no")
  RS("KK_koukoku_shuu") = Left(Request.Form("koukoku_shuu"), 6)
  RS("KK_pub_start") = BlankToNull(Request.Form("pub_start"))
  RS("KK_pub_end") = BlankToNull(Request.Form("pub_end"))
  RS("KK_busuu") = Request.Form("busuu")
  RS("KK_keisai_irai") = BlankToNull(Request.Form("keisai_irai"))
  RS("KK_koukoku_msg") = koukoku_msg
  RS("KK_mms_melonpan") = False
  RS("KK_mms_mag") = False
  RS("KK_mms_pubz") = False
  RS("KK_mms_melma") = False
  RS("KK_mms_macky") = False
  RS("KK_mms_kapu") = False
  RS("KK_mms_emag") = False
  RS("KK_mms_tengoku") = False
  RS("KK_mms_tiara") = False
  RS("KK_mms_merubox") = False
  RS("KK_mms_dokuji") = False
  RS("KK_mms_other") = False
  RS("KK_cat_int") = CheckBox(Request.Form("cat_int").Item)
  RS("KK_cat_pcc") = CheckBox(Request.Form("cat_pcc").Item)
  RS("KK_cat_soft") = CheckBox(Request.Form("cat_soft").Item)
  RS("KK_cat_mvl") = CheckBox(Request.Form("cat_mvl").Item)
  RS("KK_cat_bns") = CheckBox(Request.Form("cat_bns").Item)
  RS("KK_cat_seiji") = CheckBox(Request.Form("cat_seiji").Item)
  RS("KK_cat_kabu") = CheckBox(Request.Form("cat_kabu").Item)
  RS("KK_cat_fsn") = CheckBox(Request.Form("cat_fsn").Item)
  RS("KK_cat_lif") = CheckBox(Request.Form("cat_lif").Item)
  RS("KK_cat_shumi") = CheckBox(Request.Form("cat_shumi").Item)
  RS("KK_cat_res") = CheckBox(Request.Form("cat_res").Item)
  RS("KK_cat_tvl") = CheckBox(Request.Form("cat_tvl").Item)
  RS("KK_cat_spt") = CheckBox(Request.Form("cat_spt").Item)
  RS("KK_cat_gamble") = CheckBox(Request.Form("cat_gamble").Item)
  RS("KK_cat_hlt") = CheckBox(Request.Form("cat_hlt").Item)
  RS("KK_cat_art") = CheckBox(Request.Form("cat_art").Item)
  RS("KK_cat_shp") = CheckBox(Request.Form("cat_shp").Item)
  RS("KK_cat_ent") = CheckBox(Request.Form("cat_ent").Item)
  RS("KK_cat_game") = CheckBox(Request.Form("cat_game").Item)
  RS("KK_cat_movie") = CheckBox(Request.Form("cat_movie").Item)
  RS("KK_cat_uranai") = CheckBox(Request.Form("cat_uranai").Item)
  RS("KK_cat_std") = CheckBox(Request.Form("cat_std").Item)
  RS("KK_cat_shikaku") = CheckBox(Request.Form("cat_shikaku").Item)
  RS("KK_cat_gogaku") = CheckBox(Request.Form("cat_gogaku").Item)
  RS("KK_cat_nws") = CheckBox(Request.Form("cat_nws").Item)
  RS("KK_cat_zatsu") = CheckBox(Request.Form("cat_zatsu").Item)
  RS("KK_cat_kenshou") = CheckBox(Request.Form("cat_kenshou").Item)
  RS("KK_cat_homepage") = CheckBox(Request.Form("cat_homepage").Item)
  RS("KK_cat_hitokoto") = CheckBox(Request.Form("cat_hitokoto").Item)
  RS("KK_cat_other") = CheckBox(Request.Form("cat_other").Item)
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
  RS("KK_limit_date") = BlankToNull(Request.Form("limit_date"))
  RS("KK_koukoku_type") = BlankToNull(Left(Request.Form("koukoku_type"), 1))
  RS("KK_koukoku_line") = BlankToNull(Mid(Request.Form("koukoku_type"), 2))
  RS("KK_status") = 0
  RS.Update
  RS.Close
End If

'=== ‹ó•¶Žš‚ðNull‚É•ÏŠ· ===
Function BlankToNull(text)
  If text = "" Then
    BlankToNull = Null
  Else
    BlankToNull = text
  End If
End Function

Response.Redirect("yoyaku.asp")
%>
