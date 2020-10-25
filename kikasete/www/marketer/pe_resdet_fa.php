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

if ($enquete_id == $pro_enq->enquete_id)
	$enquete = &$pro_enq->enquete;
elseif ($enquete_id == $pro_enq->enquete2_id)
	$enquete = &$pro_enq->enquete2;
else
	redirect('pe_index.php');

$job_name = htmlspecialchars($enquete->title);
$enq_type = decode_enq_type($enquete->enq_type);

$question = $enquete->question[$question_no];
$qno = mb_convert_kana($question_no, 'N');
$sno = mb_convert_kana($sel_no, 'N');

// 利用ネットワークの取得	2006/02/27 BTI
$pro_enq_target = new pro_enq_target_class;
$pro_enq_target->read_db($enquete_id);
$target_flg = $pro_enq_target->target_flg;

?>
<? marketer_header('集計結果表示', PG_NULL) ?>
<style type="text/css">
<!--
.style1 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>

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
		<td width="400" valign="top">≪集計結果表示≫</td>
	</tr>
</table>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td rowspan="2" width="20"><img src="images/common/spacer.gif" width="20" height="1" border="0"></td>
		<td width="768"><img src="images/common/spacer.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="99%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right">

						<a href="javascript:window.close()">ウィンドウを閉じる</a>
					</td>
				</tr>
			</table>
			<br><br>
			<table width="80%" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">ID</td>
					<td width="75%"><?=$enquete_id?></td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee">アンケートタイトル</td>
					<td width="75%"><?=$job_name?>についてのアンケート</td>
				</tr>
			</table>
			<br><br>
			<table border="1" width="80%" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr>
					<td align="center" colspan=5>
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								Ｑ<?=$qno?>(設問文)　：　								</td>
								<td width="550">
								<?=nl2br(htmlspecialchars($question->question_text))?>								</td>
							</tr>
<?
if ($sel_no != 0) {
?>
							<tr>
								<td width="100" align="right" valign="top">
								(選択肢)　：　								</td>
								<td width="550">
								<?=$sno?>．<?=htmlspecialchars($question->sel_text[$sel_no])?>								</td>
							</tr>
<?
}
?>
						</table>					</td>
				</tr>
				<tr>
					<td width="6%" align="center" bgcolor="#0000CC"><span class="style1"><nobr>SEQ</nobr></span></td>
                    <?
switch ($type) {
case 'age':
?>
					<td width="6%" align="center" bgcolor="#0000CC"><span class="style1"><nobr>年代</nobr></span></td>
<?
	break;
case 'sex':
?>
					<td width="6%" align="center" bgcolor="#0000CC"><span class="style1"><nobr>性別</nobr></span></td>
<?
	break;
case 'age_sex':
?>
					<td width="8%" align="center" bgcolor="#0000CC"><span class="style1"><nobr>年代</nobr></span></td>
					<td width="6%" align="center" bgcolor="#0000CC"><span class="style1"><nobr>性別</nobr></span></td>
<?
	break;
case 'sex_age':
?>
					<td width="6%" align="center" bgcolor="#0000CC"><span class="style1"><nobr>性別</nobr></span></td>
					<td width="6%" align="center" bgcolor="#0000CC"><span class="style1"><nobr>年代</nobr></span></td>
<?
	break;
}

if ($sel_qno != 0) {
	$add_select = ',as2.as_sel_no';
	$add_join = " LEFT JOIN t_ans_select as2 ON as2.as_enquete_id=an_enquete_id AND as2.as_monitor_id=an_monitor_id AND as2.as_question_no=$sel_qno";
?>
					<td width="6%" align="center" bgcolor="#0000CC"><span class="style1"><nobr>Ｑ
		            <?=$sel_qno?>
					</nobr></span></td>
<?
}
?>
					<td align="center" bgcolor="#0000CC"><span class="style1"><nobr>回答</nobr></span></td>
				</tr>
<?
// ソート順
$order = array();
if ($sel_qno != 0) {
	switch ($sort) {
	case 1:		// 回答順
		$order[] = 'an_date';
		break;
	case 2:		// 昇順
		$order[] = 'as2.as_sel_no';
		break;
	case 3:		// 降順
		$order[] = '-as2.as_sel_no';
		break;
	}
}

// 文字数でソート
if ($num_sort) {
	if ($question->question_type == 3 || $question->question_type == 8)
		$order[] = 'char_length(as1.as_free_answer) DESC';
	elseif ($question->question_type == 6)
		$order[] = 'cast(as1.as_free_answer as integer)';
}
$order[] = 'an_date';
$order = join(',', array_unique($order));

if ($target_flg == 't') {
	switch ($type) {
	case 'age':
		$sql = "SELECT ge_age_text,as1.as_free_answer,an_date" . $add_select
				. " FROM t_enquete_list"
				. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
				. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
				. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to";
		break;
	case 'sex':
		$sql = "SELECT mn_sex,as1.as_free_answer,an_date" . $add_select
				. " FROM t_enquete_list"
				. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
				. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id";
		break;
	case 'age_sex':
		$sql = "SELECT mn_sex,ae_age_text,as1.as_free_answer,an_date" . $add_select
				. " FROM t_enquete_list"
				. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
				. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
				. " JOIN m_age ON mn_age BETWEEN ae_age_from AND ae_age_to";
		break;
	case 'sex_age':
		$sql = "SELECT mn_sex,ge_age_text,as1.as_free_answer,an_date" . $add_select
				. " FROM t_enquete_list"
				. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
				. " JOIN t_enq_monitor ON mn_enquete_id=el_enquete_id AND mn_monitor_id=el_monitor_id"
				. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to";
		break;
	}
	$sql .= " LEFT JOIN t_ans_select as1 ON as1.as_enquete_id=an_enquete_id AND as1.as_monitor_id=an_monitor_id AND as1.as_question_no=$question_no AND as1.as_sel_no=$sel_no"
				. $add_join
				. " WHERE el_enquete_id=$enquete_id AND an_valid_flag"
				. " ORDER BY $order";
} else {
	switch ($type) {
	case 'age':
		$sql = "SELECT ge_age_text,as1.as_free_answer,an_date" . $add_select
				. " FROM t_answer"
				. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
				. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to";
		break;
	case 'sex':
		$sql = "SELECT mn_sex,as1.as_free_answer,an_date" . $add_select
				. " FROM t_answer"
				. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id";
		break;
	case 'age_sex':
		$sql = "SELECT mn_sex,ae_age_text,as1.as_free_answer,an_date" . $add_select
				. " FROM t_answer"
				. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
				. " JOIN m_age ON mn_age BETWEEN ae_age_from AND ae_age_to";
		break;
	case 'sex_age':
		$sql = "SELECT mn_sex,ge_age_text,as1.as_free_answer,an_date" . $add_select
				. " FROM t_answer"
				. " JOIN {$target_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
				. " JOIN m_age10 ON mn_age BETWEEN ge_age_from AND ge_age_to";
		break;
	}
	$sql .= " LEFT JOIN t_ans_select as1 ON as1.as_enquete_id=an_enquete_id AND as1.as_monitor_id=an_monitor_id AND as1.as_question_no=$question_no AND as1.as_sel_no=$sel_no"
				. $add_join
				. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
				. " ORDER BY $order";
}

$result = db_exec($sql);
$nrow = pg_numrows($result);

// 回答受付連番
$ary = array();
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$ary[$i] = $fetch->an_date;
}
asort($ary);
$i = 0;
$num_ary = array();
foreach ($ary as $key => $val)
	$num_ary[$key] = ++$i;

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($fetch->as_free_answer != '') {
?>
			<tr>
				<td align="center"><?=$num_ary[$i]?></td>
<?
		switch ($type) {
		case 'age':
?>
				<td align="center"><?=$fetch->ge_age_text?></td>
<?
			break;
		case 'sex':
?>
				<td align="center"><?=decode_sex($fetch->mn_sex)?></td>
<?
			break;
		case 'age_sex':
?>
				<td align="center"><?=$fetch->ae_age_text?></td>
				<td align="center"><?=decode_sex($fetch->mn_sex)?></td>
<?
			break;
		case 'sex_age':
?>
				<td align="center"><?=decode_sex($fetch->mn_sex)?></td>
				<td align="center"><?=$fetch->ge_age_text?></td>
<?
			break;
		}

		if ($sel_qno != 0) {
?>
				<td align="center"><?=$fetch->as_sel_no ? $fetch->as_sel_no : '-'?></td>
<?
		}
?>
				<td><?=nl2br(htmlspecialchars(trim($fetch->as_free_answer)))?></td>
			</tr>
<?
	}
}
?>
			</table>
			<br><br>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
