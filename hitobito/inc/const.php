<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �������
 *
 *
 * @package
 * @author
 * @version
 */

// ���������
function get_const($key) {
	global $g_const;

	if (!isset($g_const[$key])) {
		$sql = "SELECT sys_value FROM m_system WHERE sys_key='$key'";
		$result = db_exec($sql);
		$g_const[$key] = pg_fetch_result($result, 0, 0);
	}

	return $g_const[$key];
}
?>