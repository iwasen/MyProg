<%
Private Sub SetClickCountURL(sKoukoku, vKoukokuId, vMagId, vKoukokuUrl)

  Dim skoukokuId
  Dim sMagId
  Dim sRand
  Dim iOffset
  Dim iCheckSum
  Dim sParam
  Dim sURL
  Dim i
  Dim sTemp
  Const ClickURL = "http://www.melmel.net/m.asp?p=123456789012"

  If IsNull(vKoukokuUrl) Or IsEmpty(vKoukokuUrl) Then
    Exit Sub
  End If

  skoukokuId = vKoukokuId
  sMagId = vMagId

  '乱数列
  sRand = "5374112481591282435131234042196122667712144723901683762966630824862004266405453034177296253053378416"

  '広告ID暗号化
  iOffset = CLng(sMagId) Mod 100
  sTemp = ""
  For i = 1 To 6
    sTemp = sTemp & Chr((CInt(Mid(skoukokuId, i, 1)) + Cint(Mid(sRand, iOffset + 1, 1))) Mod 10 + Asc("0"))
    iOffset = (iOffset + 1) Mod 100
  Next
  skoukokuId = sTemp

  'メルマガID暗号化
  iOffset = CLng(skoukokuId) Mod 100
  sTemp = ""
  For i = 1 To 5
    sTemp = sTemp & Chr((CInt(Mid(sMagId, i, 1)) + CInt(Mid(sRand, iOffset + 1, 1))) Mod 10 + Asc("0"))
    iOffset = (iOffset + 1) Mod 100
  Next
  sMagId = sTemp

  'パラメータ生成
  sParam = skoukokuId & sMagId

  'チェックサム付加
  iCheckSum = 0
  For i = 1 To 11
    iCheckSum = (iCheckSum + CInt(Mid(sParam, i, 1))) Mod 10
  Next
  sParam = sParam & Chr((10 - iCheckSum) Mod 10 + Asc("0"))

  'クリックカウント用URL埋め込み
  sURL = Replace(ClickURL, "123456789012", sParam)
  sKoukoku = Replace(sKoukoku, ClickURL, sURL)

End Sub
%>
