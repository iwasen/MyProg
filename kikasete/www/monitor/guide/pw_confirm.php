<?
$top = './..';
$inc = "$top/../inc";
include("$inc/mail.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");

if ($mail_addr == '')
	redirect('pwrequest.php');

$sql = "SELECT mn_mail_addr,mn_password FROM t_monitor WHERE mn_status <> 9 and mn_mail_addr_low=" . sql_char(strtolower($mail_addr));
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);

	get_mail_template('pwd_mn', $subject, $from, $cc, $bcc, $repty_to, $body);
	$body = str_replace('%PASSWORD%', $fetch->mn_password, $body);
	send_mail($subject, $fetch->mn_mail_addr, $from, $body, $cc, $bcc, $reply_to);

	$title = 'パスワードのお問合せを受け付けました。';
	$msg = 'ご登録のメールアドレス宛に、パスワードを記載したメールをお送りしております。<br>
						メールをご確認ください。<br>
						<br>
						<table border="1" cellpadding="3" cellspacing="0" width="100%">
							<tr>
								<td colspan="3" bgcolor="#ffdcb2"><font size="2" color="#ff000b">※</font><font size="2">パスワードはモニター活動をおこなう上で、とても大切な情報となっています。<br>
									　大切に保管していただけますよう、よろしくお願い致します。</font></td>
							</tr>
						</table>';
} else {
	$title = '入力されたメールアドレスは登録されていないようです。';
	$msg = 'メールアドレスをご確認後、再度送信していただくか、事務局までお問い合せください。';
}
?>
<?monitor_menu()?>
<TABLE cellSpacing=0 cellPadding=0 border=0 width="100%">
	<TBODY>
		<TR>
			<TD align="left" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_pw_inquiry.gif" width=590 height=30 border=0 alt="モニター規約"></TD>
			<TD align="right" bgcolor="#EEEEEE" background="<?=$img?>/tit_bk.gif"><IMG src="<?=$img?>/tit_bk.gif" width=10 height=30 border=0></TD>
		</TR>
		<TR>
			<TD align="left" colspan="2"><IMG src="<?=$img?>/spacer.gif" width=1 height=15 border=0></TD>
		</TR>
	</TBODY>
</TABLE>
	<table border="0" cellpadding="15" cellspacing="0" width="500" bgcolor="white">
		<tr>
			<td><font size="2"><br></font><font color="#2b2b63"><strong><?=$title?><br></strong></font><font size="2"><br><br><?=$msg?></font></td>
		</tr>
	</table>