<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
include("$inc/error.php");

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
<? staff_header('���󥱡��ȷ�̤򶵤���') ?>

<?
if (!$msg) {
?>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1">�㥢�󥱡��ȷ�̤�Ǥ�դΥ��ɥ쥹��������</font><br><br></td>
	</tr>
</table>
<form method="post" action="nmyenq_com.php">
	<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
	<table width="580" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<font size="3" class="honbun1">�����Υ��ɥ쥹�ˤ��ʤ��Υ��󥱡��Ȥη�̤��Τ餻���ޤ���<br>
				���ɥ쥹�˴ְ㤤���ʤ�������ǧ����������<br>
				�ְ㤤���ʤ���С������ܥ���򲡤��Ƥ���������<br>
				</font>
			</td>
		</tr>
	</table>
	<br>
	<table width="580" border="1" cellspacing="0" cellpadding="3">
		<tr>
			<td><font size="3" class="honbun1">
<?
foreach ($ary as $mail_addr) {
	$mail_addr = trim($mail_addr);
	echo "$mail_addr<br>";
	echo '<input type="hidden" name="mail_addr[]" value="', htmlspecialchars($mail_addr), '">';
}
?>
			</font></td>
		</tr>
		<tr>
			<td align="center">
				<font size="3" class="honbun1">
					<input type="submit" value="��������">��
					<input type="button" value="��������" onclick="history.back()">
				</font>
			</td>
		</tr>
	</table>
</form>
<?
} else
	error_msg($msg);
?>
<? staff_footer() ?>
