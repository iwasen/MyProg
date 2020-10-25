<?
/******************************************************
' System :ICP��ļ��ڡ���
' Content:ȯ��Ͽ��������ɼ¹�
'******************************************************/

$top = "../..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/csv.php");
include("$inc/format.php");
include("$inc/define.php");

$inc = "$top/room/inc";
include("$inc/room_common.php");
include("$inc/login_check.php");
include("$inc/record.php");

// ����åɽ���
function output_thread($job_id, $remark_id = 0, $parent_remark_id = 0, $depth = 0) {
	$sql = sprintf(
				"SELECT rm_remark_id,rm_seq_no,rm_date,rm_subject,rm_remark_type,rm_job_id,rm_member_id,rm_child_flag,rm_marking_id"
			. " FROM l_remark"
			. " WHERE rm_job_id=%s AND %s=%s AND rm_status=1 AND rm_disp_type=1"
			. " ORDER BY rm_remark_id",
			sql_number($job_id),
			$remark_id != 0 ? 'rm_remark_id' : 'rm_parent_remark_id',
			sql_number($remark_id != 0 ? $remark_id : $parent_remark_id));
	$result = db_exec($sql);
	$nrow = pg_num_rows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_object($result, $i);

		// �˥å��͡�������ʳ��ϥ᡼��ξ��Ϻ��п�̾�������
		$nickname = get_nickname($fetch->rm_remark_type, $fetch->rm_job_id, $fetch->rm_member_id);

		for ($j = 0; $j < $depth - 1; $j++)
			set_csv($csv, ' ');

		if ($depth > 0)
			set_csv($csv, '��' );

		set_csv($csv, "[$fetch->rm_seq_no]");
		set_csv($csv, $fetch->rm_subject);
		set_csv($csv, $nickname);
		set_csv($csv, format_datetime($fetch->rm_date));
		output_csv($csv);

		if ($fetch->rm_child_flag == DBTRUE)
			output_thread($job_id, 0, $fetch->rm_remark_id, $depth + 1);
	}
}

// ���ϥѥ�᡼��
$job_id = $_SESSION[SCD]['job_id'];
$remark_id = $_GET['remark_id'];

// �ץ�������̾�������̾������־ܺ٤����
$sql = "SELECT pj_pjt_cd,pj_name,jb_job_cd,jb_name"
		. " FROM t_job"
		. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
		. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

// ���ϥե�����̾
prepare_csv("record_{$fetch->pj_pjt_cd}_{$fetch->jb_job_cd}.csv");

// �ץ�������̾����
set_csv($csv, '���ץ�������̾');
set_csv($csv, $fetch->pj_name);
output_csv($csv);

// �����̾����
set_csv($csv, '�������̾');
set_csv($csv, $fetch->jb_name);
output_csv($csv);

// ����
output_csv($csv);

$sql = "SELECT rm_subject,rm_content FROM l_remark WHERE rm_remark_id=$remark_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

// �������
set_csv($csv, '������');
output_csv($csv);

// ��̾����
set_csv($csv, '��̾');
set_csv($csv, $fetch->rm_subject);
output_csv($csv);

// ��ʸ����
set_csv($csv, '��ʸ');
set_csv($csv, $fetch->rm_content);
output_csv($csv);

// ����
output_csv($csv);

// ����åɰ���
set_csv($csv, '������åɰ���');
output_csv($csv);
output_thread($job_id, $remark_id);

// ����
output_csv($csv);

// ȯ��Ͽ�ǡ���
set_csv($csv, '��ȯ��Ͽ�ǡ���');
output_csv($csv);

// �˥å��͡������
$nickname_ary = get_nickname_ary($job_id);
foreach ($nickname_ary as $nickname)
	set_csv($csv, $nickname);
output_csv($csv);

// ȯ��Ͽ�ǡ�������
$remark_ary = get_record_ary($job_id, $remark_id);
$member_id_ary = array_keys($nickname_ary);
foreach ($remark_ary as $remark) {
	// ��̾����
	foreach ($member_id_ary as $member_id) {
		if ($remark[$member_id]->seq_no)
			set_csv($csv, "[{$remark[$member_id]->seq_no}] {$remark[$member_id]->subject}");
		else
			set_csv($csv, '');
	}
	output_csv($csv);

	// ��ʸ����
	foreach ($member_id_ary as $member_id) {
		set_csv($csv, $remark[$member_id]->content);
	}
	output_csv($csv);
}

exit;
?>