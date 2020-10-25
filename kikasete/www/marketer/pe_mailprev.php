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
include("$inc/enq_mail.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// 本永扑亦件質咥
if (isset($pro_enq_no)) {
	$pro_enq = new pro_enquete_class;
	$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
} else {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
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
$mail_text = make_enq_mail($enquete, $header, $body, $footer);
?>
<? marketer_header('�苺礗鴷╞韞情憧�', PG_NULL) ?>
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
			<br>

			<div align="center">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="m5">╯<?=htmlspecialchars($subject)?></td>
				</tr>
				<tr>
					<td colspan=2>
<table border="0">
<tr>
    <td bgcolor="#dadada">
      <table border="0" cellspacing="1" cellpadding="5" width="100%">
        <tr>
          <td bgcolor="#FFFFFF"><xmp><?=$mail_text?></xmp></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
					</td>
				</tr>
			</table>
			<br>
			<a href="javascript:history.back();"><img src="images/mkk_bt/back.gif" alt="枑月" name="image2" width="108" height="31" border="0"></a>
			</div>

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
