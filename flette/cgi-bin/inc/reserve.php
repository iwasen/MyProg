<?
/******************************************************
' System :fletteスタッフページ
' Content:予約情報クラス
'******************************************************/

class choice_class {
	var $room_id;

	var $room;
	var $date;
	var $start_time;
	var $end_time;
	var $sex;
};

class reserve_class {
	var $shop_id;
	var $course_id;
	var $year;
	var $month;

	var $seq;
	var $date;
	var $course;
	var $shop;
	var $choice;
	var $choice_num;

	var $name_1;
	var $name_2;
	var $kana_1;
	var $kana_2;
	var $mail_addr;
	var $mail_addr2;
	var $contact_flag;
	var $contact_mail;
	var $contact_keitai;
	var $contact_jitaku;
	var $contact_time;
	var $contact_time_memo;
//	var $birthday;
	var $counseling_id;
	var $change;
	var $comment;

	var $error;

	// コンストラクタ
	function reserve_class() {
		for ($i = 0; $i < MAX_CHOICE; $i++)
			$this->choice[$i] = new choice_class;

		$this->counseling_id = array();
	}
};
?>