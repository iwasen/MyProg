<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ����ꥯ�롼�ȥ���ֹ���
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/format.php");
include("$inc/check.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/get_form.php");
include("$inc/image.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

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

// �ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ����ꥯ�롼�ȥ���ֹ���', BACK_TOP);

// My�ѡ��ȥʡ����饹����
$myp = &$_SESSION['s_my_partner'];
$pjt_id = $myp->pjt_id;
$enquete = &$myp->enquete;
$search = &$myp->search;

// �ե�����ʼ»ܳ��סˤ��饯�饹������
if ($add) {
	$myp->add_send_date_y = get_number($add_send_date_y);
	$myp->add_send_date_m = get_number($add_send_date_m);
	$myp->add_send_date_d = get_number($add_send_date_d);
	$myp->add_send_date_h = get_number($add_send_date_h);
} else {
	$myp->recruit_start_y = get_number($recruit_start_y);
	$myp->recruit_start_m = get_number($recruit_start_m);
	$myp->recruit_start_d = get_number($recruit_start_d);
	$myp->recruit_start_h = get_number($recruit_start_h);
	$enquete->point = get_number($point);
}
$myp->recruit_end_y = get_number($recruit_end_y);
$myp->recruit_end_m = get_number($recruit_end_m);
$myp->recruit_end_d = get_number($recruit_end_d);
$myp->recruit_end_h = get_number($recruit_end_h);
$myp->send_num = get_number($send_num);

// �ե�����ʼ��������ˤ��饯�饹������
$enquete->title = $title;

if (!$add) {
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
}

// �ե�����ʾ�������ˤ��饯�饹������
$search->type = $_POST['type'];
$search->sex = (count($_POST['sex']) == 1) ? $_POST['sex'][0] : '';
$myp->age_type = $_POST['age_type'];
if ($myp->age_type == '1') {
	$search->age_cd = get_multi_data($_POST['age_cd']);
	$search->age_from = '';
	$search->age_to = '';
} elseif ($myp->age_type == '2') {
	$search->age_cd = '';
	$search->age_from = $_POST['age_from'];
	$search->age_to = $_POST['age_to'];
} else {
	$search->age_cd = '';
	$search->age_from = '';
	$search->age_to = '';
}
$search->mikikon = (count($_POST['mikikon']) == 1) ? $_POST['mikikon'][0] : '';
$search->jitaku_area = get_multi_data($_POST['jitaku_area']);
$search->shokugyou = get_multi_data($_POST['shokugyou']);
$search->have_child = get_multi_data($_POST['have_child']);
$search->housing_form = get_multi_data($_POST['housing_form']);
$search->have_car = get_multi_data($_POST['have_car']);
$search->genre = get_multi_data($_POST['genre']);
$search->conveni = get_multi_data($_POST['conveni']);
$search->super = get_multi_data($_POST['super']);
$search->ma_profile = get_multi_data($_POST['ma_profile']);

// �����ɲý���
if ($next_action == 'add_question') {
	$enquete->max_question_no++;
	redirect('r_job_edit.php?edit=1');
}

// Web�ץ�ӥ塼����
if ($next_action == 'web_preview')
	redirect('preveiw_web.php');

// �᡼��ץ�ӥ塼����
if ($next_action == 'mail_preview')
	redirect('preveiw_mail.php');

// �ףţ²��̤μ�ư����
if ($next_action == 'web_edit')
	redirect('edit_web.php?edit=1');

// �᡼��ʸ�̤μ�ư����
if ($next_action == 'mail_edit')
	redirect('edit_mail.php?edit=1');

// �ꥯ�롼�ȴ��֤γ���ǯ�����å�
$recruit_start_ok = true;
if ($myp->recruit_start_y == '') {
	$msg[] = '�ꥯ�롼�ȴ��֤γ���ǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_y, 2004, 2100)) {
	$msg[] = '�ꥯ�롼�ȴ��֤γ���ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_start_ok = false;
}

// �ꥯ�롼�ȴ��֤γ��Ϸ�����å�
if ($myp->recruit_start_m == '') {
	$msg[] = '�ꥯ�롼�ȴ��֤γ��Ϸ���Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_m, 1, 12)) {
	$msg[] = '�ꥯ�롼�ȴ��֤γ��Ϸ���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_start_ok = false;
}

// �ꥯ�롼�ȴ��֤γ����������å�
if ($myp->recruit_start_d == '') {
	$msg[] = '�ꥯ�롼�ȴ��֤γ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_d, 1, 31)) {
	$msg[] = '�ꥯ�롼�ȴ��֤γ����������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_start_ok = false;
}

// �ꥯ�롼�ȴ��֤γ��ϻ��֥����å�
if ($myp->recruit_start_h == '') {
	$msg[] = '�ꥯ�롼�ȴ��֤γ��ϻ��֤����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_start_ok = false;
} elseif (!check_num($myp->recruit_start_h, 0, 23)) {
	$msg[] = '�ꥯ�롼�ȴ��֤γ��ϻ��֤����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_start_ok = false;
}

// �ꥯ�롼�ȴ��֤γ���ǯ���������������å�
if ($recruit_start_ok) {
	if (!checkdate($myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_y)) {
		$msg[] = '�ꥯ�롼�ȴ��֤γ���ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$recruit_start_ok = false;
	} else
		$recruit_start = mktime($myp->recruit_start_h, 0, 0, $myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_y);
}

if ($add) {
	// �ɲ�ȯ������������ǯ�����å�
	$add_send_date_ok = true;
	if ($myp->add_send_date_y == '') {
		$msg[] = '�ɲ�ȯ������������ǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$add_send_date_ok = false;
	} elseif (!check_num($myp->add_send_date_y, 2004, 2100)) {
		$msg[] = '�ɲ�ȯ������������ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$add_send_date_ok = false;
	}

	// �ɲ�ȯ�����������η�����å�
	if ($myp->add_send_date_m == '') {
		$msg[] = '�ɲ�ȯ�����������η���Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$add_send_date_ok = false;
	} elseif (!check_num($myp->add_send_date_m, 1, 12)) {
		$msg[] = '�ɲ�ȯ�����������η���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$add_send_date_ok = false;
	}

	// �ɲ�ȯ�������������������å�
	if ($myp->add_send_date_d == '') {
		$msg[] = '�ɲ�ȯ�������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$add_send_date_ok = false;
	} elseif (!check_num($myp->add_send_date_d, 1, 31)) {
		$msg[] = '�ɲ�ȯ�������������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$add_send_date_ok = false;
	}

	// �ɲ�ȯ�����������λ��֥����å�
	if ($myp->add_send_date_h == '') {
		$msg[] = '�ɲ�ȯ�����������λ��֤����Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$add_send_date_ok = false;
	} elseif (!check_num($myp->add_send_date_h, 0, 23)) {
		$msg[] = '�ɲ�ȯ�����������λ��֤����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$add_send_date_ok = false;
	}

	// �ɲ�ȯ������������ǯ���������������å�
	if ($add_send_date_ok) {
		if (!checkdate($myp->add_send_date_m, $myp->add_send_date_d, $myp->add_send_date_y)) {
			$msg[] = '�ɲ�ȯ������������ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
			$add_send_date_ok = false;
		} else
			$add_send_date = mktime($myp->add_send_date_h, 0, 0, $myp->add_send_date_m, $myp->add_send_date_d, $myp->add_send_date_y);
	}
}

// �ꥯ�롼�ȴ��֤ν�λǯ�����å�
$recruit_end_ok = true;
if ($myp->recruit_end_y == '') {
	$msg[] = '�ꥯ�롼�ȴ��֤ν�λǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_y, 2004, 2100)) {
	$msg[] = '�ꥯ�롼�ȴ��֤ν�λǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_end_ok = false;
}

// �ꥯ�롼�ȴ��֤ν�λ������å�
if ($myp->recruit_end_m == '') {
	$msg[] = '�ꥯ�롼�ȴ��֤ν�λ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_m, 1, 12)) {
	$msg[] = '�ꥯ�롼�ȴ��֤ν�λ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_end_ok = false;
}

// �ꥯ�롼�ȴ��֤ν�λ�������å�
if ($myp->recruit_end_d == '') {
	$msg[] = '�ꥯ�롼�ȴ��֤ν�λ�������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_d, 1, 31)) {
	$msg[] = '�ꥯ�롼�ȴ��֤ν�λ�������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_end_ok = false;
}

// �ꥯ�롼�ȴ��֤ν�λ���֥����å�
if ($myp->recruit_end_h == '') {
	$msg[] = '�ꥯ�롼�ȴ��֤ν�λ���֤����Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_end_ok = false;
} elseif (!check_num($myp->recruit_end_h, 0, 23)) {
	$msg[] = '�ꥯ�롼�ȴ��֤ν�λ���֤����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	$recruit_end_ok = false;
}

// �ꥯ�롼�ȴ��֤ν�λǯ���������������å�
if ($recruit_end_ok) {
	if (!checkdate($myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_y)) {
		$msg[] = '�ꥯ�롼�ȴ��֤ν�λǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$recruit_end_ok = false;
	} else
		$recruit_end = mktime($myp->recruit_end_h, 0, 0, $myp->recruit_end_m, $myp->recruit_end_d, $myp->recruit_end_y);
}

// �ꥯ�롼�ȴ��֤γ��ϤȽ�λǯ�����δط������å�
if ($recruit_start && $recruit_end) {
	if ($recruit_start >= $recruit_end)
		$msg[] = '�ꥯ�롼�ȴ��֤ν�λ���ϳ�������������դ����Ϥ��Ƥ���������';
}

// �ɲ�ȯ�����������ȥꥯ�롼�ȴ��֤ν�λǯ�����δط������å�
if ($add) {
	if ($add_send_date && $recruit_end) {
		if ($add_send_date >= $recruit_end)
			$msg[] = '�ɲ�ȯ�����������ϥꥯ�롼�ȴ��֤ν�λ������������դ����Ϥ��Ƥ���������';
	}
}

// �ꥯ�롼�Ⱦ�����ϥ����å�
if (count($myp->recruit_jouken) == 0)
	$msg[] = '�ꥯ�롼�Ⱦ��򣱤İʾ����Ϥ��Ƥ���������';

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

// ���󥱡��ȥ����ȥ�����å�
if ($search->type == '')
	$msg[] = '�оݥ�˥��������򤵤�Ƥ��ʤ��褦�Ǥ���';

// �оݼԿ������å�
if (!$msg && $search->get_monitor_num() < $myp->send_num)
	$msg[] = '�оݥ�˥�����������ȯ�����������ʤ��褦�Ǥ���';

if (!$exist)
	$msg[] = '���󥱡��Ȥμ��䤬�ҤȤĤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';

if (!$msg) {
	// DB����¸
	$myp->save_enquete = true;
	$myp->save_search = true;
	$myp->recruit_flag |= 0x07;
	$myp->write_db();

	// ���å�����ѿ����
	unset($_SESSION['s_my_partner']);
}
?>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=EUC-JP">
<meta http-equiv="Pragma" content="no-cache">
<title><?=$g_title?></title>
<link rel="stylesheet" type="text/css" href="<?=$top?>/css/main.css">
<? menu_script() ?>
</head>
<body>
<? page_header() ?>
<? menu_start() ?>

<?
if (!$msg) {
?>
<table border=0 cellspacing=2 cellpadding=3 width=700>
	<tr>
		<td align="center" class="m4"><?=$g_title?></td>
	</tr>
	<tr>
		<td align="center"><br>
			<p class="msg">�ꥯ�롼�ȥ���֤򹹿����ޤ�����</p>
			<p><input type="button" value="����롡" onclick="location.href='<?=get_return_page("r_job.php?pjt_id=$pjt_id")?>'"></p>
		</td>
	</tr>
</table>
<?
} else
	error_msg($msg);
?>

<? menu_end() ?>
<? page_footer() ?>
</body>
</html>
