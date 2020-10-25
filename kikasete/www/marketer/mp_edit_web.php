<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/enq_temp.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	$myp->read_db($pjt_id);
}
$enquete = &$myp->enquete;

if ($enquete->description == '')
	$description = get_enq_description($myp);
else
	$description = $enquete->description;
?>
<? marketer_header('Myパートナー', PG_NULL) ?>

<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="788" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="710" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="710">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="700" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<form method="post" name="form1" action="mp_update_web.php">
			<table border=0 cellspacing=2 cellpadding=3 width="778" bgcolor="#ffffff">
				<tr>
					<td bgcolor="#eff7e8">■アンケートＷＥＢ画面のカスタマイズ</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
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
		if ($question->question_type == 1 || $question->question_type == 2) {
?>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=$question->question_type == 1 ? '単一選択(SA)' : '複数選択(MA)'?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>　<?=htmlspecialchars($sel_text)?><br>
<?
			}
			if ($question->fa_flag == 't') {
?>
									（最後の選択肢でフリー回答を取得する）
<?
			}
?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
<?
		} elseif ($question->question_type == 3) {
?>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">自由回答</td>
							</tr>
						</table>
					</td>
				</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">Ｑ<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=$question->question_type == 4 ? 'マトリックス(SA)' : 'マトリックス(MA)'?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="2" cellpadding="2" width="100%">
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
		}
	}
}
?>
			</table>

			<br>
			<input type="submit" value="ＷＥＢプレビュー" onclick="document.form1.next_action.value='preview'">
			<input type="submit" value="　登録　" onclick="document.form1.next_action.value='update'">
			<input type="submit" value="リセット" onclick="document.form1.next_action.value='reset'">
			<input type="button" value="　戻る　" onclick="location.href='mp_r_job.php?pjt_id=<?=$pjt_id?>'">

			<input type="hidden" name="next_action">
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
