<%
Sub SetItemList(sDaibunruiCode, sChubunruiCode)
 	Set MyCon = Session("xxxNDB")
	Sql = "SELECT DISTINCT ����01 FROM F_GAZOU WHERE �啪�ރR�[�h='" & sDaibunruiCode & "'"
	if sChubunruiCode <> "" Then
		Sql = Sql + " AND �����ރR�[�h='" & sChubunruiCode & "'"
	End If

	Set MyRS = MyCon.Execute(SQL)

	Response.Write "<option value="""" default>*�S��*"

	Do While (Not MyRS.Eof)
		sValue = MyRS.Fields("����01")
		If IsNull(sValue) then
			sText = "*���ݒ�*"
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
	Sql = "SELECT DISTINCT ������ FROM F_GAZOU WHERE �啪�ރR�[�h='" & sDaibunruiCode & "'"
	if sChubunruiCode <> "" Then
		Sql = Sql + " AND �����ރR�[�h='" & sChubunruiCode & "'"
	End If
	Sql = Sql + " ORDER BY ������"
	Set MyRS = MyCon.Execute(SQL)

	Response.Write "<input type=""radio"" name=""Shobunrui"" value="""" checked>�S��<br>"

	Do While (Not MyRS.Eof)
		sShobunrui = MyRS.Fields("������")
		If Not IsNull(sZokusei) then
			Response.Write "<input type=""radio"" name=""Shobunrui"" value=""" & sShobunrui & """>" & sShobunrui & "<br>" & vbcrlf
		End If
		MyRS.MoveNext
	Loop

	MyRS.Close
End Sub
%>
