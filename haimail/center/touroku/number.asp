<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:登録者数表示
'******************************************************

SetHeaderTitle "登録者数表示"

Dim sql, ds, kari_web, kari_mail, member_web, member_mail, shoukai, hishoukai, skari, smember, taikai, yukkuri_cnt, yukkuri_sum, hamail_letter
Dim present(3)

'仮登録者数
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

'本登録者数
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

'紹介者数
sql = "SELECT COUNT(*) FROM T_SHOUKAI"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	shoukai = 0
Else
	shoukai = NVL(ds(0).Value, 0)
End If

'被紹介者数
sql = "SELECT COUNT(*) FROM T_HISHOUKAI"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	hishoukai = 0
Else
	hishoukai = NVL(ds(0).Value, 0)
End If

'紹介による仮登録者数
sql = "SELECT COUNT(*) FROM T_KARI_TOUROKU WHERE KT_status<>'2' AND KT_shoukai_no IS NOT NULL"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	skari = 0
Else
	skari = NVL(ds(0).Value, 0)
End If

'紹介による本登録者数
sql = "SELECT COUNT(*) FROM T_MEMBER WHERE MM_status<>'9' AND MM_shoukai_no IS NOT NULL"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	smember = 0
Else
	smember = NVL(ds(0).Value, 0)
End If

'退会者数
sql = "SELECT COUNT(*) FROM T_MEMBER WHERE MM_status='9'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	taikai = 0
Else
	taikai = NVL(ds(0).Value, 0)
End If

'ゆっくり登録請求者数
sql = "SELECT COUNT(*),SUM(TR_count) FROM T_TOUROKU_REQ"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	yukkuri_cnt = 0
	yukkuri_sum = 0
Else
	yukkuri_cnt = NVL(ds(0).Value, 0)
	yukkuri_sum = NVL(ds(1).Value, 0)
End If

'はいめーるレター登録数
sql = "SELECT COUNT(*) FROM T_HAIMAIL_LETTER"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	hamail_letter = 0
Else
	hamail_letter = NVL(ds(0).Value, 0)
End If

'ミレニアム・キャンペーン応募数
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
		<td class="m0">■<%=TimeFormat(Now)%> 現在</td>
	</tr>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width='100%'>
				<tr>
					<td class="m4" width="30%">仮登録者数</td>
					<td class="n4"><%=NumFormat(CDbl(kari_web) + CDbl(kari_mail)) & "　（web:" & NumFormat(kari_web) & " mail:" & NumFormat(kari_mail) & "）"%></td>
				</tr>
				<tr>
					<td class="m4">本登録者数</td>
					<td class="n4"><%=NumFormat(CDbl(member_web) + CDbl(member_mail)) & "　（web:" & NumFormat(member_web) & " mail:" & NumFormat(member_mail) & "）"%></td>
				</tr>
				<tr>
					<td class="m4">クチコミ</td>
					<td class="n4">紹介者数：<%=NumFormat(shoukai)%>　被紹介者数：<%=NumFormat(hishoukai)%>　仮登録者数：<%=NumFormat(skari)%>　本登録者数：<%=NumFormat(smember)%></td>
				</tr>
				<tr>
					<td class="m4">退会者数</td>
					<td class="n4"><%=NumFormat(taikai)%></td>
				</tr>
				<tr>
					<td class="m4">ゆっくり登録メール請求数</td>
					<td class="n4"><%=NumFormat(yukkuri_cnt)%>人 （<%=NumFormat(yukkuri_sum)%>回）</td>
				</tr>
				<tr>
					<td class="m4">はいめーるレター登録数</td>
					<td class="n4"><%=NumFormat(hamail_letter)%></td>
				</tr>
<!--
				<tr>
					<td class="m4">ﾐﾚﾆｱﾑ・ｷｬﾝﾍﾟｰﾝ応募数</td>
					<td class="n4">ＡＩＢＯ：<%=NumFormat(present(1))%>　プレステ：<%=NumFormat(present(2))%>　ドラえもん：<%=NumFormat(present(3))%></td>
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
