<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

if ($addr_list == '')
	$msg = '������Υ᡼�륢�ɥ쥹�����Ϥ��Ƥ���������';
else {
	$addr_list = str_replace("\n", ',', $addr_list);
	$ary = explode(',', $addr_list);
	foreach ($ary as $mail_addr) {
		$mail_addr = trim($mail_addr);
		if (!check_mail_addr($mail_addr)) {
			$msg = '�������ʤ��᡼�륢�ɥ쥹�����Ϥ���Ƥ���褦�Ǥ���';
			break;
		}
	}
}
?>
<? marketer_header('�У�異�󥱡��ȷ�̤򶵤���', PG_NULL) ?>

<?
if (!$msg) {
?>
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
		<td width="400" valign="top"><span style="font-weight:bold;">��У�異�󥱡��ȷ�̤�Ǥ�դΥ��ɥ쥹��������</span><br><br></td>
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
			<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
			<table width="580" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td align="center">
						�����Υ��ɥ쥹�ˤ��ʤ��ΣУ�異�󥱡��Ȥη�̤��Τ餻���ޤ���<br>
						���ɥ쥹�˴ְ㤤���ʤ�������ǧ����������<br>
						�ְ㤤���ʤ���С������ܥ���򲡤��Ƥ���������<br>
						
					</td>
				</tr>
				<tr>
					<td><img src="images/common/spacer.gif" width="1" height="20" border="0">
					</td>
				</tr>
				<tr>
					<td bgcolor="#dadada" align="center">
<?
foreach ($ary as $mail_addr) {
	$mail_addr = trim($mail_addr);
	echo "$mail_addr<br>";
	echo '<input type="hidden" name="mail_addr[]" value="', htmlspecialchars($mail_addr), '">';
}
?>
					</td>
				</tr>
				<tr>
					<td><img src="images/common/spacer.gif" width="1" height="20" border="0">
					</td>
				</tr>
				<tr>
					<td align="center">

							<input type="image" src="images/mkk_bt/transmit.gif" alt="����" width="108" height="31">
							<a href="javascript:history.back();"><img src="images/mkk_bt/back.gif" alt="���" name="image2" width="108" 

height="31" border="0"></a>


					</td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
</table>
<?
} else{?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>

<? }?>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="1" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
