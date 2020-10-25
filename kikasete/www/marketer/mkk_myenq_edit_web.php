<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/mye_temp.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���å�������
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	if ($my_enq_no == '') {
		$myenq->marketer_id = $_SESSION['ss_owner_id'];
		$myenq->creator_id = $_SESSION['ss_marketer_id'];
	} else
		$myenq->read_db($_SESSION['ss_owner_id'], $my_enq_no);
}

$enquete = &$myenq->enquete;

if ($enquete->description == '')
	$description = get_enq_description($myenq);
else
	$description = $enquete->description;
?>
<? marketer_header('My���󥱡���', PG_NULL) ?>

<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" width="788" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="710" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="710" bgcolor="#ffffff">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="700" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<form method="post" name="form1" action="mkk_myenq_update_web.php">
			<table border=0 cellspacing=2 cellpadding=3 width="90%">
				<tr>
					<td bgcolor="#eff7e8">�����󥱡��ȣףţ²��̤Υ������ޥ���</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">����ʸ</td>
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
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">��<?=mb_convert_kana($qno, 'N')?></td>
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
									<?=$sno?>��<?=htmlspecialchars($sel_text)?><?=$question->fa_sno == $sno ? ' �ʥե꡼������' : ''?><?=$question->ex_sno == $sno ? ' ����¾��' : ''?><br>
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
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">��<?=mb_convert_kana($qno, 'N')?></td>
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
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td width="20%" rowspan="3" bgcolor="#eeeeee">��<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="2" cellpadding="2" width="100%">
										<tr>
											<td>&nbsp;</td>
<?
			foreach ($question->hyoutou as $hno => $hyoutou) {
?>
											<td bgcolor="#ffffff"><?=mb_convert_kana($hno, 'N')?>��<?=htmlspecialchars($hyoutou)?></td>
<?
			}
?>
										</tr>
<?
			foreach ($question->hyousoku as $hno => $hyousoku) {
?>
										<tr bgcolor="<?=$hno % 2 ? '#e8fbfd' : '#ffffff'?>">
											<td><?=mb_convert_kana($hno, 'N')?>��<?=htmlspecialchars($hyousoku)?></td>
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
					<!-- �ơ��֥����� -->
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
								<td width="20%" rowspan="3" bgcolor="#eeeeee">��<?=mb_convert_kana($qno, 'N')?></td>
								<td bgcolor="#ffeecc"><?=htmlspecialchars($question->question_text)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc"><?=decode_question_type($question->question_type)?></td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<div>�����������ɽ������ʸ����<?=htmlspecialchars($question->pre_text)?></div>
									<div>������θ��ɽ������ʸ����<?=htmlspecialchars($question->post_text)?></div>
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
			<input type="submit" value="�ףţ¥ץ�ӥ塼" onclick="document.form1.next_action.value='preview'">
			<input type="submit" value="����Ͽ��" onclick="document.form1.next_action.value='update'">
			<input type="submit" value="�ꥻ�å�" onclick="document.form1.next_action.value='reset'">
			<input type="button" value="����롡" onclick="location.href='mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
			</form>
			</div>
		</td>
	</tr>
</table>

<? marketer_footer () ?>
