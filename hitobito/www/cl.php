<?php
/**
 * �ҤȤӤȎ�net �桼���ڡ���
 *
 * ���С��罸�ѥ���å������󥿡�����
 *
 *
 * @package
 * @author
 * @version
 */

$top = '.';
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/crypt.php");

// �ѥ�᡼������
$code = trim($_SERVER['QUERY_STRING']);

if ($code != '') {
	$recruit_id = decrypt_id($code);
	if ($recruit_id) {
		// ������URL����
		$sql = sprintf("SELECT rct_jump_url FROM t_recruit_click WHERE rct_recruit_id=%s", sql_number($recruit_id));
		$jump_url = db_fetch1($sql);

		if ($jump_url != '') {
			db_begin_trans();

			// ����å���������ȥ��å�
			$rec['rcc_click_count'] = 'rcc_click_count+1';
			db_update('c_recruit_click', $rec, sprintf("rcc_recruit_id=%s", sql_number($recruit_id)));

			// ����å�����
			$rec['rcl_recruit_id'] = sql_number($recruit_id);
			$rec['rcl_ip_adr'] = sql_char($_SERVER['REMOTE_ADDR']);
			db_insert('l_recruit_click', $rec);

			db_commit_trans();

			// ���å�����ID�򥻥å�
			SetCookie('cookie_recruit_id', $recruit_id, 0, '/');

			redirect($jump_url);
		}
	}
}

// �ѥ�᡼���������ξ��
header('HTTP/1.0 404 Not Found');
exit;
?>