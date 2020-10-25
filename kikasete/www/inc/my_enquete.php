<?
/******************************************************
' System :きかせて・net共通
' Content:Myアンケート関連
'******************************************************/

include_once('mye_comm.php');

class my_enquete_class {
	var $enq_kind;
	var $marketer_id;
	var $my_enq_no;
	var $make_step;
	var $enquete_id;
	var $search_id;
	var $send_num;
	var $mail_title;
	var $mail_header;
	var $mail_contents;
	var $mail_footer;
	var $creator_id;
	var $ans_cond;
	var $ans_cond_count;
	var $age_type;
	var $notify_assistant;
	var $notify_mail_addr;

	var $enquete;
	var $search;
	var $cell;

	var $save_enquete;
	var $save_search;

	// 初期化処理
	function my_enquete_class() {
		$this->enq_kind = 'myenq';
		$this->enquete = new enquete_class;
		$this->search = new search_class;
		$this->cell = new cell_class;
		$this->enquete->enq_kind = 2;
		$this->enquete->start_date_h = 18;
		$this->ans_cond_count = 1;
	}

	// DBから読み込み
	function read_db($marketer_id, $my_enq_no) {
		if (!$marketer_id || !$my_enq_no)
			return;

		// 初期化
		$this->my_enquete_class();

		// Myアンケート情報取得
		$sql = "SELECT * FROM t_my_enquete WHERE me_marketer_id=$marketer_id AND me_my_enq_no=$my_enq_no";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$this->marketer_id = $fetch->me_marketer_id;
			$this->my_enq_no = $fetch->me_my_enq_no;
			$this->make_step = $fetch->me_make_step;
			$this->enquete_id = $fetch->me_enquete_id;
			$this->search_id = $fetch->me_search_id;
			$this->send_num = $fetch->me_send_num;
			$this->mail_title = $fetch->me_mail_title;
			$this->mail_header = $fetch->me_mail_header;
			$this->mail_contents = $fetch->me_mail_contents;
			$this->mail_footer = $fetch->me_mail_footer;
			$this->creator_id = $fetch->me_creator_id;
			$this->ans_cond = $fetch->me_ans_cond;
            $this->notify_assistant = $fetch->me_notify_assistant;
            $this->notify_mail_addr = $fetch->me_notify_mail_addr;

			$ans_cond_ary = explode("\n", $this->ans_cond);
			if (is_array($ans_cond_ary)) {
				for ($i = 0; $i < count($ans_cond_ary); $i++) {
					if ($ans_cond_ary[$i] == '')
						break;
				}
				if ($this->ans_cond_count != 0)
					$this->ans_cond_count = $i;
			}
		}

		// アンケート登録情報取得
		if ($this->enquete_id)
			$this->enquete->read_db($this->enquete_id);

		// 検索条件取得
		if ($this->search_id) {
			$this->search->read_db($this->search_id);
			$this->cell->read_db($this->search_id);
		}

		// 年齢指定方法
		if ($this->search->age_cd != '')
			$this->age_type = '1';
		elseif ($this->search->age_from != '' || $this->search->age_to != '')
			$this->age_type = '2';
		else
			$this->age_type = '';
	}

	// DBに書き込み
	function write_db() {
		db_begin_trans();

		// アンケート登録情報書き込み
		if ($this->save_enquete)
			$this->enquete_id = $this->enquete->write_db($this->enquete_id);

		// 検索条件書き込み
		if ($this->save_search) {
			$this->search_id = $this->search->write_db($this->search_id);
			$this->cell->write_db($this->search_id);
		}

		// Myアンケート情報書き込み
		if ($this->my_enq_no == '') {
			$sql = "SELECT MAX(me_my_enq_no) FROM t_my_enquete WHERE me_marketer_id=$this->marketer_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_row($result, 0);
				$this->my_enq_no = $fetch[0] + 1;
			} else
				$this->my_enq_no = 1;

			$sql = "INSERT INTO t_my_enquete (me_marketer_id,me_my_enq_no,me_make_step,me_enquete_id,me_search_id,me_send_num,me_mail_title,me_mail_header,me_mail_contents,me_mail_footer,me_creator_id,me_ans_cond,me_notify_assistant,me_notify_mail_addr) VALUES ($this->marketer_id,$this->my_enq_no,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		} else {
			$sql = "UPDATE t_my_enquete SET me_make_step=%s,me_enquete_id=%s,me_search_id=%s,me_send_num=%s,me_mail_title=%s,me_mail_header=%s,me_mail_contents=%s,me_mail_footer=%s,me_creator_id=%s,me_ans_cond=%s,me_notify_assistant=%s,me_notify_mail_addr=%s WHERE me_marketer_id=$this->marketer_id AND me_my_enq_no=$this->my_enq_no";
		}
		$sql = sprintf($sql,
				sql_number($this->make_step),
				sql_number($this->enquete_id),
				sql_number($this->search_id),
				sql_number($this->send_num),
				sql_char($this->mail_title),
				sql_char($this->mail_header),
				sql_char($this->mail_contents),
				sql_char($this->mail_footer),
				sql_number($this->creator_id),
				sql_char($this->ans_cond),
				sql_char($this->notify_assistant),
				sql_char($this->notify_mail_addr));

		db_exec($sql);

		db_commit_trans();
	}

	// 通知先メールアドレス取得
	function get_notify_mail_addr(&$to, &$cc) {
		$sql = "SELECT mr_mail_addr FROM t_marketer WHERE mr_marketer_id=$this->marketer_id";
        $result = db_exec($sql);
        if (pg_numrows($result)) {
            $fetch = pg_fetch_object($result, 0);
			$to = $fetch->mr_mail_addr;
		}


		$cc_ary = array();
		if ($this->notify_assistant) {
			$sql = "SELECT mr_mail_addr FROM t_marketer WHERE mr_marketer_id IN ($this->notify_assistant) ORDER BY mr_marketer_id";
	        $result = db_exec($sql);
	        $nrow = pg_numrows($result);
	        for ($i = 0; $i < $nrow; $i++) {
	            $fetch = pg_fetch_object($result, $i);
				$cc_ary[] = $fetch->mr_mail_addr;
			}
		}

		foreach (explode(',', $this->notify_mail_addr) as $notify_mail_addr) {
			if ($notify_mail_addr)
				$cc_ary[] = $notify_mail_addr;
		}

		$cc = join(',', $cc_ary);
	}

    // 分岐条件、改行位置を補正
    function adjust_question_no() {
        $enquete = &$this->enquete;
        $enquete_id = $this->enquete_id;

        // 新旧質問番号の対応表作成
        $new_qno = 0;
        $unmatch_qno = -1;
        $change_question_type = false;
        $org_qno_ary = array();
        $new_qno_ary = array();
        if (is_array($enquete->question)) {
            foreach ($enquete->question as $qno => $question) {
                $old_qno = $question->question_no;

                $org_qno_ary[$old_qno] = $qno;

                if (!$question->delete_flag)
                    $new_qno_ary[$old_qno] = ++$new_qno;

                if ($new_qno != $old_qno && $unmatch_qno == -1)
                    $unmatch_qno = $new_qno;

                if ($question->question_type != $question->question_type_org)
                    $change_question_type = true;
            }
        }

        if ($unmatch_qno >= 0) {
            // 改行位置補正
            if (is_array($enquete->question)) {
                foreach ($enquete->question as $qno => $question) {
					if (isset($new_qno_ary[$qno])) {
	                    $new_qno = $new_qno_ary[$qno];
	                    if ($new_qno > $unmatch_qno)
	                        $enquete->question[$qno]->page_break = DBFALSE;
					}
                }
            }

            // 条件分岐補正
            if (is_array($enquete->branch_cond)) {
                $branch_cond_ary = array();
                foreach ($enquete->branch_cond as $qno => $branch_cond) {
					if (isset($new_qno_ary[$qno])) {
	                    $new_qno = $new_qno_ary[$qno];
	                    if ($new_qno < $unmatch_qno)
	                        $branch_cond_ary[$new_qno] = $branch_cond;
					}
                }
                $enquete->branch_cond = $branch_cond_ary;
            }
        }
    }
}
?>