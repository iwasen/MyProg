<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mkk_myp_check.php");

// �����ɽ��
function disp_sel(&$sel) {
	if (is_array($sel))
		echo join("\n", $sel);
}

if ($edit) {
	$myp = &$_SESSION['s_my_partner'];
	$pjt_id = $myp->pjt_id;
} else {
	$_SESSION['s_my_partner'] = new my_partner_class;
	$myp = &$_SESSION['s_my_partner'];
	$myp->read_db($pjt_id);
}
$enquete = &$myp->enquete;

if ($enquete->title == '')
	$title = "��{$myp->room_name}�ٻ��ü��罸";
else
	$title = $enquete->title;

if ($enquete->max_question_no == 0)
	$enquete->max_question_no = 1;
?>
<? marketer_header('My�ѡ��ȥʡ�', PG_NULL, '', false) ?>

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
	if (navigator.appName.charAt(0) == "N") {
		switch (f["question_type" + n].selectedIndex) {
		case 0:
		case 1:
		case 2:
		case 3:
			document.getElementById("q" + n).rowSpan = 4;
			break;
		case 4:
			document.getElementById("q" + n).rowSpan = 3;
			break;
		}
	}
}

function onload_body() {
	for (var i = 1; i <= <?=$enquete->max_question_no?>; i++)
		sel_question_type(i);
}

window.onload = function() {
  onload_body();
}
//-->
</script>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- my�ѡ��ȥʡ� -->

			<div align="center">
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td width="95"><img src="images/fl_l_1_0.gif" alt="" width="95" height="18" border="0"></td>
				<td colspan="3" width="285"><img src="images/fl_l_2_1.gif" alt="" width="285" height="18" border="0"></td>
				<td colspan="2" width="190"><img src="images/fl_l_3_0.gif" alt="" width="190" height="18" border="0"></td>
				<td width="95"><img src="images/fl_l_4_0.gif" alt="" width="95" height="18" border="0"></td>
			</tr>
			<tr>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
				<td width="95"><img src="images/common/spacer.gif" width="95" height="2" border="0"></td>
			</tr>
			<tr>
				<td><img src="images/fl_s_1_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_2_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_3_2.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_4_1.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_5_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_6_0.gif" alt="" width="95" height="50" border="0"></td>
				<td><img src="images/fl_s_7_0.gif" alt="" width="95" height="50" border="0"></td>
			</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" width="665">
			<tr>
				<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
			</tr>
			</table>
			</div>


			<form method="post" name="form1" action="mp_r_job_update2.php" enctype="multipart/form-data">
			<table border=0 cellspacing=2 cellpadding=3 width="90%">
				<tr>
					<td bgcolor="#eff7e8">�����󥱡������Ƥ�����</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
							<tr>
								<td bgcolor="#eeeeee">���󥱡��ȥ����ȥ�</td>
								<td bgcolor="#ffeecc">
									<input class="kanji" type="text" name="title" size="50" maxlength="20" <?=value($title)?>>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" id="question">
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
								<td bgcolor="#eeeeee" width="15%" rowspan="6" id="q<?=$i?>">��<?=mb_convert_kana($i, 'N')?></td>
								<td bgcolor="#ffeecc">
									<div>������ʸ</div>
									<textarea rows="3" cols="70" name="question_text<?=$i?>"><?=htmlspecialchars($question->question_text)?></textarea>
								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<div>�������λ���<div>
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
									<strong>������21�Ĥޤ�)</strong><br>
									<textarea name="sel_text<?=$i?>" cols="50" rows="10"><? disp_sel($question->sel_text) ?></textarea>
									<div><input type="checkbox" name="fa_flag<?=$i?>" <?=value_checked('t', $question->fa_flag)?>>�Ǹ�������ǥե꡼���������</div>
								</td>
							</tr>
							<tr id="matrix<?=$i?>">
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="0" cellpadding="0" width="100%">
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
															<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box">
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

$fetch = get_system_info('sy_myp_max_question');
if ($enquete->max_question_no < $fetch->sy_myp_max_question) {
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
			<input type="submit" value="<?=$myp->recruit_flag & 0x02 ? '��������' : '����Ͽ��'?>" onclick="document.form1.next_action.value='update'">
			<input type="button" value="����롡" onclick="location.href='mp_r_job.php?pjt_id=<?=$pjt_id?>'">

			<input type="hidden" name="next_action">
			</form>
			<!-- my�ѡ��ȥʡ� -->
<br><br>
		</td>
	</tr>
</table>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>

<form method="post" name="form2" action="mp_pre_matrix.php" target="pre_matrix">
<input type="hidden" name="hyousoku">
<input type="hidden" name="hyoutou">
</form>

<? marketer_footer () ?>
