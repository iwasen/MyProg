<?
/******************************************************
' System :�������ơ�net������������ѥڡ���
' Content:���󥱡��Ȳ�������CSV����
'******************************************************/

$top = '../../..';
$inc = "$top/inc";
include("$inc/login_check.php");
$inc = "$top/../inc";
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/format.php");
include("$inc/csv.php");

// �褯�Ԥ�����ӥ˼���
function get_conveni($monitor_id) {
	$sql = "SELECT cv_name"
			. " FROM m_conveni JOIN t_conveni ON cv_conveni_cd=dc_conveni_cd"
			. " WHERE dc_monitor_id=$monitor_id AND cv_status=0"
			. " ORDER BY cv_order";
	return get_item_name($sql);
}

// �褯�Ԥ������ѡ�����
function get_super($monitor_id) {
	$sql = "SELECT sp_name"
			. " FROM m_super JOIN t_super ON sp_super_cd=ds_super_cd"
			. " WHERE ds_monitor_id=$monitor_id AND sp_status=0"
			. " ORDER BY sp_order";
	return get_item_name($sql);
}

// ��̣���������
function get_genre($monitor_id) {
	$sql = "SELECT kg_name"
			. " FROM m_genre JOIN t_genre ON kg_genre_cd=dk_genre_cd"
			. " WHERE dk_monitor_id=$monitor_id AND kg_status=0"
			. " ORDER BY kg_order";
	return get_item_name($sql);
}

// ����̾����
function get_item_name($sql) {
	$item = array();
	$result = db_exec($sql);
	$nrow = pg_numrows($result);
	for ($i = 0; $i < $nrow; $i++) {
		$fetch = pg_fetch_row($result, $i);
		$item[] = $fetch[0];
	}
	return join(',', $item);
}

if ($job_id == '')
	redirect('index.php');

// CSV�ǡ�������
$csv = '��������Ϣ��,�ɣ�,����,̤����,ǯ��,�ｻ�ϰ�,����,�ȼ�,����,��̳���ϰ�,�褯�Ԥ�����ӥ�,�褯�Ԥ������ѡ�,��̣�Ԥ�������,��������';

// ���󥱡���ID����
$sql = "SELECT jb_enquete_id FROM t_job WHERE jb_job_id=$job_id";
$enquete_id = db_fetch1($sql);

$sql = "SELECT eq_question_no,eq_question_type,eq_fa_flag,es_sel_no"
		. " FROM t_enq_question"
		. " LEFT JOIN t_enq_select ON es_enquete_id=$enquete_id AND es_question_no=eq_question_no AND eq_question_type=3"
		. " WHERE eq_enquete_id=$enquete_id"
		. " ORDER BY eq_question_no,es_sel_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	if ($fetch->eq_question_type == 3 && $fetch->es_sel_no != '')
		set_csv($csv, "����$fetch->eq_question_no-$fetch->es_sel_no");
	else
		set_csv($csv, "����$fetch->eq_question_no");

	if (($fetch->eq_question_type == 1 || $fetch->eq_question_type == 2) && $fetch->eq_fa_flag == 't')
		set_csv($csv, "����$fetch->eq_question_no-(FA)");
}

$filename = "job_recv_$job_id.csv";
prepare_csv($filename);
output_csv($csv);

// ���󥱡��ȳ�����������
$sql = "SELECT en_start_date FROM t_enquete WHERE en_enquete_id=$enquete_id";
$start_date = "'" . format_date(db_fetch1($sql)) . "'";

$sql = "SELECT mn_monitor_id,mn_sex,DATE_PART('Y',AGE($start_date,mn_birthday)) AS mn_age,mn_mikikon,m_area1.ar_area_name AS jitaku_area_name,sg_shokugyou_name,gs_gyoushu_name,ss_shokushu_name,m_area2.ar_area_name AS kinmu_area_name,an_date,eq_question_no,eq_question_type,eq_fa_flag,as_sel_no,as_free_answer"
		. " FROM t_answer JOIN t_monitor ON mn_monitor_id=an_monitor_id"
		. " LEFT JOIN m_area AS m_area1 ON m_area1.ar_area_cd=mn_jitaku_area"
		. " LEFT JOIN m_area AS m_area2 ON m_area2.ar_area_cd=mn_kinmu_area"
		. " LEFT JOIN m_shokugyou ON sg_shokugyou_cd=mn_shokugyou_cd"
		. " LEFT JOIN m_gyoushu ON gs_gyoushu_cd=mn_gyoushu_cd"
		. " LEFT JOIN m_shokushu ON ss_shokushu_cd=mn_shokushu_cd"
		. " JOIN t_enq_question ON eq_enquete_id=an_enquete_id"
		. " LEFT JOIN t_enq_select ON es_enquete_id=$enquete_id AND es_question_no=eq_question_no"
		. " LEFT JOIN t_ans_select ON as_enquete_id=$enquete_id AND as_question_no=eq_question_no AND (as_sel_no=es_sel_no OR as_sel_no=0) AND as_monitor_id=an_monitor_id"
		. " WHERE an_enquete_id=$enquete_id"
		. " ORDER BY an_date,mn_monitor_id,eq_question_no,es_sel_no";

$result = db_exec($sql);
$nrow = pg_numrows($result);
$no = 0;
for ($i = 0; $i < $nrow; ) {
	$fetch = pg_fetch_object($result, $i);

	$csv = '';
	set_csv($csv, ++$no);
	set_csv($csv, $fetch->mn_monitor_id);
	set_csv($csv, decode_sex($fetch->mn_sex));
	set_csv($csv, decode_mikikon($fetch->mn_mikikon));
	set_csv($csv, $fetch->mn_age);
	set_csv($csv, $fetch->jitaku_area_name);
	set_csv($csv, $fetch->sg_shokugyou_name);
	set_csv($csv, $fetch->gs_gyoushu_name);
	set_csv($csv, $fetch->ss_shokushu_name);
	set_csv($csv, $fetch->kinmu_area_name);
	set_csv($csv, get_conveni($fetch->mn_monitor_id));
	set_csv($csv, get_super($fetch->mn_monitor_id));
	set_csv($csv, get_genre($fetch->mn_monitor_id));
	set_csv($csv, format_datetime($fetch->an_date, '��̤������'));

	$monitor_id = $fetch->mn_monitor_id;
	while ($i < $nrow) {
		if ($monitor_id != $fetch->mn_monitor_id)
			break;

		$question_no = $fetch->eq_question_no;
		$answer = array();
		$answer_f = false;
		$fa = '';
		$fa_flag = $fetch->eq_fa_flag;
		while ($i < $nrow) {
			$fetch = pg_fetch_object($result, $i);

			if ($monitor_id != $fetch->mn_monitor_id || $question_no != $fetch->eq_question_no)
				break;

			if ($fetch->eq_question_type == 3)
				set_csv($csv, $fetch->as_free_answer);
			else {
				if ($fetch->as_sel_no) {
					if ($fetch->as_free_answer != '')
						$fa .= $fetch->as_free_answer;
					else {
						$answer[] = $fetch->as_sel_no;
					}
				}
				$answer_f = true;
			}

			$i++;
		}

		if ($answer_f)
			set_csv($csv, join(',', $answer));

		if ($fa_flag == 't')
			set_csv($csv, $fa);
	}

	output_csv($csv);
}
?>