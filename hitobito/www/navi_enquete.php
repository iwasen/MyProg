<?php
/**
 * �ҤȤӤȎ�net �桼���ڡ���
 *
 * �ʥӥ��󥱡��ȥ���å�����
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
include("$inc/const.php");

// ��mojavi�Υ��å��������
function setSessionMojavi($attr, $val) {
	session_name('Mojavi');
	session_start();
	$_SESSION['org/mojavi/user/User/attributes']['org/mojavi'][$attr] = $val;
}

// �桼���ڡ���URL����
$user_page_url = get_const('user_page_url');

// ���󥱡���ID��������ֹ����
$enquete_id = $_GET['id'];
$option_no = $_GET['op'];

if (is_numeric($enquete_id)) {
	// �ʥӥڡ���ID�ȥ��󥱡��ȤΥ��ơ����������
	$sql = "SELECT nen_navi_page_id,nen_status FROM t_navi_enquete WHERE nen_enquete_id=$enquete_id";
	$result = db_exec($sql);
	if (pg_num_rows($result)) {
		$fetch = pg_fetch_object($result, 0);
		$navi_page_id = $fetch->nen_navi_page_id;
		$status = $fetch->nen_status;

		if (is_numeric($option_no) && $status == 2) {
			// �»���ʤ�������ֹ椬¸�ߤ��뤫�����å�
			$sql = "SELECT COUNT(*) FROM t_navi_enq_option WHERE neo_enquete_id=$enquete_id AND neo_option_no=$option_no";
			if (db_fetch1($sql)) {
				setSessionMojavi('enquete_answer_flag', true);
				redirect("{$user_page_url}index.php?module=Enquete&action=AnswerEnquete&navipage_id=$navi_page_id&enquete_id=$enquete_id&option_no=$option_no");
			}
		}

		// �»��椫��λ�ʤ��̤�ɽ��
		if ($status == 2 || $status == 3)
			redirect("{$user_page_url}index.php?module=Enquete&action=ShowResult&navipage_id=$navi_page_id&enquete_id=$enquete_id");
	}
}

// ���顼���ϥ桼���ڡ����ȥåפ�
redirect($user_page_url);
?>