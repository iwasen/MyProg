<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/enquete.php");
include("$inc/format.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");
include("$inc/mye_check.php");
?>
<? staff_header('集計結果表示') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="8" border="0"></td>
	</tr>
</table>
<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="400" valign="top"><font size="3" class="honbun1">≪集計結果表示≫</font></td>
	</tr>
</table>
<table width="700" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td rowspan="2" width="20"><img src="images/shim.gif" width="20" height="1" border="0"></td>
		<td width="680"><img src="images/shim.gif" width="1" height="20" border="0"></td>
	</tr>
	<tr>
		<td>
			<table width="580" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right">
						<font size="3" class="honbun1">
						<a href="javascript:window.close()">ウィンドウを閉じる</a></font>
					</td>
				</tr>
			</table>
			<br><br>
<?
if ($enquete_id == '')
	exit;

$enquete = new enquete_class;
$enquete->read_db($enquete_id);
$job_name = htmlspecialchars($enquete->title);
$enq_type = decode_enq_type($enquete->enq_type);
?>
			<table width="600" border="1" cellspacing="0" cellpadding="5" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee"><font size="3" class="honbun1">ジョブCD　：</font></td>
					<td width="75%"><font size="3" class="honbun1"><?=$enquete_id?></font></td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee"><font size="3" class="honbun1">ジョブ名　：</font></td>
					<td width="75%"><font size="3" class="honbun1"><?=$job_name?></font></td>
				</tr>
				<tr>
					<td width="25%" align="right" bgcolor="#eeeeee"><font size="3" class="honbun1">種類　：</font></td>
					<td width="75%"><font size="3" class="honbun1"><?=$enq_type?></font></td>
				</tr>
			</table>
<?
$question = $enquete->question[$question_no];
$qno = mb_convert_kana($question_no, 'N');
$sno = mb_convert_kana($sel_no, 'N');
?>
			<br><br>
			<table border="1" cellspacing="0" cellpadding="0" bordercolor="#c0c0c0" bordercolorlight="#c0c0c0" bordercolordark="#c0c0c0" frame="box">
				<tr>
					<td align="center" colspan=4>
						<table>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2" class="honbun2">Ｑ<?=$qno?>(設問文)　：　</font>
								</td>
								<td width="550">
								<font size="2" class="honbun2"><?=nl2br(htmlspecialchars($question->question_text))?></font>
								</td>
							</tr>
<?
if ($sel_no != 0) {
?>
							<tr>
								<td width="100" align="right" valign="top">
								<font size="2" class="honbun2">(選択肢)　：　</font>
								</td>
								<td width="550">
								<font size="2" class="honbun2"><?=$sno?>．<?=htmlspecialchars($question->sel_text[$sel_no])?></font>
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
				<tr>
					<th width="6%"><font size="2" class="honbun2"><nobr>回答受<wbr>付連番</nobr></font></th>
<?
switch ($type) {
case 'age':
?>
					<th width="6%"><font size="2" class="honbun2"><nobr>年代</nobr></font></th>
					<th><font size="2" class="honbun2"><nobr>回答</nobr></font></th>
<?
	break;
case 'sex':
?>
					<th width="6%"><font size="2" class="honbun2"><nobr>性別</nobr></font></th>
					<th><font size="2" class="honbun2"><nobr>回答</nobr></font></th>
<?
	break;
case 'age_sex':
?>
					<th width="8%"><font size="2" class="honbun2"><nobr>年代</nobr></font></th>
					<th width="6%"><font size="2" class="honbun2"><nobr>性別</nobr></font></th>
					<th><font size="2" class="honbun2"><nobr>回答</nobr></font></th>
<?
	break;
case 'sex_age':
?>
					<th width="6%"><font size="2" class="honbun2"><nobr>性別</nobr></font></th>
					<th width="6%"><font size="2" class="honbun2"><nobr>年代</nobr></font></th>
					<th><font size="2" class="honbun2"><nobr>回答</nobr></font></th>
<?
	break;
}
?>
				</tr>
<?
// アンケート開始日時取得
$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
$start_date = "'" . format_date(db_fetch1($sql)) . "'";

switch ($type) {
case 'age':
	$sql = "SELECT ge_age_text,as_free_answer"
			. " FROM t_enquete_list"
			. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
			. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
			. " JOIN m_age10 ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
			. " LEFT JOIN t_ans_select ON as_enquete_id=an_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=$question_no AND as_sel_no=$sel_no"
			. " WHERE el_enquete_id=$enquete_id"
			. " ORDER BY an_date";
	break;
case 'sex':
	$sql = "SELECT mn_sex,as_free_answer"
			. " FROM t_enquete_list"
			. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
			. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
			. " LEFT JOIN t_ans_select ON as_enquete_id=an_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=$question_no AND as_sel_no=$sel_no"
			. " WHERE el_enquete_id=$enquete_id"
			. " ORDER BY an_date";
	break;
case 'age_sex':
	$sql = "SELECT mn_sex,ae_age_text,as_free_answer"
			. " FROM t_enquete_list"
			. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
			. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
			. " JOIN m_age ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to"
			. " LEFT JOIN t_ans_select ON as_enquete_id=an_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=$question_no AND as_sel_no=$sel_no"
			. " WHERE el_enquete_id=$enquete_id"
			. " ORDER BY an_date";
	break;
case 'sex_age':
	$sql = "SELECT mn_sex,ge_age_text,as_free_answer"
			. " FROM t_enquete_list"
			. " JOIN t_answer ON an_enquete_id=el_enquete_id AND an_monitor_id=el_monitor_id"
			. " JOIN t_monitor ON mn_monitor_id=an_monitor_id"
			. " JOIN m_age10 ON DATE_PART('Y',age($start_date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to"
			. " LEFT JOIN t_ans_select ON as_enquete_id=an_enquete_id AND as_monitor_id=an_monitor_id AND as_question_no=$question_no AND as_sel_no=$sel_no"
			. " WHERE el_enquete_id=$enquete_id"
			. " ORDER BY an_date";
	break;
}
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($fetch->as_free_answer != '') {
?>
			<tr>
				<td align="center"><font size="2" class="honbun2"><?=$i + 1?></font></td>
<?
switch ($type) {
case 'age':
?>
				<td align="center"><font size="2" class="honbun2"><?=$fetch->ge_age_text?></font></td>
<?
	break;
case 'sex':
?>
				<td align="center"><font size="2" class="honbun2"><?=decode_sex($fetch->mn_sex)?></font></td>
<?
	break;
case 'age_sex':
?>
				<td align="center"><font size="2" class="honbun2"><?=$fetch->ae_age_text?></font></td>
				<td align="center"><font size="2" class="honbun2"><?=decode_sex($fetch->mn_sex)?></font></td>
<?
	break;
case 'sex_age':
?>
				<td align="center"><font size="2" class="honbun2"><?=decode_sex($fetch->mn_sex)?></font></td>
				<td align="center"><font size="2" class="honbun2"><?=$fetch->ge_age_text?></font></td>
<?
	break;
}
?>
				<td><font size="2" class="honbun2"><?=nl2br(htmlspecialchars(trim($fetch->as_free_answer)))?></font></td>
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

<? staff_footer() ?>
