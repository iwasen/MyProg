<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:枠管理共通処理
'******************************************************

Dim mms_melonpan, mms_mag, mms_pubz, mms_melma, mms_macky, mms_kapu, mms_emag, mms_tengoku, mms_tiara, mms_merubox, mms_dokuji, mms_other
Dim cat_int, cat_pcc, cat_soft, cat_mvl, cat_bns, cat_seiji, cat_kabu, cat_fsn, cat_lif, cat_shumi, cat_res, cat_tvl, cat_spt, cat_gamble, cat_hlt, cat_art, cat_shp, cat_ent, cat_game, cat_movie, cat_uranai, cat_std, cat_shikaku, cat_gogaku, cat_nws, cat_zatsu, cat_kenshou, cat_homepage, cat_hitokoto, cat_other
Dim age_10m, age_10f, age_20m, age_20f, age_30m, age_30f, age_40m, age_40f, age_50m, age_50f
Dim shk_stu, shk_enp, shk_syf, shk_other
Dim are_ht, are_kt, are_cb, are_kk, are_cs, are_ks, are_fr, are_zk

Sub GetTotalBusuu(shisuu, type_a, type_b)
  Dim RS, SQL

  SQL = "SELECT" & vbCrLf & _
        " COUNT(*) AS shisuu," & vbCrLf & _
        " SUM(MG_new_busuu) AS type_a," & vbCrLf & _
        " SUM(MG_new_busuu * MG_shuu_keisuu2) AS type_b" & vbCrLf & _
        "FROM V_VALID_MELMAG" & vbCrLf & _
        "WHERE MG_status=0 AND MG_kakunin_flag<>0" & GetWhere()

  Set RS = Server.CreateObject("ADODB.RecordSet")
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
  If Not RS.EOF Then
    shisuu = RS("shisuu")
    type_a = RS("type_a")
    type_b = RS("type_b")
  End If
  RS.Close
End Sub

Function GetUmariWaku()
  Dim RS, jouken, SUB1, SUB2, SQL

  '条件指定
  jouken = GetWhere()

  '２つの枠が埋まっている誌数の取得
  SUB1 = "SELECT" & vbCrLf & _
         " KK_koukoku_shuu AS S1_koukoku_shuu," & vbCrLf & _
         " COUNT(*) AS shisuu" & vbCrLf & _
         "FROM" & vbCrLf & _
         " (SELECT KK_koukoku_shuu,KS_mag_id,COUNT(*) AS waku" & vbCrLf & _
         "  FROM T_KEISAI INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id" & vbCrLf & _
         "  GROUP BY KK_koukoku_shuu,KS_mag_id) AS sub1 INNER JOIN T_MELMAG ON KS_mag_id=MG_mag_id" & vbCrLf & _
         "WHERE waku>=2" & jouken & vbCrLf & _
         "GROUP BY KK_koukoku_shuu"

  '埋まっている部数の取得
  SUB2 = "SELECT" & vbCrLf & _
         " KK_koukoku_shuu AS S2_koukoku_shuu," & vbCrLf & _
         " SUM(MG_new_busuu) AS type_a," & vbCrLf & _
         " SUM(MG_new_busuu * MG_shuu_keisuu2) AS type_b" & vbCrLf & _
         "FROM (T_KEISAI INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id) INNER JOIN T_MELMAG ON KS_mag_id=MG_mag_id" & vbCrLf & _
         "WHERE MG_status=0" & jouken & vbCrLf & _
         "GROUP BY KK_koukoku_shuu"

  '条件指定が無い場合、予約の部数を加える
  If IsEmpty(Jouken) Then
    SUB2 = "SELECT" & vbCrLf & _
           " ISNULL(S2_koukoku_shuu,KK_koukoku_shuu) AS S2_koukoku_shuu," & vbCrLf & _
           " ISNULL(type_a,0)+ISNULL(busuu,0) AS type_a," & vbCrLf & _
           " ISNULL(type_b,0)+ISNULL(busuu,0) AS type_b" & vbCrLf & _
           "FROM (" & SUB2 & ") AS sub1 FULL OUTER JOIN" & vbCrLf & _
           " (SELECT KK_koukoku_shuu,SUM(KK_busuu) AS busuu" & vbCrLf & _
           "  FROM T_KOUKOKU" & vbCrLf & _
           "  WHERE KK_status=0" & vbCrLf & _
           "  GROUP BY KK_koukoku_shuu) AS sub2 ON S2_koukoku_shuu=KK_koukoku_shuu"
  End If

  '直近１０週分の埋まっている誌数と部数を取得
  SQL = "SELECT WE_shuu_code,WE_start_date,shisuu,type_a,type_b" & vbCrLf & _
        "FROM (T_WEEK" & vbCrLf & _
        " LEFT OUTER JOIN (" & SUB1 & ") AS SUB1 ON WE_shuu_code=S1_koukoku_shuu)" & vbCrLf & _
        " LEFT OUTER JOIN (" & SUB2 & ") AS SUB2 ON WE_shuu_code=S2_koukoku_shuu" & vbCrLf & _
        "WHERE WE_start_date BETWEEN DATEADD(week, -1, GETDATE()) AND DATEADD(week,9,GETDATE())" & vbCrLf & _
				"ORDER BY WE_shuu_code"

  GetUmariWaku = SQL

End Function

Function KoukokuFuriwake(shuu_code, koukoku_id, jogai_id, koukoku_type, keisai_irai, mktemp)
  Dim SUB1, SUB2, SUB3, SQL, rec_count3, count, rank, akiwaku

	If mktemp Then
		'振り分け用テンポラリ削除
		SQL = "DELETE T_FIRIWAKETMP"
		DB.Execute SQL

		'クリック率によるランク分け
		SUB1 = "SELECT KK_koukoku_id FROM V_KOUKOKU WHERE KK_pub_start>DATEADD(week, -8, GETDATE()) AND ISNULL(KK_koukoku_url,'')<>''"
		SUB2 = "SELECT mag_id,SUM(busuu) AS keisai_busuu FROM V_KEISAI_ALL WHERE koukoku_id IN (" & SUB1 & ") GROUP BY mag_id"
		SUB3 = "SELECT CL_mag_id,COUNT(*) AS click_cnt FROM T_CLICKLOG WHERE CL_koukoku_id IN (" & SUB1 & ") GROUP BY CL_mag_id"
		SQL = "SELECT MG_mag_id,CAST(ISNULL(click_cnt,0) AS REAL)/keisai_busuu AS click_rate" & vbCrLf & _
		      "FROM (V_VALID_MELMAG INNER JOIN (" & SUB2 & ") AS SUB2 ON MG_mag_id=mag_id)" & vbCrLf & _
		      " LEFT OUTER JOIN (" & SUB3 & ") AS SUB3 ON MG_mag_id=CL_mag_id" & vbCrLf & _
		      "WHERE keisai_busuu>0" & vbCrLf & _
		      "ORDER BY click_rate DESC"
		RS.Open SQL, DB, adOpenKeyset, adLockReadOnly
		rec_count3 = RS.RecordCount / 3
		count = 0
		Do Until RS.EOF
		  If count < rec_count3 Then
		    rank = 3
		  ElseIf count < rec_count3 * 2 Then
		    rank = 2
		  Else
		    rank = 1
		  End If
		  count = count + 1

		  SQL = "INSERT INTO T_FIRIWAKETMP (FT_mag_id,FT_rank) VALUES ('" & RS("MG_mag_id") & "'," & rank & ")"
		  DB.Execute SQL

		  RS.MoveNext
		Loop
		RS.Close

		'掲載データの無いものはランクB(2)とする
		SQL = "INSERT INTO T_FIRIWAKETMP (FT_mag_id,FT_rank)" & vbCrLf & _
		      "SELECT MG_mag_id,2 FROM V_VALID_MELMAG LEFT OUTER JOIN T_FIRIWAKETMP ON MG_mag_id=FT_mag_id WHERE FT_mag_id IS NULL"
		DB.Execute SQL

		'ランクと乱数と残り枠から掲載依頼優先順位を求める
		SUB1 = "SELECT KS_mag_id,COUNT(*) AS waku_count" & vbCrLf & _
		       "FROM T_KEISAI INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id" & vbCrLf & _
		       "WHERE KK_koukoku_shuu='" & shuu_code & "' AND KS_koukoku_id<>'" & koukoku_id & "'" & vbCrLf & _
		       "GROUP BY KS_mag_id"
		SQL = "SELECT FT_mag_id,FT_rank,FT_order,waku_count FROM T_FIRIWAKETMP LEFT OUTER JOIN (" & SUB1 & ") AS SUB1 ON FT_mag_id=KS_mag_id"
		RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
		Randomize
		Do Until RS.EOF
		  akiwaku = 3 - NVL(RS("waku_count"), 0)
		  If akiwaku > 0 Then
		    SQL = "UPDATE T_FIRIWAKETMP SET FT_order=" & Rnd * RS("FT_rank") * akiwaku & " WHERE FT_mag_id='" & RS("FT_mag_id") & "'"
		    DB.Execute SQL
		  End If

		  RS.MoveNext
		Loop
		RS.Close
	End If

  '除外広告に掲載したメルマガの取得
  SUB1 = "SELECT DISTINCT KS_mag_id FROM T_KEISAI WHERE KS_koukoku_id IN ('" & Replace(jogai_id, ",", "','") & "')"

  '広告タイプにより依頼数と掲載数
  Select Case koukoku_type
  Case "A", "Q"
    SUB2 = "IK_irai_Atype AS irai_count,IK_keisai_Atype AS keisai_count"
  Case "B", "R"
    SUB2 = "IK_irai_Btype AS irai_count,IK_keisai_Btype AS keisai_count"
  End Select

  '掲載依頼優先順位が高い順に並べる
  SQL = "SELECT MG_mag_id,MG_m_name,MG_new_busuu,MG_shuu_keisuu2,MG_hindo," & SUB2 & ",MG_last_update" & vbCrLf & _
        "FROM (T_MELMAG INNER JOIN T_FIRIWAKETMP ON MG_mag_id=FT_mag_id)" & vbCrLf & _
        " LEFT OUTER JOIN T_IRAIKEISAI ON MG_mag_id=IK_mag_id" & vbCrLf & _
        "WHERE MG_status=0 AND MG_kakunin_flag<>0 AND FT_order IS NOT NULL AND MG_mag_id NOT IN (" & SUB1 & ")" & GetWhere() & vbCrLf

  KoukokuFuriwake = SQL
End Function

Function KoukokuFuriwake_old(shuu_code, koukoku_id, jogai_id, koukoku_type, keisai_irai)
  Dim RS, SUB1, SUB2, SUB3, SUB4, SUB5, SQL

  '依頼する週の使用中の枠数を取得
  SUB1 = "SELECT KS_mag_id AS S1_mag_id,COUNT(*) AS waku_count" & vbCrLf & _
         "FROM T_KEISAI INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id" & vbCrLf & _
         "WHERE KK_koukoku_shuu='" & shuu_code & "' AND KS_koukoku_id<>'" & koukoku_id & "'" & vbCrLf & _
         "GROUP BY KS_mag_id"

  '最も最近依頼した週と依頼数を取得
  SUB2 = "SELECT KS_mag_id AS S2_mag_id,MAX(KK_koukoku_shuu) AS koukoku_shuu,COUNT(*) AS irai" & vbCrLf & _
         "FROM T_KEISAI INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id" & vbCrLf & _
         "WHERE KS_koukoku_id<>'" & koukoku_id & "'" & vbCrLf & _
         "GROUP BY KS_mag_id"

  '報告された数を取得
  SUB3 = "SELECT RP_mag_id AS S3_mag_id,COUNT(*) AS houkoku" & vbCrLf & _
         "FROM T_REPORTLOG" & vbCrLf & _
         "GROUP BY RP_mag_id"

  '除外広告に掲載したメルマガの取得
  SUB4 = "SELECT DISTINCT KS_mag_id FROM T_KEISAI WHERE KS_koukoku_id IN ('" & Replace(jogai_id, ",", "','") & "')"

  '広告タイプにより依頼数と掲載数
  Select Case koukoku_type
  Case "A", "Q"
    SUB5 = "IK_irai_Atype AS irai_count,IK_keisai_Atype AS keisai_count"
  Case "B", "R"
    SUB5 = "IK_irai_Btype AS irai_count,IK_keisai_Btype AS keisai_count"
  End Select

  '使用している枠数が少ない、最近依頼していない、掲載率が高い順に並べる
  SQL = "SELECT MG_mag_id,MG_m_name,MG_new_busuu,MG_shuu_keisuu2," & SUB5 & vbCrLf & _
        "FROM (((V_VALID_MELMAG" & vbCrLf & _
        " LEFT OUTER JOIN (" & SUB1 & ") AS SUB1 ON MG_mag_id=S1_mag_id)" & vbCrLf & _
        " LEFT OUTER JOIN (" & SUB2 & ") AS SUB2 ON MG_mag_id=S2_mag_id)" & vbCrLf & _
        " LEFT OUTER JOIN (" & SUB3 & ") AS SUB3 ON MG_mag_id=S3_mag_id)" & vbCrLf & _
        " LEFT OUTER JOIN T_IRAIKEISAI ON MG_mag_id=IK_mag_id" & vbCrLf & _
        "WHERE MG_status=0 AND MG_kakunin_flag<>0 AND MG_mag_id NOT IN (" & SUB4 & ")" & GetWhere() & vbCrLf

  If keisai_irai = 0 Then
    SQL = SQL & "ORDER BY MG_mag_id"
  Else
    SQL = SQL & "ORDER BY ISNULL(waku_count,0),ISNULL(koukoku_shuu,'000000'),ISNULL(houkoku,0)/ISNULL(irai,1) DESC"
  End If

  KoukokuFuriwake = SQL
End Function

Function GetWhere()
  Dim where, s

  where = Empty
  s = Empty
	If mms_melonpan Then
		s = s & " OR MG_mms_melonpan=1"
	End If
	If mms_mag Then
		s = s & " OR MG_mms_mag=1"
	End If
	If mms_pubz Then
		s = s & " OR MG_mms_pubz=1"
	End If
	If mms_melma Then
		s = s & " OR MG_mms_melma=1"
	End If
	If mms_macky Then
		s = s & " OR MG_mms_macky=1"
	End If
	If mms_kapu Then
		s = s & " OR MG_mms_kapu=1"
	End If
	If mms_emag Then
		s = s & " OR MG_mms_emag=1"
	End If
	If mms_tengoku Then
		s = s & " OR MG_mms_tengoku=1"
	End If
	If mms_tiara Then
		s = s & " OR MG_mms_tiara=1"
	End If
	If mms_merubox Then
		s = s & " OR MG_mms_merubox=1"
	End If
	If mms_dokuji Then
		s = s & " OR MG_mms_dokuji=1"
	End If
	If mms_other Then
		s = s & " OR MG_mms_other=1"
	End If
  If Not IsEmpty(s) Then
    where = " AND (" & Mid(s, 5) & ")"
  End If

  s = Empty
	If cat_int Then
		s = s & " OR MG_cat_int=1"
	End If
	If cat_pcc Then
		s = s & " OR MG_cat_pcc=1"
	End If
	If cat_soft Then
		s = s & " OR MG_cat_soft=1"
	End If
	If cat_mvl Then
		s = s & " OR MG_cat_mvl=1"
	End If
	If cat_bns Then
		s = s & " OR MG_cat_bns=1"
	End If
	If cat_seiji Then
		s = s & " OR MG_cat_seiji=1"
	End If
	If cat_kabu Then
		s = s & " OR MG_cat_kabu=1"
	End If
	If cat_fsn Then
		s = s & " OR MG_cat_fsn=1"
	End If
	If cat_lif Then
		s = s & " OR MG_cat_lif=1"
	End If
	If cat_shumi Then
		s = s & " OR MG_cat_shumi=1"
	End If
	If cat_res Then
		s = s & " OR MG_cat_res=1"
	End If
	If cat_tvl Then
		s = s & " OR MG_cat_tvl=1"
	End If
	If cat_spt Then
		s = s & " OR MG_cat_spt=1"
	End If
	If cat_gamble Then
		s = s & " OR MG_cat_gamble=1"
	End If
	If cat_hlt Then
		s = s & " OR MG_cat_hlt=1"
	End If
	If cat_art Then
		s = s & " OR MG_cat_art=1"
	End If
	If cat_shp Then
		s = s & " OR MG_cat_shp=1"
	End If
	If cat_ent Then
		s = s & " OR MG_cat_ent=1"
	End If
	If cat_game Then
		s = s & " OR MG_cat_game=1"
	End If
	If cat_movie Then
		s = s & " OR MG_cat_movie=1"
	End If
	If cat_uranai Then
		s = s & " OR MG_cat_uranai=1"
	End If
	If cat_std Then
		s = s & " OR MG_cat_std=1"
	End If
	If cat_shikaku Then
		s = s & " OR MG_cat_shikaku=1"
	End If
	If cat_gogaku Then
		s = s & " OR MG_cat_gogaku=1"
	End If
	If cat_nws Then
		s = s & " OR MG_cat_nws=1"
	End If
	If cat_zatsu Then
		s = s & " OR MG_cat_zatsu=1"
	End If
	If cat_kenshou Then
		s = s & " OR MG_cat_kenshou=1"
	End If
	If cat_homepage Then
		s = s & " OR MG_cat_homepage=1"
	End If
	If cat_hitokoto Then
		s = s & " OR MG_cat_hitokoto=1"
	End If
	If cat_other Then
		s = s & " OR MG_cat_other=1"
	End If
  If Not IsEmpty(s) Then
    where = where & " AND (" & Mid(s, 5) & ")"
  End If

  s = Empty
  If age_10m then
  	s = s & " OR MG_age_10m=1"
  End If
  If age_10f then
  	s = s & " OR MG_age_10f=1"
  End If
  If age_20m then
  	s = s & " OR MG_age_20m=1"
  End If
  If age_20f then
  	s = s & " OR MG_age_20f=1"
  End If
  If age_30m then
  	s = s & " OR MG_age_30m=1"
  End If
  If age_30f then
  	s = s & " OR MG_age_30f=1"
  End If
  If age_40m then
  	s = s & " OR MG_age_40m=1"
  End If
  If age_40f then
  	s = s & " OR MG_age_40f=1"
  End If
  If age_50m then
  	s = s & " OR MG_age_50m=1"
  End If
  If age_50f then
  	s = s & " OR MG_age_50f=1"
  End If
  If Not IsEmpty(s) Then
    where = where & " AND (" & Mid(s, 5) & ")"
  End If

  s = Empty
  If shk_stu then
  	s = s & " OR MG_shk_stu=1"
  End If
  If shk_enp then
  	s = s & " OR MG_shk_enp=1"
  End If
  If shk_syf then
  	s = s & " OR MG_shk_syf=1"
  End If
  If shk_other then
  	s = s & " OR MG_shk_other=1"
  End If
  If Not IsEmpty(s) Then
    where = where & " AND (" & Mid(s, 5) & ")"
  End If

  s = Empty
  If are_ht then
  	s = s & " OR MG_are_ht=1"
  End If
  If are_kt then
  	s = s & " OR MG_are_kt=1"
  End If
  If are_cb then
  	s = s & " OR MG_are_cb=1"
  End If
  If are_kk then
  	s = s & " OR MG_are_kk=1"
  End If
  If are_cs then
  	s = s & " OR MG_are_cs=1"
  End If
  If are_ks then
  	s = s & " OR MG_are_ks=1"
  End If
  If are_fr then
  	s = s & " OR MG_are_fr=1"
  End If
  If are_zk then
  	s = s & " OR MG_are_zk=1"
  End If
  If Not IsEmpty(s) Then
    where = where & " AND (" & Mid(s, 5) & ")"
  End If

  GetWhere = where
End Function
%>