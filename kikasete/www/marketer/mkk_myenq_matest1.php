<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/enq_mail.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if (isset($my_enq_no)) {
	$marketer_id= $_SESSION['ss_marketer_id'];
	$owner_id = $_SESSION['ss_owner_id'];

	if (isset($_SESSION['ss_my_enq'])) {
		$myenq = &$_SESSION['ss_my_enq'];
		if ($myenq->my_enq_no != $my_enq_no)
			$myenq->read_db($owner_id, $my_enq_no);
	} else {
		$_SESSION['ss_my_enq'] = new my_enquete_class;
		$myenq = &$_SESSION['ss_my_enq'];
		$myenq->read_db($owner_id, $my_enq_no);
	}
} else
	redirect('mkk_myenq.php');

$enquete = &$myenq->enquete;

$header = $myenq->mail_header;
$body = $myenq->mail_contents;
$footer = $myenq->mail_footer;

$marketer_id = $_SESSION['ss_marketer_id'];
$sql = "SELECT mr_mail_addr,mr_name1 FROM t_marketer WHERE mr_marketer_id=$marketer_id";
$result = db_exec($sql);
if (pg_numrows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mail_addr = htmlspecialchars($fetch->mr_mail_addr);
	$header = str_replace('%MAIL_ADDR%', $fetch->mr_mail_addr, $header);
	$body = str_replace('%MONITOR_NAME%', $fetch->mr_name1, $body);
}

$mail_text = make_enq_mail($myenq->enquete, $header, $body, $footer);

if ($enquete->enq_type == 1)
	$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_mail AS from_name');
else
	$fetch = get_system_info('sy_enq_domain,sy_enq_account,sy_enq_web AS from_name');

if ($fetch->from_name != '')
	$from = "$fetch->from_name <$fetch->sy_enq_account@$fetch->sy_enq_domain>";
else
	$from = "$fetch->sy_enq_account@$fetch->sy_enq_domain";

send_mail($myenq->mail_title, $mail_addr, $from, $mail_text);
?>
<? marketer_header('Ｍｙアンケート', PG_NULL) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top">≪メールのテスト送信完了≫</td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="768" border="0" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td>
						<img src="images/made1_e.gif" width="90" height="61" border="0">　　
						<img src="images/made2_e.gif" width="90" height="61" border="0">　　
						<img src="images/made3_e.gif" width="90" height="61" border="0">　　
						<img src="images/made4_e.gif" width="90" height="61" border="0">　　
						<img src="images/con_s.gif" width="90" height="61" border="0">　　
						<img src="images/app_f.gif" width="90" height="61" border="0">
					</td>
				</tr>
			</table>
			<br>
			<form method="" action="mkk_myenq_con.php">
			<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
			<center>
			<table width="500" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">
						
						<b><?=$mail_addr?></b> 宛にテストメールを送信しました
						<br><br>
						※1行が極端に長い場合文字化けすることがあります。その際には適切な個所で改行してください
						
					</td>
				</tr>
				<tr>
					<td>
						<table width="500" valign="top" bgcolor="#ffffff" align="center">
							<tr>
								<td width="100%" align="center">
									
									<input type="submit" value="　戻る　">
									
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</center>
			</form>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
