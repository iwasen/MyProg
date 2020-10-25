<%
Const TC_HAKKOUSHA = "aa4488"
Const TC_KOUKOKU = "44aa88"
Const TC_MASTER = "aa8844"
Const TC_OTHER = "888888"

Const sDefaultStartLine = "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-//    PR//-=-="
Const sDefaultEndLine = "=-=-//KKKKKK-MMMMM//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-="

Dim title_text, title_color

'=== ヘッダタイトル設定 ===
Sub SetHeaderTitle(title, color)
  title_text = title
  title_color = color
End Sub

'=== エラーメッセージ表示 ===
Sub ShowErrorMessage(message)
  Session("err_msg") = message
  Server.Transfer("error_msg.asp")
End Sub

'=== </xmp>を表示可能文字に変換 ===
Function ReplaceXmp(text)
  ReplaceXmp = Replace(text, "</xmp>", "<／xmp>")
  ReplaceXmp = Replace(ReplaceXmp, "</XMP>", "<／XMP>")
End Function

'=== 週発行係数の整数化 ===
Function KeisuuInt(keisuu)
  KeisuuInt = Int(keisuu)
  If KeisuuInt = 0 Then
    KeisuuInt = 1
  End If
End Function

'=== 空文字をNullに変換 ===
Function BlankToNull(text)
  If text = "" Then
    BlankToNull = Null
  Else
    BlankToNull = text
  End If
End Function
%>