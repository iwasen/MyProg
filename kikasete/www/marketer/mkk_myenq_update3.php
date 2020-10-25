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
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

define('MAX_SEND_NUM', 10000);

// ���å�������
if (isset($my_enq_no)) {
	$myenq = &$_SESSION['ss_my_enq'];
	if ($myenq->my_enq_no != $my_enq_no)
		redirect("mkk_myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('mkk_myenq.php');

$search = &$myenq->search;

// �ե����फ�饯�饹������
$myenq->send_num = get_number($_POST['send_num']);

$search->type = $_POST['type'];

if ($_POST['cell_option'] == 2) {
	// �������ͭ��
	$search->sex = '';
	$search->age_cd = '';
	$search->age_from = $_POST['age_span_from'];
	$search->age_to = $_POST['age_span_to'];
} else {
	// �������̵��
	$search->sex = $_POST['sex'];

	$myenq->age_type = $_POST['age_type'];
	if ($myenq->age_type == '1') {
		$search->age_cd = get_multi_data($_POST['age_cd']);
		$search->age_from = '';
		$search->age_to = '';
	} elseif ($myenq->age_type == '2') {
		$search->age_cd = '';
		$search->age_from = $_POST['age_from'];
		$search->age_to = $_POST['age_to'];
	} else {
		$search->age_cd = '';
		$search->age_from = '';
		$search->age_to = '';
	}
}

$search->mikikon = $_POST['mikikon'];
$search->jitaku_area = get_multi_data($_POST['jitaku_area']);
$search->shokugyou = get_multi_data($_POST['shokugyou']);
$search->gyoushu = get_multi_data($_POST['gyoushu']);
$search->shokushu = get_multi_data($_POST['shokushu']);
$search->have_child = get_multi_data($_POST['have_child']);
$search->housing_form = get_multi_data($_POST['housing_form']);
$search->have_car = get_multi_data($_POST['have_car']);
$search->genre = get_multi_data($_POST['genre']);
$search->conveni = get_multi_data($_POST['conveni']);
$search->super = get_multi_data($_POST['super']);
$search->ma_profile = get_multi_data($_POST['ma_profile']);
$search->special_panel = get_multi_data($_POST['special_panel']);

$cell = &$myenq->cell;
$cell->cell_option = $_POST['cell_option'];
$cell->age_option = $_POST['age_option'];
$cell->sex_option = $_POST['age_option'] == 1 ? 2 : ($_POST['age_option'] == 2 ? $_POST['sex_option2'] : $_POST['sex_option3']);
$cell->rate_option = $_POST['rate_option'];
$cell->send_num = $_POST['cell'][$cell->age_option];
$cell->send_rate = $_POST['rate_option'] == 1 ? null : ($_POST['rate_option'] == 2 ? $_POST['rate_mikikon'] : $_POST['rate_area']);

// ¾�ڡ����إ�����쥯��
switch ($next_action) {
case 'search':
	// �Ϳ�����
	redirect("mkk_myenq_matest2.php?my_enq_no=$my_enq_no");
case 'send_check':
	// ȯ������ǧ
	redirect("mkk_send_check.php?my_enq_no=$my_enq_no");
case 'send_check2':
	// ȯ������ǧ
	redirect("mkk_send_check2.php?my_enq_no=$my_enq_no");
}

// ����ȯ���������å�
if ($myenq->send_num <= 0)
	$msg[] = '����ȯ���������Ϥ��Ƥ���������';
else {
	$sql = "SELECT ems_max_send_num FROM t_myenq_max_send WHERE ems_marketer_id=$myenq->marketer_id";
	$max_send_num = db_fetch1($sql, MAX_SEND_NUM);
	if (!check_num($myenq->send_num, 0, $max_send_num))
		$msg[] = "����ȯ������{$max_send_num}�̰ʲ������Ϥ��Ƥ���������";
}

if (!$msg) {
	$msg2 = 'My���󥱡��Ȥ��оݾ������ꤷ�ޤ�����';

	// �ۿ��������å�
	if ($cell->rate_option == 2) {
		// ̤�����Ǥɤ��餫��100%�ʤ餽����򸡺����Ȥ���
		for ($i = 1; $i <= 3; $i++) {
			if ($cell->send_rate[$i] == 100) {
				$cell->rate_option = 1;
				$search->mikikon = "$i";
				break;
			}
		}
	} elseif ($cell->rate_option == 3) {
		// �ۿ���礬���ꤵ��Ƥ��ʤ��ϰ�ϸ����оݤ��鳰��
		if (is_array($_POST['area_cd'])) {
			$jitaku_area = $_POST['jitaku_area'];
			foreach ($_POST['area_cd'] as $area_cd) {
				if ($_POST['rate_area'][$area_cd] == 0) {
					unset($jitaku_area[$area_cd]);
					$msg2 = '�ۿ���礬���ꤵ��Ƥ��ʤ��ϰ�ϸ����оݤ��鳰���ޤ�����';
				}
			}
			$search->jitaku_area = get_multi_data($jitaku_area);
		}
	}

	// �оݼԿ������å�
	if ($cell->cell_option == '1') {
		if (!$msg && $search->get_monitor_num() < $myenq->send_num)
			$msg2 = '�оݥ�˥�����������ȯ�����������ʤ��褦�Ǥ���';
	} elseif ($cell->cell_option == '2') {
		$search_cell = $cell->get_search_cell($search->make_sql());
		$send_cell = $cell->get_send_cell();
		$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);

		$short = false;
		if ($cell->rate_option == '1') {
			for ($sex = $sex_s; $sex <= $sex_e && !$short; $sex++) {
				for ($age = $age_s; $age <= $age_e && !$short; $age++) {
					if ((int)$search_cell[$sex][$age][0] < (int)$send_cell[$sex][$age][0])
						$short = true;
				}
			}
		} else {
			for ($sex = $sex_s; $sex <= $sex_e && !$short; $sex++) {
				for ($age = $age_s; $age <= $age_e && !$short; $age++) {
					for ($rate = $rate_s; $rate <= $rate_e && !$short; $rate++) {
						if ((int)$search_cell[$sex][$age][$rate] < (int)$send_cell[$sex][$age][$rate])
							$short = true;
					}
				}
			}
		}
		if ($short) {
			$edit = true;
			$msg2 = 'ȯ���Ϳ���­��ʤ�ǯ����ϰ�ˤ�����ޤ�������ȯ�����ʾ�ȯ�����˾��������ϡ��������Ƥ���������';
		}
	}

	// DB����¸
	$myenq->save_search = true;
	$myenq->write_db();
	$my_enq_no = $myenq->my_enq_no;

	// ���å�����ѿ����
	unset($_SESSION['ss_my_enq']);
}
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
<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td><?=$msg2?></td>
				</tr>
			</table>
			<br>
<?
if ($edit) {
?>
<a href="mkk_myenq_edit3.php?my_enq_no=<?=$my_enq_no?>"><img name="image3" src="images/mkk_bt/revision.gif" alt="������" width="108" height="31"></a>
<?
}
?>
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
    <td><? error_msg($msg, 'mkk_myenq_edit3.php?edit=1');?></td>
  </tr>
</table>
<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
    
<? marketer_footer() ?>
