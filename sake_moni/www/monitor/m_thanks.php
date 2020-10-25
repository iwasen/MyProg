<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/point.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// ��˥���̾ɽ��
function disp_monitor_name() {
	$sql = "SELECT mn_name1 FROM t_monitor WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
	echo htmlspecialchars(db_fetch1($sql));
}

// ���ϥѥ�᡼������
$enquete_id = $_POST['enquete_id'];
$monitor_id = $_SESSION['ss_monitor_id'];

// ���󥱡���ID¸�ߥ����å�
if ($enquete_id == '')
	$msg[] = '���󥱡��Ȥ����򤵤�Ƥ��ʤ��褦�Ǥ���';

// ���󥱡���ͭ���������å�
if (!$msg) {
	$sql = "SELECT en_point FROM t_enquete WHERE en_enquete_id=$enquete_id AND en_status=5 AND en_enq_type=2";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$point = $fetch->en_point;
	} else
		$msg[] = '���򤵤줿���󥱡��Ȥ�ͭ���ǤϤ���ޤ���';
}

// �����оݼԥ����å�
if (!$msg) {
	$sql = "SELECT el_enquete_id FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0)
		$msg[] = '���ʤ��Ϥ��Υ��󥱡��Ȥ˻��ä��Ƥ��ޤ���';
}

// �����Ѥߥ����å�
if (!$msg) {
	$sql = "SELECT an_enquete_id FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_num_rows($result))
		$msg[] = '���ʤ��ϴ��ˤ��Υ��󥱡��Ȥ˲�������Ƥ���褦�Ǥ���';
}

// ���󥱡������Ƽ���
$enquete = new enquete_class;
$enquete->read_db($enquete_id);

// ���������å�
if (!$msg) {
	foreach ($enquete->question as $qno => $question) {
		switch ($question->question_type) {
		case 1:		// ñ�������
		case 2:		// ʣ�������
			if (!isset($_POST["answer$qno"]))
				$msg[] = "��{$qno}�β��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
			break;
		case 3:		// �ե꡼����
			if ($_POST["answer$qno"] == "")
				$msg[] = "��{$qno}�β��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
			break;
		case 4:		// �ޥȥꥯ��(SA)
		case 5:		// �ޥȥꥯ��(MA)
			if (is_array($_POST["answer$qno"])) {
				$sno = array();
				$tno = array();
				foreach ($_POST["answer$qno"] as $no) {
					$ary = explode(':', $no);
					$sno[] = $ary[0];
					$tno[] = $ary[1];
				}

				if (count(array_unique($sno)) < count($question->hyousoku))
					$msg[] = "��{$qno}�β��������Ϥ���Ƥ��ʤ��Ԥ�����褦�Ǥ���";

				if ($question->dup_flag == 't') {
					if (count($tno) != count(array_unique($tno)))
						$msg[] = "��{$qno}�β����˽�ʣ������褦�Ǥ���";
				}
			} else
				$msg[] = "��{$qno}�β��������Ϥ���Ƥ��ʤ��褦�Ǥ���";
		}
	}
}

// ����������¸
if (!$msg) {
	db_begin_trans();

	$sql = "INSERT INTO t_answer (an_enquete_id,an_monitor_id) VALUES ($enquete_id,$monitor_id)";
	db_exec($sql);

	foreach ($enquete->question as $qno => $question) {
		if (is_array($question->sel_text))
			$last_sno = end(array_keys($question->sel_text));

		switch ($question->question_type) {
		case 1:		// ñ�������
			$answer = $_POST["answer$qno"];
			if ($answer != '') {
				$free_text = ($answer == $last_sno) ? $_POST["free_answer$qno"] : '';
				$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id,as_free_answer)"
						. " VALUES ($enquete_id,$qno,$answer,$monitor_id," . sql_char($free_text) . ")";
				db_exec($sql);
			}
			break;
		case 2:		// ʣ�������
			if (is_array($_POST["answer$qno"])) {
				foreach ($_POST["answer$qno"] as $answer) {
					$free_text = ($answer == $last_sno) ? $_POST["free_answer$qno"] : '';
					$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id,as_free_answer)"
							. " VALUES ($enquete_id,$qno,$answer,$monitor_id," . sql_char($free_text) . ")";
					db_exec($sql);
				}
			}
			break;
		case 3:		// �ե꡼����
			$answer = $_POST["answer$qno"];
			if ($answer != '') {
				$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id,as_free_answer)"
						. " VALUES ($enquete_id,$qno,0,$monitor_id," . sql_char($answer) . ")";
				db_exec($sql);
			}
			break;
		case 4:		// �ޥȥꥯ��(SA)
		case 5:		// �ޥȥꥯ��(MA)
			if (is_array($_POST["answer$qno"])) {
				foreach ($_POST["answer$qno"] as $no) {
					$ary = explode(':', $no);
					$sql = "INSERT INTO t_ans_matrix (ax_enquete_id,ax_monitor_id,ax_question_no,ax_hyousoku_no,ax_hyoutou_no)"
							. " VALUES ($enquete_id,$monitor_id,$qno,{$ary[0]},{$ary[1]})";
					db_exec($sql);
				}
			}
			break;
		}
	}

	// �ݥ������Ϳ
	monitor_point($monitor_id, PT_ENQUETE, $point);

	// �����Կ�������ȥ��å�
	$sql = "UPDATE t_ans_count SET ac_ans_count=ac_ans_count+1 WHERE ac_enquete_id=$enquete_id";
	db_exec($sql);

	db_commit_trans();
}
?>
<HTML>
<HEAD>
<TITLE>�������ʪ�Υ��󥱡��ȥ�˥����å��󥱡��Ȥ��������</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Script-Type" content="text/JavaScript">
<link rel="stylesheet" href="style.css" type="text/css">
<script language="JavaScript" src="mouseover.js"></script>
</HEAD>
<body bgcolor=#DDDDDD text=#333333 link=#333333 vlink=#333333 alink=#333333 
leftmargin=0 topmargin=0 rightmargin=0 bottommargin=0 marginwidth=0 marginheight=0>

<TABLE WIDTH=760 BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
<TD bgcolor=#FFFFFF valign=top height=900>

	<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
	<TD background="images/monitor_title2.gif">

		<TABLE WIDTH=720 BORDER=0 CELLPADDING=0 CELLSPACING=0>
		<TR>
		<TD ROWSPAN=3>
			<IMG SRC="images/m_header_01.gif" WIDTH=618 HEIGHT=106></TD>
		<TD>
			<IMG SRC="images/m_header_02.gif" WIDTH=90 HEIGHT=75></TD>
		<TD>
			<IMG SRC="images/m_header_03.gif" WIDTH=12 HEIGHT=75></TD>
		</TR>
		<TR>
		<TD><a href="m_logout.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_logout','','images/b_logout_over.gif',1)"><IMG SRC="images/b_logout.gif" WIDTH=90 HEIGHT=24 name=b_logout BORDER=0 ALT="��������"></TD>
		<TD>
			<IMG SRC="images/m_header_05.gif" WIDTH=12 HEIGHT=24></TD>
		</TR>
		<TR>
		<TD>
			<IMG SRC="images/m_header_06.gif" WIDTH=90 HEIGHT=7></TD>
		<TD>
			<IMG SRC="images/m_header_07.gif" WIDTH=12 HEIGHT=7></TD>
		</TR>
		</TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#FFFFFF"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD bgcolor="#99CCFF" height=18>

		<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width=720>
		<TR>
		<TD WIDTH=25><IMG SRC="images/spacer.gif" WIDTH=25 HEIGHT=18></TD>
		<TD align="left"><span class="t10"><a href="m_index.php">���߼»���Υ��󥱡��Ȱ���</a>&nbsp;��&nbsp;���󥱡��Ȥ�������</span></TD>
		<TD align="right" WIDTH=400><span style="font-size:14px; font-weight:bold; color:#FF6600"><a href="m_change_add.php">��<img src="images/arrow2.gif" align="absmiddle" hspace="5" border="0" />�᡼�륢�ɥ쥹���ѹ���</a>&nbsp;<a href="m_change_pw.php">��<img src="images/arrow2.gif" align="absmiddle" hspace="5" border="0" />�ѥ�����ѹ���</a>&nbsp;</span></TD>
		</TR>
		</TABLE>

	</TD>
	</TR>
	<TR>
	<TD bgcolor="#666666"><IMG SRC="images/spacer.gif" WIDTH=1 HEIGHT=1></TD>
	</TR>
	<TR>
	<TD align=left >

<TABLE WIDTH=673 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD rowspan=2 valign="top"><IMG SRC="images/monitor02_01.gif" WIDTH=88 HEIGHT=135></TD>
		<TD><IMG SRC="images/monitor02_02.gif" WIDTH=585 HEIGHT=87 ALT="���󥱡��Ȥ�������"></TD>
	</TR>
	<TR>
		<TD valign="top">

<!--��ɽ�Ȥߢ�-->
<form>
<TABLE WIDTH=585 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD>
			<IMG SRC="images/line_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD bgcolor="#EEEEEE" height="28"><SPAN class="t13">&nbsp;&nbsp;&nbsp;<B>
<?
if (!$msg) {
?>
		���������꤬�Ȥ��������ޤ�����
<?
} else {
?>
		���������Ƥ˰ʲ��Υ��顼������ޤ�����
<?
}
?>
		</B></span></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/ten_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=10></TD>
	</TR>
	<TR>
		<TD align="center">

<TABLE WIDTH=90% BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD>
<SPAN class="t12">
<?
if (!$msg) {
?>
<B>����ϡ���<?=htmlspecialchars($enquete->title)?>�פ�<BR>
�������������������꤬�Ȥ��������ޤ�����</B><BR>
<BR>
�ޤ���<? disp_monitor_name() ?>����˥��󥱡��ȤΤ��Τ餻�����ä����ˤϡ����Ҥ��������������͡�<BR></SPAN>
<?
} else {
?>
		<?=disp_msg($msg, '��', '<br><br>')?>
<?
}
?>
		</TD>
	</TR>
</TABLE>

		</TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=10></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/ten_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=2></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/line_bule.gif" WIDTH=585 HEIGHT=1></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=5></TD>
	</TR>
	<TR>
		<TD align=right><SPAN class="t12">
<?
if (!$msg) {
?>
			<a href="m_index.php">�丽�߼»���Υ��󥱡��Ȱ��������</a>
<?
} else {
?>
			<a href="JavaScript:history.back()">�䥢�󥱡��Ȳ����ڡ��������</a>
<?
}
?>
		</SPAN></TD>
	</TR>
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=585 HEIGHT=30></TD>
	</TR>
</TABLE>
</form>
<!--��ɽ�Ȥߢ�-->

		</TD>
	</TR>
</TABLE>

	</TD>
	</TR>
	<TR>
	<TD><IMG SRC="images/footer_bar.gif" WIDTH=720 HEIGHT=2></TD>
	</TR>
	<TR>
	<TD align=right><IMG SRC="images/copyright.gif" WIDTH=349 HEIGHT=22 ALT="copyright"></TD>
	</TR>
	<TR>
	<TD align=right>
<SPAN class="copyright">���Υۡ���ڡ����˻Ȥ��Ƥ���������̿����������ϡ�����&nbsp;<a href="http://www.xxxxxxx.co.jp/" target="_blank">������ҡ�����������</a>&nbsp;�˵�°���ޤ���<BR>
̵�Ǥ�ʣ���������Ѥ��뤳�Ȥ�ˡΧ�ˤ��ؤ����Ƥ��ޤ���<BR><BR></SPAN>
	</TD>
	</TR>
	</TABLE>

</TD>
<TD background="images/bg_gray.gif"><IMG SRC="images/spacer.gif" WIDTH=40 HEIGHT=1></TD>
</TR>
</table>

</BODY>
</HTML>