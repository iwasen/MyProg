<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mail.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

$owner_id = $_SESSION['ss_owner_id'];

$fetch = get_system_info('sy_url_marketer');
$marketer_url  = $fetch->sy_url_marketer;
$url = $fetch->sy_url_marketer;

$ary = file("${url}pe_resdet_pv2.php?owner_id=$owner_id&pro_enq_no=$pro_enq_no");
$html = join("", $ary);

$ary = file("${url}pe_enq_csv1.php?owner_id=$owner_id&pro_enq_no=$pro_enq_no&type=send");
$csv_send = join("", $ary);

$ary = file("${url}pe_enq_csv1.php?owner_id=$owner_id&pro_enq_no=$pro_enq_no&type=recv");
$csv_recv = join("", $ary);

$ary = file("${url}pe_enq_csv2.php?owner_id=$owner_id&pro_enq_no=$pro_enq_no");
$csv_result = join("", $ary);

$subject = 'きかせて・net Ｐｒｏアンケート結果';
$from = db_fetch1("SELECT mr_mail_addr fROM t_marketer WHERE mr_marketer_id=$owner_id");

$send_mail_id = send_mail_data($subject, $from, '', $html, '', '', '', '1');
send_mail_file($send_mail_id, 1, "send.csv", $csv_send);
send_mail_file($send_mail_id, 2, "receive.csv", $csv_recv);
send_mail_file($send_mail_id, 3, "result.csv", $csv_result);
foreach ($mail_addr as $to) {
	send_mail_list($send_mail_id, $to);
}
send_mail_ok($send_mail_id);
?>
<? marketer_header('Ｐｒｏアンケート結果を教える', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top"><span style="font-weight:bold;">≪Ｐｒｏアンケート結果を任意のアドレスに送信≫</span><br><br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
		<form method="post" action="pe_nmyenq_com.php">
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						申請が終了しました。
					</td>
				</tr>
								<tr>
					<td>
						<img src="images/common/spacer.gif" width="1" height="20" border="0">
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
