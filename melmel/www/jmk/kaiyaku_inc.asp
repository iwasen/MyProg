<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:��񏈗�
'******************************************************

Function Kaiyaku(mag_id)
  Dim DB, RS, SQL
  Dim subject, mailfrom, mailto, body, result

  Kaiyaku = false

  Set DB = OpenDB()
  Set RS = Server.CreateObject("ADODB.RecordSet")
  SQL = "SELECT MG_m_name,MM_h_name,MM_mail_adr" & vbCrLf & _
        "FROM T_MELMAG INNER JOIN T_MELMEL ON MG_mel_id=MM_mel_id" & vbCrLf & _
        "WHERE MG_mag_id='" & mag_id & "'"
  RS.Open SQL, DB, adOpenForwardOnly, adLockReadOnly

  If Not RS.EOF Then
    '�{�o�^�������[�����M
    GetSendMailInfo "CANCEL", subject, mailfrom, body
    body = Replace(body, "%MAGAZINE%", RS("MG_m_name"))
    body = Replace(body, "%HAKKOUSHA%", RS("MM_h_name"))
    mailto = RS("MM_mail_adr")
    result = SendMail(mailto, mailfrom, subject, body)
    If result = "" Then
      '�����}�K�}�X�^����폜
      SQL = "DELETE T_MELMAG WHERE MG_mag_id='" & mag_id & "'"
      DB.Execute SQL
      Kaiyaku = True
    Else
      ShowErrorMessage "���[���̑��M�Ɏ��s���܂����B[" & result & "]"
    End If
  Else
    ShowErrorMessage "�w�肳�ꂽ�h�c�������}�K�}�X�^�ɑ��݂��܂���B"
  End If
  RS.Close
End Function
%>