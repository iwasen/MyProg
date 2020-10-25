<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/my_search.php");
include("$inc/ma_profile.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ����ɽ����ǯ������
function disp_date1($year, $month, $day) {
	if ($year)
		echo sprintf('%dǯ%d��%d��', $year, $month, $day);
}

// ����ɽ����ǯ��������
function disp_date2($year, $month, $day, $hour) {
	if ($year)
		echo sprintf('%dǯ%d��%d�� %d��', $year, $month, $day, $hour);
}

// �ϰ�̾
function decode_area3($code) {
	if ($code != '') {
		$sql = "SELECT ar3_area3_name FROM m_area3 WHERE ar3_area3_cd=$code";
		$name = db_fetch1($sql);
	}
	return $name;
}

// �ե�����ǥ���̵ͭ
function decode_finding_flag($flag) {
	switch ($flag) {
	case DBTRUE:
		return '����';
	case DBFALSE:
		return '�ʤ�';
	}
	return '';
}

// ���󥱡�������ɽ��
function disp_enquete($enquete) {
	if (is_array($enquete->question)) {
		$page_break = '';

		foreach ($enquete->question as $qno => $question) {
			// ���Ѥμ����ֹ�
			$qno_zenkaku = '��' . mb_convert_kana($qno, 'N');

			// ��������󥯤λ���
			$image_msg = '��������󥯤λ��ꡧ';
			if ($question->url)
				$image_msg .= "URL����ꤹ�� [$question->url]";
			elseif ($question->image_id) {
				$sql = "SELECT im_name FROM t_image WHERE im_image_id=$question->image_id";
				$image_msg .= '������������ [' . htmlspecialchars(db_fetch1($sql)) . ']';
			} else
				$image_msg .= '�ʤ�';

			if (isset($enquete->branch_cond[$qno])) {
?>
	<tr>
		<td bgcolor="#eeeeee" rowspan="2">ʬ�����</td>
		<td bgcolor="#ffeecc">��<?=mb_convert_kana($qno, 'N')?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc"><?=htmlspecialchars($enquete->branch_cond_text($qno, '�����򤷤����Τ�', true))?></td>
	</tr>
<?
			} elseif ($question->page_break == DBTRUE) {
?>
	<tr>
		<td bgcolor="#eeeeee" colspan="2" align="center">���ڡ���</td>
	</tr>
<?
			}

			if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
	<tr>
		<td bgcolor="#eeeeee" width="20%" rowspan="4"><?=$qno_zenkaku?>
			<div><?=$question->must_flag == 't' ? '��ɬ�ܲ�����' : '��Ǥ�ղ�����'?></div></td>
		<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc"><?=$image_msg?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc">
<?
				foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>��<?=htmlspecialchars($sel_text)?><?=$question->fa_sno == $sno ? ' �ʥե꡼������' : ''?><?=$question->ex_sno == $sno ? ' ����¾��' : ''?><br>
<?
				}
?>
		</td>
	</tr>
<?
			} elseif ($question->question_type == 3) {
?>
	<tr>
		<td bgcolor="#eeeeee" width="20%" rowspan="3"><?=$qno_zenkaku?>
			<div><?=$question->must_flag == 't' ? '��ɬ�ܲ�����' : '��Ǥ�ղ�����'?></div></td>
		<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc"><?=$image_msg?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
	</tr>
<?
			} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
	<tr>
		<td bgcolor="#eeeeee" width="20%" rowspan="4"><?=$qno_zenkaku?>
			<div><?=$question->must_flag == 't' ? '��ɬ�ܲ�����' : '��Ǥ�ղ�����'?></div></td>
		<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc"><?=$image_msg?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc">
			<table border="0" cellspacing="2" cellpadding="2" width="100%" class="honbun2">
				<tr>
					<td>&nbsp;</td>
<?
				foreach ($question->hyoutou as $hno => $hyoutou) {
?>
					<td bgcolor="#ffffff"><?=mb_convert_kana($hno, 'N')?>��<?=htmlspecialchars($hyoutou)?></td>
<?
				}
?>
				</tr>
<?
				foreach ($question->hyousoku as $hno => $hyousoku) {
?>
				<tr bgcolor="<?=$hno % 2 ? '#e8fbfd' : '#ffffff'?>">
					<td><?=mb_convert_kana($hno, 'N')?>��<?=htmlspecialchars($hyousoku)?></td>
<?
					foreach ($question->hyoutou as $hno => $hyoutou) {
?>
					<td></td>
<?
					}
?>
				</tr>
<?
				}
?>
			</table>
		</td>
	</tr>
<?
			} elseif ($question->question_type == 6 || $question->question_type == 8) {
?>
	<tr>
		<td bgcolor="#eeeeee" width="20%" rowspan="4">��<?=mb_convert_kana($qno, 'N')?>
			<div><?=$question->must_flag == 't' ? '��ɬ�ܲ�����' : '��Ǥ�ղ�����'?></div></td>
		<td bgcolor="#ffeecc"><?=nl2br(htmlspecialchars($question->question_text))?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc"><?=$image_msg?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
	</tr>
	<tr>
		<td bgcolor="#ffeecc">
			<div>�����������ɽ������ʸ����<?=htmlspecialchars($question->pre_text)?></div>
			<div>������θ��ɽ������ʸ����<?=htmlspecialchars($question->post_text)?></div>
		</td>
	</tr>
<?
			}
		}
	}
}

if (isset($pro_enq_no)) {
	$pro_enq  = new pro_enquete_class;
	$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
} else
	redirect('pe_index.php');

$enquete = &$pro_enq->enquete;
$enquete2 = &$pro_enq->enquete2;
$search = &$pro_enq->search;
$cell = &$pro_enq->cell;
$cleaning = &$pro_enq->cleaning;

// �Խ���ǽ�ե饰����
$edit_flag = ($enquete->status == 0 || $enquete->status == 8) && !$show;
//$edit_flag = true;///////////////////////////////////////////////////////////////

// ������λ�ե饰����
if ($enquete->title != '')
	$make_flag |= 0x01;
if ($pro_enq->finding_flag == DBTRUE) {
	if ($enquete->get_question_num() != 0)
		$make_flag |= 0x02;
	if ($pro_enq->res_cond)
		$make_flag |= 0x04;
} else
	$make_flag |= 0x06;
if ($enquete2 && $enquete2->get_question_num() != 0)
	$make_flag |= 0x08;
if ($cleaning->exist_flag)
	$make_flag |= 0x10;
if ($pro_enq->search_id)
	$make_flag |= 0x20;
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
			<table border=0 cellspacing=2 cellpadding=3 width="95%" class="honbun2">
				<tr>
					<td align="left">

��������������ӣ������ơ�����Ѥߡפޤ��ϡ��������ספˤʤäƤ���Сֺ�����λ�ץܥ��󤬸���ޤ���<br>
�ֺ�����λ�פ򲡤��ȡֿ����פ���ǽ�ˤʤ�ޤ���<br>
<br>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width=100% class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>�������󥱡��ȳ���</b></td>
								<td align="right">
<?
if ($edit_flag) {
?>
									 <?=$make_flag & 0x01 ? '<span class="footer_text">�����ִ�¸�Υ��󥱡��Ȥ���ʣ���פ򤷤���硢�����ȥ롦�����γ�ǧ�򤷤Ƥ���������</span>' : ''?> <input type="button" name="" value="<?=$make_flag & 0x01 ? '��������' : '�����ꡡ'?>" onclick="location.href='pe_edit1.php?pro_enq_no=<?=$pro_enq_no?>'"> <?=$make_flag & 0x01 ? '<b>����Ѥ�</b>' : ''?>
<?
}
?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td width="20%" bgcolor="#eeeeee">���󥱡��ȥ����ȥ�</td>
								<td width="80%" bgcolor="#ffeecc"><?=htmlspecialchars($enquete->title)?>�ˤĤ��ƤΥ��󥱡���</td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#eeeeee">�ե�����ǥ���̵ͭ</td>
								<td width="80%" bgcolor="#ffeecc"><?=decode_finding_flag($pro_enq->finding_flag)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">��������</td>
								<td bgcolor="#ffeecc"><? disp_date1($enquete->start_date_y, $enquete->start_date_m, $enquete->start_date_d)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">��λ����</td>
								<td bgcolor="#ffeecc"><? disp_date2($enquete->end_date_y, $enquete->end_date_m, $enquete->end_date_d, $enquete->end_date_h)?></td>
							</tr>
<!--
							<tr>
								<td bgcolor="#eeeeee">�������</td>
								<td bgcolor="#ffeecc">
<?
$ans_cond_ary = explode("\n", $pro_enq->ans_cond);
foreach ($ans_cond_ary as $ans_cond) {
	if ($ans_cond != '') {
?>
								��<?=htmlspecialchars($ans_cond)?><br>
<?
	}
}
?>								</td>
							</tr>
-->
						</table>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>�����ե�����ǥ��󥰥��󥱡�������</b></td>
								<td align="right">
<?
if ($edit_flag && ($make_flag & 0x01)) {
	if ($pro_enq->finding_flag == DBTRUE) {
		if ($make_flag & 0x02) {
?>
									<input type="button" value="ʬ�������ڡ���������" onclick="location.href='pe_edit_branch.php?pro_enq_no=<?=$pro_enq_no?>'">
<?
		}
?>
									<input type="button" value="<?=$make_flag & 0x02 ? '��������' : '�����ꡡ'?>" onclick="location.href='pe_edit2.php?pro_enq_no=<?=$pro_enq_no?>'"> <?=$make_flag & 0x02 ? '<b>����Ѥ�</b>' : ''?>
<?
	} elseif ($pro_enq->finding_flag == DBFALSE) {
?>
									<b>��������</b>
<?
	}
}
?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<?
if ($pro_enq->finding_flag == DBTRUE)
	disp_enquete($enquete);
?>
						</table>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>�����ܥ��󥱡����оݾ��</b></td>
								<td align="right">
<?
if ($edit_flag && ($make_flag & 0x03) == 0x03) {
	if ($pro_enq->finding_flag == DBTRUE) {
?>
									<input type="button" value="<?=$make_flag & 0x04 ? '��������' : '�����ꡡ'?>" onclick="location.href='pe_edit_res.php?pro_enq_no=<?=$pro_enq_no?>'"> <?=$make_flag & 0x04 ? '<b>����Ѥ�</b>' : ''?>
<?
	} elseif ($pro_enq->finding_flag == DBFALSE) {
?>
									<b>��������</b>
<?
	}
}
?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<?
if ($pro_enq->finding_flag == DBTRUE && is_array($pro_enq->res_cond)) {
?>
							<tr>
								<td width="20%" bgcolor="#eeeeee">����ַ����</td>
								<td width="80%" bgcolor="#ffeecc" colspan=3><?=decode_and_or($pro_enq->res_and_or)?></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#eeeeee" rowspan="<?=count($pro_enq->res_cond)?>">�������</td>
<?
	$first = true;
	foreach ($pro_enq->res_cond as $qno => $res_cond) {
		$question = &$enquete->question[$qno];

		$sno_ary = my_explode(',', $res_cond->select_no);
		$sel_text_ary = array();
		foreach ($sno_ary as $sno) {
			if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7)
				$sel_text_ary[] = $question->sel_text[$sno];
			elseif ($question->question_type == 4 || $question->question_type == 5) {
				list($sno, $tno) = explode(':', $sno);
				$sel_text_ary[] = $question->hyousoku[$sno] . '��' . $question->hyoutou[$tno];
			}
		}
		$sel_text = join('��', $sel_text_ary);

		if ($first)
			$first = false;
		else {
?>
							<tr>
<?
		}
?>
								<td width="10%" bgcolor="#ffeecc" align="center">��<?=mb_convert_kana($qno, 'N')?></td>
								<td width="10%" bgcolor="#ffeecc" align="center"><?=decode_and_or($res_cond->and_or)?></td>
								<td width="60%" bgcolor="#ffeecc"><?=htmlspecialchars($sel_text)?></td>
							</tr>
<?
	}
?>
							<tr>
								<td width="20%" bgcolor="#eeeeee">����и�Ψ</td>
								<td width="80%" bgcolor="#ffeecc" colspan=3><?=number_format($pro_enq->appearance_ratio)?> %</td>
							</tr>
<?
}
?>
						</table>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>�����ܥ��󥱡�������</b></td>
								<td align="right">
<?
if ($edit_flag && ($make_flag & 0x01)) {
	if ($make_flag & 0x08) {
?>
									<input type="button" value="ʬ�������ڡ���������" onclick="location.href='pe_edit_branch.php?pro_enq_no=<?=$pro_enq_no?>&main=1'">
<?
	}
?>
									<input type="button" value="<?=$make_flag & 0x08 ? '��������' : '�����ꡡ'?>" onclick="location.href='pe_edit2.php?pro_enq_no=<?=$pro_enq_no?>&main=1'"> <?=$make_flag & 0x08 ? '<b>����Ѥ�</b>' : ''?>
<?
}
?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<? disp_enquete($enquete2); ?>
						</table>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td bgcolor="#eff7e8" colspan="2"><b>�����оݾ�����ץ����</b>
                                  <div class="footer_text"></div></td>
								<td align="right">
<?
if ($edit_flag && ($make_flag & 0x01)) {
?>
									<input type="button" value="<?=$make_flag & 0x20 ? '��������' : '�����ꡡ'?>" onclick="location.href='pe_edit3.php?pro_enq_no=<?=$pro_enq_no?>'"> <?=$make_flag & 0x20 ? '<b>����Ѥ�</b>' : ''?>
<?
}
?>								</td>
							</tr>
						</table>					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<?
if ($pro_enq->finding_flag == DBTRUE) {
	if ($pro_enq->appearance_ratio != 0 && $pro_enq->sample_num != 0)
		$finding_sample_num = number_format($pro_enq->sample_num / ($pro_enq->appearance_ratio / 100));
	else
		$finding_sample_num = '';
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%"><span style="font-size:12px;">�ե�����ǥ��󥰥���ץ��</span></td>
								<td bgcolor="#ffeecc"><?=$finding_sample_num?></td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%">�ܥ��󥱡���<br>�����ɸ����ץ��</td>
								<td bgcolor="#ffeecc">
								<?=$pro_enq->sample_num?>								</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�������</td>
								<td bgcolor="#ffeecc">
									<? if ($make_flag & 0x20){
											if ($cell->cell_option == 1)
												echo('���ʤ�');
											else
												 echo('����');
										}
									?>								</td>
							</tr>
<?
if ($cell->cell_option == 1) {
?>
							<tr>
								<td bgcolor="#eeeeee">����</td>
								<td bgcolor="#ffeecc">
									<?
										if ($make_flag & 0x20)
											echo(decode_sex($search->sex, '����ʤ�'))
									?>									</td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">ǯ��</td>
								<td bgcolor="#ffeecc"><? disp_age2($search->age_cd, $search->age_from, $search->age_to) ?></td>
							</tr>
<?
} elseif ($cell->cell_option == 2) {
	$send_cell = $cell->get_send_cell();
	$cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e, 2);
?>
							<tr>
								<td bgcolor="#eeeeee">���̡�ǯ��</td>
								<td bgcolor="#ffeecc">
									<?=($search->age_from || $search->age_to) ? "Ǥ�դ�ǯ�����ꤹ�롡{$search->age_from}�� �� {$search->age_to}��" : "Ǥ�դ�ǯ�����ꤷ�ʤ�"?>
			<table width="100%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
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
			$num = $send_cell[$sex][$age][0];
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
			</table>								</td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee">̤����</td>
								<td bgcolor="#ffeecc">
									<?
										if ($make_flag & 0x20)
											echo(decode_mikikon($search->mikikon, '���ꤷ�ʤ�'));
									?>								</td>
							</tr>

<?
if ($cell->rate_option == 2) {
?>
							<tr>
								<td bgcolor="#eeeeee">ȯ���������</td>
								<td bgcolor="#ffeecc">̤��<?=$cell->send_rate[1]?>% �� ����<?=$cell->send_rate[2]?>% �� ����¾<?=$cell->send_rate[3]?>%</td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee">�ｻ�ϰ�</td>
								<td bgcolor="#ffeecc"><? disp_area($search->jitaku_area) ?></td>
							</tr>
<?
if ($cell->rate_option == 3) {
?>
							<tr>
								<td bgcolor="#eeeeee">ȯ���������</td>
								<td bgcolor="#ffeecc">
<?
	if (is_array($cell->send_rate)) {
		foreach ($cell->send_rate as $area_cd => $rate)
			$rate_area_ary[] = decode_area3($area_cd) . $rate . '%';

		echo join(' �� ', $rate_area_ary);
	}
?>								</td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee">����</td>
								<td bgcolor="#ffeecc"><? disp_shokugyou($search->shokugyou) ?></td>
							</tr>
<? /*
							<tr>
								<td bgcolor="#eeeeee">�ȼ�</td>
								<td bgcolor="#ffeecc"><? disp_gyoushu($search->gyoushu) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">����</td>
								<td bgcolor="#ffeecc"><? disp_shokushu($search->shokushu) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�Ҷ���̵ͭ</td>
								<td bgcolor="#ffeecc"><?=multi_decode(decode_have_child, $search->have_child)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�������</td>
								<td bgcolor="#ffeecc"><?=multi_decode(decode_housing_form, $search->housing_form)?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�֤ν�ͭ</td>
								<td bgcolor="#ffeecc"><?=multi_decode(decode_have_car, $search->have_car)?></td>
							</tr>

<?
// �ɲåץ�ե�����
$ma_profile = get_ma_profile();
foreach ($ma_profile as $profile_id => $profile_name) {
?>
							<tr>
								<td bgcolor="#eeeeee"><?=htmlspecialchars($profile_name)?></td>
								<td bgcolor="#ffeecc"><? disp_ma_profile($profile_id, $search->ma_profile) ?></td>
							</tr>
<?
}
?>
							<tr>
								<td bgcolor="#eeeeee">�褯�Ԥ�����ӥ�</td>
								<td bgcolor="#ffeecc"><? disp_conveni($search->conveni) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">�褯�Ԥ������ѡ�</td>
								<td bgcolor="#ffeecc"><? disp_super($search->super) ?></td>
							</tr>
							<tr>
								<td bgcolor="#eeeeee">��̣������</td>
								<td bgcolor="#ffeecc"><? disp_genre($search->genre) ?></td>
							</tr>
*/ ?>
						</table>					</td>
				</tr>
				<tr bgcolor="#eff7e8">
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width="100%" class="honbun2">
							<tr>
								<td><b>�����ޡ�������������������Ȥؤ�Ʊ������</b></td>
								<td align="right">&nbsp;</td>
							</tr>
						</table>					</td>
				</tr>
<?
if ($edit_flag && ($make_flag & 0x01)) {
?>
				<tr>
					<td>&nbsp;</td>
				</tr>
<?
}
?>
				<tr>
					<td colspan=2>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
<?
if ($make_flag & 0x01) {
?>
							<tr>
								<td bgcolor="#eeeeee" width="20%">�Ƽ����Τζ�ͭ</td>
								<td bgcolor="#ffeecc" width="80%">
									<form method="post" action="pe_update_notify.php">
<?
	$sql = "SELECT mr_marketer_id,mr_mail_addr,mr_name1,mr_name2"
		. " FROM t_marketer"
		. " WHERE mr_parent_id={$_SESSION['ss_owner_id']}"
		. " ORDER BY mr_marketer_id";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
?>
									<input type="checkbox" name="notify_assistant[]" <?=value_checked_multi($fetch->mr_marketer_id, $pro_enq->notify_assistant)?><?=disabled(!$edit_flag)?>><?=htmlspecialchars("$fetch->mr_name1 $fetch->mr_name2")?> ��<?=htmlspecialchars($fetch->mr_mail_addr)?>��<br>
<?
	}
?>
									<input type="checkbox" name="notify_others" value="1", <?=checked($pro_enq->notify_mail_addr)?><?=disabled(!$edit_flag)?>>�ɲáʥ���������Ȱʳ������Τ�ͭ���줿�����ϥ����å����Ƥ�����������<br>
									����<span class="footer_text">��ʣ�����������ꤹ����ϡ�1��1�᡼�륢�ɥ쥹�����Ϥ��Ƥ���������</span><br>
									<textarea name="notify_mail_addr" cols=40 rows=5<?=disabled(!$edit_flag)?>><?=str_replace(',', "\n", $pro_enq->notify_mail_addr)?></textarea>
<?
	if ($edit_flag) {
?>
									<input type="submit" value="����">
<?
	}
?>
									<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
									</form>								</td>
							</tr>
<?
}
?>
						</table>					</td>
				</tr>
<?
if ($edit_flag) {
?>
<?
}
?>
			</table>
			<br>
<?
if ($pro_enq_no == '') {
?>
<? /*			<input type="button" value="��¸�Υ��󥱡��Ȥ���ʣ��" onclick="location.href='pe_copy.php'">
*/ ?>
<?
}
if ($make_flag & 0x01) {
?>
			<input type="button" value="�ףţ�ư���ǧ" onclick="window.open('pe_remlenq3a.php?enquete_id=<?=$pro_enq->enquete_id?>&pro_enq_no=<?=$pro_enq_no?>', 'webtest')">
			<input type="button" value="�ףţ¥ץ�ӥ塼" onclick="location.href='pe_webprev.php?pro_enq_no=<?=$pro_enq_no?>'">
			<?
}
if (!$show) {
	if ($enquete->status == 0 && ($make_flag & 0x2f) == 0x2f) {
?>
			<input type="button" value="��������λ��" onclick="location.href='pe_status.php?pro_enq_no=<?=$pro_enq_no?>'">
<?
	}
	if ($enquete->status == 1) {
?>
			<input type="button" value="��������᤹" onclick="location.href='pe_status.php?pro_enq_no=<?=$pro_enq_no?>'">
			<input type="button" value="��������" onclick="location.href='pe_app1.php?pro_enq_no=<?=$pro_enq_no?>'">
<?
	}
	if ($enquete->status == 8) {
?>
			<input type="button" value="���ƿ�����" onclick="location.href='pe_app1.php?pro_enq_no=<?=$pro_enq_no?>'">
<?
	}
}
?>
			<input type="button" value="����롡" onclick="location.href='pe_index.php'">

		</td>
	</tr>
<tr><td height="20"><img src="images/common/spacer.gif" width="1" height="20"></td></tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
