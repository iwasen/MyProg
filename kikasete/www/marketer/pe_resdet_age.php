<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/format.php");
include("$inc/pro_enquete.php");
include("$inc/pro_enq_target.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

if ($pro_enq_no == '')
	redirect('pe_index.php');

$owner_id = $_SESSION['ss_owner_id'];
$pro_enq = new pro_enquete_class;
$pro_enq->read_db($owner_id, $pro_enq_no);
?>
<? marketer_header('���׷��ɽ��', PG_NULL) ?>

<script type="text/javascript">
<!--
function open_fa(fn, sort) {
	var f = document.forms[fn];
	f.sort.value = sort;
	f.submit();
}
function check_fa(enquete_id, qno, flag) {
	document.getElementById("fa1_" + enquete_id + "_" + qno).style.display = flag ? "none" : "";
	document.getElementById("fa2_" + enquete_id + "_" + qno).style.display = flag ? "" : "none";
}
//-->
</script>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="400" valign="top">�㽸�׷��ɽ����</td>
		<td align="right"><input name="button" type="button" onclick="history.back()" value="����롡" />
		  <img src="images/common/spacer.gif" width="20" height="1" border="0"> </td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">ID</td>
					<td width="75%"><?=$pro_enq->enquete_id?></td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">���󥱡��ȥ����ȥ�</td>
					<td width="75%"><?=htmlspecialchars($pro_enq->enquete->title)?>�ˤĤ��ƤΥ��󥱡���</td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">���׹���</td>
					<td width="75%">ǯ��</td>
				</tr>
			</table>
<?
if ($pro_enq->finding_flag == DBTRUE) {
	if (is_array($pro_enq->enquete->question))
		disp_enquete($pro_enq->enquete_id, $pro_enq->enquete, '��ե�����ǥ��󥰷�̡�', '�ƣ�');
}
if (is_array($pro_enq->enquete2->question))
	disp_enquete($pro_enq->enquete2_id, $pro_enq->enquete2, '����Ĵ����̡�', '');

function disp_enquete($enquete_id, &$enquete, $title, $fd) {
	global $pro_enq_no, $pro_enq;
?>
			<br><b><?=$title?></b>
<?
	// �оݼԤ�ǯ��
	$search = &$pro_enq->search;
	$age_cd = $search->age_cd;
	$age_from = $search->age_from;
	$age_to = $search->age_to;

	if ($age_cd) {
		// �����й�ߤ��Ѵ�
		foreach (explode(',', $age_cd) as $age_cd)
			$age_ary[] = (int)($age_cd / 2) + 1;
		$age_ary = array_unique($age_ary);
	} else {
		if (!$age_from)
			$age_from = 0;
		if (!$age_to)
			$age_to = 999;
		$sql = "SELECT ge_age_cd FROM m_age10 WHERE ge_age_to>=$age_from AND ge_age_from<=$age_to ORDER BY ge_age_cd";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$age_ary[] = $fetch->ge_age_cd;
		}
	}

	// ǯ��̾�μ���
	$sql = "SELECT ge_age_cd,ge_age_text FROM m_age10";
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);
		$age_text_ary[$fetch->ge_age_cd] = str_replace('��', '��<br>', $fetch->ge_age_text);
	}

	// ���ѥͥåȥ���μ���	2006/02/23 BTI
	$pro_enq_target = new pro_enq_target_class;
	$pro_enq_target->read_db($enquete_id);
	$target_flg = $pro_enq_target->target_flg;

	foreach ($enquete->question as $question_no => $question) {
		$qno = mb_convert_kana($question_no, 'N');

		if ($question->question_type == 4 || $question->question_type == 5) {
			foreach ($question->hyousoku as $sno => $hyousoku) {
				$sno2 = mb_convert_kana($sno, 'N');
?>
			<br>
			<table width="99%" border="1" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td colspan="<?=MAX_PRO_SEL + 2?>" width="670" align="center">
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								<?=$fd?>��<?=$qno?>(����ʸ)������
								</td>
								<td width="550">
								<?=nl2br(htmlspecialchars($question->question_text))?>
								</td>
							</tr>
							<tr>
								<td width="100" align="right" valign="top">
								(ɽ¦)������
								</td>
								<td width="550">
								<?=$sno2?>��<?=htmlspecialchars($hyousoku)?>
								</td>
							</tr>
<?
				if (is_array($question->hyoutou)) {
					foreach ($question->hyoutou as $tno => $hyoutou) {
						$tno2 = mb_convert_kana($tno, 'N');
?>
							<tr>
<?
						if ($tno == 1) {
?>
								<td width="100" align="right" rowspan="<?=count($question->hyoutou)?>" valign="top">
								(ɽƬ)������
								</td>
<?
						}
?>
								<td width="550">
								<?=$tno2?>��<?=htmlspecialchars($hyoutou)?>
								</td>
							</tr>
<?
					}
				}
?>
						</table>
					</td>
				</tr>
				<tr>
					<td height=100 align="center">
						<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
							<tr><td align="right" height=30>100%</td></tr>
							<tr><td align="right" height=30>75%</td></tr>
							<tr><td align="right" height=30>50%</td></tr>
							<tr><td align="right" height=30>25%</td></tr>
							<tr><td align="right" height=15></td></tr>
						</table>
					</td>
<?
				// �����Կ�
				$sql = "SELECT COUNT(DISTINCT ax_monitor_id)"
						. " FROM t_ans_matrix"
						. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
						. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no";
				$member_cnt = db_fetch1($sql);

				// ���ν���
				$sql = "SELECT ax_hyoutou_no,COUNT(*) AS cnt"
						. " FROM t_ans_matrix"
						. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
						. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
						. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
						. " GROUP BY ax_hyoutou_no";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[(int)$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				for ($tno = 1; $tno <= MAX_PRO_SEL; $tno++) {
?>
					<td width="40" align="center" valign="bottom">
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
<?
					if ($question->hyoutou[$tno] && $cnt[$tno]) {
?>
								<td width=13 height=<?=floor($cnt[$tno] / $member_cnt * 120)?> bgcolor="#3366CC"></td>
<?
					} else
						echo "<td><br></td>\n";
?>
							</tr>
						</table>
					</td>
<?
				}
?>
					<td width="40" height=100 align="center"><br></td>
				</tr>
				<tr>
					<td align="center" bgcolor="#eeeeee">
					��<?=$qno?></td>
					<td width="40" align="center">
<?
				for ($tno = 1; $tno <= MAX_PRO_SEL; $tno++) {
?>
					<?=$tno < 10 ? mb_convert_kana($tno, 'N') : $tno?></td>
					<td width="40" align="center">
<?
				}
?>
					��</td>
				</tr>
				<tr>
					<td align="center">����</td>
<?
				for ($tno = 1; $tno <= MAX_PRO_SEL; $tno++) {
					if ($question->hyoutou[$tno]) {
?>
					<td width="40" align="center"><?=number_format($cnt[$tno])?><br><?=format_percent($cnt[$tno], $member_cnt)?></td>
<?
					} else {
?>
					<td><br></td>
<?
					}
				}
?>
					<td width="40" align="center"><?=number_format($member_cnt)?><br>100%</td>
				</tr>
<?
				// °���̽���
				if ($target_flg == 't') {
					$sql = "SELECT ax_hyoutou_no,ge_age_cd,COUNT(*) AS cnt"
							. " FROM t_ans_matrix"
							. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
							. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
							. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
							. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
							. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
							. " GROUP BY ge_age_cd,ax_hyoutou_no";
				} else {
					$sql = "SELECT ax_hyoutou_no,ge_age_cd,COUNT(*) AS cnt"
							. " FROM t_ans_matrix"
							. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
							. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
							. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
							. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
							. " GROUP BY ge_age_cd,ax_hyoutou_no";
				}
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[$fetch->ge_age_cd][$fetch->ax_hyoutou_no] = $fetch->cnt;
				}

				// °���̲����Կ�
				if ($target_flg == 't') {
					$sql = "SELECT ge_age_cd,COUNT(DISTINCT ax_monitor_id) AS cnt"
							. " FROM t_ans_matrix"
							. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
							. " JOIN t_enquete_list ON el_enquete_id=ax_enquete_id AND el_monitor_id=ax_monitor_id"
							. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
							. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
							. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
							. " GROUP BY ge_age_cd";
				} else {
					$sql = "SELECT ge_age_cd,COUNT(DISTINCT ax_monitor_id) AS cnt"
							. " FROM t_ans_matrix"
							. " JOIN t_answer ON an_enquete_id=ax_enquete_id AND an_monitor_id=ax_monitor_id AND an_valid_flag"
							. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
							. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
							. " WHERE ax_enquete_id=$enquete_id AND ax_question_no=$question_no AND ax_hyousoku_no=$sno"
							. " GROUP BY ge_age_cd";
				}
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$num = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$num[$fetch->ge_age_cd] = $fetch->cnt;
				}

				foreach ($age_ary as $age) {
?>
				<tr>
					<td align="center"><nobr><?=$age_text_ary[$age]?></nobr></td>
<?
					for ($tno = 1; $tno <= MAX_PRO_SEL; $tno++) {
						if ($question->hyoutou[$tno]) {
?>
					<td width="40" align="center"><?=number_format($cnt[$age][$tno])?><br><?=format_percent($cnt[$age][$tno], $num[$age])?></td>
<?
						} else {
?>
					<td><br></td>
<?
						}
					}
?>
					<td width="40" align="center"><?=number_format($num[$age])?><br>100%</td>
				</tr>
<?
				}
?>
			</table>
			<br>
<?
			}
		} else {
?>
			<br>
			<table width="99%" border="1" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0">
				<tr>
					<td colspan="<?=MAX_PRO_SEL + 2?>" width="670" align="center">
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								<?=$fd?>��<?=$qno?>(����ʸ)������
								</td>
								<td width="550">
								<?=nl2br(htmlspecialchars($question->question_text))?>
								</td>
							</tr>
<?
			if (is_array($question->sel_text) && $question->question_type != 3 && $question->question_type != 6 && $question->question_type != 8) {
				$tmp_ary = array_keys($question->sel_text);
				foreach ($question->sel_text as $sel_no => $sel_text) {
					$sno = mb_convert_kana($sel_no, 'N');
?>
							<tr>
<?
					if ($sel_no == 1) {
?>
								<td width="100" align="right" rowspan="<?=MAX_PRO_SEL?>" valign="top">
								(�����)������
								</td>
<?
					}
?>
								<td width="550">
<?
					if ($question->fa_flag == 't' && $sel_no == $question->fa_sno) {
?>
								<a href="pe_resdet_fa.php?pro_enq_no=<?=$pro_enq_no?>&enquete_id=<?=$enquete_id?>&question_no=<?=$question_no?>&sel_no=<?=$sel_no?>&type=age" target="_blank" title="������������ɥ�������������ɽ�����ޤ�"><?=$sno?>��<?=htmlspecialchars($sel_text)?></a>
<?
					} else {
?>
								<?=$sno?>��<?=htmlspecialchars($sel_text)?>
<?
					}
?>
								</td>
							</tr>
<?
				}
			} elseif ($question->question_type == 3 || $question->question_type == 6 || $question->question_type == 8) {
				$form_name = "form_{$enquete_id}_{$question_no}";
?>
							<tr>
								<td width="100"></td>
								<td>
<!-- option start -->
									<form name="<?=$form_name?>" action="pe_resdet_fa.php" target="_blank">
<div align="center">
<table border="0" cellspacing="0" cellpadding="1" bgcolor="#292864" width="550">
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td><img src="images/myenq_op_t.gif" alt="ɽ�����ץ����" width="120" height="20" border="0"></td>
			<td align="right"><a href="myenq_op_help.php" target="_blank"><img src="images/myenq_op_help.gif" alt="���Υ��ץ����λȤ���" width="140" height="20" border="0"
											 onmouseover="this.src='images/myenq_op_help2.gif'"
											 onmouseout="this.src='images/myenq_op_help.gif'"></a></td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
		<table border="0" cellspacing="0" cellpadding="3" bgcolor="#ffffff" width="100%">
		<tr>
			<td width="5">&nbsp;</td>
			<td>

									����ɽ����������SA�Τߡˡ���
									<input type="radio" name="sel_qno" value="0" checked onclick="check_fa(<?=$enquete_id?>,<?=$question_no?>,false)">�ʤ�
<?
				foreach ($enquete->question as $qno2 => $question2) {
					if ($question2->question_type == 1 || $question2->question_type == 7) {
?>
									<input type="radio" name="sel_qno" <?=value($qno2)?> onclick="check_fa(<?=$enquete_id?>,<?=$question_no?>,true)">Q<?=$qno2?>
<?
					}
				}
?>
									<div align="center"><img src="images/dot_line_530.gif" alt="" width="530" height="1" border="0" vspace="5"></div>
									ɽ���硡��<span id="fa1_<?=$enquete_id?>_<?=$question_no?>"><a href="javascript:open_fa('<?=$form_name?>',1)" title="������������ɥ�������������������ɽ�����ޤ�">������</a></span><span id="fa2_<?=$enquete_id?>_<?=$question_no?>" style="display:none"><a href="javascript:open_fa('<?=$form_name?>',2)" title="������������ɥ������������򾺽��ɽ�����ޤ�">����</a>��<a href="javascript:open_fa('<?=$form_name?>',3)" title="������������ɥ�������������߽��ɽ�����ޤ�">�߽�</a></span>��
									<div align="center"><img src="images/dot_line_530.gif" alt="" width="530" height="1" border="0" vspace="5"></div>
									<input type="checkbox" name="num_sort" value="1"><?=$question->question_type == 6 ? '���̤ǥ����Ȥ���' : 'ʸ�����ǥ����Ȥ���'?>
									<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
									<input type="hidden" name="enquete_id" <?=value($enquete_id)?>>
									<input type="hidden" name="question_no" <?=value($question_no)?>>
									<input type="hidden" name="sel_no" value="0">
									<input type="hidden" name="type" value="age">
									<input type="hidden" name="sort">

			</td>
			<td width="5">&nbsp;</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</div>
									</form>
<!-- option end -->
								</td>
							</tr>
<?
			}
?>
						</table>
					</td>
				</tr>
<?
			if ($question->question_type != 3 && $question->question_type != 6 && $question->question_type != 8) {
?>
				<tr>
					<td height=100 align="center">
						<table border=0 cellspacing=0 cellpadding=0 width="100%" height="100%">
							<tr><td align="right" height=30>100%</td></tr>
							<tr><td align="right" height=30>75%</td></tr>
							<tr><td align="right" height=30>50%</td></tr>
							<tr><td align="right" height=30>25%</td></tr>
							<tr><td align="right" height=15></td></tr>
						</table>
					</td>
<?
				// �����Կ�
				$sql = "SELECT COUNT(DISTINCT as_monitor_id)"
						. " FROM t_ans_select"
						. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
						. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no";
				$member_cnt = db_fetch1($sql);

				// ���ν���
				$sql = "SELECT as_sel_no,COUNT(*) AS cnt"
						. " FROM t_ans_select"
						. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
						. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
						. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
						. " GROUP BY as_sel_no";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[$fetch->as_sel_no] = $fetch->cnt;
				}

				for ($sel_no = 1; $sel_no <= MAX_PRO_SEL; $sel_no++) {
?>
					<td width="40" align="center" valign="bottom">
						<table border=0 cellspacing=0 cellpadding=0>
							<tr>
<?
					if ($question->sel_text[$sel_no] && $cnt[$sel_no]) {
?>
								<td width=13 height=<?=floor($cnt[$sel_no] / $member_cnt * 120)?> bgcolor="#3366CC"></td>
<?
					} else
						echo "<td><br></td>\n";
?>
							</tr>
						</table>
					</td>
<?
				}
?>
					<td width="40" height=100 align="center"><br></td>
				</tr>
				<tr>
					<td align="center" bgcolor="#eeeeee">
					��<?=$qno?></td>
					<td width="40" align="center">
<?
				for ($sel_no = 1; $sel_no <= MAX_PRO_SEL; $sel_no++) {
?>
					<?=$sel_no < 10 ? mb_convert_kana($sel_no, 'N') : $sel_no?></td>
					<td width="40" align="center">
<?
			}
?>
					��</td>
				</tr>
				<tr>
					<td align="center">����</td>
	<?
				for ($sel_no = 1; $sel_no <= MAX_PRO_SEL; $sel_no++) {
					if ($question->sel_text[$sel_no]) {
?>
					<td width="40" align="center"><?=number_format($cnt[$sel_no])?><br><?=format_percent($cnt[$sel_no], $member_cnt)?></td>
<?
					} else {
?>
					<td><br></td>
<?
					}
				}
?>
					<td width="40" align="center"><?=number_format($member_cnt)?><br>100%</td>
				</tr>
<?
				// ���ѥͥåȥ���μ���	2006/02/23 BTI
				$pro_enq_target = new pro_enq_target_class;
				$pro_enq_target->read_db($enquete_id);
				$target_flg = $pro_enq_target->target_flg;

				// °���̽���
				if ($target_flg == 't') {
					$sql = "SELECT as_sel_no,ge_age_cd,COUNT(*) AS cnt"
							. " FROM t_ans_select"
							. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
							. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
							. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
							. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
							. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
							. " GROUP BY ge_age_cd,as_sel_no";
				} else {
					$sql = "SELECT as_sel_no,ge_age_cd,COUNT(*) AS cnt"
							. " FROM t_ans_select"
							. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
							. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
							. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
							. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
							. " GROUP BY ge_age_cd,as_sel_no";
				}
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$cnt = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$cnt[$fetch->ge_age_cd][$fetch->as_sel_no] = $fetch->cnt;
				}

				// °���̲����Կ�
				if ($target_flg == 't') {
					$sql = "SELECT ge_age_cd,COUNT(DISTINCT as_monitor_id) AS cnt"
							. " FROM t_ans_select"
							. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
							. " JOIN t_enquete_list ON el_enquete_id=as_enquete_id AND el_monitor_id=as_monitor_id"
							. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
							. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
							. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
							. " GROUP BY ge_age_cd";
				} else {
					$sql = "SELECT ge_age_cd,COUNT(DISTINCT as_monitor_id) AS cnt"
							. " FROM t_ans_select"
							. " JOIN t_answer ON an_enquete_id=as_enquete_id AND an_monitor_id=as_monitor_id AND an_valid_flag"
							. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
							. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to"
							. " WHERE as_enquete_id=$enquete_id AND as_question_no=$question_no"
							. " GROUP BY ge_age_cd";
				}
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				$num = array();
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$num[$fetch->ge_age_cd] = $fetch->cnt;
				}

				foreach ($age_ary as $age) {
?>
				<tr>
					<td align="center"><nobr><?=$age_text_ary[$age]?></nobr></td>
<?
					for ($sel_no = 1; $sel_no <= MAX_PRO_SEL; $sel_no++) {
						if ($question->sel_text[$sel_no]) {
?>
					<td width="40" align="center"><?=number_format($cnt[$age][$sel_no])?><br><?=format_percent($cnt[$age][$sel_no], $num[$age])?></td>
<?
						} else {
?>
					<td><br></td>
<?
						}
					}
?>
					<td width="40" align="center"><?=number_format($num[$age])?><br>100%</td>
				</tr>
<?
				}
			}
?>
			</table>
			<br>
<?
		}
	}
}
?>
			<br>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
