<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
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

// ���å�������
if (isset($pro_enq_no)) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	if ($pro_enq->pro_enq_no != $pro_enq_no)
		redirect("pe_make.php?pro_enq_no=$pro_enq_no");
} else
	redirect('pe_index.php');

// �ե����फ�饯�饹������
$pro_enq->mail_title = $subject;
$pro_enq->mail_header = $header;
$pro_enq->mail_contents = $body;
$pro_enq->mail_footer = $footer;

// �ꥻ�åȽ���
if ($next_action == 'reset') {
	$pro_enq->mail_title = '';
	$pro_enq->mail_header = '';
	$pro_enq->mail_contents = '';
	$pro_enq->mail_footer = '';
	redirect('pe_edit_mail.php?edit=1');
}

// �ץ�ӥ塼����
if ($next_action == 'preview')
	redirect('pe_mailprev.php');

// �����ѿ�¸�ߥ����å�
check_variable($pro_enq->mail_header, '%POINT%', '�إå�');
check_variable($pro_enq->mail_contents, '%MONITOR_NAME%', '��ʸ');
check_variable($pro_enq->mail_contents, '%ENQUETE_PARAM%', '��ʸ');
check_variable($pro_enq->mail_contents, '%MAIL_ADDR%', '��ʸ');
check_variable($pro_enq->mail_contents, '%POINT%', '��ʸ');

if (!$msg) {
	// DB����¸
	$pro_enq->write_db();

	// ���å�����ѿ����
	unset($_SESSION['ss_pro_enq']);
}
?>
<? marketer_header('�У�異�󥱡���', PG_NULL) ?>
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
					<td>�У�異�󥱡��ȤΥ᡼��ʸ�����ꤷ�ޤ�����</td>
				</tr>
			</table>
			<br>
<a href="pe_make.php?pro_enq_no=<?=$pro_enq_no?>"><img src="images/mkk_bt/back.gif" alt="���" width="108" height="31" border="0"></a>
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
