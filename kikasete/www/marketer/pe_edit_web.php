<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/mye_temp.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// セッション処理
if ($edit) {
	$pro_enq = &$_SESSION['ss_pro_enq'];
	$pro_enq_no = $pro_enq->pro_enq_no;
} else {
	$_SESSION['ss_pro_enq'] = new pro_enquete_class;
	$pro_enq = &$_SESSION['ss_pro_enq'];
	if ($pro_enq_no == '') {
		$pro_enq->marketer_id = $_SESSION['ss_owner_id'];
		$pro_enq->creator_id = $_SESSION['ss_marketer_id'];
	} else
		$pro_enq->read_db($_SESSION['ss_owner_id'], $pro_enq_no);
}

if ($main)
	$enquete = &$pro_enq->enquete2;
else
	$enquete = &$pro_enq->enquete;

if ($enquete->description == '')
	$description = get_enq_description($pro_enq);
else
	$description = $enquete->description;
?>
<? marketer_header('Ｐｒｏアンケート', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>
			<form method="post" name="form1" action="pe_update_web.php">
			<table border=0 cellspacing=2 cellpadding=3 width="90%" class="honbun2">
				<tr>
					<td bgcolor="#eff7e8">■アンケートＷＥＢ画面のカスタマイズ</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">説明文</td>
								<td bgcolor="#ffeecc">
									<textarea rows="20" cols="70" name="description"><?=htmlspecialchars($description)?></textarea>
								</td>
							</tr>
						</table>
					</td>
				</tr>
<?
if (is_array($enquete->question)) {
	foreach ($enquete->question as $qno => $question) {
		switch ($question->question_type) {
		case 1:
		case 2:
		case 7:
?>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>　<?=htmlspecialchars($sel_text)?><?=$question->fa_sno == $sno ? ' （フリー回答）' : ''?><?=$question->ex_sno == $sno ? ' （排他）' : ''?><br>
<?
			}
?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
<?
			break;
		case 3:
?>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
							</tr>
						</table>
					</td>
				</tr>
<?
			break;
		case 4:
		case 5:
?>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
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
											<td bgcolor="#ffffff"><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyoutou)?></td>
<?
			}
?>
										</tr>
<?
			foreach ($question->hyousoku as $hno => $hyousoku) {
?>
										<tr bgcolor="<?=$hno % 2 ? '#e8fbfd' : '#ffffff'?>">
											<td><?=mb_convert_kana($hno, 'N')?>．<?=htmlspecialchars($hyousoku)?></td>
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
						</table>
					</td>
					<!-- テーブル大枠 -->
				</tr>
<?
			break;
		case 6:
		case 8:
?>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<div>回答欄の前に表示する文字列：<?=htmlspecialchars($question->pre_text)?></div>
									<div>回答欄の後に表示する文字列：<?=htmlspecialchars($question->post_text)?></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
<?
			break;
		}
	}
}
?>
			</table>

			<br>
			<input type="submit" value="ＷＥＢプレビュー" onclick="document.form1.next_action.value='preview'">
			<input type="submit" value="　登録　" onclick="document.form1.next_action.value='update'">
			<input type="submit" value="リセット" onclick="document.form1.next_action.value='reset'">
			<input type="button" value="　戻る　" onclick="location.href='pe_make.php?pro_enq_no=<?=$pro_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
			<input type="hidden" name="main" <?=value($main)?>>
			</form>
			</div>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
