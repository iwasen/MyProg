<?
$top = './..';
$inc = "$top/../inc";
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/point.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_regist.php");
include("$inc/mn_partner.php");
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// ���å�������
if (!isset($_SESSION['ss_regist']))
	redirect('m_update.php');
$reg = &$_SESSION['ss_regist'];

db_begin_trans();

// ���ܾ����ơ��֥����¸
$reg->update_db_b($_SESSION['ss_monitor_id']);

// ��������ơ��֥����¸
$reg->update_db_l($_SESSION['ss_monitor_id']);

// ���å�����ѿ����
unset($_SESSION['ss_regist']);
session_unregister('ss_regist');

// �ꥵ�����ѡ��ȥʡ������ơ��֥����¸
if ($p) {
	$partner = &$_SESSION['ss_partner'];
	$partner->update_db($_SESSION['ss_monitor_id']);

	unset($_SESSION['ss_partner']);
	session_unregister('ss_partner');
}

// �������᡼��ˤ��ݥ������Ϳ
if (isset($_SESSION['birthday_id'])) {
	$sql = "SELECT bm_point FROM t_birthday_mail WHERE bm_seq_no=" . sql_number($_SESSION['birthday_id']);
	$point = db_fetch1($sql);
	if ($point != 0) {
		// ǯ�����
		$sql = "SELECT DATE_PART('Y', AGE(mn_birthday)) AS mn_age FROM t_monitor WHERE mn_monitor_id=" . sql_number($_SESSION['ss_monitor_id']);
		$age = db_fetch1($sql);

		// �Ǹ�˹�������ǯ������
		$sql = "SELECT mp_birthday_update_age FROM t_monitor_point WHERE mp_monitor_id=" . sql_number($_SESSION['ss_monitor_id']);
		$birthday_update_age = db_fetch1($sql);

		// �ۤʤ��硢�ݥ������Ϳ
		if ($age != $birthday_update_age) {
			// �ݥ������Ϳ
			monitor_point($_SESSION['ss_monitor_id'], PT_BIRTHDAY, $point);

			// �Ǹ�˹�������ǯ��򹹿�
			$rec['mp_birthday_update_age'] = sql_number($age);
			db_update('t_monitor_point', $rec, "mp_monitor_id=" . sql_number($_SESSION['ss_monitor_id']));
		}
	}

	unset($_SESSION['birthday_id']);
	session_unregister('birthday_id');
}

// �ݥ����ͭ�����±�Ĺ
point_period_extension($_SESSION['ss_monitor_id']);

// ���������ڡ���ݥ���Ȳû�
point_update_campaign($_SESSION['ss_monitor_id']);

db_commit_trans();
?>
<? monitor_menu() ?>
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TBODY>
			<TR bgColor=#85A9DE>
				<TD><IMG height=29 src="<?=$img?>/spacer.gif" width=1 align=absMiddle>
					<SPAN class=mysttl_menu>�����ץ�ե�����</SPAN>
				</TD>
			</TR>
			<TR>
				<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD></TR>
			<TR>
				<TD bgColor=#88b7d8><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			</TR>
		</TBODY>
	</TABLE>
<br>
<table width="496" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="15">��</td>
		<td colspan="3"><table width="450" border="0" cellspacing="0" cellpadding="20">
				<tr>
					<td>
						<form>
						<table border="0" cellpadding="3" cellspacing="2" width="100%">
							<tr>
								<td bgcolor="#525294"><font color="#ffcc00">��</font><font color="white">��Ͽ����ι���</font></td>
							</tr>
							<tr>
								<td><font size="2"><b><br>
									</b>��Ͽ����򹹿����ޤ�����<br>
									<br>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
</body>
</html>
