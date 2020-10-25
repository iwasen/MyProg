<%
Sub SetItemList(sDaibunruiCode, sChubunruiCode)
 	Set MyCon = Session("xxxNDB")
	Sql = "SELECT DISTINCT 属性01 FROM F_GAZOU WHERE 大分類コード='" & sDaibunruiCode & "'"
	if sChubunruiCode <> "" Then
		Sql = Sql + " AND 中分類コード='" & sChubunruiCode & "'"
	End If

	Set MyRS = MyCon.Execute(SQL)

	Response.Write "<option value="""" default>*全て*"

	Do While (Not MyRS.Eof)
		sValue = MyRS.Fields("属性01")
		If IsNull(sValue) then
			sText = "*未設定*"
		else
			sText = sValue
		End If
		Response.Write "<option value=""" & sValue & """>" & sText & "</option>" & vbcrlf
		MyRS.MoveNext
	Loop

	MyRS.Close
End Sub

Sub SetShobunrui(sDaibunruiCode, sChubunruiCode)
 	Set MyCon = Session("xxxNDB")
	Sql = "SELECT DISTINCT 小分類 FROM F_GAZOU WHERE 大分類コード='" & sDaibunruiCode & "'"
	if sChubunruiCode <> "" Then
		Sql = Sql + " AND 中分類コード='" & sChubunruiCode & "'"
	End If
	Sql = Sql + " ORDER BY 小分類"
	Set MyRS = MyCon.Execute(SQL)

	Response.Write "<input type=""radio"" name=""Shobunrui"" value="""" checked>全て<br>"

	Do While (Not MyRS.Eof)
		sShobunrui = MyRS.Fields("小分類")
		If Not IsNull(sZokusei) then
			Response.Write "<input type=""radio"" name=""Shobunrui"" value=""" & sShobunrui & """>" & sShobunrui & "<br>" & vbcrlf
		End If
		MyRS.MoveNext
	Loop

	MyRS.Close
End Sub
%>
