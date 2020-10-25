<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/enq_temp.php");
include("$inc/enq_mail.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	$myp->read_db($pjt_id);
}
$enquete = &$myp->enquete;

if ($myp->subject == '')
	$subject = $enquete->title;
else
	$subject = $myp->subject;

if ($myp->body == '')
	get_enq_body($myp, $header, $body, $footer);
else {
	$header = $myp->header;
	$body = $myp->body;
	$footer = $myp->footer;
}
$mail_text = make_enq_mail($enquete, $header, $body, $footer);
?>
<? marketer_header('Myパートナー', PG_NULL) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<form method="post" name="form1" action="mp_update_mail.php">
			<table width="90%" border=0 cellspacing=2 cellpadding=3>
				<tr>
					<td bgcolor="#eff7e8">■メール文のカスタマイズ</td>
				</tr>
				<tr>
					<td>
						<table border=0 cellspacing=2 cellpadding=3 width="100%">
							<tr>
								<td bgcolor="#eeeeee" width="20%">件名</td>
								<td bgcolor="#ffeecc"><input type="text" name="subject" size=80 maxlength=100 <?=value($subject)?>>（全角１００文字まで）</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">ヘッダ</td>
								<td bgcolor="#ffeecc"><textarea name="header" cols=72 rows=10><?=htmlspecialchars($header)?></textarea></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee"0>本文</td>
								<td bgcolor="#ffeecc">※差込み文字が使えます。（モニター氏名= %MONITOR_NAME% ）<br>
									<textarea name="body" cols=72 rows=25><?=htmlspecialchars($body)?></textarea></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">フッタ</td>
								<td bgcolor="#ffeecc"><textarea name="footer" cols=72 rows=8><?=htmlspecialchars($footer)?></textarea></td>
							</tr>
						</table><br>
						<div align="center">
						<input type="submit" value="メールプレビュー" onclick="document.form1.next_action.value='preview'">
						<input type="submit" value="　登録　" onclick="document.form1.next_action.value='update'">
						<input type="submit" value="リセット" onclick="document.form1.next_action.value='reset'">
						<input type="button" value="　戻る　" onclick="location.href='mp_r_job.php?pjt_id=<?=$pjt_id?>'">
						<input type="hidden" name="next_action">
						</div>
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<? marketer_footer () ?>
