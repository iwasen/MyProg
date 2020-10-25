<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
$inc = "$top/inc";
include("$inc/mypage.php");
include("$inc/header2.php");

// �����ɽ��
function disp_sel(&$sel) {
	if (is_array($sel))
		echo join("\n", $sel);
}

// ���å�������
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	if ($my_enq_no == '') {
		$myenq->staff_id = $_SESSION['ss_staff_id'];
	} else
		$myenq->read_db($_SESSION['ss_staff_id'], $my_enq_no);
}

$enquete = &$myenq->enquete;

if ($enquete->title == '')
	$title = "��{$myenq->room_name}�ٻ��ü��罸";
else
	$title = $enquete->title;

if ($enquete->max_question_no == 0)
	$enquete->max_question_no = 1;
?>
<? staff_header('���󥱡�����Ͽ', 'onload="onload_body()"') ?>

<script type="text/javascript">
<!--
function onclick_pre_matrix(qno) {
	window.open("", "pre_matrix", "width=400,height=200,scrollbars=yes,resizable=yes");
	document.form2.hyousoku.value = document.form1["hyousoku" + qno].value;
	document.form2.hyoutou.value = document.form1["hyoutou" + qno].value;
	document.form2.submit();
}
function sel_question_type(n) {
	var f = document.form1;
	switch (f["question_type" + n].value) {
	case "1":
	case "2":
		document.getElementById("sel" + n).style.display = "";
		document.getElementById("matrix" + n).style.display = "none";
		break;
	case "3":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("matrix" + n).style.display = "none";
		break;
	case "4":
	case "5":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("matrix" + n).style.display = "";
		break;
	}
}
function onload_body() {
	for (var i = 1; i <= <?=$enquete->max_question_no?>; i++)
		sel_question_type(i);
}
//-->
</script>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

			<form method="post" name="form1" action="myenq_update2.php" enctype="multipart/form-data">
			<table border=0 cellspacing=2 cellpadding=3 width="95%" class="honbun2">
				<tr>
					<td bgcolor="#eff7e8">�����󥱡��Ȥ�����</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" id="question" class="honbun2">
<?
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];

	if ($question->url)
		$image_type = 2;
	elseif ($question->image_id)
		$image_type = 3;
	else
		$image_type = 1;
?>
							<tr>
								<td bgcolor="#eeeeee" width="15%" rowspan="6">��<?=mb_convert_kana($i, 'N')?></td>
								<td bgcolor="#ffeecc">
									<div>������ʸ</div>
									<textarea rows="3" cols="70" name="question_text<?=$i?>"><?=htmlspecialchars($question->question_text)?></textarea>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<div>�������λ���</div>
									<input type="radio" name="image_type<?=$i?>" <?=value_checked('1', $image_type)?>>�ʤ�<br>
									<input type="radio" name="image_type<?=$i?>" <?=value_checked('2', $image_type)?>>URL����ꤹ�� ��<input type="text" name="url<?=$i?>" size="30" <?=value($question->url)?>><br>
									<input type="radio" name="image_type<?=$i?>" <?=value_checked('3', $image_type)?>>�����������ࡡ<input type="file" name="image<?=$i?>" size="30">
<?
if ($image_type == 3) {
?>
									<br>�����ʲ���������ѤߤǤ����������񤭤��������ϡ����٥ѥ�����ꤷ�Ƥ���������
<?
}
?>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<select name="question_type<?=$i?>" onchange="sel_question_type(<?=$i?>)">
										<option <?=value_selected('1', $question->question_type)?>>ñ������(SA)
										<option <?=value_selected('2', $question->question_type)?>>ʣ������(MA)
										<option <?=value_selected('4', $question->question_type)?>>�ώĎ؎���(SA)
										<option <?=value_selected('5', $question->question_type)?>>�ώĎ؎���(MA)
										<option <?=value_selected('3', $question->question_type)?>>��ͳ����(FA)
									</select>
								</td>
							</tr>
							<tr id="sel<?=$i?>">
								<td bgcolor="#ffeecc">
									<strong>������20�Ĥޤ�)</strong><br>
									<textarea name="sel_text<?=$i?>" cols="50" rows="10"><? disp_sel($question->sel_text) ?></textarea>
									<div><input type="checkbox" name="fa_flag<?=$i?>" <?=value_checked('t', $question->fa_flag)?>>�Ǹ�������ǥե꡼���������</div>
								</td>
							</tr>
							<tr id="matrix<?=$i?>">
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="0" cellpadding="0" width="100%" class="honbun2">
										<tr>
											<td width="50%"><strong>ɽ¦��10�Ĥޤǡ�</strong><br>
											<textarea name="hyousoku<?=$i?>" cols="32" rows="10"><? disp_sel($question->hyousoku) ?></textarea></td>
											<td width="50%"><strong>ɽƬ��10�Ĥޤǡ�</strong><br>
											<textarea name="hyoutou<?=$i?>" cols="32" rows="10"><? disp_sel($question->hyoutou) ?></textarea></td>
										</tr>
										<tr>
											<td align="left">
												<table>
													<tr>
														<td>
															<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
																<tr>
																	<td width="50%" align="center"></td>
																	<td width="50%" align="center">ɽƬ</td>
																</tr>
																<tr>
																	<td align="center">ɽ¦</td>
																	<td>
																	</td>
																</tr>
															</table>
														</td>
														<td>
															<input type="button" value="�ץ�ӥ塼" onclick="onclick_pre_matrix(<?=$i?>)">
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<div><input type="checkbox" name="dup_flag<?=$i?>" <?=value_checked('t', $question->dup_flag)?>>�����ν�ʣ������ʤ�</div>
								</td>
							</tr>
							<tr></tr>
<?
}

if ($enquete->max_question_no < MAX_ENQ) {
?>
							<tr>
								<td colspan="2" bgcolor="#ffeecc">
									<input type="submit" value="��������ɲá�" onclick="document.form1.next_action.value='add_question'">
								</td>
							</tr>
<?
}
?>
						</table>
					</td>
				</tr>
			</table>

			<br>
			<input type="submit" value="�ץ�ӥ塼" onclick="document.form1.next_action.value='preview'">
			<input type="submit" value="��������" onclick="document.form1.next_action.value='update'">
			<input type="button" value="����롡" onclick="location.href='myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
			</form>


<form method="post" name="form2" action="pre_matrix.php" target="pre_matrix">
<input type="hidden" name="hyousoku">
<input type="hidden" name="hyoutou">
</form>

<? staff_footer () ?>
