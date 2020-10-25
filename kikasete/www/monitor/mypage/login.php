<?
$top = './..';
$inc = "$top/../inc";
include("$inc/login_log.php");
$inc = "$top/inc";
$img = "$top/image";
include("$inc/mn_header.php");
include("$inc/mn_regist.php");
include("$inc/mn_chara.php");

// ���å����˥�˥�ID�ȥݥ���������¸
function set_session($monitor_id, $type, $chara_name) {
	session_start();
	$_SESSION['ss_monitor_id'] = $monitor_id;
	$_SESSION['ss_monitor_type'] = $type;

	if ($chara_name == '')
		$chara_name = '�ݤ������';
	$_SESSION['ss_chara_name'] = $chara_name;
	$_SESSION['ss_chara_image'] = get_chara_image($monitor_id);
}
if (isset($mail_addr)) {
	// �᡼�륢�ɥ쥹�򥯥å�������¸
	setcookie('ck_monitor_mail_addr', $mail_addr, COOKIE_EXPIRE, '/');
	$_COOKIE['ck_monitor_mail_addr'] = $mail_addr;

	if ($mail_addr == '') {
		$msg[] = '�᡼�륢�ɥ쥹�����Ϥ���Ƥ��ʤ��褦�Ǥ���';
		redirect("login_error.php");
	}
	if ($password == '') {
		$msg[] = '�ѥ���ɤ����Ϥ���Ƥ��ʤ��褦�Ǥ���';
		redirect("login_error.php");
	}
} else {
	$msg[] = '�����󤷤Ƥ��ʤ������ޤ��ϥ����󤬥����ॢ���Ȥ��ޤ������⤦���٥����󤷤Ƥ���������';
	redirect("login_error.php");
}

if (!$msg) {
	$sql = "SELECT mn_monitor_id,mn_type,mn_password,mp_chara_name"
			. " FROM t_monitor LEFT JOIN t_monitor_point ON mn_monitor_id=mp_monitor_id"
			. " WHERE mn_mail_addr_low=" . sql_char(strtolower($mail_addr)) . " AND mn_status<>9";
	$result = db_exec($sql);
	if (pg_numrows($result) == 0) {
		$msg[] = '��Ͽ����λ���Ƥ��ʤ��������ɥ쥹���ְ�äƤ��붲�줬����ޤ����⤦���٤��Τ���ξ塢���Ϥ��Ƥ���������';
		redirect("login_error.php");
	} else {
		$fetch = pg_fetch_object($result, 0);
		if ($fetch->mn_password != $password) {
			$msg[] = '�ѥ���ɤ��ְ�äƤ��붲�줬����ޤ����⤦���٤��Τ���ξ塢���Ϥ��Ƥ���������';
			redirect("login_error.php");
		} else {
			set_session($fetch->mn_monitor_id, $fetch->mn_type, $fetch->mp_chara_name);
			login_log(1, $mail_addr, $password, $fetch->mn_monitor_id);
			if ($url == '')
				$url = 'm_index.php';
			redirect($ssl ? https_url($url) : http_url($url));
		}
	}
}

login_log(1, $mail_addr, $password, null);
?>