<?
/******************************************************
' System :�������ơ�net��̳���ѥڡ���
' Content:�ͣ����󥱡����ѹ�����
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/decode.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");

// �����ɽ��
function disp_sel(&$sel, $ex_sno = 0) {
	if (is_array($sel)) {
		foreach ($sel as $sno => $text) {
			if ($sno != $ex_sno)
				echo "$text\n";
		}
	}
}

set_global('myenquete', '�ͣ����󥱡��ȴ���', '�ͣ����󥱡��Ⱦ����ѹ�', BACK_TOP);

// ���å�������
if ($edit) {
	$myenq = &$_SESSION['ss_my_enq'];
	$my_enq_no = $myenq->my_enq_no;
	$marketer_id = $myenq->marketer_id;
} else {
	$_SESSION['ss_my_enq'] = new my_enquete_class;
	$myenq = &$_SESSION['ss_my_enq'];
	$myenq->read_db($marketer_id, $my_enq_no);
}

$enquete = &$myenq->enquete;

if ($enquete->title == '')
	$title = "��{$myenq->room_name}�ٻ��ü��罸";
else
	$title = $enquete->title;

if ($enquete->max_question_no == 0)
	$enquete->max_question_no = 1;
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
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
	window.open("<?=$top?>/../common/move_question.php?ss=ss_my_enq&move_from=" + i, "move_question", "width=" + width + ",height=" + height + ",left=" + left + ",top=" + top);
}
window.onload = function() {
  onload_body();
}
//-->
</script>
</head>
<body onload="onload_body()">
<? page_header() ?>

<div align="center">
<form method="post" name="form1" action="update2.php" enctype="multipart/form-data">
<table border=0 cellspacing=2 cellpadding=3 width="80%">
	<tr>
		<td class="m0">�����󥱡��Ȥ�����</td>
	</tr>
	<tr>
		<td>
			<table border=2 cellspacing=0 cellpadding=3 width="100%" frame="box" id="question" bordercolorlight="#ffffff" bordercolordark="#ffffff">
<?
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
					<td class="m1" width="15%" rowspan="8" id="q<?=$i?>" height="100%">
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
					<td class="n1">
						<div>������ʸ</div>
						<textarea rows="5" cols="70" name="question_text<?=$i?>"><?=htmlspecialchars($question->question_text)?></textarea>
					</td>
				</tr>
				<tr>
					<td class="n1">
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
?>
					</td>
				</tr>
				<tr>
					<td class="n1">
						<select name="question_type<?=$i?>" onchange="sel_question_type(<?=$i?>)">
							<option <?=value_selected('1', $question->question_type)?>><?=decode_question_type(1)?>
							<option <?=value_selected('7', $question->question_type)?>><?=decode_question_type(7)?>
							<option <?=value_selected('2', $question->question_type)?>><?=decode_question_type(2)?>
							<option <?=value_selected('8', $question->question_type)?>><?=decode_question_type(8)?>
							<option <?=value_selected('3', $question->question_type)?>><?=decode_question_type(3)?>
							<option <?=value_selected('6', $question->question_type)?>><?=decode_question_type(6)?>
							<option <?=value_selected('4', $question->question_type)?>><?=decode_question_type(4)?>
							<option <?=value_selected('5', $question->question_type)?>><?=decode_question_type(5)?>
						</select>
					</td>
				</tr>
				<tr id="sel<?=$i?>">
					<td class="n1">
						<strong>������<?=MAX_SEL - 2?>�Ĥޤ� ���ե꡼��������¾������ä������32�Ĥޤǡ�</strong><br>
						<textarea name="sel_text<?=$i?>" cols="50" rows="10"><? disp_sel($question->sel_text, $question->ex_sno) ?></textarea>
						<div id="faf<?=$i?>"><input type="checkbox" name="fa_flag<?=$i?>" <?=value_checked('t', $question->fa_flag)?>>�Ǹ�������ǥե꡼���������</div>
						<div id="exf<?=$i?>"><input type="checkbox" name="ex_flag<?=$i?>" <?=value_checked(true, $question->ex_sno != 0)?>>��¾�����������ˤĤ���ʡ֤�����ˤʤ��ס��äˤʤ��פʤɡ�<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��¾������ʸ���� <input type="text" name="ex_sel<?=$i?>" <?=value($question->sel_text[$question->ex_sno])?> size=50></div>
					</td>
				</tr>
				<tr id="mtx<?=$i?>">
					<td class="n1">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>ɽ¦��10�Ĥޤǡ�<br>
								<textarea name="hyousoku<?=$i?>" cols="32" rows="10"><? disp_sel($question->hyousoku) ?></textarea></td>
								<td>&nbsp;&nbsp;</td>
								<td>ɽƬ��10�Ĥޤǡ�<br>
								<textarea name="hyoutou<?=$i?>" cols="32" rows="10"><? disp_sel($question->hyoutou) ?></textarea></td>
							</tr>
						</table>
						<div><input type="checkbox" name="dup_flag<?=$i?>" <?=value_checked('t', $question->dup_flag)?>>�����ν�ʣ������ʤ�</div>
					</td>
				</tr>
				<tr id="na1<?=$i?>">
					<td class="n1">
						<div>�������������ɽ������ʸ��������ѣ���ʸ�����١�</div>
						<textarea name="pre_text<?=$i?>" cols="50" rows="3"><?=htmlspecialchars($question->pre_text)?></textarea>
					</td>
				</tr>
				<tr id="na2<?=$i?>">
					<td class="n1">
						<div>��������θ��ɽ������ʸ��������ѣ���ʸ�����١�</div>
						<textarea name="post_text<?=$i?>" cols="50" rows="3"><?=htmlspecialchars($question->post_text)?></textarea>
					</td>
				</tr>
				<tr></tr>
<?
	$qno++;
}

if ($qno <= MAX_ENQ) {
?>
				<tr>
					<td colspan="2" class="n1">
						<input type="button" value="��������ɲá�" onclick="add_question()">
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
<input type="button" value="��������" onclick="update()">
<input type="button" value="����롡" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
<input type="hidden" name="next_action">
<input type="hidden" name="marketer_id" <?=value($marketer_id)?>>
<input type="hidden" name="my_enq_no" <?=value($my_enq_no)?>>
<input type="hidden" name="delete_qno">
<input type="hidden" name="move_from">
<input type="hidden" name="move_to">
</form>

<? page_footer() ?>
</body>
</html>
