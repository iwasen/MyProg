<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// ���å�������
$myenq = new my_enquete_class;
if ($my_enq_no == '') {
	$myenq->marketer_id = $_SESSION['ss_owner_id'];
	$myenq->creator_id = $_SESSION['ss_marketer_id'];
} else
	$myenq->read_db($_SESSION['ss_owner_id'], $my_enq_no);

// �᡼�륢�ɥ쥹�����å�
$notify_mail_addr_ary = array();
if ($notify_others) {
	foreach (explode(',', str_replace("\n", ',', str_replace("\r", '', $notify_mail_addr))) as $mail_addr) {
		if ($mail_addr != '') {
			$notify_mail_addr_ary[] = $mail_addr;
			if (!check_mail_addr($mail_addr)) {
				$msg[] = "�᡼�륢�ɥ쥹���������ʤ��褦�Ǥ���";
				break;
			}
		}
	}
}

$rec['me_notify_assistant'] = sql_char($notify_assistant ? join(',', $notify_assistant) : '');
$rec['me_notify_mail_addr'] = sql_char($notify_others ? join(',', $notify_mail_addr_ary) : '');
db_update('t_my_enquete', $rec, "me_marketer_id=$myenq->marketer_id AND me_my_enq_no=$myenq->my_enq_no");
?>
<? marketer_header('My���󥱡���', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<?
if (!$msg) {
?>
<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>�ޡ�������������������Ȥؤ�Ʊ�����Τ����ꤷ�ޤ�����</td>
				</tr>
			</table>
			<br>

			<a href="mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>"><img src="images/mkk_bt/back.gif" alt="���" name="image2" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
<?
} else{?>

	<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>

<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
