<!--#INCLUDE FILE="../inc/common.asp"-->
<!--#INCLUDE FILE="../inc/sub.asp"-->
<!--#INCLUDE FILE="../inc/database.asp"-->
<!--#INCLUDE FILE="../inc/login.asp"-->
<!--#INCLUDE FILE="../inc/estimate.asp"-->
<!--#INCLUDE FILE="../inc/search.asp"-->
<%
'******************************************************
' System :�͂��߁[��Enet�c�Ɨp�y�[�W
' Content:���ς���z��ʏ���
'******************************************************

'=== ���C������ ===
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

SetHeaderTitle "���ς茋��"
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
				<p>���w������ł̂����ς�͉��L�ƂȂ��Ă���܂��B<br>
					�@ �z�M�\�������ꍇ�ɂ́A�u���̏����Ŕz�M�\�񂷂�v�{�^���������ĉ������B</p>
			</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">���M�\��</td>
						<td class="n3"><%=NumFormat(kanou_suu)%> ��</td>
					</tr>
					<tr>
						<td class="m3" width="25%">���M��</td>
						<td class="n3"><input type="hidden" name="send_num" value="<%=send_num%>"><%=NumFormat(send_num)%> ��</td>
					</tr>
					<tr>
						<td class="m3">���[���`��</td>
						<td class="n3"><input type="hidden" name="mail_format" value="<%=mail_format%>"><%=MailFormatText(mail_format)%></td>
					</tr>
					<tr>
						<td class="m3">���[���^�C�v</td>
						<td class="n3"><input type="hidden" name="mail_type" value="<%=mail_type%>"><%=MailTypeText(mail_type)%></td>
					</tr>
					<tr>
						<td class="m3">�z�M����</td>
						<td class="n3"><font size="-1"><%=Replace(condition, vbCrLF, "<br>")%></font></td>
					</tr>
					<tr><td><br></td></tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="m0">�������ς茋��</td>
		</tr>
		<tr>
			<td>
				<table border=0 cellspacing=2 cellpadding=3 width="100%">
					<tr>
						<td class="m3" width="25%">�����ς���z(�ŕʁj</td>
						<td class="n3"><%=NumFormat(amount)%> �~</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<p>�@</p>
		<table width="700" border="2" cellspacing="0" cellpadding="0" bordercolor="peru">
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="10" cellpadding="0">
						<tr>
							<td class="np" colspan="2">
								<div align="center"><font color="336699"><b>������</b></font></div>
							</td>
						</tr>
						<tr>
							<td class="info" valign="top">
								<div align="right"><font color="336699">��</font></div>
							</td>
							<td class="info">�z�M�����ɕt���܂��ẮA��L�����������m��Ƃ����Ă��������܂��B<br>������A����]�̔z�M�����ƈقȂ��������Ŕz�M����Ă��܂����ꍇ�A���Ђł̐ӔC�͕������˂܂��̂ŁA���炩���߂������������B</td>
						</tr>
						<tr>
							<td class="info" valign="top">
								<div align="right"><font color="336699">��</font></div>
							</td>
							<td class="info">�L�����y�[�������́A��L���i�ɔ��f����Ă���܂���B�i<a href="http://www.haimail.net/6/download.html" target="_blank">�L�����y�[�����</a>�j</td>
						</tr>
						<tr>
							<td class="info" valign="top">
								<div align="right"><font color="336699">��</font></div>
							</td>
							<td class="info">�z�M���Ɋւ�炸�A�Œ�L�������͂T���~����Ƃ����Ă��������Ă���܂��B</td>
						</tr>
						<tr>
							<td class="info" valign="top">
								<div align="right">��</div>
							</td>
							<td class="info">�z�M�����̂��ύX�́A�������܂����A������ʂɂčēx���w�艺�����B </td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<p>
<%If CheckPermission(PM_HACCHUU) Then%>
			<%search.WriteCondition%>
			<input type="submit" value="���̏����Ŕz�M�\�񂷂�">
<%End If%>
			<input type="button" value="������ʂɖ߂�" onclick="history.go(-3)">
		</p>
	</form>
</center>

<!--#INCLUDE FILE="../inc/footer.asp"-->

</body>
</html>
