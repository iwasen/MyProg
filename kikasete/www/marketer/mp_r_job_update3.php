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

// My�ѡ��ȥʡ����饹����
$myp = &$_SESSION['s_my_partner'];
$search = &$myp->search;
$pjt_id = $myp->pjt_id;
if (!$pjt_id)
	redirect('mp_pjt_list.php');

// �ե����फ�饯�饹������
$myp->send_num = $_POST['send_num'];
$search->type = $_POST['type'];
$search->sex = (count($_POST['sex']) == 1) ? $_POST['sex'][0] : '';
$myp->age_type = $_POST['age_type'];
if ($myp->age_type == '1') {
	$search->age_cd = get_multi_data($_POST['age_cd']);
	$search->age_from = '';
	$search->age_to = '';
} elseif ($myp->age_type == '2') {
	$search->age_cd = '';
	$search->age_from = $_POST['age_from'];
	$search->age_to = $_POST['age_to'];
} else {
	$search->age_cd = '';
	$search->age_from = '';
	$search->age_to = '';
}
$search->mikikon = (count($_POST['mikikon']) == 1) ? $_POST['mikikon'][0] : '';
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
//jeon_start seq=mp3
$search->zip = $_POST['zip'];
$search->zip_kinmuchi = $_POST['kinmuchi'];
$search->zip_kinmuchi_andor = $_POST['andor'];
//jeon_end seq=mp3
// �Ϳ���������
if ($next_action == 'search') {
	redirect('mp_r_job_search.php');
}

// ����ȯ���������å�
if ($myp->send_num <= 0)
	$msg[] = '����ȯ���������Ϥ��Ƥ���������';
else {
		$fetch = get_system_info('sy_myp_max_send');
		$max_send_num = $fetch->sy_myp_max_send;
	if (!check_num($myp->send_num, 0, $max_send_num))
		$msg[] = "����ȯ������{$max_send_num}�̰ʲ������Ϥ��Ƥ���������";
}

// ���󥱡��ȥ����ȥ�����å�
if ($search->type == '')
	$msg[] = '�оݥ�˥��������򤵤�Ƥ��ʤ��褦�Ǥ���';

// �оݼԿ������å�
if (!$msg && $search->get_monitor_num() < $myp->send_num)
	$msg[] = '�оݥ�˥�����������ȯ�����������ʤ��褦�Ǥ���';

if (!$msg) {
	// DB����¸
	$myp->save_search = true;
	$myp->recruit_flag |= 0x04;
	$myp->write_db();

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
					<td>�ꥯ�롼�ȥ��󥱡��Ȥ��оݾ������꤬��λ���ޤ�����</td>
				</tr>
			</table>
			<br>
			<a href="mp_r_job.php?pjt_id=<?=$pjt_id?>"><img src="images/mkk_bt/back.gif" alt="���" name="image2" width="108" height="31" border="0"></a>
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
