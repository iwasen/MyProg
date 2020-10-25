<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<%
'******************************************************
' System :はいめーる・net事務局用ページ
' Content:コントロールマスタ修正画面
'******************************************************

SetHeaderTitle "コントロールマスタ"

Dim sql, ds

sql = "SELECT * FROM T_CONTROL WHERE CT_control_id='0'"
Set ds = CreateDynaset(sql, ORADYN_READONLY)
%>
<html>
<head>
<meta HTTP-EQUIV="Content-Type" Content="text/html; charset=x-sjis">
<meta HTTP-EQUIV="Pragma" Content="no-cache">
<title><%=title_text%></title>
<link rel="stylesheet" type="text/css" href="../haimail.css">
<SCRIPT LANGUAGE=javascript>
<!--
function OnSubmit_form1() {
	return confirm("コントロールマスタを更新します。よろしいですか？");
}
//-->
</SCRIPT>
</head>
<body>

<!--#INCLUDE FILE="../inc/header.asp"-->

<center>
<form method="post" action="control2.asp" name="form1" onSubmit="return OnSubmit_form1();">
	<table border=0 cellspacing=0 cellpadding=0 width="700">
		<tr>
			<td class="m0">■メールサーバ情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">メインSMTPサーバ名</td>
						<td class="n3">
							<input type="text" name="smtp_server1" size=50 maxlength=50 value="<%=ds("CT_smtp_server1").Value%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">バックアップSMTPサーバ名</td>
						<td class="n3">
							<input type="text" name="smtp_server2" size=50 maxlength=50 value="<%=ds("CT_smtp_server2").Value%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">メインPOP3サーバ名</td>
						<td class="n3">
							<input type="text" name="pop3_server" size=50 maxlength=50 value="<%=ds("CT_pop3_server").Value%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td><br></td></tr>
		<tr>
			<td class="m0">■メールアカウント情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">仮登録ログインユーザ名</td>
						<td class="n3">
							<input type="text" name="pop3_regist_user" size=50 maxlength=50 value="<%=ds("CT_pop3_regist_user").Value%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">仮登録ログインパスワード</td>
						<td class="n3">
							<input type="password" name="pop3_regist_pass" size=50 maxlength=50 value="<%=ds("CT_pop3_regist_pass").Value%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td><br></td></tr>
		<tr>
			<td class="m0">■自動処理設定情報</td>
		</tr>
		<tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">自動処理実行インターバル</td>
						<td class="n3">
							<input type="text" name="auto_interval" maxlength=4 value="<%=ds("CT_auto_interval").Value%>"> 秒
							<font class="note">（数字４桁まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">ログ保存期間</td>
						<td class="n3">
							<input type="text" name="log_limit" maxlength=4 value="<%=ds("CT_log_limit").Value%>"> 日
							<font class="note">（数字４桁まで）</font>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td><br></td></tr>
		<tr>
			<td class="m0">■各種通知先メールアドレス</td>
		</tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">発信メール速報通知先アドレス</td>
						<td class="n3">
							<input type="text" name="sokuhou_adr" size=50 maxlength=50 value="<%=HTMLEncode(ds("CT_sokuhou_adr").Value)%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">配信予約通知先アドレス</td>
						<td class="n3">
							<input type="text" name="reserve_adr" size=50 maxlength=50 value="<%=HTMLEncode(ds("CT_reserve_adr").Value)%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">配信状況通知先アドレス</td>
						<td class="n3">
							<input type="text" name="send_report_adr" size=50 maxlength=50 value="<%=HTMLEncode(ds("CT_send_report_adr").Value)%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
				</table>
			</td>
		<tr><td><br></td></tr>
		<tr>
			<td class="m0">■不達メール設定</td>
		</tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">不達メール受信アカウント</td>
						<td class="n3">
							<input type="text" name="ud_user" size=50 maxlength=50 value="<%=HTMLEncode(ds("CT_ud_user").Value)%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">不達メール受信パスワード</td>
						<td class="n3">
							<input type="password" name="ud_pass" size=50 maxlength=50 value="<%=HTMLEncode(ds("CT_ud_pass").Value)%>">
							<font class="note">（半角５０文字まで）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">自動削除カウンタ</td>
						<td class="n3">
							<input type="text" name="ud_count" size=2 maxlength=4 value="<%=HTMLEncode(ds("CT_ud_count").Value)%>">回
							<font class="note">（0は自動削除無し）</font>
						</td>
					</tr>
				</table>
			</td>
		<tr><td><br></td></tr>
		<tr>
			<td class="m0">■その他設定</td>
		</tr>
		<tr>
			<td>
				<table border=0 align="center" width="100%">
					<tr>
						<td class="m3" width="40%">ゆっくり登録エラーメール処理</td>
						<td class="n3">
							<input type="radio" name="error_mail" value="0"<%=RadioChecked(ds("CT_error_mail").Value, "0")%>>事務局処理&nbsp;
							<input type="radio" name="error_mail" value="1"<%=RadioChecked(ds("CT_error_mail").Value, "1")%>>自動返信
							</td>
					</tr>
					<tr>
						<td class="m3">未本登録者への催促メール</td>
						<td class="n3">
							<input type="text" name="reminder" size=2 maxlength=2 value="<%=ds("CT_reminder").Value%>">日後
							<font class="note">（0は送信無し）</font>
						</td>
					</tr>
					<tr>
						<td class="m3">アンケート無回答メール処理</td>
						<td class="n3">
							<input type="radio" name="enq_error" value="0"<%=RadioChecked(ds("CT_enq_error").Value, "0")%>>処理しない&nbsp;
							<input type="radio" name="enq_error" value="1"<%=RadioChecked(ds("CT_enq_error").Value, "1")%>>フォローメール自動返信
							</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<input type="submit" value=" 更新 " name="submit1">
	<input type="reset" value="リセット">
	<input type="button" value=" 戻る " onclick="history.back()">
</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
