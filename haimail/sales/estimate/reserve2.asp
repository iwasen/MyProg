<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<!--#INCLUDE FILE="../inc/mail_header.asp"-->
<!--#INCLUDE FILE="../inc/estimate.asp"-->
<!--#INCLUDE FILE="../inc/click_counter.asp"-->
<%
'******************************************************
' System :はいめーる・net営業共通ページ
' Content:登録情報確認画面処理
'******************************************************

'=== クライアント名取得 ===
Private Sub GetCompanyTantousha()
	Dim sql, ds

	sql = "SELECT CM_name" & vbCrLf & _
				"FROM T_COMPANY,T_TANTOUSHA" & vbCrLf & _
				"WHERE TS_tantousha_id='" & tantousha_id & "' AND CM_company_id=TS_company_id"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		client_name = ds("CM_name").Value
	End If
End Sub

'=== クリックカウンタ挿入 ===
Private Sub InsertClickCounter(body)
	Dim sql, ds, p1, p2, jump_url, ct_id, click_url

	sql = "SELECT MAX(CC_ct_id) FROM T_CLICK_COUNT"
	Set ds = CreateDynaset(sql, ORADYN_READONLY)
	If Not ds.EOF Then
		ct_id = CLng(ds.Fields(0).Value)
	End If

	p1 = 1
	Do While True
		p1 = InStr(p1, body, "http://")
		If p1 = 0 Then
			Exit Do
		End If

		p2 = SearchUrlEnd(p1, body)
		jump_url = Mid(body, p1, p2 - p1)
		If InStr(jump_url, "%CLICK%") = 0 Then
			ct_id = ct_id + 1
			click_url = ClickCountURL(ct_id)

			body = Left(body, p1 - 1) & Replace(body, jump_url, click_url, p1, 1)
			p1 = p1 + Len(click_url)
		Else
			p1 = p1 + Len(jump_url)
		End If
	Loop
End Sub

'=== メイン処理 ===
Dim send_num, mail_format, mail_type, search, tantousha_id, client_name
Dim start_date_y, start_date_m, start_date_d, job_name, subject, body, click_counter
Dim id, header, footer, impression, imp_limit, body2, start_date

send_num = Request.Form("send_num")
mail_format = Request.Form("mail_format")
mail_type = Request.Form("mail_type")
tantousha_id = Request.Form("tantousha_id")
start_date_y = Request.Form("start_date_y")
start_date_m = Request.Form("start_date_m")
start_date_d = Request.Form("start_date_d")
job_name = Request.Form("job_name")
subject = Request.Form("subject")
body = Request.Form("body")
click_counter = Request.Form("click_counter")
start_date = CDate(start_date_y & "/" & start_date_m & "/" & start_date_d)

Set search = New CSearch
search.ReadCondition

'クリックカウンタ挿入
body2 = body
If Request("click_counter") = "1" Then
	InsertClickCounter body2
End If

If mail_format = "0" Then
	id = 1	'TEXT
Else
	id = 2	'HTML
End If

'ヘッダ、感想文、フッタ取得
header = GetHeader(id, tantousha_id)
If mail_type <> "TG" Then
	imp_limit = DateAdd("d", 7, start_date)
	impression = GetImpression(id, imp_limit)
Else
	impression = Null
End If
footer = GetFooter(id, search.kyoumi_genre1, search.kyoumi_genre2)

'ヘッダ、感想文、フッタ付加
body2 = AddMailHeader(body2, header, impression, footer)

GetCompanyTantousha

SetHeaderTitle "登録情報確認"
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" name="form1" action="reserve3.asp">
	<table border=0 cellspacing=0 cellpadding=0 width='800'>
		<tr>
			<td class="m0">■内容をご確認下さい</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width='100%'>
					<tr>
						<td class="m3" width="200">メールタイプ</td>
						<td class="n3" width="600"><%=MailTypeText(mail_type)%></td>
					</tr>
					<tr>
						<td class="m3" width="200">メール形式</td>
						<td class="n3" width="600"><%=MailFormatText(mail_format)%> 通</td>
					</tr>
					<tr>
						<td class="m3" width="200">掲載希望日</td>
						<td class="n3" width="600"><%=DateFormat(start_date) & "（" & GetWeekName(start_date) & "）"%></td>
					</tr>
					<tr>
						<td class="m3" width="200">配信数</td>
						<td class="n3" width="600"><%=NumFormat(send_num)%> 通</td>
					</tr>
					<tr>
						<td class="m3" width="200">配信単価</td>
						<td class="n3" width="600">＠<%=HaimailTanka(mail_format, mail_type)%> 円</td>
					</tr>
					<tr>
						<td class="m3" width="200">料金総額</td>
						<td class="n3" width="600"><%=NumFormat(Estimate(send_num, mail_format, mail_type))%> 円</td>
					</tr>
					<tr>
						<td class="m3" width="200">絞り込み</td>
						<td class="n3" width="600"><%=ReplaceTag(DeleteLaxxxrLf(search.GetConditionText))%></td>
					</tr>
					<tr>
						<td class="m3" width="200">件名</td>
						<td class="n3" width="600"><%=ReplaceTag(subject)%></td>
					</tr>
					<tr>
						<td class="m3" width="200">クリックカウンタの有無</td>
						<td class="n3" width="600">
<%
If click_counter = "1" Then
	Response.Write "有"
Else
	Response.Write "無"
End If
%>
						</td>
					</tr>
					<tr>
						<td class="m3" width="200">クライアント名</td>
						<td class="n3" width="600"><%=ReplaceTag(client_name)%></td>
					</tr>
					<tr>
						<td class="m3" width="200">本文</td>
						<td class="n3" width="600">
<%If mail_format = "0" Then%>
							<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" bgcolor="white" width="100%">
								<tr>
									<td><xmp class="np"><%=body2%></xmp></td>
								</tr>
							</table>
<%Else%>
							テキストＨＴＭＬ、ＨＴＭＬメールのご入稿の際には、<br>
							<a href="mailto:ad@haimail.net">ad@haimail.net</a>までメール添付にてご入稿ください。
<%End If%>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<p>　</p>
	<table border="3" cellspacing="0" cellpadding="0" bordercolor="cd853f" width="800">
      <tr>
        <td nowrap class="np" height="19">
          <table width="100%" cellspacing="5" cellpadding="0">
            <tr valign="top">
              <td nowrap width="19" class="info">
                <div align="right"><font color="336699">■</font></div>
              </td>
              <td width="781" class="info"><font color="red"><b>配信数・配信日・発信企業名・件名・本文</b></font>が表示されます。再度ご確認下さい。</td>
            </tr>
            <tr valign="top">
              <td nowrap width="19" class="info">
                <div align="right"><font color="336699">■</font></div>
              </td>
              <td width="781" class="info">クリックカウンタ設定をご希望されている場合、実際のメールでは<font color="#0000FF"><br>
                http://www.haimail.net/ct/c.asp?0000-0000</font>のようなURLが挿入されます。</td>
            </tr>
            <tr valign="top">
              <td nowrap width="19" class="info">
                <div align="right"><font color="336699">■</font></div>
              </td>
              <td width="781" class="info"><b>ご指定いただいた配信条件・原稿で原則最終の確定とさせていただきます。</b>くれぐれもご登録内容に間違いのないようお願いいたします。万が一、ご希望の配信条件と異なって配信された場合、弊社での責任は一切請け負いかねますので、予めご了承下さい。</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
	<p>
		<input type="submit" value="　配信予約　">
		<input type="button" value="　戻る　" onclick="history.back()">
		<input type="hidden" name="send_num" value="<%=send_num%>">
		<input type="hidden" name="mail_format" value="<%=mail_format%>">
		<input type="hidden" name="mail_type" value="<%=mail_type%>">
		<input type="hidden" name="tantousha_id" value="<%=HTMLEncode(tantousha_id)%>">
		<input type="hidden" name="start_date_y" value="<%=start_date_y%>">
		<input type="hidden" name="start_date_m" value="<%=start_date_m%>">
		<input type="hidden" name="start_date_d" value="<%=start_date_d%>">
		<input type="hidden" name="job_name" value="<%=HTMLEncode(job_name)%>">
		<input type="hidden" name="subject" value="<%=HTMLEncode(subject)%>">
		<input type="hidden" name="body" value="<%=HTMLEncode(body)%>">
		<input type="hidden" name="click_counter" value="<%=click_counter%>">
		<%search.WriteCondition%> <font color="#CC0000"> </font>
	</p>
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
