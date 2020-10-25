<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/point.php");
$inc = "$top/inc";
include("$inc/mypage.php");

// モニター名表示
function disp_monitor_name() {
	$sql = "SELECT mn_name1 FROM t_monitor WHERE mn_monitor_id={$_SESSION['ss_monitor_id']}";
	echo htmlspecialchars(db_fetch1($sql));
}

// 入力パラメータ取得
$enquete_id = $_POST['enquete_id'];
$monitor_id = $_SESSION['ss_monitor_id'];

// アンケートID存在チェック
if ($enquete_id == '')
	$msg[] = 'アンケートが選択されていないようです。';

// アンケート有効性チェック
if (!$msg) {
	$sql = "SELECT en_point FROM t_enquete WHERE en_enquete_id=$enquete_id AND en_status=5 AND en_enq_type=2";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$point = $fetch->en_point;
	} else
		$msg[] = '選択されたアンケートは有効ではありません。';
}

// 回答対象者チェック
if (!$msg) {
	$sql = "SELECT el_enquete_id FROM t_enquete_list WHERE el_enquete_id=$enquete_id AND el_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_num_rows($result) == 0)
		$msg[] = 'あなたはこのアンケートに参加していません。';
}

// 回答済みチェック
if (!$msg) {
	$sql = "SELECT an_enquete_id FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_num_rows($result))
		$msg[] = 'あなたは既にこのアンケートに回答されているようです。';
}

// アンケート内容取得
$enquete = new enquete_class;
$enquete->read_db($enquete_id);

// 回答チェック
if (!$msg) {
	foreach ($enquete->question as $qno => $question) {
		switch ($question->question_type) {
		case 1:		// 単一選択肢
		case 2:		// 複数選択肢
			if (!isset($_POST["answer$qno"]))
				$msg[] = "Ｑ{$qno}の回答が入力されていないようです。";
			break;
		case 3:		// フリー回答
			if ($_POST["answer$qno"] == "")
				$msg[] = "Ｑ{$qno}の回答が入力されていないようです。";
			break;
		case 4:		// マトリクス(SA)
		case 5:		// マトリクス(MA)
			if (is_array($_POST["answer$qno"])) {
				$sno = array();
				$tno = array();
				foreach ($_POST["answer$qno"] as $no) {
					$ary = explode(':', $no);
					$sno[] = $ary[0];
					$tno[] = $ary[1];
				}

				if (count(array_unique($sno)) < count($question->hyousoku))
					$msg[] = "Ｑ{$qno}の回答に入力されていない行があるようです。";

				if ($question->dup_flag == 't') {
					if (count($tno) != count(array_unique($tno)))
						$msg[] = "Ｑ{$qno}の回答に重複があるようです。";
				}
			} else
				$msg[] = "Ｑ{$qno}の回答が入力されていないようです。";
		}
	}
}

// 回答内容保存
if (!$msg) {
	db_begin_trans();

	$sql = "INSERT INTO t_answer (an_enquete_id,an_monitor_id) VALUES ($enquete_id,$monitor_id)";
	db_exec($sql);

	foreach ($enquete->question as $qno => $question) {
		if (is_array($question->sel_text))
			$last_sno = end(array_keys($question->sel_text));

		switch ($question->question_type) {
		case 1:		// 単一選択肢
			$answer = $_POST["answer$qno"];
			if ($answer != '') {
				$free_text = ($answer == $last_sno) ? $_POST["free_answer$qno"] : '';
				$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id,as_free_answer)"
						. " VALUES ($enquete_id,$qno,$answer,$monitor_id," . sql_char($free_text) . ")";
				db_exec($sql);
			}
			break;
		case 2:		// 複数選択肢
			if (is_array($_POST["answer$qno"])) {
				foreach ($_POST["answer$qno"] as $answer) {
					$free_text = ($answer == $last_sno) ? $_POST["free_answer$qno"] : '';
					$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id,as_free_answer)"
							. " VALUES ($enquete_id,$qno,$answer,$monitor_id," . sql_char($free_text) . ")";
					db_exec($sql);
				}
			}
			break;
		case 3:		// フリー回答
			$answer = $_POST["answer$qno"];
			if ($answer != '') {
				$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id,as_free_answer)"
						. " VALUES ($enquete_id,$qno,0,$monitor_id," . sql_char($answer) . ")";
				db_exec($sql);
			}
			break;
		case 4:		// マトリクス(SA)
		case 5:		// マトリクス(MA)
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

	// ポイント付与
	monitor_point($monitor_id, PT_ENQUETE, $point);

	// 回答者数カウントアップ
	$sql = "UPDATE t_ans_count SET ac_ans_count=ac_ans_count+1 WHERE ac_enquete_id=$enquete_id";
	db_exec($sql);

	db_commit_trans();
}
?>
<HTML>
<HEAD>
<TITLE>お酒と買物のアンケートモニター｜アンケートに答える｜</TITLE>
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
		<TD><a href="m_logout.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('b_logout','','images/b_logout_over.gif',1)"><IMG SRC="images/b_logout.gif" WIDTH=90 HEIGHT=24 name=b_logout BORDER=0 ALT="ログアウト"></TD>
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
		<TD align="left"><span class="t10"><a href="m_index.php">現在実施中のアンケート一覧</a>&nbsp;＞&nbsp;アンケートに答える</span></TD>
		<TD align="right" WIDTH=400><span style="font-size:14px; font-weight:bold; color:#FF6600"><a href="m_change_add.php">［<img src="images/arrow2.gif" align="absmiddle" hspace="5" border="0" />メールアドレスの変更］</a>&nbsp;<a href="m_change_pw.php">［<img src="images/arrow2.gif" align="absmiddle" hspace="5" border="0" />パスワード変更］</a>&nbsp;</span></TD>
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
		<TD><IMG SRC="images/monitor02_02.gif" WIDTH=585 HEIGHT=87 ALT="アンケートに答える"></TD>
	</TR>
	<TR>
		<TD valign="top">

<!--▼表組み▼-->
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
		ご回答ありがとうございました。
<?
} else {
?>
		ご回答内容に以下のエラーがありました。
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
<B>今回は、「<?=htmlspecialchars($enquete->title)?>」に<BR>
ご回答いただき、ありがとうございました。</B><BR>
<BR>
また、<? disp_monitor_name() ?>さんにアンケートのお知らせがあった場合には、ぜひご回答くださいね。<BR></SPAN>
<?
} else {
?>
		<?=disp_msg($msg, '・', '<br><br>')?>
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
			<a href="m_index.php">≫現在実施中のアンケート一覧に戻る</a>
<?
} else {
?>
			<a href="JavaScript:history.back()">≫アンケート回答ページに戻る</a>
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
<!--▲表組み▲-->

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
<SPAN class="copyright">このホームページに使われている画像・写真・記事等は、全て&nbsp;<a href="http://www.xxxxxxx.co.jp/" target="_blank">株式会社○○○○○○</a>&nbsp;に帰属します。<BR>
無断で複製・再利用することは法律により禁じられています。<BR><BR></SPAN>
	</TD>
	</TR>
	</TABLE>

</TD>
<TD background="images/bg_gray.gif"><IMG SRC="images/spacer.gif" WIDTH=40 HEIGHT=1></TD>
</TR>
</table>

</BODY>
</HTML>