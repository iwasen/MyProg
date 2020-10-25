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
include("$inc/check.php");
include("$inc/image.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

set_global('myenquete', '�ͣ����󥱡��ȴ���', '�ͣ����󥱡��Ⱦ����ѹ�', BACK_TOP);

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
$myenq = &$_SESSION['ss_my_enq'];
if ($myenq->my_enq_no != $my_enq_no)
	redirect("show.php?marketer_id=$marketer_id&my_enq_no=$my_enq_no");

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
	redirect("edit2.php?marketer_id=$marketer_id&edit=1&new_question={$enquete->max_question_no}");
	break;
case 'delete_question':	// ������
	$enquete->question[$delete_qno]->delete_flag = true;
	redirect("edit2.php?marketer_id=$marketer_id&edit=1");
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
	redirect('edit2.php?marketer_id=$marketer_id&edit=1');
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

				if ($sel_count > MAX_SEL - 2)
					$msg[] = "{$qno}��������" . (MAX_SEL - 2) . "�İ���ˤ��Ƥ���������";
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
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
</head>
<body>
<? page_header() ?>

<br>
<?
if (!$msg) {
?>
<div align="center">
<form>
<p>�ͣ����󥱡��ȤΥ��󥱡��Ȥ����ꤷ�ޤ�����</p>
<input type="button" value="����롡" onclick="location.href='show.php?marketer_id=<?=$marketer_id?>&my_enq_no=<?=$my_enq_no?>'">
</form>
</div>
<?
} else
    error_msg($msg, 'edit2.php?edit=1');
?>
<? page_footer() ?>
</body>
</html>
