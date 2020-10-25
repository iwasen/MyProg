<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�����Ǘp�y�[�W
' Content:�o�^�Ґ��\��
'******************************************************

SetHeaderTitle "�o�^�Ґ��\��"

Dim sql, ds, kari_web, kari_mail, member_web, member_mail, shoukai, hishoukai, skari, smember, taikai, yukkuri_cnt, yukkuri_sum, hamail_letter
Dim present(3)

'���o�^�Ґ�
sql = "SELECT COUNT(*),KT_regist_type FROM T_KARI_TOUROKU WHERE KT_status<>'2' GROUP BY KT_regist_type"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
Do Until ds.EOF
	Select Case ds(1).Value
	Case "0"
		kari_web = NVL(ds(0).Value, 0)
	Case "1"
		kari_mail = NVL(ds(0).Value, 0)
	End Select
	ds.MoveNext
Loop

'�{�o�^�Ґ�
sql = "SELECT COUNT(*),MM_regist_type FROM T_MEMBER WHERE MM_status<>'9' GROUP BY MM_regist_type"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
Do Until ds.EOF
	Select Case ds(1).Value
	Case "0"
		member_web = NVL(ds(0).Value, 0)
	Case "1"
		member_mail = NVL(ds(0).Value, 0)
	End Select
	ds.MoveNext
Loop

'�Љ�Ґ�
sql = "SELECT COUNT(*) FROM T_SHOUKAI"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	shoukai = 0
Else
	shoukai = NVL(ds(0).Value, 0)
End If

'��Љ�Ґ�
sql = "SELECT COUNT(*) FROM T_HISHOUKAI"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	hishoukai = 0
Else
	hishoukai = NVL(ds(0).Value, 0)
End If

'�Љ�ɂ�鉼�o�^�Ґ�
sql = "SELECT COUNT(*) FROM T_KARI_TOUROKU WHERE KT_status<>'2' AND KT_shoukai_no IS NOT NULL"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	skari = 0
Else
	skari = NVL(ds(0).Value, 0)
End If

'�Љ�ɂ��{�o�^�Ґ�
sql = "SELECT COUNT(*) FROM T_MEMBER WHERE MM_status<>'9' AND MM_shoukai_no IS NOT NULL"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	smember = 0
Else
	smember = NVL(ds(0).Value, 0)
End If

'�މ�Ґ�
sql = "SELECT COUNT(*) FROM T_MEMBER WHERE MM_status='9'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	taikai = 0
Else
	taikai = NVL(ds(0).Value, 0)
End If

'�������o�^�����Ґ�
sql = "SELECT COUNT(*),SUM(TR_count) FROM T_TOUROKU_REQ"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	yukkuri_cnt = 0
	yukkuri_sum = 0
Else
	yukkuri_cnt = NVL(ds(0).Value, 0)
	yukkuri_sum = NVL(ds(1).Value, 0)
End If

'�͂��߁[�郌�^�[�o�^��
sql = "SELECT COUNT(*) FROM T_HAIMAIL_LETTER"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	hamail_letter = 0
Else
	hamail_letter = NVL(ds(0).Value, 0)
End If

'�~���j�A���E�L�����y�[�����吔
'sql = "SELECT PS_present_no,COUNT(PS_member_id) FROM T_PRESENT,T_MEMBER WHERE PS_member_id=MM_member_id AND MM_status<>'9' GROUP BY PS_present_no"
'Set ds = CreateDynaset(sql, ORADYN_READONLY)
'Do Until ds.EOF
'	present(ds(0).Value) = ds(1).Value
'	ds.MoveNext
'Loop
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<meta HTTP-EQUIV="Refresh" Content="60">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<table border=0 cellspacing=0 cellpadding=0 width='750'>
	<tr>
		<td class="m0">��<%=TimeFormat(Now)%> ����</td>
	</tr>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width='100%'>
				<tr>
					<td class="m4" width="30%">���o�^�Ґ�</td>
					<td class="n4"><%=NumFormat(CDbl(kari_web) + CDbl(kari_mail)) & "�@�iweb:" & NumFormat(kari_web) & " mail:" & NumFormat(kari_mail) & "�j"%></td>
				</tr>
				<tr>
					<td class="m4">�{�o�^�Ґ�</td>
					<td class="n4"><%=NumFormat(CDbl(member_web) + CDbl(member_mail)) & "�@�iweb:" & NumFormat(member_web) & " mail:" & NumFormat(member_mail) & "�j"%></td>
				</tr>
				<tr>
					<td class="m4">�N�`�R�~</td>
					<td class="n4">�Љ�Ґ��F<%=NumFormat(shoukai)%>�@��Љ�Ґ��F<%=NumFormat(hishoukai)%>�@���o�^�Ґ��F<%=NumFormat(skari)%>�@�{�o�^�Ґ��F<%=NumFormat(smember)%></td>
				</tr>
				<tr>
					<td class="m4">�މ�Ґ�</td>
					<td class="n4"><%=NumFormat(taikai)%></td>
				</tr>
				<tr>
					<td class="m4">�������o�^���[��������</td>
					<td class="n4"><%=NumFormat(yukkuri_cnt)%>�l �i<%=NumFormat(yukkuri_sum)%>��j</td>
				</tr>
				<tr>
					<td class="m4">�͂��߁[�郌�^�[�o�^��</td>
					<td class="n4"><%=NumFormat(hamail_letter)%></td>
				</tr>
<!--
				<tr>
					<td class="m4">��ƱсE����߰݉��吔</td>
					<td class="n4">�`�h�a�n�F<%=NumFormat(present(1))%>�@�v���X�e�F<%=NumFormat(present(2))%>�@�h��������F<%=NumFormat(present(3))%></td>
				</tr>
-->
			</table>
		</td>
	</tr>
</table>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
