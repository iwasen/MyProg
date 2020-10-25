<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
include("$inc/enq_mail.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// セッション処理
if (isset($my_enq_no)) {
	$myenq = new my_enquete_class;
	$myenq->read_db($_SESSION['ss_staff_id'], $my_enq_no);
} else {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
}

$enquete = &$myenq->enquete;

if ($myenq->mail_title == '')
	$subject = $enquete->title;
else
	$subject = $myenq->mail_title;

if ($myenq->mail_contents == '')
	get_enq_body($myenq, $header, $body, $footer);
else {
	$header = $myenq->mail_header;
	$body = $myenq->mail_contents;
	$footer = $myenq->mail_footer;
}
$mail_text = make_enq_mail($enquete, $header, $body, $footer);
?>
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="710" align="center" valign="top">
			<div align="center">
			<table border=0 cellspacing=1 cellpadding=0>
				<tr>
					<td class="m5">■<?=htmlspecialchars($subject)?></td>
				</tr>
				<tr>
					<td colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 bordercolor="navy" width="100%">
							<tr>
								<td><xmp><?=$mail_text?></xmp>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="button" value="　戻る　" onclick="history.back()">
			</div>

		</td>
	</tr>
</table>

<? staff_footer() ?>
