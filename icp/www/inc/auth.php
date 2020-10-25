<?
/******************************************************
' System :ICP���̥��󥯥롼�ɥե�����
' Content:����
'******************************************************/

define('MAX_STAFF_AUTH', 10);
define('MAX_PARTICIPANT_AUTH', 10);
define('AUTH_ON', '1');
define('AUTH_OFF', '0');

// �����åշϸ���
$g_staff_auth = array(
	'�ץ������ȥ��д���' => 0,
	'����ִ���' => 1,
	'ȯ�����������ݡ���' => 2,
	'ȯ��Ͽ�������ݡ���' => 3,
	'���üԥ��Х������ݡ���' => 4,
	'ȯ�����ץ������ݡ���' => 5
);

// ���üԷϸ���
$g_participant_auth = array(
	'�ʲ��' => 0,
	'Webȯ��' => 1,
	'�᡼������' => 2,
	'�᡼�����' => 3,
	'ȯ��ON/OFF' => 4
);

// �����åշϸ��½����
function init_staff_auth() {
	return str_repeat(AUTH_OFF, MAX_STAFF_AUTH);
}

// ���üԷϸ��½����
function init_participant_auth() {
	return str_repeat('0', MAX_PARTICIPANT_AUTH);
}

// ���ڥ졼���Υ����åշϥǥե���ȸ���
function operator_staff_auth() {
	$auth = init_staff_auth();

	add_staff_auth('�ץ������ȥ��д���', $auth);
	add_staff_auth('����ִ���', $auth);
	add_staff_auth('ȯ�����������ݡ���', $auth);
	add_staff_auth('ȯ��Ͽ�������ݡ���', $auth);
	add_staff_auth('���üԥ��Х������ݡ���', $auth);
	add_staff_auth('ȯ�����ץ������ݡ���', $auth);

	return $auth;
}

// ���ڥ졼���λ��üԷϥǥե���ȸ���
function operator_participant_auth() {
	$auth = init_participant_auth();

	add_participant_auth('�ʲ��', $auth);
	add_participant_auth('�᡼�����', $auth);
	add_participant_auth('ȯ��ON/OFF', $auth);

	return $auth;
}

// �̾���ФΥ����åշϥǥե���ȸ���
function member_staff_auth() {
	$auth = init_staff_auth();

	return $auth;
}

// �̾���Фλ��üԷϥǥե���ȸ���
function member_participant_auth() {
	$auth = init_participant_auth();

	add_participant_auth('Webȯ��', $auth);
	add_participant_auth('�᡼������', $auth);
	add_participant_auth('�᡼�����', $auth);
	add_participant_auth('ȯ��ON/OFF', $auth);

	return $auth;
}

// �����åշϸ��¤�̵ͭ�����å�
function check_staff_auth($auth_key, $auth = null) {
	global $g_staff_auth;

	if ($auth == null)
		$auth = &$_SESSION[SCD]['staff_auth'];

	return $auth{$g_staff_auth[$auth_key]} == AUTH_ON;
}

// �����åշϸ����ɲ�
function add_staff_auth($auth_key, &$auth) {
	global $g_staff_auth;

	$auth{$g_staff_auth[$auth_key]} = AUTH_ON;
}

// �����åշϸ��º��
function del_staff_auth($auth_key, &$auth) {
	global $g_staff_auth;

	$auth{$g_staff_auth[$auth_key]} = AUTH_OFF;
}

// ���üԷϸ��¤�̵ͭ�����å�
function check_participant_auth($auth_key, $auth = null) {
	global $g_participant_auth;

	if ($auth == null)
		$auth = &$_SESSION[SCD]['participant_auth'];

	return $auth{$g_participant_auth[$auth_key]} == AUTH_ON;
}

// ���üԷϸ����ɲ�
function add_participant_auth($auth_key, &$auth) {
	global $g_participant_auth;

	$auth{$g_participant_auth[$auth_key]} = AUTH_ON;
}

// ���üԷϸ��º��
function del_participant_auth($auth_key, &$auth) {
	global $g_participant_auth;

	$auth{$g_participant_auth[$auth_key]} = AUTH_OFF;
}

// ����޶��ڤ�Υ����åո��¥����ɤ����Խ�
function str_staff_auth($str) {
	$auth = init_staff_auth();

	$ary = explode(',', $str);
	foreach ($ary as $auth_cd) {
		if ($auth_cd !== '')
			$auth{$auth_cd} = AUTH_ON;
	}

	return $auth;
}

// ����޶��ڤ�λ��üԸ��¥����ɤ����Խ�
function str_participant_auth($str) {
	$auth = init_participant_auth();

	$ary = explode(',', $str);
	foreach ($ary as $auth_cd) {
		if ($auth_cd !== '')
			$auth{$auth_cd} = AUTH_ON;
	}

	return $auth;
}

// �����åո���SQL���ʸ
function cond_staff_auth($auth_key) {
	global $g_staff_auth;

	return "substr(pm_staff_auth," . ($g_staff_auth[$auth_key] + 1) . ",1)='" . AUTH_ON . "'";
}

// ���üԸ���SQL���ʸ
function cond_participant_auth($auth_key) {
	global $g_participant_auth;

	return "substr(jm_participant_auth," . ($g_participant_auth[$auth_key] + 1) . ",1)='" . AUTH_ON . "'";
}
?>