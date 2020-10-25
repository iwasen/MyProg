<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/mr_permission.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/pro_enquete.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");

// �����ɽ��
function disp_sel(&$sel, $ex_sno = 0) {
	if (is_array($sel)) {
		foreach ($sel as $sno => $text) {
			if ($sno != $ex_sno)
				echo "$text\n";
		}
	}
}

// ���å�������
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

if ($enquete->max_question_no == 0)
	$enquete->max_question_no = 1;
?>
<? marketer_header('�У�異�󥱡���', PG_NULL, '') ?>

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
	var t = f["question_type" + n].value;
	switch (t) {
	case "1":
	case "2":
	case "7":
		document.getElementById("sel" + n).style.display = "";
		document.getElementById("mtx" + n).style.display = "none";
		document.getElementById("na1" + n).style.display = "none";
		document.getElementById("na2" + n).style.display = "none";
		document.getElementById("exf" + n).style.display = t == "2" ? "" : "none";
		document.getElementById("faf" + n).style.display = t != "7" ? "" : "none";
		break;
	case "3":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("mtx" + n).style.display = "none";
		document.getElementById("na1" + n).style.display = "none";
		document.getElementById("na2" + n).style.display = "none";
		break;
	case "4":
	case "5":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("mtx" + n).style.display = "";
		document.getElementById("na1" + n).style.display = "none";
		document.getElementById("na2" + n).style.display = "none";
		break;
	case "6":
	case "8":
		document.getElementById("sel" + n).style.display = "none";
		document.getElementById("mtx" + n).style.display = "none";
		document.getElementById("na1" + n).style.display = "";
		document.getElementById("na2" + n).style.display = "";
		break;
	}
}
function onload_body() {
	var f = document.form1;
	for (var i = 1; i <= <?=$enquete->max_question_no?>; i++) {
		if (f["qno" + i])
			sel_question_type(i);
	}
<?
if ($new_question) {
?>
	location.href = "#new_question";
<?
}
?>
}
function preview() {
	var f = document.form1;
	var target = f.target;
	f.next_action.value = "preview";
	f.target = "_blank";
	f.submit();
	f.target = target;
}
function update() {
	var f = document.form1;
	for (var i = 1; i <= <?=$enquete->max_question_no?>; i++) {
		if (f["qno" + i]) {
			if (f["image" + i].value != "" || f["current_image_type" + i].value != 3) {
				if (f["image_type" + i][2].checked) {
					switch (f["image" + i].value.slice(-4).toLowerCase()) {
					case ".jpg":
					case ".gif":
						break;
					default:
						alert("���������ϡ�jpg�פޤ��ϡ�gif�׷����Υե��������ꤷ�Ƥ���������");
						f["image" + i].focus();
						return false;
					}
				}
			}
		}
	}
	f.next_action.value = "update";
	f.submit();
}
function add_question() {
	var f = document.form1;
	f.next_action.value = "add_question";
	f.submit();
}
function delete_question(i, qno) {
	var f = document.form1;
	if (confirm("Q" + qno + " �������Ƥ������Ǥ�����")) {
		f.delete_qno.value = i;
		f.next_action.value = "delete_question";
		f.submit();
	}
}
function move_question(i, qno) {
	var width = 300;
	var height = 200;
	var left = (window.screen.width - width) / 2;
	var top = (window.screen.height - height) / 2;
	window.open("../common/move_question.php?ss=ss_pro_enq&main=<?=$main?>&move_from=" + i, "move_question", "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top);
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

<table  border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/mkk_pe/title_01.gif"></td>
				</tr>
			</table>

			<form method="post" name="form1" action="pe_update2.php" enctype="multipart/form-data">
			<table border=0 cellspacing=2 cellpadding=3 width="90%" class="honbun2">
				<tr>
				  <td bgcolor="#ffffff" align="right" class="footer_text"><a href="#bottom">���ֲ��آ�</a><a name="top"></a></td>
			    </tr>
				<tr>
					<td bgcolor="#eff7e8">�����󥱡��Ȥ�����</td>
				</tr>
				<tr>
					<td>
						<table border=1 cellspacing=0 cellpadding=3 width="100%" frame="box" id="question" class="honbun2">
<?
if ($main && $pro_enq->finding_flag == DBTRUE) {
	$description = $enquete->description;
	if ($description == '')
		$description = $pro_enq->res_cond_text('�Ȥ������������������ˤ�ʹ�����ޤ���');
?>
							<tr>
								<td bgcolor="#eeeeee" width="15%">����ʸ</td>
								<td bgcolor="#ffeecc">
									���󥱡��Ȥ�����ʸ������Ƥ�������<br>
									<textarea rows="5" cols="70" name="description"><?=htmlspecialchars($description)?></textarea>								</td>
							</tr>
<?
}

// ͭ���ʡʺ������Ƥ��ʤ��˼���������
$qnum = 0;
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	if (!$enquete->question[$i]->delete_flag)
		$qnum++;
}

// ���䤴�ȤΥ롼��
$qno = 1;
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];

	if ($question->delete_flag)
		continue;

	// ���������
	if (!isset($question))
		$question->must_flag = 't';

	// ��������󥯤λ���
	if ($question->url)
		$image_type = 2;
	elseif ($question->image_id)
		$image_type = 3;
	else
		$image_type = 1;
?>
							<tr>
								<td bgcolor="#eeeeee" width="15%" rowspan="8" id="q<?=$i?>" height="100%">
<?
	if ($new_question == $i) {
?>
									<a name="new_question"></a>
<?
	}
?>
									<input type="hidden" name="qno<?=$i?>" value="1">
									<b>��<?=mb_convert_kana($qno, 'N')?></b>
									<div><nobr><input type="checkbox" name="must_flag<?=$i?>" <?=value_checked('t', $question->must_flag)?>>ɬ��</nobr></div>
<?
	if ($qnum >= 2) {
?>
									<div><input type="button" value="�������" onclick="delete_question(<?=$i?>,<?=$qno?>)"></div>
									<div><input type="button" value="����ư��" onclick="move_question(<?=$i?>,<?=$qno?>)"></div>
<?
	}
?>
								</td>
								<td bgcolor="#ffeecc">
									<div>������ʸ</div>
									<textarea rows="5" cols="70" name="question_text<?=$i?>"><?=htmlspecialchars($question->question_text)?></textarea>								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<div>����������󥯤λ���<br>
									���ֲ�����������׾�硢���Ӥ��������⤬������ޤ����ޤ��礭�ʲ����������ߤ��Ǥ��ޤ���<br>
									���ܤ����ϥ���������Ȥˤ����̤���������</div>
									<input type="radio" name="image_type<?=$i?>" <?=value_checked('1', $image_type)?>>�ʤ�<br>
									<input type="radio" name="image_type<?=$i?>" <?=value_checked('2', $image_type)?>>URL����ꤹ�� ��<input type="text" name="url<?=$i?>" size="30" <?=value($question->url)?>><br>
									<input type="radio" name="image_type<?=$i?>" <?=value_checked('3', $image_type)?>>�����������ࡡ<input type="file" name="image<?=$i?>" size="30">
									<input type="hidden" name="current_image_type<?=$i?>" <?=value($image_type)?>>
<?
if ($image_type == 3) {
?>
									<br>�����ʲ���������ѤߤǤ����������񤭤��������ϡ����٥ѥ�����ꤷ�Ƥ���������
<?
}
?>								</td>
							</tr>
							<tr>
								<td bgcolor="#ffeecc">
									<select name="question_type<?=$i?>" onchange="sel_question_type(<?=$i?>)">
										<option <?=value_selected('1', $question->question_type)?>><?=decode_question_type(1)?>
										<option <?=value_selected('7', $question->question_type)?>><?=decode_question_type(7)?>
										<option <?=value_selected('2', $question->question_type)?>><?=decode_question_type(2)?>
<?
// FA���ܥ��󥱡��ȤΤ�
if ($main) {
?>
										<option <?=value_selected('8', $question->question_type)?>><?=decode_question_type(8)?>
										<option <?=value_selected('3', $question->question_type)?>><?=decode_question_type(3)?>
<?
}
?>
										<option <?=value_selected('6', $question->question_type)?>><?=decode_question_type(6)?>
										<option <?=value_selected('4', $question->question_type)?>><?=decode_question_type(4)?>
										<option <?=value_selected('5', $question->question_type)?>><?=decode_question_type(5)?>
									</select>
									<input type="hidden" name="question_type_org<?=$i?>" <?=value($question->question_type)?>>
									<input type="hidden" name="res_cond_flag<?=$i?>" <?=value(!$main && isset($pro_enq->res_cond[$question->question_no]) ? 1 : 0)?>>								</td>
							</tr>
							<tr id="sel<?=$i?>">
								<td bgcolor="#ffeecc">
									<strong>������<?=MAX_PRO_SEL - 12?>�Ĥޤ� ���ե꡼��������¾������ä������22�Ĥޤǡ�</strong><br>
									<textarea name="sel_text<?=$i?>" cols="50" rows="10"><? disp_sel($question->sel_text, $question->ex_sno) ?></textarea>
									<div id="faf<?=$i?>"><input type="checkbox" name="fa_flag<?=$i?>" <?=value_checked('t', $question->fa_flag)?>>�Ǹ�������ǥե꡼���������</div>
									<div id="exf<?=$i?>"><input type="checkbox" name="ex_flag<?=$i?>" <?=value_checked(true, $question->ex_sno != 0)?>>��¾�����������ˤĤ���ʡ֤�����ˤʤ��ס��äˤʤ��פʤɡ�<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��¾������ʸ���� <input type="text" name="ex_sel<?=$i?>" <?=value($question->sel_text[$question->ex_sno])?> size=50></div>								</td>
							</tr>
							<tr id="mtx<?=$i?>">
								<td bgcolor="#ffeecc">
									<table border="0" cellspacing="0" cellpadding="0" width="100%" class="honbun2">
										<tr>
											<td width="50%"><strong>ɽ¦��10�Ĥޤǡ�</strong><br>
											<textarea name="hyousoku<?=$i?>" cols="32" rows="10"><? disp_sel($question->hyousoku) ?></textarea></td>
											<td width="50%"><strong>ɽƬ��10�Ĥޤǡ�</strong><br>
											<textarea name="hyoutou<?=$i?>" cols="32" rows="10"><? disp_sel($question->hyoutou) ?></textarea></td>
										</tr>
										<tr>
											<td align="left" colspan=2>
												<table class="honbun2">
													<tr>
														<td>
															<table border=1 cellspacing=0 cellpadding=3 width=100% frame="box" class="honbun2">
																<tr>
																	<td width="50%" align="center"></td>
																	<td width="50%" align="center">ɽƬ</td>
																</tr>
																<tr>
																	<td align="center">ɽ¦</td>
																	<td>																	</td>
																</tr>
															</table>														</td>
														<td>
															<input type="button" value="�ץ�ӥ塼" onclick="onclick_pre_matrix(<?=$i?>)">
															��ɽ¦�ˤϡּ�����ܡס�ɽƬ�ˤϡ������פ����ꤷ�Ƥ���������														</td>
													</tr>
												</table>											</td>
										</tr>
									</table>
									<div><input type="checkbox" name="dup_flag<?=$i?>" <?=value_checked('t', $question->dup_flag)?>>�����ν�ʣ������ʤ�</div>								</td>
							</tr>
							<tr id="na1<?=$i?>">
								<td bgcolor="#ffeecc">
									<div>�������������ɽ������ʸ��������ѣ���ʸ�����١�</div>
									<textarea name="pre_text<?=$i?>" cols="50" rows="3"><?=htmlspecialchars($question->pre_text)?></textarea>
								</td>
							</tr>
							<tr id="na2<?=$i?>">
								<td bgcolor="#ffeecc">
									<div>��������θ��ɽ������ʸ��������ѣ���ʸ�����١�</div>
									<textarea name="post_text<?=$i?>" cols="50" rows="3"><?=htmlspecialchars($question->post_text)?></textarea>
								</td>
							</tr>
							<tr></tr>
<?
	$qno++;
}

if ($main)
	$max_enq = MAX_PRO_ENQ;
else
	$max_enq = MAX_PRO_FINDING;

if ($qno <= $max_enq) {
?>
							<tr>
								<td colspan="2" bgcolor="#ffeecc">
									<input type="button" value="��������ɲá�" onclick="add_question()">								</td>
							</tr>
<?
}
?>
						</table>					</td>
				</tr>
			</table>
			<br>
<table width="90%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td align="right" class="footer_text"><a href="#top">���־�آ�</a><a name="bottom"></a></td>
  </tr>
</table>

			<br>
			<input type="button" value="�ץ�ӥ塼" onclick="preview()">
			<input type="button" value="��������" onclick="update()">
			<input type="button" value="����롡" onclick="location.href='pe_make.php?pro_enq_no=<?=$pro_enq_no?>'">
			<input type="hidden" name="next_action">
			<input type="hidden" name="pro_enq_no" <?=value($pro_enq_no)?>>
			<input type="hidden" name="delete_qno">
			<input type="hidden" name="move_from">
			<input type="hidden" name="move_to">
			<input type="hidden" name="main" <?=value($main)?>>
			</form>

		</td>
	</tr>
</table>

<form method="post" name="form2" action="mp_pre_matrix.php" target="pre_matrix">
<input type="hidden" name="hyousoku">
<input type="hidden" name="hyoutou">
</form>

</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer () ?>
