<?php
/**
 * �ҤȤӤȎ�net ���̥ե�����
 *
 * �ڡ����ӥ塼��Ϣ
 *
 *
 * @package
 * @author
 * @version
 */

// �������ڡ����ӥ塼�����󥿼���
function get_new_pv($log_flag = true) {
	$rec['pvc_log_flag'] = sql_number($log_flag ? 1 : 0);
	db_insert('c_page_view', $rec);
	return get_current_seq('c_page_view', 'pvc_pv_id');
}
?>