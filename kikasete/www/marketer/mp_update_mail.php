<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/check.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// �����ѿ�¸�ߥ����å�
function check_variable(&$text, $name, $place) {
	global $msg;

	if (strpos($text, $name) === false)
		$msg[] = "ɬ�פʺ����ѿ���{$name}�ˤ�{$place}��̵���褦�Ǥ���";
}

// My�ѡ��ȥʡ����饹����
$mp = &$_SESSION['s_my_partner'];
$enquete = &$mp->enquete;
$pjt_id = $mp->pjt_id;
if (!$pjt_id)
	redirect('mp_pjt_list.php');

// �ե����फ�饯�饹������
$mp->subject = $subject;
$mp->header = $header;
$mp->body = $body;
$mp->footer = $footer;

// �ꥻ�åȽ���
if ($next_action == 'reset') {
	$mp->subject = '';
	$mp->header = '';
	$mp->body = '';
	$mp->footer = '';
	redirect('mp_edit_mail.php?edit=1');
}

// �ץ�ӥ塼����
if ($next_action == 'preview') {
	redirect('mp_preveiw_mail.php');
}

// �����ѿ�¸�ߥ����å�
check_variable($mp->header, '%POINT%', '�إå�');
check_variable($mp->body, '%MONITOR_NAME%', '��ʸ');
check_variable($mp->body, '%ENQUETE_PARAM%', '��ʸ');
check_variable($mp->body, '%MAIL_ADDR%', '��ʸ');
check_variable($mp->body, '%POINT%', '��ʸ');

if (!$msg) {
	// DB����¸
	$mp->write_db();

	// ���å�����ѿ����
	unset($_SESSION['s_my_partner']);
}
?>
<? marketer_header('My�ѡ��ȥʡ�', PG_NULL) ?>
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
<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- my�ѡ��ȥʡ� -->
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>My�ѡ��ȥʡ��Υ᡼��ʸ�����ꤷ�ޤ�����</td>
				</tr>
			</table>
			<br>
			
			<input type="button" value="����롡" onclick="location.href='mp_r_job.php?pjt_id=<?=$pjt_id?>'">
			</form>
			</div>
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
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>