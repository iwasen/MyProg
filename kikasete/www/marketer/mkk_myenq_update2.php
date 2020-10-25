<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_enquete.php");
include("$inc/check.php");
include("$inc/image.php");
$inc = "$top/inc";
include("$inc/mkk_mypage.php");
include("$inc/mkk_header.php");
include("$inc/mk_error.php");

// ��������
function get_sel($text) {
	$ary = array();
	$ary2 = explode("\n", $text);
	$i = 1;
	foreach ($ary2 as $sel) {
		$sel = trim($sel);
		if ($sel != '')
			$ary[$i++] = $sel;
	}
	return $ary;
}

// ���å�������
if (isset($my_enq_no)) {
	$myenq = &$_SESSION['ss_my_enq'];
	if ($myenq->my_enq_no != $my_enq_no)
		redirect("mkk_myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('mkk_myenq.php');

$enquete = &$myenq->enquete;

// �ե����फ�饯�饹������
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	if (isset($_POST["qno$i"])) {
		$question = &$enquete->question[$i];

		$question->question_text = $_POST["question_text$i"];
		$question->question_type = $_POST["question_type$i"];

		if ($question->question_type == 1 || $question->question_type == 2)
			$question->fa_flag = $_POST["fa_flag$i"];
		else
			$question->fa_flag = '';

		switch ($_POST["image_type$i"]) {
		case 1:
			$question->url = '';
			$question->image_id = 0;
			break;
		case 2:
			$question->url = $_POST["url$i"];
			$question->image_id = 0;
			break;
		case 3:
			$question->url = '';
			$question->image_id = save_image($question->image_id, $_FILES["image$i"]);
			break;
		}

		$question->must_flag = $_POST["must_flag$i"];
		$question->dup_flag = $_POST["dup_flag$i"];

		$question->sel_text = get_sel($_POST["sel_text$i"]);
		$question->hyousoku = get_sel($_POST["hyousoku$i"]);
		$question->hyoutou = get_sel($_POST["hyoutou$i"]);

		// NA�������ʸ����
		$question->pre_text = $_POST["pre_text$i"];
		$question->post_text = $_POST["post_text$i"];

		// �ե꡼���������
		$last_no = end(array_keys($question->sel_text));
		if ($question->fa_flag == DBTRUE)
			$question->fa_sno = $last_no;

		// ��¾�����
		$question->ex_sno = 0;
		if ($_POST["ex_flag$i"]) {
			$question->ex_sno = $last_no + 1;
			$question->sel_text[$last_no + 1] = $_POST["ex_sel$i"];
		}
	}
}

// �ץ�ӥ塼����
switch ($next_action) {
case 'add_question':	// �����ɲ�
	$enquete->max_question_no++;
	redirect("mkk_myenq_edit2.php?edit=1&new_question={$enquete->max_question_no}");
	break;
case 'delete_question':	// ������
	$enquete->question[$delete_qno]->delete_flag = true;
	redirect("mkk_myenq_edit2.php?edit=1");
	break;
case 'move_question':	// �����ư
	unset($question);
	$question = array();
	$j = 1;
	for ($i = 1; $i <= $enquete->max_question_no; $i++) {
		if ($i != $move_from) {
			if ($j == $move_to)
				$question[$j++] = $enquete->question[$move_from];
			$question[$j++] = $enquete->question[$i];
		}
	}
	if ($j == $move_to)
		$question[$j] = $enquete->question[$move_from];
	$enquete->question = $question;
	redirect('mkk_myenq_edit2.php?edit=1');
	break;
case 'preview';				// �ץ�ӥ塼
	redirect('mkk_myenq_webprev.php?popup=1');
	break;
}

// ʬ�������԰��֤�����
$myenq->adjust_question_no();

// ��������å�
$exist = false;
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];

	if (!$question->delete_flag) {
		$qno = '��' . mb_convert_kana($i, 'N');

		if ($question->question_text == '') {
			switch ($question->question_type) {
			case 1:
			case 2:
			case 7:
				if (count($question->sel_text) != 0)
					$msg[] = "{$qno}�μ���ʸ�����Ϥ���Ƥ��ʤ��褦�Ǥ���";
				break;
			case 4:
			case 5:
				if (count($question->hyousoku) != 0 || count($question->hyoutou) != 0)
					$msg[] = "{$qno}�μ���ʸ�����Ϥ���Ƥ��ʤ��褦�Ǥ���";
				break;
			}
			continue;
		}

		if ($question->question_text != '') {
			switch ($_POST["image_type$i"]) {
			case 2:
				if ($question->url == '')
					$msg[] = "{$qno}�β���URL�����Ϥ���Ƥ��ʤ��褦�Ǥ���";
				elseif (!check_url($question->url))
					$msg[] = "{$qno}�β���URL�����������Ϥ���Ƥ��ʤ��褦�Ǥ���";
				break;
			case 3:
				if ($question->image_id == '')
					$msg[] = "{$qno}����������������Ϥ���Ƥ��ʤ��褦�Ǥ���";
				break;
			}
		}

		switch ($question->question_type) {
		case 1:
		case 2:
		case 7:
			if (count($question->sel_text) == 0)
				$msg[] = "{$qno}������褬���Ϥ���Ƥ��ʤ��褦�Ǥ���";
			else {
				$sel_count = count($question->sel_text);
				if ($question->fa_sno)
					$sel_count--;
				if ($question->ex_sno)
					$sel_count--;

				if ($sel_count > 20)
					$msg[] = "{$qno}��������" . (MAX_SEL - 12) . "�İ���ˤ��Ƥ���������";
			}
			break;
		case 4:
		case 5:
			if (count($question->hyousoku) == 0)
				$msg[] = "{$qno}��ɽ¦�����Ϥ���Ƥ��ʤ��褦�Ǥ���";
			elseif (count($question->hyousoku) > 10)
				$msg[] = "{$qno}��ɽ¦��10�İ���ˤ��Ƥ���������";

			if (count($question->hyoutou) == 0)
				$msg[] = "{$qno}��ɽƬ�����Ϥ���Ƥ��ʤ��褦�Ǥ���";
			elseif (count($question->hyoutou) > 10)
				$msg[] = "{$qno}��ɽƬ��10�İ���ˤ��Ƥ���������";
			break;
		}

		$exist = true;
	}
}

if (!$exist)
	$msg[] = '���󥱡��Ȥμ��䤬�ҤȤĤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if (!$msg) {
	// DB����¸
	$myenq->save_enquete = true;
	$myenq->write_db();
	$my_enq_no = $myenq->my_enq_no;

	// ʬ�������¸
	$enquete->write_branch_cond();

	// ���å�����ѿ����
	unset($_SESSION['ss_my_enq']);
}
?>
<? marketer_header('My���󥱡���', PG_NULL) ?>
<table width="789" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<table width="788" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
	<tr>
		<td><img src="images/common/spacer.gif" width="1" height="10" border="0"></td>
	</tr>
</table>
<?
if (!$msg) {
?>
<table border="0" cellspacing="0" cellpadding="0" bgcolor="ffffff">
	<tr>
		<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
		<td width="778" align="center" valign="top">
			<table border="0" cellspacing="0" cellpadding="0" width="778">
				<tr>
					<td width="10"><img src="images/common/spacer.gif" width="10" border="0"></td>
					<td width="768" valign="top"><img src="images/t_mk_myenq2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>My���󥱡��ȤΥ��󥱡��Ȥ����ꤷ�ޤ�����</td>
				</tr>
			</table>
			<br>
			<a href="mkk_myenq_make.php?my_enq_no=<?=$my_enq_no?>"><img src="images/mkk_bt/back.gif" alt="���" name="image2" width="108" height="31" border="0"></a>

			</form>
			</div>
		</td>
	</tr>
</table>
<? } else{?>
	<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg, 'mkk_myenq_edit2.php?edit=1');?></td>
  </tr>
</table>

<? }?></td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>

<? marketer_footer() ?>
