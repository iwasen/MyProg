<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:���̴ؿ�
'******************************************************/

// �ѥ�����ץ������ȥ����ɼ���
function get_pjt_cd() {
	global $top;

	$ary = explode('/', $_SERVER['PHP_SELF']);
	return $ary[count($ary) - substr_count($top, '..') - 2];
}

// �˥å��͡������
function get_nickname($remark_type, $job_id, $member_id) {
	// �˥å��͡�������ʳ��ϥ᡼��ξ��Ϻ��п�̾�������
	if ($remark_type == 2)
		$sql = "SELECT jb_start_from_name FROM t_job WHERE jb_job_id=$job_id";
	else
		$sql = "SELECT COALESCE(jm_nickname,pm_nickname) AS pm_nickname FROM t_job_member JOIN t_pjt_member ON pm_member_id=jm_member_id WHERE jm_job_id=$job_id AND jm_member_id=$member_id";

	return db_fetch1($sql);
}

define('SCD', 'r' . get_pjt_cd());
?>