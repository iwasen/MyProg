<?
/******************************************************
' System :ICP�ץ������ȥڡ���
' Content:���С��������ݡ���
'******************************************************/

$top = "..";
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/auth.php");
include("$inc/csv.php");
include("$inc/format.php");

$inc = "$top/inc";
include("$inc/pjt_common.php");
include("$inc/login_check.php");

// ���ϥѥ�᡼��
$job_id = $_POST['job_id'];

// �ץ�������CD�������CD�����
$sql = "SELECT pj_pjt_cd,jb_job_cd"
		. " FROM t_job"
		. " JOIN t_pjt ON pj_pjt_id=jb_pjt_id"
		. " WHERE jb_job_id=$job_id";
$result = db_exec($sql);
$fetch = pg_fetch_object($result, 0);

// ���ϥե�����̾
prepare_csv("member_{$fetch->pj_pjt_cd}_{$fetch->jb_job_cd}.csv");

// �إå�����
$csv = '�᡼�륢�ɥ쥹,password,�ȿ�ID,�ȿ����С�ID,�˥å��͡���,��,̾,����,�ᥤ,���̥�����,������,̤����,����,�ȼ�,����,͹���ֹ�,����,�����ֹ�,�ʲ��,Webȯ��,�᡼������,�᡼�����,ȯ��ON/OFF,�ץ������ȥ��д���,����ִ���,ȯ�����������ݡ���,ȯ��Ͽ�������ݡ���,���üԥ��Х������ݡ���,ȯ�����ץ������ݡ���';
output_csv($csv);

// ���С��������
$sql = "SELECT pm_mail_addr,pm_password,pm_org_id,pm_org_member_id,COALESCE(jm_nickname,pm_nickname) AS pm_nickname,pm_name1,pm_name2,pm_name1_kana,pm_name2_kana,pm_sex_cd,to_char(pm_birthday, 'YYYY/MM/DD') AS pm_birthday,pm_mikikon_cd,pm_shokugyou,pm_gyoushu,pm_gyoushu,pm_shokushu,pm_zip_cd,pm_address,pm_tel_no,pm_staff_auth,jm_participant_auth"
		. " FROM t_job_member"
		. " JOIN t_pjt_member ON pm_member_id=jm_member_id"
		. " WHERE jm_job_id=$job_id AND jm_status=1"
		. " ORDER BY pm_member_id";
$result = db_exec($sql);
$nrow = pg_num_rows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	set_csv($csv, $fetch->pm_mail_addr);
	set_csv($csv, $fetch->pm_password);
	set_csv($csv, $fetch->pm_org_id);
	set_csv($csv, $fetch->pm_org_member_id);
	set_csv($csv, $fetch->pm_nickname);
	set_csv($csv, $fetch->pm_name1);
	set_csv($csv, $fetch->pm_name2);
	set_csv($csv, $fetch->pm_name1_kana);
	set_csv($csv, $fetch->pm_name2_kana);
	set_csv($csv, $fetch->pm_sex_cd);
	set_csv($csv, $fetch->pm_birthday);
	set_csv($csv, $fetch->pm_mikikon_cd);
	set_csv($csv, $fetch->pm_shokugyou);
	set_csv($csv, $fetch->pm_gyoushu);
	set_csv($csv, $fetch->pm_shokushu);
	set_csv($csv, $fetch->pm_zip_cd);
	set_csv($csv, $fetch->pm_address);
	set_csv($csv, $fetch->pm_tel_no);

	set_csv($csv, check_participant_auth('�ʲ��', $fetch->jm_participant_auth) ? '1' : '0');
	set_csv($csv, check_participant_auth('Webȯ��', $fetch->jm_participant_auth) ? '1' : '0');
	set_csv($csv, check_participant_auth('�᡼������', $fetch->jm_participant_auth) ? '1' : '0');
	set_csv($csv, check_participant_auth('�᡼�����', $fetch->jm_participant_auth) ? '1' : '0');
	set_csv($csv, check_participant_auth('ȯ��ON/OFF', $fetch->jm_participant_auth) ? '1' : '0');

	set_csv($csv, check_staff_auth('�ץ������ȥ��д���', $fetch->pm_staff_auth) ? '1' : '0');
	set_csv($csv, check_staff_auth('����ִ���', $fetch->pm_staff_auth) ? '1' : '0');
	set_csv($csv, check_staff_auth('ȯ�����������ݡ���', $fetch->pm_staff_auth) ? '1' : '0');
	set_csv($csv, check_staff_auth('ȯ��Ͽ�������ݡ���', $fetch->pm_staff_auth) ? '1' : '0');
	set_csv($csv, check_staff_auth('���üԥ��Х������ݡ���', $fetch->pm_staff_auth) ? '1' : '0');
	set_csv($csv, check_staff_auth('ȯ�����ץ������ݡ���', $fetch->pm_staff_auth) ? '1' : '0');

	output_csv($csv);
}

exit;
?>