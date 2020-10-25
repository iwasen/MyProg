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
$pro_enq->res_and_or = $_POST['res_and_or'];
$pro_enq->appearance_ratio = get_number($_POST['appearance_ratio']);

if ($pro_enq->appearance_ratio == 0)
	$msg[] = '����и�Ψ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
elseif (!check_num($pro_enq->appearance_ratio, 1, 99))
	$msg[] = '����и�Ψ��1��99���ϰϤ����Ϥ��Ƥ���������';

if (!$msg) {
	$pro_enq->res_cond = array();
	if (is_array($_POST['and_or'])) {
		foreach ($_POST['and_or'] as $qno => $and_or) {
			$pro_enq->res_cond[$qno]->and_or = $and_or;
			$pro_enq->res_cond[$qno]->not_cond = $_POST['not_cond'][$qno];
			$pro_enq->res_cond[$qno]->select_no = $_POST['select_no'][$qno] ? join(',', $_POST['select_no'][$qno]) : '';
		}
	} else
		$pro_enq->res_cond = null;

	// ���顼̵���ʤ鼡�ڡ�����
	if (!$msg) {
		// Pro���󥱡�����¸
		$pro_enq->save_res_cond = true;

		if ($pro_enq->finding_flag == DBTRUE) {
			$pro_enq->enquete2->description = $pro_enq->res_cond_text('�Ȥ������������������ˤ�ʹ�����ޤ���');
			$pro_enq->save_enquete2 = true;
		}

		$pro_enq->write_db();
		$pro_enq_no = $pro_enq->pro_enq_no;

		// ���å�����ѿ����
		unset($_SESSION['ss_pro_enq']);
	}
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
					<td>�У�異�󥱡��Ȥ��ܥ��󥱡����оݾ������ꤷ�ޤ�����</td>
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
