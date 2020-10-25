<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�ʃN���b�N���\��
'******************************************************

Dim sub1, sub2, sub3, sql, ds, line

SetHeaderTitle "�o�q�o�^�ꗗ"

sub1 = "SELECT CL_pr_id,COUNT(*) AS CL_count,MAX(CL_param) AS CL_param FROM T_CLICK_LOG GROUP BY CL_pr_id"
sub2 = "SELECT KT_pr_id,COUNT(*) AS KT_count FROM T_KARI_TOUROKU WHERE KT_status<>'2' GROUP BY KT_pr_id"
sub3 = "SELECT MM_pr_id,COUNT(*) AS MM_count FROM T_MEMBER WHERE MM_status<>'9' GROUP BY MM_pr_id"

sql = "SELECT *" & vbCrLf & _
			"FROM T_PR,T_BAITAI,T_KOUKOKU_TYPE,(" & sub1 & "),(" & sub2 & "),(" & sub3 & ")" & vbCrLf & _
			"WHERE PR_koukoku_id=KT_koukoku_id(+) AND PR_baitai_id=BT_baitai_id(+) AND PR_pr_id=CL_pr_id(+) AND PR_pr_id=MM_pr_id(+) AND PR_pr_id=KT_pr_id(+)" & vbCrLf & _
			"ORDER BY PR_pr_id DESC"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<table border=0 cellpadding=0 cellspacing=0 width="100%">
	<form id=form1 name=form1>
	<tr>
		<td>�����F<%=ds.RecordCount%></td>
		<td align="right"><input type="button" value="�V�K�o�^" onclick="location.href='pr_regist.asp'"></td>
	</tr>
	</form>
</table>

<table border=1 cellpadding=0 cellspacing=1 width="100%" class="small">
	<tr bgcolor="#E0FFE0">
		<th>PRID</th>
		<th>�}�̖�</th>
		<th>PR����</th>
		<th>�L���^�C�v</th>
		<th>�z�M��</th>
		<th>�ۏظد���</th>
		<th>�z�M�J�n</th>
		<th>�z�M�I��</th>
		<th>�د���</th>
		<th>���o�^��</th>
		<th>�o�^��</th>
	</tr>
<%
line = 1
Do Until ds.EOF
	Writeln "<tr class='tc" & line Mod 2 & "'>"
	Writeln "<td align='center'><a href='pr_regist.asp?id=" & ds("PR_pr_id").Value & "'>" & ds("PR_pr_id").Value & "</a></td>"
	Writeln "<td>" & ReplaceTag(ds("BT_name").Value) & "</td>"
	If IsNull(ds("CL_param").Value) Then
		Writeln "<td>" & ReplaceTag(ds("PR_pr_name").Value) & "</td>"
	Else
		Writeln "<td><a href=pr_list.asp?id=" & ds("PR_pr_id").Value & ">" & ReplaceTag(ds("PR_pr_name").Value) & "</a></td>"
	End If
	Writeln "<td>" & ReplaceTag(ds("KT_name").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat2(ds("PR_haishin_suu").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat2(ds("PR_click_suu").Value) & "</td>"
	Writeln "<td align='center'>" & DateFormat2(ds("PR_haishin_start").Value) & "</td>"
	Writeln "<td align='center'>" & DateFormat2(ds("PR_haishin_end").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("CL_count").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("KT_count").Value) & "</td>"
	Writeln "<td align='right'>" & NumFormat(ds("MM_count").Value) & "</td>"
	Writeln "</tr>"
	ds.MoveNext
	line = line + 1
Loop
%>
</table>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
