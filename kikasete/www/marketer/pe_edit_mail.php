<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/mye_temp.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if ($edit) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
} else {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	if ($pro_enq_no == '') {
		$pro_enq->marketer_id = $_SESSION['ss_owner_id'];
		$pro_enq->creator_id = $_SESSION['ss_marketer_id'];
	} else
		$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
}

$enquete = &$pro_enq->enquete;

if ($pro_enq->mail_title == '')
	$subject = $enquete->title;
else
	$subject = $pro_enq->mail_title;

if ($pro_enq->mail_contents == '')
	get_enq_body($pro_enq, $header, $body, $footer);
else {
	$header = $pro_enq->mail_header;
	$body = $pro_enq->mail_contents;
	$footer = $pro_enq->mail_footer;
}
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>
			<form method="post" name="form1" action="pe_update_mail.php">
			<table width="90%" border=0 cellspacing=2 cellpadding=3 class="honbun2">
				<tr>
					<td bgcolor="#eff7e8">■メール文のカスタマイズ</td>
				</tr>
				<tr>
					<td>
						<table border=0 cellspacing=2 cellpadding=3 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eeeeee" width="20%">件名</td>
								<td bgcolor="#ffeecc"><input type="text" name="subject" size=60 maxlength=100 <?=value($subject)?>>（全角１００文字まで）</td>
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
						<input type="button" value="　戻る　" onclick="location.href='pe_make.php?pro_enq_no=<?=$pro_enq_no?>'">
						<input type="hidden" name="next_action">
						<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
						</div>
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
