<!--#INCLUDE FILE="../melmel_inc.asp"-->
<html>

<head>
<title>本登録完了ページ</title>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
</head>

<body>
<%
Dim DB, RS1, RS2, SQL
Dim subject, mailfrom, body, mel_id

Set DB = OpenDB()
Set RS1 = Server.CreateObject("ADODB.RecordSet")

SQL = "SELECT * FROM T_MELMAG WHERE MG_mag_id = '" & Session("seq") & "'"
RS1.Open SQL, DB, adOpenKeyset, adLockPessimistic

If Not RS1.EOF Then
	'--- Repeated Entry ---
	Response.Write "既に本登録されています"
Else
  '--- 発行者マスタに未登録なら登録する
  mel_id = Session("mel_id")
  If IsNull(mel_id) Or Len(mel_id) = 0 Then
    SQL = "SELECT *" & vbCrLf & _
          "FROM T_MELMEL" & vbCrLf & _
          "WHERE REPLACE(REPLACE(MM_h_name,' ', ''),'　','')='" & Replace(Replace(Session("h_name"), " ", ""), "　", "") & "'" & vbCrLf & _
          " AND MM_mail_adr='" & Session("madd") & "'" & vbCrLf & _
          " AND MM_status<>9"
    Set RS2 = Server.CreateObject("ADODB.RecordSet")
    RS2.Open SQL, DB, adOpenKeyset, adLockPessimistic

    If RS2.EOF Then
      '--- 発行者新規登録 ---
      RS2.AddNew
      RS2("MM_mel_id") = Session("seq")
      RS2("MM_mail_adr") = Session("madd")
      RS2("MM_h_name") = Session("h_name")
      RS2("MM_c_name") = Session("c_name")
      RS2("MM_password") = Session("password")
      RS2("MM_bk_acc") = Session("bk_acc")
      RS2("MM_bk_nm") = Session("bk_nm")
      RS2("MM_bk_st") = Session("bk_st")
      RS2("MM_bk_knd") = Session("bk_knd")
      RS2("MM_bk_knm") = Session("bk_knm")
      RS2("MM_regist_date") = Now()
      RS2("MM_last_update") = Now()
      RS2("MM_status") = 0
      RS2("MM_point") = 0
      RS2.Update
      mel_id = Session("seq")
    Else
      '--- 既存の発行者ＩＤ取得 ---
      mel_id = RS2("MM_mel_id")
    End If
  End If

	'--- New Entry ---
	RS1.AddNew
	RS1("MG_mag_id") = Session("seq")
	RS1("MG_mel_id") = mel_id
	RS1("MG_m_name") = Session("m_name")
	RS1("MG_busuu") = Session("busuu")
	RS1("MG_hindo") = Session("hindo")
	RS1("MG_mms_melonpan") = Checkbox(Session("mms_melonpan"))
	RS1("MG_mms_mag") = Checkbox(Session("mms_mag"))
	RS1("MG_mms_pubz") = Checkbox(Session("mms_pubz"))
	RS1("MG_mms_melma") = Checkbox(Session("mms_melma"))
	RS1("MG_mms_macky") = Checkbox(Session("mms_macky"))
	RS1("MG_mms_kapu") = Checkbox(Session("mms_kapu"))
	RS1("MG_mms_emag") = Checkbox(Session("mms_emag"))
	RS1("MG_mms_tengoku") = Checkbox(Session("mms_tengoku"))
	RS1("MG_mms_tiara") = Checkbox(Session("mms_tiara"))
	RS1("MG_mms_merubox") = Checkbox(Session("mms_merubox"))
	RS1("MG_mms_dokuji") = Checkbox(Session("mms_dokuji"))
	RS1("MG_mms_other") = Checkbox(Session("mms_other"))
	RS1("MG_cat_int") = Checkbox(Session("cat_int"))
	RS1("MG_cat_pcc") = Checkbox(Session("cat_pcc"))
	RS1("MG_cat_soft") = Checkbox(Session("cat_soft"))
	RS1("MG_cat_mvl") = Checkbox(Session("cat_mvl"))
	RS1("MG_cat_bns") = Checkbox(Session("cat_bns"))
	RS1("MG_cat_seiji") = Checkbox(Session("cat_seiji"))
	RS1("MG_cat_kabu") = Checkbox(Session("cat_kabu"))
	RS1("MG_cat_fsn") = Checkbox(Session("cat_fsn"))
	RS1("MG_cat_lif") = Checkbox(Session("cat_lif"))
	RS1("MG_cat_shumi") = Checkbox(Session("cat_shumi"))
	RS1("MG_cat_res") = Checkbox(Session("cat_res"))
	RS1("MG_cat_tvl") = Checkbox(Session("cat_tvl"))
	RS1("MG_cat_spt") = Checkbox(Session("cat_spt"))
	RS1("MG_cat_gamble") = Checkbox(Session("cat_gamble"))
	RS1("MG_cat_hlt") = Checkbox(Session("cat_hlt"))
	RS1("MG_cat_art") = Checkbox(Session("cat_art"))
	RS1("MG_cat_shp") = Checkbox(Session("cat_shp"))
	RS1("MG_cat_ent") = Checkbox(Session("cat_ent"))
	RS1("MG_cat_game") = Checkbox(Session("cat_game"))
	RS1("MG_cat_movie") = Checkbox(Session("cat_movie"))
	RS1("MG_cat_uranai") = Checkbox(Session("cat_uranai"))
	RS1("MG_cat_std") = Checkbox(Session("cat_std"))
	RS1("MG_cat_shikaku") = Checkbox(Session("cat_shikaku"))
	RS1("MG_cat_gogaku") = Checkbox(Session("cat_gogaku"))
	RS1("MG_cat_nws") = Checkbox(Session("cat_nws"))
	RS1("MG_cat_zatsu") = Checkbox(Session("cat_zatsu"))
	RS1("MG_cat_kenshou") = Checkbox(Session("cat_kenshou"))
	RS1("MG_cat_homepage") = Checkbox(Session("cat_homepage"))
	RS1("MG_cat_hitokoto") = Checkbox(Session("cat_hitokoto"))
	RS1("MG_cat_other") = Checkbox(Session("cat_other"))
  RS1("MG_age_10m") = Checkbox(Session("age_10m"))
  RS1("MG_age_10f") = Checkbox(Session("age_10f"))
  RS1("MG_age_20m") = Checkbox(Session("age_20m"))
  RS1("MG_age_20f") = Checkbox(Session("age_20f"))
  RS1("MG_age_30m") = Checkbox(Session("age_30m"))
  RS1("MG_age_30f") = Checkbox(Session("age_30f"))
  RS1("MG_age_40m") = Checkbox(Session("age_40m"))
  RS1("MG_age_40f") = Checkbox(Session("age_40f"))
  RS1("MG_age_50m") = Checkbox(Session("age_50m"))
  RS1("MG_age_50f") = Checkbox(Session("age_50f"))
  RS1("MG_shk_stu") = Checkbox(Session("shk_stu"))
  RS1("MG_shk_enp") = Checkbox(Session("shk_enp"))
  RS1("MG_shk_syf") = Checkbox(Session("shk_syf"))
  RS1("MG_shk_other") = Checkbox(Session("shk_other"))
  RS1("MG_are_ht") = Checkbox(Session("are_ht"))
  RS1("MG_are_kt") = Checkbox(Session("are_kt"))
  RS1("MG_are_cb") = Checkbox(Session("are_cb"))
  RS1("MG_are_kk") = Checkbox(Session("are_kk"))
  RS1("MG_are_cs") = Checkbox(Session("are_cs"))
  RS1("MG_are_ks") = Checkbox(Session("are_ks"))
  RS1("MG_are_fr") = Checkbox(Session("are_fr"))
  RS1("MG_are_zk") = Checkbox(Session("are_zk"))
	RS1("MG_melonpan_id") = Session("melonpan_id")
	RS1("MG_mgmg_id") = Session("mgmg_id")
	RS1("MG_regist_date") = Now()
	RS1("MG_last_update") = Now()
  RS1("MG_status") = 0
  RS1("MG_new_busuu") = Session("busuu")
  RS1("MG_keisen_id") = Session("keisen_id")
  RS1("MG_moji_suu") = Session("moji_suu")
	RS1("MG_koudoku_url") = Session("koudoku_url")
	RS1("MG_shousai_url") = Session("shousai_url")
  RS1("MG_shoukai_text") = Session("shoukai_text")
  RS1("MG_shoukai_flag1") = Session("shoukai_flag1")
	RS1.Update
	RS1.Close

	SQL = "SELECT PR_status FROM T_PROVREG WHERE PR_seq_no = '" & Session("seq") & "'"
	RS1.Open SQL, DB, adOpenKeyset, adLockPessimistic

	If Not RS1.EOF Then
		RS1("PR_status") = 2
		RS1.Update
	End If

	'--- 本登録確認メール送信 ---
  Call GetSendMailInfo("HREG", subject, mailfrom, body)
  body = Replace(body, "%MAGAZINE%", Session("m_name"))
  body = Replace(body, "%HAKKOUSHA%", Session("h_name"))
  body = Replace(body, "%EMAIL%", Session("madd"))
  body = Replace(body, "%MELMELID%", mel_id)
  Call SendMail(Session("madd"), mailfrom, subject, body)

	Response.Write "<CENTER><BR><BR><BR>"
	Response.Write "<FONT SIZE=+2 COLOR=""#FF80C0"">ありがとうございました。</FONT><BR><BR><BR>"
	Response.Write "今後ともめるめる･netをよろしくお願いします。<BR><BR>"
	Response.Write "良質の広告をふんだんにお届けできるよう、スタッフ一同、頑張ります！<BR><BR><BR><BR>"
	Response.Write "<A HREF=""http://www.melmel.net/"">めるめる・netホームページへ</A>"
	
End If
%>
</body>
</html>
