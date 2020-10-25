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

// Myパートナークラス取得
if ($pjt_id) {
	$myp = new my_partner_class;
	$myp->read_db($pjt_id);
} else
	$myp = &$_SESSION['s_my_partner'];
$enquete = &$myp->enquete;

// 件名を取得
if ($myp->subject == '')
	$subject = $myp->enquete->title;
else
	$subject = $myp->subject;

// ヘッダ、本文、フッタを取得
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
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
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
			<br>

			<div align="center">
			<table border=0 cellspacing=1 cellpadding=0>
				<tr>
					<td class="m5">■<?=htmlspecialchars($subject)?></td>
				</tr>
				<tr>
					<td colspan=2>
<table border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td bgcolor="#dadada"> 
      <table border=0 cellspacing=1 cellpadding=5 width="100%">
        <tr> 
          <td bgcolor="#FFFFFF" width="100%">
		  <xmp><?=$mail_text?></xmp>
		  </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

					</td>
				</tr>
			</table>
			<br>
			<a href="javascript:history.back();"><img name="image2" src="images/mkk_achange/bt01.gif" alt="戻る" width="108" height="31"></a>
			</div>
			<!-- myパートナー -->

		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
