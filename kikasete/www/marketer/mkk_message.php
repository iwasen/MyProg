<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_subr.php");

$owner_id = $_SESSION['ss_owner_id'];
?>
<? marketer_header('����������Ȥإ�å�����������', PG_CONTACT) ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top"><img src="images/t_mkk_message.gif" width="538" height="20"><br><br></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="700"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<form method="post" name"form1" action="mkk_messconfirm.php">
			<table width="600" border="1" cellspacing="0" cellpadding="3">
				<tr>
					<td bgcolor="#eeeeee">�����ȥ�</td>
				</tr>
				<tr>
					<td><input type="text" size="54" name="title" value="����ˡ����η�ˤĤ���"></td>
				</tr>
				<tr>
					<td bgcolor="#eeeeee">��å�������ʸ</td>
				</tr>
				<tr>
					<td><textarea rows="15" cols="70" wrap="hard" name="text"></textarea></td>
				</tr>
				<tr>
					<td>
<?
// ̾���ȥ᡼�륢�ɥ쥹����
$fetch = get_owner_info('mr_name1,mr_name2,mr_mail_addr');
$marketer_name = htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2");
$mail_addr = htmlspecialchars($fetch->mr_mail_addr);
?>
						<table width="580" border="0" cellspacing="0" cellpadding="3">
							<tr>
								<td width="30%">��̾��
								<td width="70%"><?=$marketer_name?>
							</tr>
							<tr>
								<td width="30%">�᡼�륢�ɥ쥹
								<td width="70%"><?=$mail_addr?>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table width="570" border="0" cellspacing="0" cellpadding="3">
							<tr>
								<td align="center"><input type="submit" value="���Ρ�ǧ��">��<input type="reset" value="�����ꥢ��"></td>
							</tr>
							<tr>
								<td>�� ��å�����������塢��°����������Ȥ��餴Ϣ�����Ƥ��������ޤ���</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</form>
			<br>
			<form method="post" name="form2" action="mkk_messdelm.php">
			<table width="700" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><b>������°����������Ȥ����ä���å���������</b>�ʲ������ä���å�������������ɽ���������</td>
				</tr>
				<tr>
					<td>�����ȥ�򥯥�å�����ȡ����˥���������Ȥ�����������å������ξܺ٤�ߤ뤳�Ȥ��Ǥ��ޤ���</td>
				</tr>
			</table>
			<br>
			<table width="600" border="1" cellspacing="0" cellpadding="3">
				<tr bgcolor="#eeeeee">
					<td width="65%">�����ȥ�</td>
					<td width="25%">��������</td>
					<td width="10%">���</td>
				</tr>
<?
// ����������Ȥ���Υ�å�������������
$sql = "SELECT co_seq_no,co_regist_date,co_title"
		. " FROM t_communication"
		. " WHERE co_marketer_id=$owner_id AND co_status=0 AND co_direction=1 ORDER BY co_regist_date DESC";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$seq_no = $fetch->co_seq_no;
	$title = htmlspecialchars($fetch->co_title);
	$regist_date = format_date($fetch->co_regist_date);
?>
				<tr>
					<td width="65%"><a href="mkk_pastmess1.php?seq_no=<?=$seq_no?>"><?=$title?></a></td>
					<td width="25%" align="right"><?=$regist_date?></td>
					<td width="10%"><input type="checkbox" name="delete[]" value="<?=$seq_no?>"></td>
				</tr>
<?
}
?>
			</table>
			<br>
			<table width="600" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right"><input type="submit" value="���"></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
