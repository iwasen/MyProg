<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
include("$inc/enq_mail.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if (isset($my_enq_no)) {
	$myenq = new my_enquete_class;
	$myenq->read_db($_SESSION['ss_owner_id'], $my_enq_no);
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
<? marketer_header('Ｍｙアンケート', PG_NULL) ?>

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
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
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

		</td>
	</tr>
</table>

<? marketer_footer() ?>
