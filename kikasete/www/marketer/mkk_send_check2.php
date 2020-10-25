<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���å�������
if (isset($my_enq_no)) {
	$myenq = &$_SESSION['ss_my_enq'];
	if ($myenq->my_enq_no != $my_enq_no)
		redirect("mkk_myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('mkk_myenq.php');

$search = &$myenq->search;
$cell = &$myenq->cell;

$search_cell = $cell->get_search_cell($search->make_sql());
$send_cell = $cell->get_send_cell();

$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);

// ��­�����å�
$short = false;
for ($sex = $sex_s; $sex <= $sex_e && !$short; $sex++) {
	for ($age = $age_s; $age <= $age_e && !$short; $age++) {
		for ($rate = $rate_s; $rate <= $rate_e && !$short; $rate++) {
			if ((int)$search_cell[$sex][$age][$rate] < (int)$send_cell[$sex][$age][$rate])
				$short = true;
		}
	}
}

$rowspan = $cell->age_option == 2 ? 3 : 2;
$colspan = $age_e - $age_s + 1;
?>
<? marketer_header('My���󥱡���', PG_NULL) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

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
			<br>
			<table width="650" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						�����Կ�
					</td>
				</tr>
				<tr>
					<td>
<?
if ($short) {
?>
						<br>���ꤷ���ۿ������Ǥϡ����Ť�����Ƥ��륻��οͿ���­��ޤ���<br>
						<a href="javascript:history.back()">������Υڡ���</a>����ꡢ������ۿ��������򤪴ꤤ�������ޤ���
<?
} else {
?>
						<br>���ꤷ���ۿ������ǡ��ƥ���Ȥ�Ϳ����������Ƥ��ޤ��Τ�<br>�����Τ褦�ʳ��դ����᡼�����ۿ������ͽ��Ǥ���
<?
}
?>
					</td>
				</tr>
			</table>
			<br>
			<div align="center">
			<form>
			<table width="650" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td width="100%" valign="top" bgcolor="#ffffff" align="center">
						<table width="650" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
                          <tr bgcolor="#eeeeee" align="center">
                            <td nowrap width="10%" rowspan="<?=$rowspan?>">&nbsp;</td>
                            <?
if ($cell->sex_option == 2) {
?>
                            <td nowrap width="40%" colspan="<?=$colspan?>">����</td>
                            <td nowrap width="40%" colspan="<?=$colspan?>">����</td>
                            <?
} else {
?>
                            <td nowrap width="80%" colspan="<?=$colspan?>">���̻���ʤ�</td>
                            <?
}
?>
                            <td nowrap width="10%" rowspan="<?=$rowspan?>">���</td>
                          </tr>
                          <tr bgcolor="#eeeeee" align="center">
                            <?
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
	if ($cell->age_option == 2) {
?>
                            <td nowrap width="5%">10��</td>
                            <td nowrap width="10%" colspan=2>20��</td>
                            <td nowrap width="10%" colspan=2>30��</td>
                            <td nowrap width="10%" colspan=2>40��</td>
                            <td nowrap width="10%" colspan=2>50��</td>
                            <td nowrap width="10%" colspan=2>60��</td>
                            <td nowrap width="5%" rowspan=2>70��<br>
                              �ʾ�</td>
                            <?
	} elseif ($cell->age_option == 3) {
?>
                            <td nowrap width="10%">10��</td>
                            <td nowrap width="10%">20��</td>
                            <td nowrap width="10%">30��</td>
                            <td nowrap width="10%">40��</td>
                            <td nowrap width="10%">50��</td>
                            <td nowrap width="10%">60��</td>
                            <td nowrap width="10%">70��<br>
                              �ʾ�</td>
                            <?
	} else {
?>
                            <td nowrap width="10%">ǯ�����ʤ�</td>
                            <?
	}
}
?>
                          </tr>
                          <?
if ($cell->age_option == 2) {
?>
                          <tr bgcolor="#eeeeee" align="center">
                            <?
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
                            <td>��</td>
                            <td>��</td>
                            <td>��</td>
                            <td>��</td>
                            <td>��</td>
                            <td>��</td>
                            <td>��</td>
                            <td>��</td>
                            <td>��</td>
                            <td>��</td>
                            <td>��</td>
                            <?
	}
?>
                          </tr>
                          <?
}

if ($cell->rate_option == 2) {
	for ($i = 1; $i <= 3; $i++)
		$rate_name[$i] = decode_mikikon($i);
} else {
	$sql = "SELECT ar3_area3_cd,ar3_area3_name FROM m_area3";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$rate_name[$fetch->ar3_area3_cd] = $fetch->ar3_area3_name;
	}
}

$sum_row = array();
for ($rate = $rate_s; $rate <= $rate_e; $rate++) {
?>
                          <tr bgcolor="#ffffff">
                            <td bgcolor="#eeeeee" nowrap align="center">
                              <?=$rate_name[$rate]?>
                            </td>
                            <?
	$sum_col = 0;
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
		for ($age = $age_s; $age <= $age_e; $age++) {
			$num = min($search_cell[$sex][$age][$rate], $send_cell[$sex][$age][$rate]);
			$sum_col += $num;
			$sum_row[$sex][$age] += $num;
			$bgcolor = (int)$search_cell[$sex][$age][$rate] < (int)$send_cell[$sex][$age][$rate] ? '#ffddef' : '#ffffff';
?>
                            <td nowrap align="right" bgcolor="<?=$bgcolor?>">
                              <?=number_format($num)?>
                            </td>
                            <?
		}
	}
?>
                            <td nowrap align="right">
                              <?=number_format($sum_col)?>
                            </td>
                          </tr>
                          <?
}
?>
                          <tr bgcolor="#ffffff">
                            <td bgcolor="#eeeeee" nowrap align="center">���</td>
                            <?
$sum_col = 0;
for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
	for ($age = $age_s; $age <= $age_e; $age++) {
		$num = $sum_row[$sex][$age];
		$sum_col += $num;
?>
                            <td nowrap align="right"><?=number_format($num)?></td>
                            <?
	}
}
?>
                            <td nowrap align="right"><?=number_format($sum_col)?></td>
                          </tr>
                        </table>
						<div align="left">
<br>
���ʤ������ꤤ�����������٤Ƥξ��򸵤˳ƥ���Ǥ��ۿ����򻻽Ф��Ƥ���ޤ�����<br>
���ºݤ��ۿ����Ͼ�������1�̤�ͼθ�������뤿�ᡢ�嵭���ۿ����̤���ۿ�����ʤ�<br>
�����뤬�㴳�����礬����ޤ������餫���ᤴλ������������<br>
</div>
<?
if ($short) {
?>
						<br>
						<table width="650" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>�ۿ������</td>
							</tr>
						</table>
						<table width="650" border="0" cellspacing="0" cellpadding="5">
							<tr>
								<td width="100%" valign="top" bgcolor="#ffffff" align="center">
									<table width="650" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
										<tr bgcolor="#eeeeee" align="center">
											<td nowrap width="10%" rowspan="<?=$rowspan?>">&nbsp;</td>
<?
	if ($cell->sex_option == 2) {
?>
											<td nowrap width="40%" colspan="<?=$colspan?>">����</font></td>
											<td nowrap width="40%" colspan="<?=$colspan?>">����</font></td>
<?
	} else {
?>
											<td nowrap width="80%" colspan="<?=$colspan?>">���̻���ʤ�</td>
<?
	}
?>
											<td nowrap width="10%" rowspan="<?=$rowspan?>">���</td>
										</tr>
										<tr bgcolor="#eeeeee" align="center">
<?
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
		if ($cell->age_option == 2) {
?>
											<td nowrap width="5%">10��</td>
											<td nowrap width="10%" colspan=2>20��</td>
											<td nowrap width="10%" colspan=2>30��</td>
											<td nowrap width="10%" colspan=2>40��</td>
											<td nowrap width="10%" colspan=2>50��</td>
											<td nowrap width="10%" colspan=2>60��</td>
											<td nowrap width="5%" rowspan=2>70��<br>�ʾ�</td>
<?
		} elseif ($cell->age_option == 3) {
?>
											<td nowrap width="10%">10��</td>
											<td nowrap width="10%">20��</td>
											<td nowrap width="10%">30��</td>
											<td nowrap width="10%">40��</td>
											<td nowrap width="10%">50��</td>
											<td nowrap width="10%">60��</td>
											<td nowrap width="10%">70��<br>�ʾ�</td>
<?
		} else {
?>
											<td nowrap width="10%">ǯ�����ʤ�</td>
<?
		}
	}
?>
										</tr>
<?
	if ($cell->age_option == 2) {
?>
										<tr bgcolor="#eeeeee" align="center">
<?
		for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
?>
										<td>��</font></td>
										<td>��</td>
										<td>��</td>
										<td>��</td>
										<td>��</td>
										<td>��</td>
										<td>��</td>
										<td>��</td>
										<td>��</td>
										<td>��</td>
										<td>��</td>
										<td>��</td>
<?
		}
?>
									</tr>
<?
	}

	if ($cell->rate_option == 2) {
		for ($i = 1; $i <= 3; $i++)
			$rate_name[$i] = decode_mikikon($i);
	} else {
		$sql = "SELECT ar3_area3_cd,ar3_area3_name FROM m_area3";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$rate_name[$fetch->ar3_area3_cd] = $fetch->ar3_area3_name;
		}
	}

	$sum_row = array();
	for ($rate = $rate_s; $rate <= $rate_e; $rate++) {
?>
									<tr bgcolor="#ffffff">
										<td bgcolor="#eeeeee" nowrap align="center"><?=$rate_name[$rate]?></td>
<?
		$sum_col = 0;
		for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
			for ($age = $age_s; $age <= $age_e; $age++) {
				$num = $send_cell[$sex][$age][$rate];
				$sum_col += $num;
				$sum_row[$sex][$age] += $num;
?>
										<td nowrap align="right"><?=number_format($num)?></td>
<?
			}
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
	for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
		for ($age = $age_s; $age <= $age_e; $age++) {
			$num = $sum_row[$sex][$age];
			$sum_col += $num;
?>
										<td nowrap align="right"><?=number_format($num)?></td>
<?
		}
	}
?>
										<td nowrap align="right"><?=number_format($sum_col)?></td>
									</tr>
								</table>
<?
}
?>
							</td>
						</tr>
						<tr>
						<td>
							<table width="500" valign="top" bgcolor="#ffffff" align="center">
								<tr>
									<td width="100%" align="center">
									<a href="mkk_myenq_edit3.php?edit=1"><img src="images/mkk_achange/bt01.gif" alt="���" name="image2" width="108" height="31" border="0"></a>
								</td>
							</tr>
							</table>
						</td>
					</tr>
				</table>
				</td>
				</tr>
				</table>
			</form>
			</div>
		</td>
	</tr>
</table>

<? marketer_footer() ?>
