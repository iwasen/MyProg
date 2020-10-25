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
include("$inc/mypage.php");
include("$inc/header2.php");
include("$inc/error.php");

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
		redirect("myenq_make.php?my_enq_no=$my_enq_no");
} else
	redirect('myenq.php');

$enquete = &$myenq->enquete;

// �ե����फ�饯�饹������
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];

	$question->question_text = $_POST["question_text$i"];
	$question->question_type = $_POST["question_type$i"];
	$question->fa_flag = ($question->question_type == 1 || $question->question_type == 2) ? $_POST["fa_flag$i"] : '';

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

	$question->dup_flag = $_POST["dup_flag$i"];

	$question->sel_text = get_sel($_POST["sel_text$i"]);
	$question->hyousoku = get_sel($_POST["hyousoku$i"]);
	$question->hyoutou = get_sel($_POST["hyoutou$i"]);
}

// �����ɲý���
if ($next_action == 'add_question') {
	$enquete->max_question_no++;
	redirect('myenq_edit2.php?edit=1');
}

// �ץ�ӥ塼����
if ($next_action == 'preview')
	redirect('myenq_webprev.php');

// ��������å�
$exist = false;
for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];
	$qno = '��' . mb_convert_kana($i, 'N');

	if ($question->question_text == '') {
		switch ($question->question_type) {
		case 1:
		case 2:
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
		if (count($question->sel_text) == 0)
			$msg[] = "{$qno}������褬���Ϥ���Ƥ��ʤ��褦�Ǥ���";
		elseif (count($question->sel_text) > 20)
			$msg[] = "{$qno}��������20�İ���ˤ��Ƥ���������";
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

if (!$exist)
	$msg[] = '���󥱡��Ȥμ��䤬�ҤȤĤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if (!$msg) {
	// DB����¸
	$myenq->save_enquete = true;
	$myenq->write_db();
	$my_enq_no = $myenq->my_enq_no;

	// ���å�����ѿ����
	unset($_SESSION['ss_my_enq']);
}
?>
<? staff_header('���󥱡�����Ͽ') ?>

<table width="720" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td><img src="images/shim.gif" width="1" height="10" border="0"></td>
	</tr>
</table>

<?
if (!$msg) {
?>
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10"><img src="images/shim.gif" width="10" border="0"></td>
		<td width="710" align="center" valign="top">
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td><font size="2" class="honbun2">���󥱡��ȤΥ��󥱡��Ȥ����ꤷ�ޤ�����</font></td>
				</tr>
			</table>
			<br>
			<input type="button" value="����롡" onclick="location.href='myenq_make.php?my_enq_no=<?=$my_enq_no?>'">
			</form>
			</div>
		</td>
	</tr>
</table>
<?
} else
	error_msg($msg);
?>

<? staff_footer() ?>
