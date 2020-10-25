<!--#INCLUDE FILE="../melmel_inc.asp"-->
<%
'******************************************************
' System :めるめる・net事務局用ページ
' Content:掲載実績報告書出力済み設定処理
'******************************************************

Dim DB, RS, RS2, RS3, SQL, SUB1, SUB2, koukoku_id

koukoku_id = Request.QueryString("id")
If Not IsEmpty(koukoku_id) Then
  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")
  Set RS2 = Server.CreateObject("ADODB.RecordSet")
  Set RS3 = Server.CreateObject("ADODB.RecordSet")
  SQL = "SELECT * FROM V_KOUKOKU WHERE (KK_koukoku_id='" & koukoku_id & "' OR KK_tsuika_id='" & koukoku_id & "') AND KK_status<>9"
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic

  Do Until RS.EOF
    SUB1 = "SELECT KS_mag_id AS irai_mag_id,COUNT(*) AS irai_cnt FROM T_KEISAI WHERE KS_koukoku_id='" & RS("KK_koukoku_id") & "' GROUP BY KS_mag_id"
    SUB2 = "SELECT mag_id AS keisai_mag_id,COUNT(*) AS keisai_cnt FROM V_KEISAI_ALL WHERE koukoku_id='" & RS("KK_koukoku_id") & "' GROUP BY mag_id"
    SQL = "SELECT * FROM (" & SUB1 & ") AS SUB1 LEFT OUTER JOIN (" & SUB2 & ") AS SUB2 ON irai_mag_id=keisai_mag_id"
    RS2.Open SQL, DB, adOpenForwardOnly, adLockReadOnly
    Do Until RS2.EOF
      SQL = "SELECT * FROM T_IRAIKEISAI WHERE IK_mag_id='" & RS2("irai_mag_id") & "'"
      RS3.Open SQL, DB, adOpenKeyset, adLockPessimistic
      If RS3.EOF Then
        RS3.AddNew
        RS3("IK_mag_id") = RS2("irai_mag_id")
        Select Case RS("KK_koukoku_type")
        Case "A", "Q"
          RS3("IK_irai_Atype") = RS2("irai_cnt")
          RS3("IK_keisai_Atype") = NVL(RS2("keisai_cnt"), 0)
          RS3("IK_irai_Btype") = 0
          RS3("IK_keisai_Btype") = 0
        Case "B", "R"
          RS3("IK_irai_Atype") = 0
          RS3("IK_keisai_Atype") = 0
          RS3("IK_irai_Btype") = RS2("irai_cnt")
          RS3("IK_keisai_Btype") = NVL(RS2("keisai_cnt"), 0)
        End Select
      Else
        Select Case RS("KK_koukoku_type")
        Case "A", "Q"
          RS3("IK_irai_Atype") = RS3("IK_irai_Atype") + RS2("irai_cnt")
          RS3("IK_keisai_Atype") = RS3("IK_keisai_Atype") + NVL(RS2("keisai_cnt"), 0)
        Case "B", "R"
          RS3("IK_irai_Btype") = RS3("IK_irai_Btype") + RS2("irai_cnt")
          RS3("IK_keisai_Btype") = RS3("IK_keisai_Btype") + NVL(RS2("keisai_cnt"), 0)
        End Select
      End If
      RS3.Update
      RS3.Close

      RS2.MoveNext
    Loop

    RS2.Close

    RS("KK_status") = 9
    RS.Update
    RS.MoveNext
  Loop

  RS.Close
End If

Response.Redirect("report.asp")
%>
