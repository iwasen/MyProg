<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

$staff_id = $_SESSION['ss_staff_id'];

$fetch = get_system_info('sy_url_staff');
$staff_url  = $fetch->sy_url_staff;
$url = $fetch->sy_url_staff;

$ary = file("${url}resdet_pv2.php?staff_id=$staff_id&enquete_id=$enquete_id");
$html = join("", $ary);

$ary = file("${url}enq_csv1.php?staff_id=$staff_id&enquete_id=$enquete_id&type=send");
$csv_send = join("", $ary);

$ary = file("${url}enq_csv1.php?staff_id=$staff_id&enquete_id=$enquete_id&type=recv");
$csv_recv = join("", $ary);

$ary = file("${url}enq_csv2.php?staff_id=$staff_id&enquete_id=$enquete_id");
$csv_result = join("", $ary);

$subject = 'お酒と買物のアンケートモニター アンケート結果';
$from = db_fetch1("SELECT st_mail_addr fROM t_staff WHERE st_staff_id=$staff_id");

$send_mail_id = send_mail_data($subject, $from, '', $html, '', '', '', '1');
send_mail_file($send_mail_id, 1, "send.csv", $csv_send);
send_mail_file($send_mail_id, 2, "receive.csv", $csv_recv);
send_mail_file($send_mail_id, 3, "result.csv", $csv_result);
foreach ($mail_addr as $to) {
	send_mail_list($send_mail_id, $to);
}
send_mail_ok($send_mail_id);
?>
<? staff_header('アンケート結果を教える') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1">≪アンケート結果を任意のアドレスに送信≫</font><br><br></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
		<form method="post" action="nmyenq_com.php">
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<font size="3" class="honbun1">申請が終了しました。</font>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<? staff_footer() ?>
