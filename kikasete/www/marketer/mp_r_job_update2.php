<?
$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
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

// My�ѡ��ȥʡ����饹����
$myp = &$_SESSION['s_my_partner'];
$enquete = &$myp->enquete;
$pjt_id = $myp->pjt_id;
if (!$pjt_id)
	redirect('mp_pjt_list.php');

// �ե����फ�饯�饹������
$enquete->title = $title;

for ($i = 1; $i <= $enquete->max_question_no; $i++) {
	$question = &$enquete->question[$i];

	$question->question_text = $_POST["question_text$i"];
	$question->question_type = $_POST["question_type$i"];
	$question->fa_flag = $_POST["fa_flag$i"];

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
	redirect('mp_r_job_edit2.php?edit=1');
}

// �ץ�ӥ塼����
if ($next_action == 'preview') {
	redirect('mp_preveiw_web.php');
}

// ���󥱡��ȥ����ȥ�����å�
if ($enquete->title == '')
	$msg[] = '���󥱡��ȥ����ȥ뤬���Ϥ���Ƥ��ʤ��褦�Ǥ���';

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
		elseif (count($question->sel_text) > 21)
			$msg[] = "{$qno}��������21�İ���ˤ��Ƥ���������";
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
	$myp->save_enquete = true;
	$myp->recruit_flag |= 0x02;
	$myp->write_db();

	// ���å�����ѿ����
	unset($_SESSION['s_my_partner']);
}
?>
<? marketer_header('My�ѡ��ȥʡ�', PG_NULL) ?>
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
					<td width="768" valign="top"><img src="images/t_mk_mymonitor2.gif" width="538" height="20"></td>
				</tr>
			</table>
			<!-- my�ѡ��ȥʡ� -->
			<div align="center">
			<form>
			<table border=0 cellspacing=2 cellpadding=3 width="95%">
				<tr>
					<td>�ꥯ�롼�ȥ��󥱡������Ƥ������λ���ޤ�����</td>
				</tr>
			</table>
			<br>

<a href="mp_r_job.php?pjt_id=<?=$pjt_id?>"><img src="images/mkk_bt/back.gif" alt="���" name="image2" width="108" height="31" border="0"></a>
			</form>
			</div>
		</td>
	</tr>
</table>
<? } else{?>
<table width="788" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? error_msg($msg);?></td>
  </tr>
</table>
<? }?>
</td>
<td width="1" bgcolor="#dbdbdb"><img src="images/common/spacer.gif" width="1" height="17" border="0"></td>
  </tr>
</table>
<? marketer_footer() ?>
