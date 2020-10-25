<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:������ID�����ؿ�
'******************************************************/

// ������ID����
function create_login_id() {
	while (true) {
		// ID����
		$login_id = get_alha_chars(7) . get_num_chars(3);

		// ���̵֤꤬���������å�
		$sql = "SELECT COUNT(*) FROM t_user_login WHERE ulg_login_id='$login_id'";
		$result = db_exec($sql);
		if (pg_fetch_result($result, 0, 0) == 0)
			break;
	}

	return $login_id;
}

// �ѥ��������
function create_password() {
	return get_num_chars(3) . get_alha_chars(3);
}

// ��������
function init_rand() {
	global $g_init_rand;

	if (!$g_init_rand) {
		mt_srand((double)microtime() * 1000000);
		$g_init_rand = true;
	}
}

// �Ѿ�ʸ���Υ������ʸ���������
function get_alha_chars($n) {
	$chars = 'abcdefghjkmnprstwxyz';

	return get_rand_chars($chars, $n);
}

// �����Υ������ʸ���������
function get_num_chars($n) {
	$chars = '23456789';

	return get_rand_chars($chars, $n);
}

// �������ʸ���������
function get_rand_chars($chars, $n) {
	init_rand();

	$s = '';
	for ($i = 0; $i < $n; $i++)
		$s .= $chars{mt_rand(0, strlen($chars) - 1)};

	return $s;
}
?>