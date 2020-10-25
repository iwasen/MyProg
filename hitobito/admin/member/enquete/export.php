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

// エクスポート処理
$enquete_id = $_GET['enquete_id'];

$list = new list_class('');
$csv = new csv_class("member_enquete.csv");

// 設問数
$sql = "SELECT count(req_question_no) FROM t_room_enq_question WHERE req_enquete_id=$enquete_id GROUP BY req_enquete_id";
$quest_num = db_fetch1($sql);

// CSVヘッダ
$sql = "SELECT req_question_no, req_type, req_free_flag FROM t_room_enq_question WHERE req_enquete_id=$enquete_id ORDER BY req_question_no";
$result = db_exec($sql);
$nrow = pg_numrows($result);

$list->set_header('メンバーID');
$list->set_header('メールアドレス');
$list->set_header('ニックネーム');
$list->set_header('ひとびと・net登録日時');
$list->set_header('ワイワイ会議登録日時');

$list->set_header('ステータス');

$list->set_header('氏名（漢字）');
$list->set_header('氏名（かな）');
$list->set_header('性別');
$list->set_header('生年月日');
$list->set_header('郵便番号');
$list->set_header('都道府県');
$list->set_header('住所（市町村以下の項目）');
$list->set_header('電話番号');
$list->set_header('自己紹介');
for ($i = 0; $i < $nrow; $i++) {
	$fetch = pg_fetch_object($result, $i);

	$list->set_header('設問' . $fetch->req_question_no . '-' . decode_type($fetch->req_type));
	if ($fetch->req_free_flag == 1)
		$list->set_header('設問' . $fetch->req_question_no . '-(FA)');
}

// CSVボディ
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

// 設問タイプデコード
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