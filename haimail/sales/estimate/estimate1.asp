<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/estimate.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :はいめーる・net営業用ページ
' Content:見積り金額画面処理
'******************************************************

'=== メイン処理 ===
Dim kanou_suu, send_num, mail_format, mail_type, amount, jogai_domain
Dim search, condition

kanou_suu = Request.Form("kanou_suu")
If Not IsEmpty(kanou_suu) Then
	kanou_suu = CLng(kanou_suu)
End If
send_num = CLng(Request.Form("send_num"))
mail_format = Request.Form("mail_format")
mail_type = Request.Form("mail_type")

Set search = New CSearch
search.ReadCondition

jogai_domain = search.jogai_domain
search.jogai_domain = ""
condition = search.GetConditionText
search.jogai_domain = jogai_domain

amount = Estimate(send_num, mail_format, mail_type)

SetHeaderTitle "見積り結果"
'Response.CacheControl = "no-cache"
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
<form method="post" action="reserve.asp" name="form1">
	<table border=0 cellspacing=0 cellpadding=0 width='700'>
		<tr>
			<td class="m0">
				<p>■指定条件でのお見積りは下記となっております。<br>
					　 配信予約をする場合には、「この条件で配信予約する」ボタンを押して下さい。</p>
			</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">発信可能数</td>
						<td class="n3"><%=NumFormat(kanou_suu)%> 通</td>
					</tr>
					<tr>
						<td class="m3" width="25%">発信数</td>
						<td class="n3"><input type="hidden" name="send_num" value="<%=send_num%>"><%=NumFormat(send_num)%> 通</td>
					</tr>
					<tr>
						<td class="m3">メール形式</td>
						<td class="n3"><input type="hidden" name="mail_format" value="<%=mail_format%>"><%=MailFormatText(mail_format)%></td>
					</tr>
					<tr>
						<td class="m3">メールタイプ</td>
						<td class="n3"><input type="hidden" name="mail_type" value="<%=mail_type%>"><%=MailTypeText(mail_type)%></td>
					</tr>
					<tr>
						<td class="m3">配信条件</td>
						<td class="n3"><font size="-1"><%=Replace(condition, vbCrLF, "<br>")%></font></td>
					</tr>
					<tr><td><br></td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="m0">■お見積り結果</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">お見積り金額(税別）</td>
						<td class="n3"><%=NumFormat(amount)%> 円</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<p>　</p>
		<table width="700" border="2" cellspacing="0" cellpadding="0" bordercolor="peru">
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="10" cellpadding="0">
						<tr>
							<td class="np" colspan="2">
								<div align="center"><font color="336699"><b>ご注意</b></font></div>
							</td>
						</tr>
						<tr>
							<td class="info" valign="top">
								<div align="right"><font color="336699">■</font></div>
							</td>
							<td class="info">配信条件に付きましては、上記検索条件を確定とさせていただきます。<br>万が一、ご希望の配信条件と異なった条件で配信されてしまった場合、弊社での責任は負いかねますので、あらかじめご了承下さい。</td>
						</tr>
						<tr>
							<td class="info" valign="top">
								<div align="right"><font color="336699">■</font></div>
							</td>
							<td class="info">キャンペーン料金は、上記価格に反映されておりません。（<a href="http://www.haimail.net/6/download.html" target="_blank">キャンペーン情報</a>）</td>
						</tr>
						<tr>
							<td class="info" valign="top">
								<div align="right"><font color="336699">■</font></div>
							</td>
							<td class="info">配信数に関わらず、最低広告料金は５万円からとさせていただいております。</td>
						</tr>
						<tr>
							<td class="info" valign="top">
								<div align="right">※</div>
							</td>
							<td class="info">配信条件のご変更は、恐れ入りますが、検索画面にて再度ご指定下さい。 </td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<p>
<%If CheckPermission(PM_HACCHUU) Then%>
			<%search.WriteCondition%>
			<input type="submit" value="この条件で配信予約する">
<%End If%>
			<input type="button" value="検索画面に戻る" onclick="history.go(-3)">
		</p>
	</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
