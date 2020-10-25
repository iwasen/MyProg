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
include("$inc/mypage.php");
include("$inc/header2.php");
include("$inc/error.php");

// �����ѿ�¸�ߥ����å�
function check_variable(&$text, $name, $place) {
	global $msg;

	if (strpos($text, $name) === false)
		$msg[] = "ɬ�פʺ����ѿ���{$name}�ˤ�{$place}��̵���褦�Ǥ���";
}

// ���å�������
if (isset($my_enq_no)) {
	$myenq = &$_SESSION['ss_my_enq'];
	if ($myenq->my_enq_no != $my_enq_no)
		redirect("myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('myenq.php');

// �ե����फ�饯�饹������
$myenq->mail_title = $subject;
$myenq->mail_header = $header;
$myenq->mail_contents = $body;
$myenq->mail_footer = $footer;

// �ꥻ�åȽ���
if ($next_action == 'reset') {
	$myenq->mail_title = '';
	$myenq->mail_header = '';
	$myenq->mail_contents = '';
	$myenq->mail_footer = '';
	redirect('myenq_edit_mail.php?edit=1');
}

// �ץ�ӥ塼����
if ($next_action == 'preview')
	redirect('myenq_mailprev.php');

// �����ѿ�¸�ߥ����å�
check_variable($myenq->mail_header, '%POINT%', '�إå�');
check_variable($myenq->mail_contents, '%MONITOR_NAME%', '��ʸ');
check_variable($myenq->mail_contents, '%ENQUETE_PARAM%', '��ʸ');
check_variable($myenq->mail_contents, '%MAIL_ADDR%', '��ʸ');
check_variable($myenq->mail_contents, '%POINT%', '��ʸ');

if (!$msg) {
	// DB����¸
	$myenq->write_db();

	// ���å�����ѿ����
	unset($_SESSION['ss_my_enq']);
}
?>
<? staff_header('���󥱡�����Ͽ') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<?
if (!$msg) {
?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="710" align="center" valign="top">
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td><font size="2" class="honbun2">���󥱡��ȤΥ᡼��ʸ�����ꤷ�ޤ�����</font></td>
				</tr>
			</table>
			<br>
			<input type="button" value="����롡" onclick="location.href='myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			</form>
			</div>
		</td>
	</tr>
</table>
<?
} else
	error_msg($msg);
?>

<? staff_footer() ?>
