<?php

$top = '../..';
$inc = "$top/../inc";
include("$inc/login_check.php");
include("$inc/header.php");
include("$inc/common.php");
include("$inc/database.php");
include("$inc/decode.php");
include("$inc/list.php");
include("$inc/csv.php");

// �������ݡ��Ƚ���
$enquete_id = $_GET['enquete_id'];

$list = new list_class('');
$csv = new csv_class("member_enquete.csv");

// �����
$sql = "SELECT count(req_question_no) FROM t_room_enq_question WHERE req_enquete_id=$enquete_id GROUP BY req_enquete_id";
$quest_num = db_fetch1($sql);

// CSV�إå�
$sql = "SELECT req_question_no, req_type, req_free_flag FROM t_room_enq_question WHERE req_enquete_id=$enquete_id ORDER BY req_question_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);

$list->set_header('���С�ID');
$list->set_header('�᡼�륢�ɥ쥹');
$list->set_header('�˥å��͡���');
$list->set_header('�ҤȤӤȡ�net��Ͽ����');
$list->set_header('�磻�磻�����Ͽ����');

$list->set_header('���ơ�����');

$list->set_header('��̾�ʴ�����');
$list->set_header('��̾�ʤ��ʡ�');
$list->set_header('����');
$list->set_header('��ǯ����');
$list->set_header('͹���ֹ�');
$list->set_header('��ƻ�ܸ�');
$list->set_header('����ʻ�Į¼�ʲ��ι��ܡ�');
$list->set_header('�����ֹ�');
$list->set_header('���ʾҲ�');
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_header('����' . $fetch->req_question_no . '-' . decode_type($fetch->req_type));
	if ($fetch->req_free_flag == 1)
		$list->set_header('����' . $fetch->req_question_no . '-(FA)');
}

// CSV�ܥǥ�
$sql = "SELECT *"
	. " FROM l_room_enq_answer"
	. " LEFT JOIN t_room_enquete ON rel_enquete_id=ren_enquete_id"
	. " LEFT JOIN t_room_enq_question ON rel_enquete_id=req_enquete_id AND rel_question_no=req_question_no"
	. " LEFT JOIN t_member ON rel_member_id=mbr_member_id"
	. " LEFT JOIN t_room_member ON rmm_room_id=ren_room_id AND mbr_member_id=rmm_member_id"
	. " WHERE rel_enquete_id=$enquete_id"
	. " ORDER BY rel_date, rel_question_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);

for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$options = $fetch->rel_options;
	$text = $fetch->rel_free_text;
	$flag = $fetch->rel_free_flag;

	$tmp['option'] = $fetch->rel_options;
	$tmp['text'] = $fetch->rel_free_text;
	$tmp['type'] = $fetch->req_type;
	$tmp['flag'] = $fetch->req_free_flag;

	$ans_ary[] = $tmp;

	if (($i + 1) % $quest_num == 0) {
		$list->set_data($fetch->mbr_member_id);
		$list->set_data($fetch->mbr_mail_addr);
		$list->set_data($fetch->rmm_nickname);
		$list->set_data($fetch->mbr_regist_date);
		$list->set_data($fetch->rmm_regist_date);

		$list->set_data(decode_member_ml_status($fetch->rmm_status));
		$list->set_data($fetch->mbr_name);
		$list->set_data($fetch->mbr_name_kana);
		$list->set_data(decode_sex_cd($fetch->mbr_sex_cd));
		$list->set_data($fetch->mbr_birthday);
		$list->set_data(decode_zip_cd($fetch->mbr_zip_cd));
		$list->set_data(decode_address1($fetch->mbr_address1));
		$list->set_data($fetch->mbr_address2);
		$list->set_data($fetch->mbr_tel);
		$list->set_data($fetch->mbr_self_introduction);
		foreach ($ans_ary as $ans) {
			if ($ans['type'] != 3) {
				if ($ans['flag'] == 1) {
					$list->set_data($ans['option']);
					$list->set_data($ans['text']);
				} else {
					$list->set_data($ans['option']);
				}
			} else {
				$list->set_data($ans['text']);
			}
		}

		$ans_ary = array();
	}
}

$csv->export_data($list);
exit;

// ���䥿���ץǥ�����
function decode_type($code) {
	switch($code) {
	case 1:
		return 'SA';
	case 2:
		return 'MA';
	case 3:
		return 'FA';
	}
}
?>