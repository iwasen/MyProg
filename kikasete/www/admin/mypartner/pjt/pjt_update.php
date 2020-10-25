<?
/******************************************************
' System :�������ơ�net��̳�ɡ�������������ѥڡ���
' Content:My�ѡ��ȥʡ�����ļ����󹹿�
'******************************************************/

$top = '../..';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/define.php");
include("$inc/database.php");
include("$inc/enquete.php");
include("$inc/search.php");
include("$inc/my_partner.php");
include("$inc/check.php");
include("$inc/mail.php");
include("$inc/get_form.php");
$inc = "$top/inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/error_msg.php");

// ����������Ȱ�����Ͽ���Υ᡼������
function send_notify_mail(&$myp) {
	$owner_id = $_POST['marketer_id'];
	$enquete = &$myp->enquete;

	$sql = "SELECT mr_mail_addr,mr_name1,mr_name2,mr_agent_id FROM t_marketer WHERE mr_marketer_id=$owner_id";
	$result = db_exec($sql);
	if (pg_numrows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$mail_addr = $fetch->mr_mail_addr;
		$name1 = $fetch->mr_name1;
		$name2 = $fetch->mr_name2;
		$agent_id = $fetch->mr_agent_id;
	}

	get_mail_template('myp_pjt', $subject, $from, $cc, $bcc, $repty_to, $body);

	$body = str_replace('%REG_DATE%', date('Y/m/d H:i'), $body);
	$body = str_replace('%MARKETER_NAME%', "$name1 $name2", $body);
	$body = str_replace('%MAIL_ADDR%', $mail_addr, $body);
	$body = str_replace('%PJT_ID%', $myp->pjt_id, $body);
	$body = str_replace('%PJT_NAME%', $myp->room_name, $body);
	$body = str_replace('%START_DATE%', "$myp->recruit_start_y/$myp->recruit_start_m/$myp->recruit_start_d", $body);
	$body = str_replace('%END_DATE%', "$myp->room_end_y/$myp->room_end_m/$myp->room_end_d", $body);

	// ����������Ȱ�������
	if ($agent_id) {
		$sql = "SELECT ag_mail_addr,ag_bcc_addr FROM t_agent WHERE ag_agent_id=$agent_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			if ($fetch->ag_bcc_addr != '') {
				if ($bcc != '')
					$bcc .= ',';
				$bcc .= $fetch->ag_bcc_addr;
			}

			send_mail($subject, $fetch->ag_mail_addr, $from, $body, $cc, $bcc, $reply_to);
		}
	}
}

//�ᥤ�����
set_global2('mypartner', 'My�ѡ��ȥʡ�����', 'My�ѡ��ȥʡ�����ļ����󹹿�', BACK_TOP);

// My�ѡ��ȥʡ����饹����
if (!isset($_SESSION['s_my_partner']))
	redirect('pjt_list.php');
$myp = &$_SESSION['s_my_partner'];

// �ե����फ�饯�饹������
$myp->room_name = $room_name;
$myp->purpose = $purpose;
$myp->policy = $policy;
$myp->member_num = get_number($member_num);
$myp->recruit_start_y = get_number($recruit_start_y);
$myp->recruit_start_m = get_number($recruit_start_m);
$myp->recruit_start_d = get_number($recruit_start_d);
$myp->recruit_start_h = get_number($recruit_start_h);
$myp->recruit_end_y = get_number($recruit_end_y);
$myp->recruit_end_m = get_number($recruit_end_m);
$myp->recruit_end_d = get_number($recruit_end_d);
$myp->recruit_end_h = get_number($recruit_end_h);
$myp->decision_date_y = get_number($decision_date_y);
$myp->decision_date_m = get_number($decision_date_m);
$myp->decision_date_d = get_number($decision_date_d);
$myp->room_start_y = get_number($room_start_y);
$myp->room_start_m = get_number($room_start_m);
$myp->room_start_d = get_number($room_start_d);
$myp->room_end_y = get_number($room_end_y);
$myp->room_end_m = get_number($room_end_m);
$myp->room_end_d = get_number($room_end_d);

// ���þ��μ���
$seq_no = 1;
$myp->sanka_jouken = array();
for ($i = 1; $i <= $myp->sanka_jouken_num; $i++) {
	if ($_POST["sanka_jouken$i"] != '') {
		$myp->sanka_jouken[$seq_no] = $_POST["sanka_jouken$i"];
		$seq_no++;
	}
}

// �ꥯ�롼�Ⱦ��μ���
$seq_no = 1;
$myp->recruit_jouken = array();
for ($i = 1; $i <= $myp->recruit_jouken_num; $i++) {
	if ($_POST["recruit_jouken$i"] != '') {
		$myp->recruit_jouken[$seq_no] = $_POST["recruit_jouken$i"];
		$seq_no++;
	}
}

// ���þ���ɲý���
if ($next_action == 'add_sanka_jouken') {
	$myp->sanka_jouken_num++;
	redirect('pjt_new2.php?edit=1');
}

// �ꥯ�롼�Ⱦ���ɲý���
if ($next_action == 'add_recruit_jouken') {
	$myp->recruit_jouken_num++;
	redirect('pjt_new2.php?edit=1');
}

if ($next_action != 'calc_date') {
	// ��ļ�̾�����å�
	if ($myp->room_name == '')
		$msg[] = '��ļ�̾�����Ϥ���Ƥ��ʤ��褦�Ǥ���';

	// ��Ū�����å�
	if ($myp->purpose == '')
		$msg[] = '��Ū�����Ϥ���Ƥ��ʤ��褦�Ǥ���';

	// ���þ�����ϥ����å�
//	if (count($myp->sanka_jouken) == 0)
//		$msg[] = '���þ��򣱤İʾ����Ϥ��Ƥ���������';

	// �ꥯ�롼�Ⱦ�����ϥ����å�
	if (count($myp->recruit_jouken) == 0)
		$msg[] = '�ꥯ�롼�Ⱦ��򣱤İʾ����Ϥ��Ƥ���������';

	// ��ļ����ĥݥꥷ�����ϥ����å�
	if ($myp->policy == '')
		$msg[] = '��ļ����ĥݥꥷ�������򤵤�Ƥ��ʤ��褦�Ǥ���';

	// ���С������ϥ����å�
	if ($myp->member_num == '')
		$msg[] = '���С��������Ϥ���Ƥ��ʤ��褦�Ǥ���';
	elseif (!check_num($myp->member_num, 1))
		$msg[] = '���С��������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
}

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
	} else {
		$recruit_start = mktime($myp->recruit_start_h, 0, 0, $myp->recruit_start_m, $myp->recruit_start_d, $myp->recruit_start_y);

		// �������ե����å�
		if ($recruit_start <= time())
			$msg[] = '�ꥯ�롼�ȴ��֤γ������˲������դ����Ϥ���Ƥ���褦�Ǥ���';
	}
}

if ($next_action != 'calc_date') {
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

	// ��������ǯ�����å�
	$decision_date_ok = true;
	if ($myp->decision_date_y == '') {
		$msg[] = '��������ǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$decision_date_ok = false;
	} elseif (!check_num($myp->decision_date_y, 2004, 2100)) {
		$msg[] = '��������ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$decision_date_ok = false;
	}

	// �������η�����å�
	if ($myp->decision_date_m == '') {
		$msg[] = '�������η���Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$decision_date_ok = false;
	} elseif (!check_num($myp->decision_date_m, 1, 12)) {
		$msg[] = '�������η���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$decision_date_ok = false;
	}

	// ���������������å�
	if ($myp->decision_date_d == '') {
		$msg[] = '���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$decision_date_ok = false;
	} elseif (!check_num($myp->decision_date_d, 1, 31)) {
		$msg[] = '���������������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$decision_date_ok = false;
	}

	// ��������ǯ�����å�
	if ($decision_date_ok) {
		if (!checkdate($myp->decision_date_m, $myp->decision_date_d, $myp->decision_date_y)) {
			$msg[] = '��������ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
			$decision_date_ok = false;
		} else {
			$decision_date = mktime(0, 0, 0, $myp->decision_date_m, $myp->decision_date_d, $myp->decision_date_y);

			// �������ե����å�
			if ($decision_date <= time())
				$msg[] = '�������˲������դ����Ϥ���Ƥ���褦�Ǥ���';
		}
	}

	// �ꥯ�롼�Ƚ�λ�ȷ������δط������å�
	if ($recruit_end && $decision_date) {
		if ($recruit_end > ($decision_date + 24 * 60 * 60))
			$msg[] = '�������ϥꥯ�롼�ȴ��֤ν�λ����������դ����Ϥ��Ƥ���������';
	}

	// ��ļ��»ܴ��֤γ���ǯ�����å�
	$room_start_ok = true;
	if ($myp->room_start_y == '') {
		$msg[] = '��ļ��»ܴ��֤γ���ǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_start_ok = false;
	} elseif (!check_num($myp->room_start_y, 2004, 2100)) {
		$msg[] = '��ļ��»ܴ��֤γ���ǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_start_ok = false;
	}

	// ��ļ��»ܴ��֤γ��Ϸ�����å�
	if ($myp->room_start_m == '') {
		$msg[] = '��ļ��»ܴ��֤γ��Ϸ���Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_start_ok = false;
	} elseif (!check_num($myp->room_start_m, 1, 12)) {
		$msg[] = '��ļ��»ܴ��֤γ��Ϸ���������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_start_ok = false;
	}

	// ��ļ��»ܴ��֤γ����������å�
	if ($myp->room_start_d == '') {
		$msg[] = '��ļ��»ܴ��֤γ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_start_ok = false;
	} elseif (!check_num($myp->room_start_d, 1, 31)) {
		$msg[] = '��ļ��»ܴ��֤γ����������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_start_ok = false;
	}

	// ��ļ��»ܴ��֤γ���ǯ���������������å�
	if ($room_start_ok) {
		if (!checkdate($myp->room_start_m, $myp->room_start_d, $myp->room_start_y)) {
			$msg[] = '��ļ��»ܴ��֤γ���ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
			$room_start_ok = false;
		} else {
			$room_start = mktime(0, 0, 0, $myp->room_start_m, $myp->room_start_d, $myp->room_start_y);

			// �������ե����å�
			if ($room_start <= time())
				$msg[] = '��ļ��»ܴ��֤γ������˲������դ����Ϥ���Ƥ���褦�Ǥ���';
		}
	}

	// ��ļ��»ܴ��֤ν�λǯ�����å�
	$room_end_ok = true;
	if ($myp->room_end_y == '') {
		$msg[] = '��ļ��»ܴ��֤ν�λǯ�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_end_ok = false;
	} elseif (!check_num($myp->room_end_y, 2004, 2100)) {
		$msg[] = '��ļ��»ܴ��֤ν�λǯ�����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_end_ok = false;
	}

	// ��ļ��»ܴ��֤ν�λ������å�
	if ($myp->room_end_m == '') {
		$msg[] = '��ļ��»ܴ��֤ν�λ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_end_ok = false;
	} elseif (!check_num($myp->room_end_m, 1, 12)) {
		$msg[] = '��ļ��»ܴ��֤ν�λ����������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_end_ok = false;
	}

	// ��ļ��»ܴ��֤ν�λ�������å�
	if ($myp->room_end_d == '') {
		$msg[] = '��ļ��»ܴ��֤ν�λ�������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_end_ok = false;
	} elseif (!check_num($myp->room_end_d, 1, 31)) {
		$msg[] = '��ļ��»ܴ��֤ν�λ�������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
		$room_end_ok = false;
	}

	// ��ļ��»ܴ��֤ν�λǯ���������������å�
	if ($room_end_ok) {
		if (!checkdate($myp->room_end_m, $myp->room_end_d, $myp->room_end_y)) {
			$msg[] = '��ļ��»ܴ��֤�ǯ���������������Ϥ���Ƥ��ʤ��褦�Ǥ���';
			$room_end_ok = false;
		} else
			$room_end = mktime(0, 0, 0, $myp->room_end_m, $myp->room_end_d, $myp->room_end_y);
	}

	// ��ļ��»ܴ��֤γ��ϤȽ�λǯ�����δط������å�
	if ($room_start && $room_end) {
		if ($room_start >= $room_end)
			$msg[] = '��ļ��»ܴ��֤ν�λ���ϳ�������������դ����Ϥ��Ƥ���������';
	}

	// �������Ȳ�ļ��»ܳ��Ϥδط������å�
	if ($decision_date && $room_start) {
		if ($decision_date > $room_start)
			$msg[] = '��ļ��»ܴ��֤γ������Ϸ�������������դ����Ϥ��Ƥ���������';
	}
}

if (!$msg) {
	// ���դμ�ư�׻�����
	if ($next_action == 'calc_date') {
		calc_date($recruit_start, &$recruit_end, &$decision_date, &$room_start);
		$myp->recruit_end_y = date('Y', $recruit_end);
		$myp->recruit_end_m = date('n', $recruit_end);
		$myp->recruit_end_d = date('j', $recruit_end);
		$myp->recruit_end_h = date('G', $recruit_end);
		$myp->decision_date_y = date('Y', $decision_date);
		$myp->decision_date_m = date('n', $decision_date);
		$myp->decision_date_d = date('j', $decision_date);
		$myp->room_start_y = date('Y', $room_start);
		$myp->room_start_m = date('n', $room_start);
		$myp->room_start_d = date('j', $room_start);
		$myp->room_end_y = date('Y', $room_start);

		redirect('pjt_new2.php?edit=1');
	}

	$pjt_id = $myp->pjt_id;

	// DB����¸
	$myp->recruit_flag |= 0x01;
	$myp->save_sanka_jouken = true;
	$myp->save_recruit_jouken = true;
	$myp->write_db();

	// ����������Ȥ���Ͽ���Υ᡼������
	if (!$pjt_id)
		send_notify_mail($myp);

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
			<p class="msg">My�ѡ��ȥʡ�����Ͽ���ޤ�����</p>
			<p><input type="button" value="����롡" onclick="location.href='pjt_list.php'"></p>
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
