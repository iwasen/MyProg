<?
$top = './..';
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_mypage.php");
include("$inc/mn_header2.php");

// アンケートIDが無ければトップへ戻す
if ($enquete_id == '')
	redirect('m_index_main.php');

// 該当のアンケートが無ければトップへ戻す
$sql = "SELECT en_title,en_description FROM t_enquete WHERE en_enquete_id=$enquete_id AND en_status=5 AND en_enq_kind=1 AND en_enq_type=2";
$result = db_exec($sql);
if (pg_numrows($result) == 0)
	redirect('m_index_main.php');
$fetch = pg_fetch_object($result, 0);
$title = htmlspecialchars($fetch->en_title);
$description = nl2br(htmlspecialchars($fetch->en_description));
?>
<? monitor_menu() ?>
<!-- コンテンツここから -->
<form method="post" action="m_enqresult.php">
<input type="hidden" name="enquete_id" value="<?=$enquete_id?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" background="<?=$img?>/title_bg.gif"><img src="<?=$img?>/title_enq.gif" width="320" height="39" alt="今週のきかせて１万人アンケート"></td>
	</tr>
	<tr>
		<td align="center">
			<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD background="<?=$img?>/title_catch_01.gif"><IMG SRC="<?=$img?>/spacer.gif" WIDTH=1 HEIGHT=2></TD>
				</TR>
				<TR>
					<TD bgcolor="#E8F6FF">
					<table width="100%" border="0" cellspacing="10" cellpadding="0">
				<tr>
					<td><font size="3" class=fs12_lh130>きかせて・netのモニターなら誰でも参加ＯＫ!<BR>毎週テーマが替わり「みんなはどんな風に思っているんだろう？」が一目で分かります。<BR>また選択肢を選んでポンと答えるだけで獲得ポイントが３〜５Ｐと結構お得！<BR><?=htmlspecialchars($monitor_name)?>さんもぜひ回答してくださいね。<BR></font></td>
				</tr>
			</table>
		</TD>
	</TR>
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
	<table width="620" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td><img src="<?=$img?>/spacer.gif" width="1" height="15" border="0"></td>
		</tr>
	</table>
<?
$sql = "SELECT eq_question_no,eq_question_text,eq_question_type FROM t_enq_question WHERE eq_enquete_id=$enquete_id ORDER BY eq_question_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);
	$question_no = $fetch->eq_question_no;

	$question_text = ($nrow > 1) ? "Ｑ$question_no. " : '';
// 07/04/23 wakasa HTML差込機能追加
        $question_text .= ($fetch->eq_question_text != '') ? nl2br($fetch->eq_question_text) : '下記からあてはまるものを選び、送信ボタンを>押してください。';
?>
	<table width="600" border="0" cellspacing="0" cellpadding="5" bgcolor="#EEEEEE">
		<tr>
			<td align="left"><font size="3" class=fs12_lh130><strong><?=$question_text?></strong></font></td>
		</tr>
	</table>
	<table width="600" border="0" cellspacing="0" cellpadding="5" bgcolor="#EEEEEE">
<?
	switch ($fetch->eq_question_type) {
	case 1:		// 単一選択
		$sql = "SELECT es_sel_no,es_sel_text FROM t_enq_select WHERE es_enquete_id=$enquete_id AND es_question_no=$question_no ORDER BY es_sel_no";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);
			if ($fetch2->es_sel_text != '') {
				$sel_no = $fetch2->es_sel_no;
				$sel_text = htmlspecialchars($fetch2->es_sel_text);
?>
										<tr>
											<td width="39" align="center" bgcolor="white"><input type="radio" name="answer<?=$question_no?>" value="<?=$sel_no?>"></td>
											<td width="358" bgcolor="#f8f8f8"><font size="2"><?=htmlspecialchars($sel_text)?></font></td>
										</tr>
<?
			}
		}
		break;
	case 2:		// 複数選択
		$sql = "SELECT es_sel_no,es_sel_text FROM t_enq_select WHERE es_enquete_id=$enquete_id AND es_question_no=$question_no ORDER BY es_sel_no";
		$result2 = db_exec($sql);
		$nrow2 = pg_numrows($result2);
		for ($j = 0; $j < $nrow2; $j++) {
			$fetch2 = pg_fetch_object($result2, $j);
			if ($fetch2->es_sel_text != '') {
				$sel_no = $fetch2->es_sel_no;
				$sel_text = htmlspecialchars($fetch2->es_sel_text);
?>
										<tr>
											<td width="39" align="center" bgcolor="white"><input type="checkbox" name="answer<?=$question_no?>[]" value="<?=$sel_no?>"></td>
											<td width="358" bgcolor="#f8f8f8"><font size="2"><?=htmlspecialchars($sel_text)?></font></td>
										</tr>
<?
			}
		}
		break;
	}
?>
									</table>
<?
}
?>
						<font size="2"><br>
						<input type="submit" value="送信">
						<input type="reset" value="リセット">
						</font>
						</form>
					</td>
				</tr>
			</table></td>
	</tr>
</table>
