<html>

<%
Dim sKomokuNo(10)
Dim sKomokuName(10)
Dim nItem
Dim nPageSize, nPageCount
Dim sDaibunrui
Dim conDB
Dim rsGazou
%>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>検索結果一覧</title>



<meta name="Microsoft Theme" content="mstheme22243 011, default"><meta name="Microsoft Border" content="t, default"></head>
<%Initialize%>
<body background="_themes/mstheme22243/sumtextb.jpg" bgcolor="#FFFFFF" text="#000066" link="#3333CC" vlink="#3333CC" alink="#990099"><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><!--mstheme--><font face="ＭＳ Ｐゴシック">

<p align="left"><font size="6"><strong><img src="_derived/ichiran.asp_cmp_mstheme22243010_bnr.gif" width="600" height="60" border="0" alt="検索結果一覧"></strong></font></p>

<!--mstheme--></font></td></tr><!--msnavigation--></table><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><!--msnavigation--><td valign="top"><!--mstheme--><font face="ＭＳ Ｐゴシック"><!--mstheme--></font><table border="1" align="right" bordercolordark="#003366" bordercolorlight="#C0C0C0">
<%OutputPage%>
</table><!--mstheme--><font face="ＭＳ Ｐゴシック">
<form action="ichiran.asp" method="POST">
<%SetPageButton%>
</form>

<!--mstheme--></font><table border="1" width="100%" bordercolordark="#003366" bordercolorlight="#C0C0C0">
<%OutputData%>
</table><!--mstheme--><font face="ＭＳ Ｐゴシック">
<%
	If rsGazou.PageCount = 0 Then
		Response.Write "<br><a href=""JavaScript:history.back()"">該当するデータは１件もありません。</a>"
	End If
%>
<!--msthemeseparator--><p align="left"><img src="_themes/mstheme22243/sumhorsa.gif" width="600" height="10"></p>
<a href="default.asp"><img border="0" src="images/b11_036.gif" WIDTH="90" HEIGHT="22"></a>
<!--mstheme--></font><!--msnavigation--></td></tr><!--msnavigation--></table></body>

<%
Private Sub OutputPage()
	Response.Write "<tr>" & vbcrlf
	Response.Write "<td width=60 align=""right"">" & rsGazou.RecordCount & "件</td>" & vbvrlf
	Response.Write "<td width=100 align=""right"">" & nPageCount & "/" & rsGazou.PageCount & "ﾍﾟｰｼﾞ</td>" & vbcrlf
	Response.Write "</tr>" & vbcrlf
End Sub

Private Sub Initialize()

	GetParameter

	GetItemInfo

	if Not IsObject(rsGazou) Then
		CreateIchiranRS
	End If

End Sub

Private Sub GetParameter()
	nPageSize = 10

	'遺品ＤＢオブジェクト取得
	Set conDB = Session("xxxNDB")

	Select Case Request("PageMove")
	Case "前ページ"
		Set rsGazou = Session("IchiranRS")
		nPageCount = Session("PageCount") - 1
		sDaibunrui = Session("Daibunrui")
	Case "次ページ"
		Set rsGazou = Session("IchiranRS")
		nPageCount = Session("PageCount") + 1
		sDaibunrui = Session("Daibunrui")
	Case Else
		nPageCount = 1
		sDaibunrui = Request("Daibunrui")
		Session("Daibunrui") = sDaibunrui
	End Select

	Session("PageCount") = nPageCount

End Sub

Private Sub GetItemInfo()
	'コントロールテーブルから一覧表示内容を取得SQL
	Sql = "SELECT 項目番号,項目名称,一覧表示フラグ FROM M_CONTROL " & _
		"WHERE 大分類コード='" & sDaibunrui & "' " & _
		"ORDER BY 項目番号"
	Set rsControl = conDB.Execute(Sql)

	'コントロールテーブルから一覧表示内容を取得
	nItem = 0
	Do While (Not rsControl.Eof)
		If rsControl.Fields("一覧表示フラグ") = True Then
			nItem = nItem + 1
			sKomokuNo(nItem) = rsControl.Fields("項目番号")
			sKomokuName(nItem) = rsControl.Fields("項目名称")
		End If
		rsControl.MoveNext
	Loop
	rsControl.Close
End Sub

Private Sub CreateIchiranRS()
	'SQL抽出項目編集
	SqlSelect = "画像フォルダID,画像ID"
	For i = 1 To nItem
		SqlSelect = SqlSelect + ",属性" & sKomokuNo(i)
	Next

	'SQL検索条件編集
	SqlWhere = "大分類コード='" & Request("Daibunrui") & "'"
	If Request("Chubunrui") <> "" then
		SqlWhere = SqlWhere & " AND 中分類コード='" & Request("Chubunrui") & "'"
	End If
	If Request("Shobunrui") <> "" then
		SqlWhere = SqlWhere & " AND 小分類='" & Request("Shobunrui") & "'"
	End If
	For i = 1 To 10
		sZokusei = "Zokusei" & i
		If Request(sZokusei) <> "" Then
			sField = "属性" & Right("0" & i, 2)
			SqlWhere = SqlWhere & " AND " & sField & "='" & Request(sZokusei) & "'"
		End If
	Next
	If Request("Zokusei2F") <> "" Then
		'SqlWhere = SqlWhere & " AND " & "CDate(属性02)>=CDate('" & Request("Zokusei2F") & "')"
		SqlWhere = SqlWhere & " AND " & "属性02>='" & Request("Zokusei2F") & "'"
	End If
	If Request("Zokusei2T") <> "" Then
		'SqlWhere = SqlWhere & " AND " & "CDate(属性02)<=CDate('" & Request("Zokusei2T") & "')"
		SqlWhere = SqlWhere & " AND " & "属性02<='" & Request("Zokusei2T") & "'"
	End If

	'SQLオーダ編集
	SqlOrder = "属性01,画像フォルダID,画像ID"

	'SQL文編集＆実行
	Sql = "SELECT " & SqlSelect & " FROM F_GAZOU WHERE " & SqlWhere & " ORDER BY " & SqlOrder
	Set rsGazou = CreateObject("ADODB.Recordset")
	rsGazou.CursorLocation = 3'adUseClient
	rsGazou.PageSize = nPageSize
	rsGazou.Open Sql, conDB, adOpenKeyset
	Set Session("IchiranRS") = rsGazou
End Sub

Private Sub OutputData()
	On Error Resume Next
	
	'表のヘッダ（見出し）を出力
	Response.Write "<tr>" & vbcrlf
	For i = 1 To nItem
		Response.Write "<th>" & sKomokuName(i) & "</th>" & vbcrlf
	Next
	Response.Write "</tr>" & vbcrlf

	rsGazou.AbsolutePage = nPageCount

	'検索結果出力
	For j = 1 To nPageSize
		If rsGazou.Eof Then
			Exit For
		End If

		Response.Write "<tr>" & vbcrlf
		For i = 1 To nItem
			Response.Write "<td>"
			If i = 1 Then
				Response.Write "<a href=shosai.asp?folder=" & rsGazou.Fields("画像フォルダID")& "&id=" & rsGazou.Fields("画像ID") & ">"
			End If
			sData = rsGazou.Fields("属性" & sKomokuNo(i))
			If IsNull(sData) Then
				If i = 1 Then
					sData = "*未設定*"
				Else
					sData = "　"
				End If
			End If
			Response.Write sData & "</td>" & vbcrlf
		Next
		Response.Write "</tr>" & vbcrlf
		rsGazou.MoveNext
	Next

End Sub

Private Sub SetPageButton()
	If nPageCount < rsGazou.PageCount Then
		sDisabled = ""
	Else
		sDisabled = " disabled"
	End If
	Response.Write "<input type=""submit"" value=""次ページ"" name=""PageMove""" & sDisabled & ">" & vbcrlf

	If nPageCount > 1 Then
		sDisabled = ""
	Else
		sDisabled = " disabled"
	End If
	Response.Write "<input type=""submit"" value=""前ページ"" name=""PageMove""" & sDisabled & ">" & vbcrlf

	Response.Write "<input type=""submit"" value=""サムネイル"" name=""PageMove"">" & vbcrlf
End Sub

%>

</html>
