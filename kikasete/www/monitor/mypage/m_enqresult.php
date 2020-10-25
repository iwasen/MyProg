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

// アンケート回答集計値更新
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

// アンケートID存在チェック
if ($enquete_id == '')
	$msg = 'アンケートが選択されていないようです。';

// アンケート有効性チェック
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
		$msg = '選択されたアンケートは有効ではありません。';
}

if (!$retry) {
	// 回答済みチェック
	if (!$msg) {
		$sql = "SELECT an_enquete_id FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=$monitor_id";
		$result = db_exec($sql);
		if (pg_numrows($result))
			$msg = 'あなたは既にこのアンケートに回答されているようです。';
	}

	// 性別、未既婚取得
	$sql = "SELECT mn_sex,mn_mikikon FROM t_monitor WHERE mn_monitor_id=$monitor_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$sex = $fetch->mn_sex;
		$mikikon = $fetch->mn_mikikon;
	}

	// 回答内容保存
	if (!$msg) {
		$enquete = new enquete_class;
		$enquete->read_db($enquete_id);

		db_begin_trans();

		$sql = "INSERT INTO t_answer (an_enquete_id,an_monitor_id) VALUES ($enquete_id,$monitor_id)";
		db_exec($sql);

		foreach ($enquete->question as $qno => $question) {
			switch ($question->question_type) {
			case 1:		// 単一選択肢
				$answer = $_POST["answer$qno"];
				if ($answer != '') {
					$sql = "INSERT INTO t_ans_select (as_enquete_id,as_question_no,as_sel_no,as_monitor_id)"
							. " VALUES ($enquete_id,$qno,$answer,$monitor_id)";
					db_exec($sql);
					update_enq_sum($enquete_id, $qno, $answer, $sex, $mikikon);
					$answer_flag = true;
				}
				break;
			case 2:		// 複数選択肢
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

			// 質問別回答者数カウント
			update_enq_sum($enquete_id, $qno, 0, $sex, $mikikon);
		}

		if ($answer_flag) {
			// 全アンケート回答者数
			$sql = "UPDATE t_enquete_sum SET em_sum=em_sum+1 WHERE em_enquete_id=$enquete_id AND em_question_no=0 AND em_sum_kind=0 AND em_sel_no=0";
			db_exec($sql);

			monitor_point($monitor_id, PT_ENQUETE, $point, null, null, $enquete_id);
			db_commit_trans();

			$_SESSION['ss_chara_image'] = get_chara_image($monitor_id);
		} else {
			db_rollback();
			$msg = '回答が入力されていないようです。';
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
		<td align="left" background="<?=$img?>/title_bg.gif"><img src="<?=$img?>/title_enq.gif" width="320" height="39" alt="今週のきかせて１万人アンケート"></td>
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
<? /* 07/04/23 wakasa HTML差込機能追加 */ ?>
<td align="left" colspan="3"><font size="3" class=fs12_lh130><strong>Ｑ<?=$question_no?>．<?=nl2br($fetch->eq_question_text)?></strong></font></td>
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
	$html = '<TD width=10>　</TD><TD align=left width=100>%TEXT%</TD>'.'<TD class=graphmiddle width=450><IMG height=10 src="./../image/graph.gif" width=%WIDTH%> %COUNT%</td>';
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
<font size="2"><br>ご回答いただき、ありがとうございました。現在の途中経過は、上記のとおりです。<br><br></font>
<form method="post" action="m_enqresult2.php">
<input type="hidden" name="seq_no" value="<?=$seq_no?>">
<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
	<table border="0" cellpadding="5" cellspacing="0" width="600">
		<tr>
			<td bgcolor="#2b2b63"><font color="white"><strong>あなたのエピソードが「きかせて・ｎｅｔ 通信」に掲載されるかも！</strong></font></td>
		</tr>
		<tr>
			<td bgcolor="#f8f8f8"><font size="2">きかせて・ｎｅｔ 事務局では、</font><font color="#2b2b63" size="2"><strong>「きかせて！１万人アンケート」</strong></font><font size="2">に関するあなたのエピソードを募集しています。あなたの「ハンドルネーム」「性別」「年代」（例：「ふむ〜ん」「女性」「20代」など）をご記入の上、メールをお送りください。<br>お送りいただいたエピソードは、毎週モニターのみなさまにお届けする「きかせて・ｎｅｔ 通信」の中で取り上げます！<br>ぜひぜひ、あなたの一言をお寄せくださいね。 </font></td>
		</tr>
		<tr>
			<td bgcolor="white"><font size="2" color="black"><br>ちなみに今回のテーマはこんなのでしたよ↓<br></font></td>
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
	<font size="2">あなたのエピソードはこちらから↓<br></font>
	<font size="2"><textarea name="episode" cols="55" rows="8"><?=htmlspecialchars($episode)?></textarea><P>	<font size="2">下記空欄に「ハンドルネーム／性別／年代」を入れてください。<br></font>	<font size="2" color="red">※こちらも必須になります。</font>
	<font size="2" color="red">　<br>
	</font>
	<font size="2"><input type="text" name="handle_name" size="78" <?=value($handle_name)?>><br>
	<center>
	<input type="submit" value="　回答を送る　">
	<input type="reset" value="　クリア　"><br>
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
