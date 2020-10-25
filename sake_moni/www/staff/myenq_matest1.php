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
include("$inc/mypage.php");
include("$inc/header2.php");

// セッション処理
if (isset($my_enq_no)) {
	$staff_id= $_SESSION['ss_staff_id'];

	if (isset($_SESSION['ss_my_enq'])) {
		$myenq = &$_SESSION['ss_my_enq'];
		if ($myenq->my_enq_no != $my_enq_no)
			$myenq->read_db($staff_id, $my_enq_no);
	} else {
		$_SESSION['ss_my_enq'] = new my_enquete_class;
		$myenq = &$_SESSION['ss_my_enq'];
		$myenq->read_db($staff_id, $my_enq_no);
	}
} else
	redirect('myenq.php');

$enquete = &$myenq->enquete;

$header = $myenq->mail_header;
$body = $myenq->mail_contents;
$footer = $myenq->mail_footer;

$staff_id = $_SESSION['ss_staff_id'];
$sql = "SELECT st_mail_addr,st_name1 FROM t_staff WHERE st_staff_id=$staff_id";
$result = db_exec($sql);
if (pg_num_rows($result)) {
	$fetch = pg_fetch_object($result, 0);
	$mail_addr = htmlspecialchars($fetch->st_mail_addr);
	$header = str_replace('%MAIL_ADDR%', $fetch->st_mail_addr, $header);
	$body = str_replace('%MONITOR_NAME%', $fetch->st_name1, $body);
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
<? staff_header('アンケート登録') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1">≪メールのテスト送信完了≫</font></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="700" border="0" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td><font size="3" class="honbun1">
						<img src="images/made1_e.gif" width="90" height="61" border="0">　　
						<img src="images/made2_e.gif" width="90" height="61" border="0">　　
						<img src="images/made3_e.gif" width="90" height="61" border="0">　　
						<img src="images/made4_e.gif" width="90" height="61" border="0">　　
						<img src="images/con_s.gif" width="90" height="61" border="0">　　
						<img src="images/app_f.gif" width="90" height="61" border="0"></font>
					</td>
				</tr>
			</table>
			<br>
			<form method="" action="myenq_con.php">
			<input type="hidden" name="my_enq_no" value="<?=$my_enq_no?>">
			<center>
			<table width="500" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">
						<font size="2" class="honbun2">
						<b><?=$mail_addr?></b> 宛にテストメールを送信しました
						<br><br>
						※1行が極端に長い場合文字化けすることがあります。その際には適切な個所で改行してください
						</font>
					</td>
				</tr>
				<tr>
					<td>
						<table width="500" valign="top" bgcolor="#ffffff" align="center">
							<tr>
								<td width="100%" align="center">
									<font size="2" class="honbun2">
									<input type="submit" value="　戻る　">
									</font>
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

<? staff_footer() ?>
