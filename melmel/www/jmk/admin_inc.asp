<%
Const TC_HAKKOUSHA = "aa4488"
Const TC_KOUKOKU = "44aa88"
Const TC_MASTER = "aa8844"
Const TC_OTHER = "888888"

Const sDefaultStartLine = "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//    PR//-=-="
Const sDefaultEndLine = "=-=-//KKKKKK-MMMMM//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-="

Dim title_text, title_color

'=== �w�b�_�^�C�g���ݒ� ===
Sub SetHeaderTitle(title, color)
  title_text = title
  title_color = color
End Sub

'=== �G���[���b�Z�[�W�\�� ===
Sub ShowErrorMessage(message)
  Session("err_msg") = message
  Server.Transfer("error_msg.asp")
End Sub

'=== </xmp>��\���\�����ɕϊ� ===
Function ReplaceXmp(text)
  ReplaceXmp = Replace(text, "</xmp>", "<�^xmp>")
  ReplaceXmp = Replace(ReplaceXmp, "</XMP>", "<�^XMP>")
End Function

'=== �T���s�W���̐����� ===
Function KeisuuInt(keisuu)
  KeisuuInt = Int(keisuu)
  If KeisuuInt = 0 Then
    KeisuuInt = 1
  End If
End Function

'=== �󕶎���Null�ɕϊ� ===
Function BlankToNull(text)
  If text = "" Then
    BlankToNull = Null
  Else
    BlankToNull = text
  End If
End Function
%>