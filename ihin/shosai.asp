<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>詳細</title>


<meta name="Microsoft Theme" content="mstheme22243 011, default"><meta name="Microsoft Border" content="t, default"></head>

<body background="_themes/mstheme22243/sumtextb.jpg" bgcolor="#FFFFFF" text="#000066" link="#3333CC" vlink="#3333CC" alink="#990099"><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><!--mstheme--><font face="ＭＳ Ｐゴシック">

<p align="left"><font size="6"><strong><img src="_derived/shosai.asp_cmp_mstheme22243010_bnr.gif" width="600" height="60" border="0" alt="詳細"></strong></font></p>

<!--mstheme--></font></td></tr><!--msnavigation--></table><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><!--msnavigation--><td valign="top"><!--mstheme--><font face="ＭＳ Ｐゴシック"><!--mstheme--></font><table border="1" bordercolordark="#003366" bordercolorlight="#C0C0C0">
	<tr>
	<%SetShosaiData%>
	<td colspan="2" height="200" valign="top"><!--mstheme--><font face="ＭＳ Ｐゴシック"><a href="gazou.asp">画像（大）をみる</a>
	<select size="5" name="Kanren" width="100"></select>      
    <!--mstheme--></font></td>      
</table><!--mstheme--><font face="ＭＳ Ｐゴシック">  
<!--msthemeseparator--><p align="left"><img src="_themes/mstheme22243/sumhorsa.gif" width="600" height="10"></p> 
<a href="ichiran.asp"><img border="0" src="images/b11_036.gif" width="90" height="22"></a>    
<p>　</p>    
<!--mstheme--></font><!--msnavigation--></td></tr><!--msnavigation--></table></body> 
 
<%    
Private Sub SetShosaiData    
	Dim KomokuNo(10)    
	Dim KomokuName(10)    
    
	'遺品ＤＢオブジェクト取得    
	Set MyCon = Session("xxxNDB")    
    
	'SQL検索条件編集    
	SqlWhere = "画像フォルダID='" & Request("folder") & "' AND 画像ID='" & Request("id") & "'"    
    
	'大分類コード取得    
	Sql = "SELECT 大分類コード FROM F_GAZOU WHERE " & SqlWhere    
	Set MyRS = MyCon.Execute(Sql)    
    
	'コントロールテーブルから詳細表示内容を取得SQL    
	Sql = "SELECT 項目番号,項目名称,詳細表示フラグ FROM M_CONTROL " & _    
		"WHERE 大分類コード='" & MyRS.Fields(0) & "' " & _    
		"ORDER BY 項目番号"    
	Set MyRS = MyCon.Execute(Sql)    
    
	'コントロールテーブルから一覧表示内容を取得    
	nItem = 0    
	Do While (Not MyRS.Eof)    
		If MyRS.Fields("詳細表示フラグ") = True Then    
			nItem  = nItem + 1    
			KomokuNo(nItem) = MyRS.Fields("項目番号")    
			KomokuName(nItem) = MyRS.Fields("項目名称")    
		End If    
		MyRS.MoveNext    
	Loop    
	MyRS.Close    
    
	'SQL抽出項目編集    
	SqlSelect = "画像フォルダID,画像ID"    
	For i = 1 To nItem     
		SqlSelect = SqlSelect + ",属性"  & KomokuNo(i)    
	Next    
    
	'SQL文編集＆実行    
	Sql = "SELECT " & SqlSelect & " FROM F_GAZOU WHERE " & SqlWhere    
	Set MyRS = MyCon.Execute(Sql)    
    
	'検索結果出力    
	If (Not MyRS.Eof) Then    
		For i = 1 To nItem    
			Response.Write "<tr>" & vbcrlf    
			Response.Write "<td width=""100""><b>" & KomokuName(i) & "</b></td>" & vbcrlf    
			sData = MyRS.Fields("属性" & KomokuNo(i))    
			If IsNull(sData) Then    
				sData = "　"    
			End If    
			Response.Write "<td>" & sData & "</td>" & vbcrlf    
			If i = 1 Then    
				Response.Write "<td rowspan=20 valign=""top""><a href=""gazou.asp""><img src=""images/img0061s.jpg"" width=""410""></a></td>"    
			End If    
			Response.Write "</tr>" & vbcrlf    
		Next    
	End If    
    
	MyRS.Close    
End Sub    
%>    
    
</html>    
