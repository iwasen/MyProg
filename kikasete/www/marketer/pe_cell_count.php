<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enq_target.php");
include("$inc/pro_enquete.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���å�������
if (isset($pro_enq_no)) {
	$pro_enq = new pro_enquete_class;
	$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
} else
	redirect('pe_index.php');

$search = &$pro_enq->search;
$cell = &$pro_enq->cell;

$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);

$pro_enq_target =& new pro_enq_target_class;
$pro_enq_target->read_db($pro_enq->enquete_id);

// ������ץ������
$sample_cell = $cell->get_sample_cell($pro_enq->enquete_id, $enquete->start_date, $pro_enq_target->target_flg);
?>
<? marketer_header('�У�異�󥱡���', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mkk_enqdet1.gif" width="538" height="20"></td>
				</tr>
			</table>
			<br>
			<table width="650" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						�ե�����ǥ��󥰲�����
					</td>
					<td align="right">
						<form method="post" action="pe_cell_csv.php">
						<input type="hidden" name="pro_enq_no" value="<?=$pro_enq_no?>">
						<input type="image" src="images/mkk_bt/dl_csv.gif" alt="CSV�Υ��������">
						</form>
					</td>
				</tr>
				<tr>
					<td align="right" colspan=2>
						<a href="pe_enqdet1.php?pro_enq_no=<?=$pro_enq_no?>">���󥱡��Ⱦܺ�ɽ�������</a>
					</td>
				</tr>
			</table>
			<br>
			<form>
			<center>
			<table width="650" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">

			<table width="650" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
<?
if ($cell->age_option == 2) {
?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="5%" rowspan=2>&nbsp;</td>
					<td nowrap width="5%">10��</td>
					<td nowrap width="10%" colspan=2>20��</td>
					<td nowrap width="10%" colspan=2>30��</td>
					<td nowrap width="10%" colspan=2>40��</td>
					<td nowrap width="10%" colspan=2>50��</td>
					<td nowrap width="10%" colspan=2>60��</td>
					<td nowrap width="5%" rowspan=2>70��<br>�ʾ�</td>
					<td nowrap width="5%" rowspan=2>���</td>
				</tr>
				<tr bgcolor="#eeeeee" align="center">
					<td>��Ⱦ</td>
					<td>��Ⱦ</td>
					<td>��Ⱦ</td>
					<td>��Ⱦ</td>
					<td>��Ⱦ</td>
					<td>��Ⱦ</td>
					<td>��Ⱦ</td>
					<td>��Ⱦ</td>
					<td>��Ⱦ</td>
					<td>��Ⱦ</td>
					<td>��Ⱦ</td>
				</tr>
<?
} elseif ($cell->age_option == 3) {
?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">10��</td>
					<td nowrap width="10%">20��</td>
					<td nowrap width="10%">30��</td>
					<td nowrap width="10%">40��</td>
					<td nowrap width="10%">50��</td>
					<td nowrap width="10%">60��</td>
					<td nowrap width="10%">70��ʾ�</td>
					<td nowrap width="10%">���</td>
				</tr>
<?
} else {
?>
				<tr bgcolor="#eeeeee" align="center">
					<td nowrap width="10%">&nbsp;</td>
					<td nowrap width="10%">ǯ�����ʤ�</td>
					<td nowrap width="10%">���</td>
				</tr>
<?
}

$sum_row = array();
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
				<tr bgcolor="#ffffff">
					<td bgcolor="#eeeeee" nowrap align="center"><?=decode_sex($sex, '���̻���ʤ�')?></td>
<?
	$sum_col = 0;
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = $sample_cell[$sex][$age];
		$sum_col += $num;
		$sum_row[$age] += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?
	}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
<?
}
?>
				<tr bgcolor="#ffffff">
					<td bgcolor="#eeeeee" nowrap align="center">���</td>
<?
$sum_col = 0;
for ($age = $age_s; $age <= $age_e; $age++) {
	$num = $sum_row[$age];
	$sum_col += $num;
?>
					<td nowrap align="right"><?=number_format($num)?></td>
<?
}
?>
					<td nowrap align="right"><?=number_format($sum_col)?></td>
				</tr>
			</table>
					</td>
				</tr>
				<tr>
					<td>
						<table width="500" valign="top" bgcolor="#ffffff" align="center">
							<tr>
								<td width="100%" align="center">
									<a href="javascript:history.back();"><img src="images/mkk_bt/back.gif" alt="���" name="image2" width="108" height="31" border="0"></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</center>
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
