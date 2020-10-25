<%
'******************************************************
' System :�߂�߂�Enet�L��������y�[�W
' Content:�g�Ǘ����ʏ���
'******************************************************

Dim mms_melonpan, mms_mag, mms_pubz, mms_melma, mms_macky, mms_kapu, mms_emag, mms_tengoku, mms_tiara, mms_merubox, mms_dokuji, mms_other
Dim cat_int, cat_pcc, cat_soft, cat_mvl, cat_bns, cat_seiji, cat_kabu, cat_fsn, cat_lif, cat_shumi, cat_res, cat_tvl, cat_spt, cat_gamble, cat_hlt, cat_art, cat_shp, cat_ent, cat_game, cat_movie, cat_uranai, cat_std, cat_shikaku, cat_gogaku, cat_nws, cat_zatsu, cat_kenshou, cat_homepage, cat_hitokoto, cat_other
Dim age_10m, age_10f, age_20m, age_20f, age_30m, age_30f, age_40m, age_40f, age_50m, age_50f
Dim shk_stu, shk_enp, shk_syf, shk_other
Dim are_ht, are_kt, are_cb, are_kk, are_cs, are_ks, are_fr, are_zk

Sub GetTotalBusuu(koukoku_id, type_a, type_b)
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
    type_a = RS("type_a")
    type_b = RS("type_b")
  End If
  RS.Close
End Sub

Function GetUmariWaku(shuu_code)
  Dim RS, jouken, SUB1, SUB2, SQL

  '�����w��
  jouken = GetWhere()

  '�Q�̘g�����܂��Ă��鎏���̎擾
  SUB1 = "SELECT" & vbCrLf & _
         " KK_koukoku_shuu AS S1_koukoku_shuu," & vbCrLf & _
         " COUNT(*) AS shisuu" & vbCrLf & _
         "FROM" & vbCrLf & _
         " (SELECT KK_koukoku_shuu,KS_mag_id,COUNT(*) AS waku" & vbCrLf & _
         "  FROM T_KEISAI INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id" & vbCrLf & _
         "  GROUP BY KK_koukoku_shuu,KS_mag_id) AS sub1 INNER JOIN T_MELMAG ON KS_mag_id=MG_mag_id" & vbCrLf & _
         "WHERE waku>=2" & jouken & vbCrLf & _
         "GROUP BY KK_koukoku_shuu"

  '���܂��Ă��镔���̎擾
  SUB2 = "SELECT" & vbCrLf & _
         " KK_koukoku_shuu AS S2_koukoku_shuu," & vbCrLf & _
         " SUM(MG_new_busuu) AS type_a," & vbCrLf & _
         " SUM(MG_new_busuu * MG_shuu_keisuu2) AS type_b" & vbCrLf & _
         "FROM (T_KEISAI INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id) INNER JOIN T_MELMAG ON KS_mag_id=MG_mag_id" & vbCrLf & _
         "WHERE MG_status=0" & jouken & vbCrLf & _
         "GROUP BY KK_koukoku_shuu"

  '�����w�肪�����ꍇ�A�\��̕�����������
  If IsEmpty(Jouken) Then
    SUB2 = "SELECT" & vbCrLf & _
           " ISNULL(S2_koukoku_shuu,KK_koukoku_shuu) AS S2_koukoku_shuu," & vbCrLf & _
           " ISNULL(type_a,0)+ISNULL(busuu,0) AS type_a," & vbCrLf & _
           " ISNULL(type_b,0)+ISNULL(busuu,0) AS type_b" & vbCrLf & _
           "FROM (" & SUB2 & ") AS sub1 FULL OUTER JOIN" & vbCrLf & _
           " (SELECT KK_koukoku_shuu,SUM(KK_busuu) AS busuu" & vbCrLf & _
           "  FROM V_KOUKOKU" & vbCrLf & _
           "  WHERE KK_status=0 AND KK_koukoku_id<>'" & koukoku_id & "'" & vbCrLf & _
           "  GROUP BY KK_koukoku_shuu) AS sub2 ON S2_koukoku_shuu=KK_koukoku_shuu"
  End If

  '���߂P�O�T���̖��܂��Ă��鎏���ƕ������擾
  SQL = "SELECT WE_shuu_code,WE_start_date,shisuu,type_a,type_b" & vbCrLf & _
        "FROM (T_WEEK" & vbCrLf & _
        " LEFT OUTER JOIN (" & SUB1 & ") AS SUB1 ON WE_shuu_code=S1_koukoku_shuu)" & vbCrLf & _
        " LEFT OUTER JOIN (" & SUB2 & ") AS SUB2 ON WE_shuu_code=S2_koukoku_shuu" & vbCrLf & _
        "WHERE WE_shuu_code='" & shuu_code & "'"

  GetUmariWaku = SQL

End Function

Function KoukokuFuriwake(shuu_code, koukoku_id, jogai_id, koukoku_type, keisai_irai)
  Dim RS, SUB1, SUB2, SUB3, SUB4, SUB5, SQL

  '�˗�����T�̎g�p���̘g�����擾
  SUB1 = "SELECT KS_mag_id AS S1_mag_id,COUNT(*) AS waku_count" & vbCrLf & _
         "FROM T_KEISAI INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id" & vbCrLf & _
         "WHERE KK_koukoku_shuu='" & shuu_code & "' AND KS_koukoku_id<>'" & koukoku_id & "'" & vbCrLf & _
         "GROUP BY KS_mag_id"

  '�ł��ŋ߈˗������T�ƈ˗������擾
  SUB2 = "SELECT KS_mag_id AS S2_mag_id,MAX(KK_koukoku_shuu) AS koukoku_shuu,COUNT(*) AS irai" & vbCrLf & _
         "FROM T_KEISAI INNER JOIN V_KOUKOKU ON KS_koukoku_id=KK_koukoku_id" & vbCrLf & _
         "WHERE KS_koukoku_id<>'" & koukoku_id & "'" & vbCrLf & _
         "GROUP BY KS_mag_id"

  '�񍐂��ꂽ�����擾
  SUB3 = "SELECT RP_mag_id AS S3_mag_id,COUNT(*) AS houkoku" & vbCrLf & _
         "FROM T_REPORTLOG" & vbCrLf & _
         "GROUP BY RP_mag_id"

  '���O�L���Ɍf�ڂ��������}�K�̎擾
  SUB4 = "SELECT DISTINCT KS_mag_id FROM T_KEISAI WHERE KS_koukoku_id IN ('" & Replace(jogai_id, ",", "','") & "')"

  '�L���^�C�v�ɂ��˗����ƌf�ڐ�
  Select Case koukoku_type
  Case "A", "Q"
    SUB5 = "IK_irai_Atype AS irai_count,IK_keisai_Atype AS keisai_count"
  Case "B", "R"
    SUB5 = "IK_irai_Btype AS irai_count,IK_keisai_Btype AS keisai_count"
  End Select

  '�g�p���Ă���g�������Ȃ��A�ŋ߈˗����Ă��Ȃ��A�f�ڗ����������ɕ��ׂ�
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