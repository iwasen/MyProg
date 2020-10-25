<?
$top = './..';
$inc = "$top/../inc";
include("$inc/enquete.php");
include("$inc/enq_graph.php");
include("$inc/point.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");
include("$inc/mn_chara.php");
include("$inc/mn_error.php");

// ���󥱡��Ȳ��������͹���
function update_enq_sum($enquete_id, $question_no, $sel_no, $sex, $mikikon) {
	$kind = '1';

	if ($sex == 1)
		$kind .= ',2';
	else
		$kind .= ',3';

	if ($mikikon != 2)
		$kind .= ',4';
	else
		$kind .= ',5';

	$sql = "UPDATE t_enquete_sum SET em_sum=em_sum+1 WHERE em_enquete_id=$enquete_id AND em_question_no=$question_no AND em_sum_kind IN ($kind) AND em_sel_no=$sel_no";
	db_exec($sql);
}

$monitor_id = $_SESSION['ss_monitor_id'];

// ���󥱡���ID¸�ߥ����å�
if ($enquete_id == '')
	$msg = '���󥱡��Ȥ����򤵤�Ƥ��ʤ��褦�Ǥ���';

// ���󥱡���ͭ���������å�
if (!$msg) {
	$sql = "SELECT fe_seq_no,en_title,en_description,en_point"
			. " FROM t_free_enquete JOIN t_enquete ON en_enquete_id=fe_enquete_id AND en_enq_kind=1"
			." WHERE en_enquete_id=$enquete_id AND en_status=5 AND en_enq_type=2";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$seq_no = $fetch->fe_seq_no;
		$title = htmlspecialchars($fetch->en_title);
		$description = nl2br(htmlspecialchars($fetch->en_description));
		$point = $fetch->en_point;
	} else
		$msg = '���򤵤줿���󥱡��Ȥ�ͭ���ǤϤ���ޤ���';
}

if (!$retry) {
	// �����Ѥߥ����å�
	if (!$msg) {
		$sql = "SELECT an_enquete_id FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=$monitor_id";
		$result = db_exec($sql);
		if (pg_numrows($result))
			$msg = '���ʤ��ϴ��ˤ��Υ��󥱡��Ȥ˲�������Ƥ���褦�Ǥ���';
	}

	// ���̡�̤��������
	$sql = "SELECT mn_sex,mn_mikikon FROM t_monitor WHERE mn_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$sex = $fetch->mn_sex;
		$mikikon = $fetch->mn_mikikon;
	}

	// ����������¸
	if (!$msg) {
		$enquete = new enquete_class;
		$enquete->read_db($enquete_id);

		db_begin_trans();

		$sql = "INSERT INTO t_answer (an_enquete_id,an_monitor_id) VALUES ($enquete_id,$monitor_id)";
		db_exec($sql);

		foreach ($enquete->question as $qno => $question) {
			switch ($question->question_type) {
			case 1:		// ñ�������
				$answer = $_POST["answer$qno"];
				if ($answer != '') {
					$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id)"
							. " VALUES ($enquete_id,$qno,$answer,$monitor_id)";
					db_exec($sql);
					update_enq_sum($enquete_id, $qno, $answer, $sex, $mikikon);
					$answer_flag = true;
				}
				break;
			case 2:		// ʣ�������
				if (is_array($_POST["answer$qno"])) {
					foreach ($_POST["answer$qno"] as $answer) {
						$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id)"
								. " VALUES ($enquete_id,$qno,$answer,$monitor_id)";
						db_exec($sql);
						update_enq_sum($enquete_id, $qno, $answer, $sex, $mikikon);
						$answer_flag = true;
					}
				}
				break;
			}

			// �����̲����Կ��������
			update_enq_sum($enquete_id, $qno, 0, $sex, $mikikon);
		}

		if ($answer_flag) {
			// �����󥱡��Ȳ����Կ�
			$sql = "UPDATE t_enquete_sum SET em_sum=em_sum+1 WHERE em_enquete_id=$enquete_id AND em_question_no=0 AND em_sum_kind=0 AND em_sel_no=0";
			db_exec($sql);

			monitor_point($monitor_id, PT_ENQUETE, $point, null, null, $enquete_id);
			db_commit_trans();

			$_SESSION['ss_chara_image'] = get_chara_image($monitor_id);
		} else {
			db_rollback();
			$msg = '���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		}
	}
}
?>
<? monitor_menu() ?>

<?
if (!$msg) {
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" background="<?=$img?>/title_bg.gif"><img src="<?=$img?>/title_enq.gif" width="320" height="39" alt="�����Τ������ƣ����ͥ��󥱡���"></td>
	</tr>
	<TR>
		<TD background="<?=$img?>/title_catch_03.gif"><IMG SRC="<?=$img?>/spacer.gif" WIDTH=1 HEIGHT=2></TD>
	</TR>
</TABLE>
<table width="660" border="0" cellspacing="0" cellpadding="0" align="left">
	<tr>
		<td align="center">
			<table width="620" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="<?=$img?>/spacer.gif" width="1" height="15" border="0"></td>
				</tr>
			</table>
<?
$sql = "SELECT eq_question_no,eq_question_text FROM t_enq_question WHERE eq_enquete_id=$enquete_id ORDER BY eq_question_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$question_no = $fetch->eq_question_no;
?>
	<table width="600" border="0" cellspacing="0" cellpadding="5">
<?
	if ($fetch->eq_question_text != '') {
?>
		<TR>
<? /* 07/04/23 wakasa HTML������ǽ�ɲ� */ ?>
<td align="left" colspan="3"><font size="3" class=fs12_lh130><strong>��<?=$question_no?>��<?=nl2br($fetch->eq_question_text)?></strong></font></td>
		</TR>
		<TR>
			<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
			<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			<TD class=graphtop><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
		</TR>
		<TR>
<?
	}
	$scale = 380;
	$html = '<TD width=10>��</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph.gif" width=%WIDTH%> %COUNT%</td>';
	enq_graph_all($enquete_id, $question_no, $scale, $html, true);
?>
		</TR>
		<TR>
			<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=50></TD>
			<TD><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
			<TD class=graphbottom><IMG height=1 src="<?=$img?>/spacer.gif" width=1></TD>
		</TR>
	</table>
<?
}
?>
<br>
<font size="2"><br>�������������������꤬�Ȥ��������ޤ��������ߤ�����в�ϡ��嵭�ΤȤ���Ǥ���<br><br></font>
<form method="post" action="m_enqresult2.php">
<input type="hidden" name="seq_no" value="<?=$seq_no?>">
<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
	<table border="0" cellpadding="5" cellspacing="0" width="600">
		<tr>
			<td bgcolor="#2b2b63"><font color="white"><strong>���ʤ��Υ��ԥ����ɤ��֤������ơ����� �̿��פ˷Ǻܤ���뤫�⡪</strong></font></td>
		</tr>
		<tr>
			<td bgcolor="#f8f8f8"><font size="2">�������ơ����� ��̳�ɤǤϡ�</font><font color="#2b2b63" size="2"><strong>�֤������ơ������ͥ��󥱡��ȡ�</strong></font><font size="2">�˴ؤ��뤢�ʤ��Υ��ԥ����ɤ��罸���Ƥ��ޤ������ʤ��Ρ֥ϥ�ɥ�͡���ס����̡ס�ǯ��ס��㡧�֤դ����סֽ����ס�20��פʤɡˤ򤴵����ξ塢�᡼������꤯��������<br>�����ꤤ�����������ԥ����ɤϡ��轵��˥����Τߤʤ��ޤˤ��Ϥ�����֤������ơ����� �̿��פ���Ǽ��夲�ޤ���<br>���Ҥ��ҡ����ʤ��ΰ���򤪴󤻤��������͡� </font></td>
		</tr>
		<tr>
			<td bgcolor="white"><font size="2" color="black"><br>���ʤߤ˺���Υơ��ޤϤ���ʤΤǤ����袭<br></font></td>
		</tr>
	</table>
	<table width="600" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td bgcolor="#DDDDDD">
				<table width="100%" border="0" cellspacing="1" cellpadding="10">
					<tr>
						<td align="left" bgcolor="#FFFFFF"><font size="3" class=fs12_lh130><strong><?=$title?></strong><BR><BR><?=$description?></font></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<font size="2">���ʤ��Υ��ԥ����ɤϤ����餫�颭<br></font>
	<font size="2"><textarea name="episode" cols="55" rows="8"><?=htmlspecialchars($episode)?></textarea><P>	<font size="2">��������ˡ֥ϥ�ɥ�͡��ࡿ���̡�ǯ��פ�����Ƥ���������<br></font>	<font size="2" color="red">���������ɬ�ܤˤʤ�ޤ���</font>
	<font size="2" color="red">��<br>
	</font>
	<font size="2"><input type="text" name="handle_name" size="78" <?=value($handle_name)?>><br>
	<center>
	<input type="submit" value="�����������롡">
	<input type="reset" value="�����ꥢ��"><br>
	<br>
	<br>
</form>
		</td>
	</tr>
</table>
<?
} else
	error_msg($msg);
?>
