<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/check.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// ���å�������
if (isset($pro_enq_no)) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	if ($pro_enq->pro_enq_no != $pro_enq_no)
		redirect("pe_make.php?pro_enq_no=$pro_enq_no");
} else
	redirect('pe_index.php');

// �ե����फ�����ϥǡ�������
$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;

$pro_enq->finding_flag = $_POST['finding_flag'];
$enquete->enq_kind = $pro_enq->finding_flag == DBTRUE ? 5 : 6;
$enquete->title = trim($_POST['title']);
$enquete2->title = trim($_POST['title']);
$enquete->enq_type = 2;
$enquete->start_date_y = get_number($_POST['start_date_y']);
$enquete->start_date_m = get_number($_POST['start_date_m']);
$enquete->start_date_d = get_number($_POST['start_date_d']);
$enquete->start_date_h = get_number($_POST['start_date_h']);
$enquete->end_date_y = get_number($_POST['end_date_y']);
$enquete->end_date_m = get_number($_POST['end_date_m']);
$enquete->end_date_d = get_number($_POST['end_date_d']);
$enquete->end_date_h = get_number($_POST['end_date_h']);
if (isset($_POST['ans_cond'])) {
	$ans_cond_ary = array();
	foreach ($_POST['ans_cond'] as $ans_cond) {
		if ($ans_cond != '')
			$ans_cond_ary[] = $ans_cond;
	}
	$pro_enq->ans_cond = join("\n", $ans_cond_ary);
}

if ($_POST['next_action'] == 'add_cond') {
	$pro_enq->ans_cond_count++;
	redirect("pe_edit1.php?edit=1");
}

// ���ϥ����å�
if ($enquete->title == '' )
	$msg[] = '���󥱡��ȥ����ȥ�����Ϥ��Ƥ���������';

if ($enquete->enq_type == '')
	$msg[] = '���󥱡��ȼ�������򤷤Ƥ���������';

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

get_quickest_date_pro($send_date_y, $send_date_m, $send_date_d, $send_date_h);
$quickest_date = strtotime("$send_date_y/$send_date_m/$send_date_d $send_date_h:00:00");

if ($start_date_ok) {
	$start_date = strtotime("$enquete->start_date_y/$enquete->start_date_m/$enquete->start_date_d $send_date_h:00:00");
	if ($start_date == -1) {
		$msg[] = '�������դ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$start_date_ok = false;
	} else if ($start_date < $quickest_date) {
		$msg[] = '����ȯ�����ϻ��ְʹߤ������ǳ������������ꤷ�Ƥ���������';
		$start_date_ok = false;
	} elseif (date('Y', $start_date) != $enquete->start_date_y || date('n', $start_date) != $enquete->start_date_m || date('j', $start_date) != $enquete->start_date_d) {
		$msg[] = '�������դ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
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
	$end_date = strtotime("$enquete->end_date_y/$enquete->end_date_m/$enquete->end_date_d $enquete->end_date_h:00:00");
	if ($end_date == -1) {
		$msg[] = '��λ���դ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$end_date_ok = false;
	} else if ($end_date <= $quickest_date) {
		$msg[] = '����ȯ�����ϻ��ְʹߤ������ǽ�λ���������ꤷ�Ƥ���������';
		$end_date_ok = false;
	} else if ($start_date_ok && $end_date < $start_date) {
		$msg[] = '��λ�����ϳ��������ʹߤ����Ϥ��Ƥ���������';
		$end_date_ok = false;
	} elseif (date('Y', $end_date) != $enquete->end_date_y || date('n', $end_date) != $enquete->end_date_m || date('j', $end_date) != $enquete->end_date_d) {
		$msg[] = '��λ���դ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$end_date_ok = false;
	}
}

// ������Υǥե���Ȥ򥻥å�
if ($pro_enq->pro_enq_no == '') {
	if ($_SESSION['ss_marketer_type'] == MK_ASSISTANT)
		$pro_enq->notify_assistant = $_SESSION['ss_marketer_id'];
}

// �᡼�륿���ȥ�Υǥե���Ȥ򥻥å�
$pro_enq->mail_title = $enquete->title . '�ˤĤ��ƤΥ��󥱡���';

// ���顼̵���ʤ鼡�ڡ�����
if (!$msg) {
	// Pro���󥱡�����¸
	$pro_enq->save_enquete = true;
	$pro_enq->save_enquete2 = true;
	$pro_enq->write_db();
	$pro_enq_no = $pro_enq->pro_enq_no;

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
					<td>�У�異�󥱡��Ȥμ»ܳ��פ����ꤷ�ޤ�����</td>
				</tr>
			</table>
			<br>

			<a href="pe_make.php?pro_enq_no=<?=$pro_enq_no?>"><img src="images/mkk_bt/back.gif" alt="���" name="image2" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
<?
} else{?>

	<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg, 'pe_edit1.php?edit=1');?></td>
  </tr>
</table>

<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>