<!--#INCLUDE FILE="../melmel_inc.asp"-->
<!--#INCLUDE FILE="admin_inc.asp"-->
<!--#INCLUDE FILE="kaiyaku_inc.asp"-->
<%
'******************************************************
' System :�߂�߂�Enet�����Ǘp�y�[�W
' Content:���s��ID��������
'******************************************************

SetHeaderTitle "���s��ID������������", TC_HAKKOUSHA
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

<%
Dim DB, RS, RS2, SQL, mel_id, mel_id2, msg

'�p�����[���擾
mel_id = Request.Form("mel_id")
mel_id2 = Request.Form("mel_id2")
If Not IsEmpty(mel_id) and Not IsEmpty(mel_id2) Then
  Set DB = OpenDB()
  DB.BeginTrans
  Set RS = Server.CreateObject("ADODB.RecordSet")
  SQL = "SELECT MM_point FROM T_MELMEL WHERE MM_mel_id='" & mel_id & "'"
  RS.Open SQL, DB, adOpenKeyset, adLockPessimistic
  If Not RS.EOF Then
    Set RS2 = Server.CreateObject("ADODB.RecordSet")
    SQL = "SELECT MM_point FROM T_MELMEL WHERE MM_mel_id='" & mel_id2 & "'"
    RS2.Open SQL, DB, adOpenKeyset, adLockPessimistic
    If Not RS2.EOF Then
      '�|�C���g���Z
      RS("MM_point") = CDbl(RS("MM_point")) + CDbl(RS2("MM_point"))
      RS.Update

      '�����}�K�}�X�^�̔��s��ID�ύX
      SQL = "UPDATE T_MELMAG SET MG_mel_id='" & mel_id & "' WHERE MG_mel_id='" & mel_id2 & "'"
      DB.Execute SQL

      '�|�C���g���Z���O�̔��s��ID�ύX
      SQL = "UPDATE T_SPOINT SET SP_mel_id='" & mel_id & "' WHERE SP_mel_id='" & mel_id2 & "'"
      DB.Execute SQL

      '�|�C���g�Ǘ����O�̔��s��ID�ύX
      SQL = "UPDATE T_MPOINT SET MP_mel_id='" & mel_id & "' WHERE MP_mel_id='" & mel_id2 & "'"
      DB.Execute SQL

      '��������ID���폜
      RS2.Delete

      msg = "�����͐���ɏI�����܂����B"
    Else
      msg = "���s��ID[" & mel_id2 & "]�͓o�^����Ă��܂���B"
    End If
    RS2.Close
  Else
    msg = "���s��ID[" & mel_id & "]�͓o�^����Ă��܂���B"
  End If
  RS.Close

  DB.CommitTrans
End If
%>

<center>
<p><%=msg%></p>
<input type="button" value=" OK " onClick="location.href='merge.asp'">
</center>

<!--#INCLUDE FILE="admin_footer.asp"-->

</body>
</html>
