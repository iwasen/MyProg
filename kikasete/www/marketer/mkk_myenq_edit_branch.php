<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// ���å�������
$myenq = new my_enquete_class;
$myenq->read_db($_SESSION['ss_owner_id'], $my_enq_no);

$enquete = &$myenq->enquete;
?>
<? marketer_header('�ͣ����󥱡���', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif"></td>
				</tr>
			</table>

			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="100%">
				<tr>
					<td bgcolor="#eff7e8">��ʬ�������ڡ���������</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
<?
if (is_array($enquete->question)) {
	$branch_button_flag = false;
	$count = count($enquete->question);
	foreach ($enquete->question as $qno => $question) {
		if ($qno > 1) {
			if (isset($enquete->branch_cond[$qno])) {
?>
							<tr>
								<td bgcolor="#ffff99" align="center" colspan=2>ʬ������ʲ��ڡ�����<span style="color:#ff0000;">��<?=htmlspecialchars($enquete->branch_cond_text($qno, '�����򤷤����Τ�', false, false))?>��</span></td>
							</tr>
<?
			} elseif ($question->page_break == DBTRUE) {
?>
							<tr>
								<td bgcolor="#66ccff" align="center" colspan=2>���ڡ���</td>
							</tr>
<?
			}
		}
?>
							<tr>
								<td bgcolor="#ffeecc">
									<table border=0 cellspacing=0 cellpadding=0 width=100%>
										<tr>
											<td>��<?=mb_convert_kana($qno, 'N')?>��<?=nl2br(htmlspecialchars($question->question_text))?></td>
											<td align="right" valign="top">
<?
		if ($qno > 1) {
			if ($question->page_break == DBTRUE) {
?>
												<input type="button" value="���ڡ����β��" onclick="if (confirm('���ڡ����������ޤ���������Ǥ�����')) location.href='mkk_myenq_update_pb.php?my_enq_no=<?=$my_enq_no?>&qno=<?=$qno?>&pb=0'"<?=isset($enquete->branch_cond[$qno]) ? ' disabled' : ''?>>
<?
			} else {
?>
												<input type="button" value="���ڡ���������" onclick="location.href='mkk_myenq_update_pb.php?my_enq_no=<?=$my_enq_no?>&qno=<?=$qno?>&pb=1'"<?=isset($enquete->branch_cond[$qno]) ? ' disabled' : ''?>>
<?
			}
?>
												<input type="button" value="ʬ����������" onclick="location.href='mkk_myenq_edit_branch2.php?my_enq_no=<?=$my_enq_no?>&qno=<?=$qno?>'"<?=$branch_button_flag ? '' : ' disabled'?>>
<?
		}
?>
											</td>
										</tr>
									</table>
								</td>
							</tr>
<?
		if ($question->question_type == 1 || $question->question_type == 2 || $question->question_type == 7) {
?>
							<tr>
								<td bgcolor="#eeeeee">
<?
			foreach ($question->sel_text as $sno => $sel_text) {
?>
									<?=$sno?>��<?=htmlspecialchars($sel_text)?><br>
<?
			}
?>
								</td>
							</tr>
<?
		} elseif ($question->question_type == 4 || $question->question_type == 5) {
?>
							<tr>
								<td bgcolor="#eeeeee">
									<table border="1" cellspacing="0" cellpadding="2" width="100%" class="honbun2" frame="box">
										<tr>
											<td bgcolor="#ffffff">&nbsp;</td>
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
<?
		}

		if ($question->question_type != 3 && $question->question_type != 6 && $question->question_type != 8)
			$branch_button_flag = true;
	}
}
?>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<input type="button" value="�ץ�ӥ塼" onclick="location.href='mkk_myenq_webprev.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="button" value="����롡" onclick="location.href='mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			</form>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
