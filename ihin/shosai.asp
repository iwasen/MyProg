<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>�ڍ�</title>


<meta name="Microsoft Theme" content="mstheme22243 011, default"><meta name="Microsoft Border" content="t, default"></head>

<body background="_themes/mstheme22243/sumtextb.jpg" bgcolor="#FFFFFF" text="#000066" link="#3333CC" vlink="#3333CC" alink="#990099"><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td><!--mstheme--><font face="�l�r �o�S�V�b�N">

<p align="left"><font size="6"><strong><img src="_derived/shosai.asp_cmp_mstheme22243010_bnr.gif" width="600" height="60" border="0" alt="�ڍ�"></strong></font></p>

<!--mstheme--></font></td></tr><!--msnavigation--></table><!--msnavigation--><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><!--msnavigation--><td valign="top"><!--mstheme--><font face="�l�r �o�S�V�b�N"><!--mstheme--></font><table border="1" bordercolordark="#003366" bordercolorlight="#C0C0C0">
	<tr>
	<%SetShosaiData%>
	<td colspan="2" height="200" valign="top"><!--mstheme--><font face="�l�r �o�S�V�b�N"><a href="gazou.asp">�摜�i��j���݂�</a>
	<select size="5" name="Kanren" width="100"></select>      
    <!--mstheme--></font></td>      
</table><!--mstheme--><font face="�l�r �o�S�V�b�N">  
<!--msthemeseparator--><p align="left"><img src="_themes/mstheme22243/sumhorsa.gif" width="600" height="10"></p> 
<a href="ichiran.asp"><img border="0" src="images/b11_036.gif" width="90" height="22"></a>    
<p>�@</p>    
<!--mstheme--></font><!--msnavigation--></td></tr><!--msnavigation--></table></body> 
 
<%    
Private Sub SetShosaiData    
	Dim KomokuNo(10)    
	Dim KomokuName(10)    
    
	'��i�c�a�I�u�W�F�N�g�擾    
	Set MyCon = Session("xxxNDB")    
    
	'SQL���������ҏW    
	SqlWhere = "�摜�t�H���_ID='" & Request("folder") & "' AND �摜ID='" & Request("id") & "'"    
    
	'�啪�ރR�[�h�擾    
	Sql = "SELECT �啪�ރR�[�h FROM F_GAZOU WHERE " & SqlWhere    
	Set MyRS = MyCon.Execute(Sql)    
    
	'�R���g���[���e�[�u������ڍו\�����e���擾SQL    
	Sql = "SELECT ���ڔԍ�,���ږ���,�ڍו\���t���O FROM M_CONTROL " & _    
		"WHERE �啪�ރR�[�h='" & MyRS.Fields(0) & "' " & _    
		"ORDER BY ���ڔԍ�"    
	Set MyRS = MyCon.Execute(Sql)    
    
	'�R���g���[���e�[�u������ꗗ�\�����e���擾    
	nItem = 0    
	Do While (Not MyRS.Eof)    
		If MyRS.Fields("�ڍו\���t���O") = True Then    
			nItem  = nItem + 1    
			KomokuNo(nItem) = MyRS.Fields("���ڔԍ�")    
			KomokuName(nItem) = MyRS.Fields("���ږ���")    
		End If    
		MyRS.MoveNext    
	Loop    
	MyRS.Close    
    
	'SQL���o���ڕҏW    
	SqlSelect = "�摜�t�H���_ID,�摜ID"    
	For i = 1 To nItem     
		SqlSelect = SqlSelect + ",����"  & KomokuNo(i)    
	Next    
    
	'SQL���ҏW�����s    
	Sql = "SELECT " & SqlSelect & " FROM F_GAZOU WHERE " & SqlWhere    
	Set MyRS = MyCon.Execute(Sql)    
    
	'�������ʏo��    
	If (Not MyRS.Eof) Then    
		For i = 1 To nItem    
			Response.Write "<tr>" & vbcrlf    
			Response.Write "<td width=""100""><b>" & KomokuName(i) & "</b></td>" & vbcrlf    
			sData = MyRS.Fields("����" & KomokuNo(i))    
			If IsNull(sData) Then    
				sData = "�@"    
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
