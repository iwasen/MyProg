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
<? marketer_header('�ͣ����󥱡��ȷ�̤򶵤���', PG_NULL) ?>

<?
if (!$msg) {
?>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top">��ͣ����󥱡��ȷ�̤�Ǥ�դΥ��ɥ쥹��������<br><br></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
		<form method="post" action="mkk_nmyenq_com.php">
			<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						�����Υ��ɥ쥹�ˤ��ʤ��Σͣ����󥱡��Ȥη�̤��Τ餻���ޤ���<br>
						���ɥ쥹�˴ְ㤤���ʤ�������ǧ����������<br>
						�ְ㤤���ʤ���С������ܥ���򲡤��Ƥ���������<br>
					</td>
				</tr>
			</table>
			<br>
			
        <table width="580" border="0" cellspacing="0" cellpadding="3">
          <tr>
					<td>
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
					<td align="center">
						
							<input type="submit" value="��������">��
							<input type="button" value="��������" onclick="history.back()">
						
					</td>
				</tr>
			</table>
		</form>
		</td>
	</tr>
</table>
<? } else{?>
<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>
<? }?>
<? marketer_footer() ?>
