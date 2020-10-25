<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
include("$inc/error.php");

// ���å�������
if (isset($my_enq_no)) {
	$myenq = &$_SESSION['ss_my_enq'];
	if ($myenq->my_enq_no != $my_enq_no)
		redirect("myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('myenq.php');

// �ե����फ�����ϥǡ�������
$enquete = &$myenq->enquete;

$enquete->title = trim($_POST['title']);
$enquete->enq_type = 2;
$enquete->start_date_y = get_number($_POST['start_date_y']);
$enquete->start_date_m = get_number($_POST['start_date_m']);
$enquete->start_date_d = get_number($_POST['start_date_d']);
$enquete->start_date_h = get_number($_POST['start_date_h']);
$enquete->end_date_y = get_number($_POST['end_date_y']);
$enquete->end_date_m = get_number($_POST['end_date_m']);
$enquete->end_date_d = get_number($_POST['end_date_d']);
$enquete->end_date_h = get_number($_POST['end_date_h']);
if (isset($_POST['ans_cond']))
	$myenq->ans_cond = join("\n", $_POST['ans_cond']);

// ���ϥ����å�
if ($enquete->title == '' )
	$msg[] = '���󥱡��ȥ����ȥ�����Ϥ��Ƥ���������';

if ($enquete->enq_type == '')
	$msg[] = '���󥱡��ȼ�������򤷤Ƥ���������';

$time = time();
$start_date_ok = true;
$end_date_ok = true;

if ($enquete->start_date_y == '') {
	$msg[] = '����������ǯ�����Ϥ��Ƥ���������';
	$start_date_ok = false;
} elseif (!check_num($enquete->start_date_y, date('Y'), 2999)) {
	$msg[] = '����������ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$start_date_ok = false;
}

if ($enquete->start_date_m == '') {
	$msg[] = '���������η�����Ϥ��Ƥ���������';
	$start_date_ok = false;
} elseif (!check_num($enquete->start_date_m, 1, 12)) {
	$msg[] = '���������η���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$start_date_ok = false;
}

if ($enquete->start_date_d == '') {
	$msg[] = '�����������������Ϥ��Ƥ���������';
	$start_date_ok = false;
} elseif (!check_num($enquete->start_date_d, 1, 31)) {
	$msg[] = '�����������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$start_date_ok = false;
}

if ($enquete->start_date_h == '') {
	$msg[] = '���������λ������Ϥ��Ƥ���������';
	$start_date_ok = false;
} elseif (!check_num($enquete->start_date_h, 0, 23)) {
	$msg[] = '���������λ������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$start_date_ok = false;
}

if ($start_date_ok) {
	$start_date = strtotime("$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d $enquete->start_date_h:0:0");
	if ($start_date == -1) {
		$msg[] = '�������դ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$start_date_ok = false;
	} else if ($start_date < $time) {
		$msg[] = '����������᤮�Ƥ��ޤ���';
		$start_date_ok = false;
	}
}

if ($enquete->end_date_y == '') {
	$msg[] = '��λ������ǯ�����Ϥ��Ƥ���������';
	$end_date_ok = false;
} elseif (!check_num($enquete->end_date_y, date('Y'), 2999)) {
	$msg[] = '��λ������ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$end_date_ok = false;
}

if ($enquete->end_date_m == '') {
	$msg[] = '��λ�����η�����Ϥ��Ƥ���������';
	$end_date_ok = false;
} elseif (!check_num($enquete->end_date_m, 1, 12)) {
	$msg[] = '��λ�����η���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$end_date_ok = false;
}

if ($enquete->end_date_d == '') {
	$msg[] = '��λ�������������Ϥ��Ƥ���������';
	$end_date_ok = false;
} elseif (!check_num($enquete->end_date_d, 1, 31)) {
	$msg[] = '��λ�������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$end_date_ok = false;
}

if ($enquete->end_date_h == '') {
	$msg[] = '��λ�����λ������Ϥ��Ƥ���������';
	$end_date_ok = false;
} elseif (!check_num($enquete->end_date_h, 0, 23)) {
	$msg[] = '��λ�����λ������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$end_date_ok = false;
}

if ($end_date_ok) {
	$end_date = strtotime("$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d");
	if ($end_date == -1) {
		$msg[] = '��λ���դ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$end_date_ok = false;
	} else if ($start_date_ok && $end_date < $start_date) {
		$msg[] = '��λ�����ϳ��������ʹߤ����Ϥ��Ƥ���������';
		$end_date_ok = false;
	}
}

// ���顼̵���ʤ鼡�ڡ�����
if (!$msg) {
	// ���󥱡�����¸
	$myenq->save_enquete = true;
	$myenq->write_db();
	$my_enq_no = $myenq->my_enq_no;

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
					<td><font size="2" class="honbun2">���󥱡��Ȥμ»ܳ��פ����ꤷ�ޤ�����</font></td>
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
