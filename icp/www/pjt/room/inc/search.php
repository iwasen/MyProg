<?
/******************************************************
' System :ICP²ñµÄ¼¼¥Ú¡¼¥¸
' Content:¸¡º÷½èÍı¥¯¥é¥¹
'******************************************************/

class search_class {
	var $from_y;
	var $from_m;
	var $from_d;
	var $to_y;
	var $to_m;
	var $to_d;

	var $subject;
	var $content;
	var $member;

	var $keyword;

	// ¥Õ¥©¡¼¥à¤«¤é¸¡º÷¾ò·ï¼èÆÀ
	function get_form() {
		$this->from_y = $_GET['search_from_y'];
		$this->from_m = $_GET['search_from_m'];
		$this->from_d = $_GET['search_from_d'];
		$this->to_y = $_GET['search_to_y'];
		$this->to_m = $_GET['search_to_m'];
		$this->to_d = $_GET['search_to_d'];
		$this->subject = $_GET['search_subject'];
		$this->content = $_GET['search_content'];
		$this->member = $_GET['search_member'];
		$this->keyword = $_GET['search_keyword'];
	}

	// WHERE¾ò·ïÀ¸À®
	function get_condition() {
		$and = array();

		if ($this->from_y != '')
			$and[] = "date_trunc('day', rm_date)>=" . sql_date("$this->from_y/$this->from_m/$this->from_d");

		if ($this->to_y != '')
			$and[] = "date_trunc('day', rm_date)<=" . sql_date("$this->to_y/$this->to_m/$this->to_d");

		$keyword_ary = explode(' ', $this->keyword);

		foreach ($keyword_ary as $keyword) {
			if ($keyword != '') {
				$or = array();

				if ($this->subject)
					$or[] = "rm_subject LIKE " . sql_char("%$keyword%");

				if ($this->content)
					$or[] = "rm_content LIKE " . sql_char("%$keyword%");

				if ($this->member)
					$or[] = "EXISTS (SELECT * FROM t_job_member JOIN t_pjt_member ON pm_member_id=jm_member_id WHERE jm_job_id=rm_job_id AND jm_member_id=rm_member_id AND COALESCE(jm_nickname,pm_nickname) LIKE " . sql_char("%$keyword%") . ")";

				$or = join(' OR ', $or);
				if ($or != '')
					$and[] = "($or)";
			}
		}

		return join(' AND ', $and);
	}
};
?>