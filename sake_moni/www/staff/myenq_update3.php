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

$search = &$myenq->search;

// �ե����फ�饯�饹������
$myenq->send_num = get_number($_POST['send_num']);
$search->sex = (count($_POST['sex']) == 1) ? $_POST['sex'][0] : '';
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
$search->mikikon = (count($_POST['mikikon']) == 1) ? $_POST['mikikon'][0] : '';
$search->jitaku_area = get_multi_data($_POST['jitaku_area']);
$search->shokugyou = get_multi_data($_POST['shokugyou']);
$search->chain = get_multi_data($_POST['chain']);
$search->child = (count($_POST['child']) == 1) ? $_POST['child'][0] : '';
$search->research = (count($_POST['research']) == 1) ? $_POST['research'][0] : '';

// �Ϳ���������
if ($next_action == 'search')
	redirect("myenq_matest2.php?my_enq_no=$my_enq_no");

// ����ȯ���������å�
if ($myenq->send_num <= 0)
	$msg[] = '����ȯ���������Ϥ��Ƥ���������';
elseif (!check_num($myenq->send_num, 0, 9999))
	$msg[] = '����ȯ������9999�̰ʲ������Ϥ��Ƥ���������';

// �оݼԿ������å�
if (!$msg && $search->get_monitor_num() < $myenq->send_num)
	$msg[] = '�оݥ�˥�����������ȯ�����������ʤ��褦�Ǥ���';

if (!$msg) {
	// DB����¸
	$myenq->save_search = true;
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
					<td><font size="2" class="honbun2">���󥱡��Ȥ��оݾ������ꤷ�ޤ�����</font></td>
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
