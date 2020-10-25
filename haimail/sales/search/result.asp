<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:メンバー検索結果画面処理
'******************************************************

'=== メイン処理 ===
SetHeaderTitle "メンバー検索結果"

Dim search, result, sql, ds, find, condition

Set search = New CSearch
search.GetData
condition = search.GetConditionText

'HTMLメール受信可能を選択した場合はWebメールドメインを除外する
If search.html_mail_shitei = "1" And search.html_mail = "1" Then
	search.jogai_domain = ""
	sql = "SELECT MD_domain_id FROM T_MAIL_DOMAIN"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	Do Until ds.EOF
		If search.jogai_domain <> "" Then
			search.jogai_domain = search.jogai_domain & ","
		End If
		search.jogai_domain = search.jogai_domain & ds(0).Value
		ds.MoveNext
	Loop
End If

'インプレスを発信対象外とする（２月末までの限定処置）====
'Dim org_jogai_baitai
'org_jogai_baitai = search.jogai_baitai
'If search.jogai_baitai <> "" Then
'	search.jogai_baitai = search.jogai_baitai & ","
'End If
'search.jogai_baitai = search.jogai_baitai & "30"
'====================================================

sql = search.MakeSQL
'インプレスを発信対象外とする（２月末までの限定処置）====
'search.jogai_baitai = org_jogai_baitai
'====================================================
If CheckPermission(PM_GUEST_SEARCH) Then
	sql = "MM_regist_date<TRUNC(SYSDATE, 'MONTH') AND " & sql
End If

sql = "SELECT COUNT(*) FROM T_MEMBER WHERE " & sql
Set ds = CreateDynaset(sql, ORADYN_READONLY)
If ds.EOF Then
	find = 0
Else
	find = ds(0).Value
End If
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

<center>
<form method="post" name="form1" action="../estimate/index.asp">
<input type="hidden" name="send_num" value="<%=find%>">
<table border=0 cellspacing=0 cellpadding=0 width='700'>
	<tr>
		<td class="m0">■検索結果</td>
	</tr>
	<tr>
		<td class="np">
			<p>ご指定の条件にてお見積りをご希望の場合には、「お見積り」ボタンを押して下さい。<br>
				※配信予約をされる際には、 「お見積り」へお進み下さい。<br>
			</p>
		</td>
	</tr>
	<tr>
		<td>
			<table border=0 cellspacing=2 cellpadding=3 width='100%'>
				<tr>
					<td class="m3" width="25%">検索会員数</td>
					<td class="n3"><%=NumFormat(find)%></td>
				</tr>
				<tr>
					<td class="m3">検索条件</td>
					<td class="n3"><font size="-1"><%=Replace(condition, vbCrLF, "<br>")%></font></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<p>
<%If CheckPermission(PM_MITSUMORI) Then%>
<%search.WriteCondition%>
			<input type="submit" value="　お見積り　">
<%End If%>
			<input type="button" value="　再検索　" onclick="history.back()">
</p>
</form>
<p>　</p>
<div align="center">
	<table width="700" border="2" cellspacing="0" cellpadding="0" bordercolor="peru">
		<tr>
			<td>
				
          <table border=0 cellspacing=10 cellpadding=0 width='100%' align="center">
            <tr> 
              <td class="info" valign='TOP' width="35"> 
                <div align="right"><font color="#336699">■</font></div>
              </td>
              <td class="info"> 配信条件につきましては、メール内容との不一致があるとセンターで判断させていただいた場合、配信できない場合がございます。ご了承下さい。</td>
            </tr>
            <tr> 
              <td class="info" valign='TOP' width="35"> 
                <div align="right"></div>
              </td>
              <td class="info"> 
                <p>◎不一致があると判断させていただく例</p>
                <ul>
                  <li>結婚情報債との案内：「未婚」だけでなく「既婚」にも指定がある。<br>
                    （結婚情報を希望しているのは未婚者のみと判断させていただきます）</li>
                  <li>マンション情報の案内：「買うなら一戸建て」に指定がある。<br>
                    （マンション情報を希望しているのは「買うならマンション」にチェックしているメンバーのみと判断させていただきます）</li>
                </ul>
              </td>
            </tr>
            <tr>
              <td class="info" valign='TOP' width="35">
                <div align="right"><font color="#336699">■</font></div>
              </td>
              <td class="info">絞込み条件で検索した人数はあくまでもご参考値です。<br>
                送信停止、入退会などにより、１～２％程度通数は随時変動いたします。<br>
                予めご了承ください。</td>
            </tr>
          </table>
			</td>
		</tr>
	</table>
</div>


</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
