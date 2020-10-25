<?
/******************************************************
' System :�������ơ�net����
' Content:PRO���󥱡��ȴ�Ϣ
'******************************************************/

include_once('mye_comm.php');
include_once('enq_cleaning.php');
include_once('pro_search.php');

class pro_enquete_class {
    var $enq_kind;
    var $marketer_id;
    var $pro_enq_no;
    var $enquete_id;
    var $enquete2_id;
    var $search_id;
    var $send_num;
    var $mail_title;
    var $mail_header;
    var $mail_contents;
    var $mail_footer;
    var $real_send;
    var $creator_id;
    var $ans_cond;
	var $ans_cond_count;
    var $finding_flag;
    var $appearance_ratio;
    var $res_and_or;
    var $sample_num;
    var $send_count;
    var $first_send_date;
    var $last_send_date;
    var $add_send_date;
	var $notify_assistant;
	var $notify_mail_addr;

    var $age_type;

    var $enquete;
    var $enquete2;
    var $search;
    var $cell;
    var $cleaning;
    var $res_cond;

    var $save_enquete;
    var $save_enquete2;
    var $save_search;
    var $save_cleaning;
    var $save_res_cond;

    // ���������
    function pro_enquete_class() {
        $this->enq_kind = 'proenq';
        $this->enquete = new enquete_class;
        $this->enquete2 = new enquete_class;
        $this->search = new pro_search_class;
        $this->cell = new cell_class;
        $this->cleaning = new enq_cleaning_class;
        $this->enquete->enq_kind = 5;
        $this->enquete2->enq_kind = 6;
        $this->enquete->start_date_h = 18;
        $this->res_and_or = 'A';
		$this->ans_cond_count = 1;
    }

    // DB�����ɤ߹���
    function read_db($marketer_id, $pro_enq_no) {
        if (!$marketer_id || !$pro_enq_no)
            return;

        // �����
        $this->pro_enquete_class();

        // My���󥱡��Ⱦ������
        $sql = "SELECT * FROM t_pro_enquete WHERE mep_marketer_id=$marketer_id AND mep_pro_enq_no=$pro_enq_no";
        $result = db_exec($sql);
        if (pg_numrows($result)) {
            $fetch = pg_fetch_object($result, 0);
            $this->marketer_id = $fetch->mep_marketer_id;
            $this->pro_enq_no = $fetch->mep_pro_enq_no;
            $this->enquete_id = $fetch->mep_enquete_id;
            $this->enquete2_id = $fetch->mep_enquete2_id;
            $this->search_id = $fetch->mep_search_id;
            $this->send_num = $fetch->mep_send_num;
            $this->mail_title = $fetch->mep_mail_title;
            $this->mail_header = $fetch->mep_mail_header;
            $this->mail_contents = $fetch->mep_mail_contents;
            $this->mail_footer = $fetch->mep_mail_footer;
            $this->real_send = $fetch->mep_real_send;
            $this->creator_id = $fetch->mep_creator_id;
            $this->ans_cond = $fetch->mep_ans_cond;
            $this->finding_flag = $fetch->mep_finding_flag;
            $this->appearance_ratio = ($fetch->mep_finding_flag == DBTRUE) ? $fetch->mep_appearance_ratio : 100;
            $this->res_and_or = $fetch->mep_res_and_or;
            $this->sample_num = $fetch->mep_sample_num;
            $this->send_count = $fetch->mep_send_count;
            $this->first_send_date = $fetch->mep_first_send_date;
            $this->last_send_date = $fetch->mep_last_send_date;
            $this->add_send_date = $fetch->mep_add_send_date;
            $this->notify_assistant = $fetch->mep_notify_assistant;
            $this->notify_mail_addr = $fetch->mep_notify_mail_addr;

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

        // �ե�����ǥ󥰥��󥱡�����Ͽ�������
        if ($this->enquete_id)
            $this->enquete->read_db($this->enquete_id);

        // �ܥ��󥱡�����Ͽ�������
        if ($this->enquete2_id) {
            if ($this->enquete_id == $this->enquete2_id)
                $this->enquete2 = &$this->enquete;
            else
                $this->enquete2->read_db($this->enquete2_id);
        }

        // ����������
        if ($this->search_id) {
            $this->search->read_db($this->search_id);
            $this->cell->read_db($this->search_id, $this->sample_num);
        }

        // ���󥱡��Ȳ���������
        $sql = "SELECT erc_question_no,erc_select_no,erc_and_or,erc_not_cond"
                . " FROM t_enq_res_cond"
                . " WHERE erc_enquete_id=$this->enquete_id"
                . " ORDER BY erc_question_no";
        $result = db_exec($sql);
        $nrow = pg_numrows($result);
        for ($i = 0; $i < $nrow; $i++) {
            $fetch = pg_fetch_object($result, $i);
            $res_cond = &$this->res_cond[$fetch->erc_question_no];

            $res_cond->select_no = $fetch->erc_select_no;
            $res_cond->and_or = $fetch->erc_and_or;
            $res_cond->not_cond = $fetch->erc_not_cond;
        }

        // ���꡼�˥󥰾�����
        $this->cleaning->read_db($this->enquete2_id);

        // ǯ�������ˡ
        if ($this->search->age_cd != '')
            $this->age_type = '1';
        elseif ($this->search->age_from != '' || $this->search->age_to != '')
            $this->age_type = '2';
        else
            $this->age_type = '';
    }

    // DB�˽񤭹���
    function write_db() {
        db_begin_trans();
        // �ե�����ǥ󥰥��󥱡�����Ͽ����񤭹���
        if ($this->save_enquete) {
            $this->enquete_id = $this->enquete->write_db($this->enquete_id);
            if ($this->finding_flag == DBFALSE) {
                $this->enquete2_id = $this->enquete_id;
                $this->save_enquete2 = false;
            }
        }
        // ���󥱡�����Ͽ����񤭹���
        if ($this->save_enquete2)
            $this->enquete2_id = $this->enquete2->write_db($this->enquete2_id);
        // �������񤭹���
        if ($this->save_search) {
            $this->search_id = $this->search->write_db($this->search_id);
            $this->cell->write_db($this->search_id);
        }
        // �ܥ��󥱡��Ȳ������񤭹���
        if ($this->save_res_cond && $this->enquete_id) {
            db_delete('t_enq_res_cond', "erc_enquete_id=$this->enquete_id");

            if (is_array($this->res_cond)) {
                foreach ($this->res_cond as $qno => $res_cond) {
                    if ($res_cond->select_no != '') {
                        $rec['erc_enquete_id'] = sql_number($this->enquete_id);
                        $rec['erc_question_no'] = sql_number($qno);
                        $rec['erc_select_no'] = sql_char($res_cond->select_no);
                        $rec['erc_and_or'] = sql_char($res_cond->and_or);
                        $rec['erc_not_cond'] = sql_char($res_cond->not_cond);
                        db_insert('t_enq_res_cond', $rec);
                    }
                }
            }
        }

        // ���꡼�˥󥰾��񤭹���
        if ($this->save_cleaning)
            $this->cleaning->write_db();
        // �������׻�
        $this->calc_send_num();

        // PRO���󥱡��Ⱦ���񤭹���
        $rec['mep_enquete_id'] = sql_number($this->enquete_id);
        $rec['mep_enquete2_id'] = sql_number($this->enquete2_id);
        $rec['mep_search_id'] = sql_number($this->search_id);
        $rec['mep_send_num'] = sql_number($this->send_num);
        $rec['mep_mail_title'] = sql_char($this->mail_title);
        $rec['mep_mail_header'] = sql_char($this->mail_header);
        $rec['mep_mail_contents'] = sql_char($this->mail_contents);
        $rec['mep_mail_footer'] = sql_char($this->mail_footer);
        $rec['mep_creator_id'] = sql_number($this->creator_id);
        $rec['mep_ans_cond'] = sql_char($this->ans_cond);
        $rec['mep_finding_flag'] = sql_bool($this->finding_flag);
        $rec['mep_appearance_ratio'] = sql_number($this->appearance_ratio);
        $rec['mep_res_and_or'] = sql_char($this->res_and_or);
        $rec['mep_sample_num'] = sql_number($this->sample_num);
        $rec['mep_notify_assistant'] = sql_char($this->notify_assistant);
        $rec['mep_notify_mail_addr'] = sql_char($this->notify_mail_addr);
        if ($this->pro_enq_no == '') {
            $sql = "SELECT MAX(mep_pro_enq_no) FROM t_pro_enquete WHERE mep_marketer_id=$this->marketer_id";
            $this->pro_enq_no = db_fetch1($sql, 0) + 1;

            $rec['mep_marketer_id'] = sql_number($this->marketer_id);
            $rec['mep_pro_enq_no'] = sql_number($this->pro_enq_no);

            db_insert('t_pro_enquete', $rec);
        } else {
            db_update('t_pro_enquete', $rec, "mep_marketer_id=$this->marketer_id AND mep_pro_enq_no=$this->pro_enq_no");
        }
        db_commit_trans();
    }

    // ����������
    function calc_send_num() {
        $search = $this->search;
        $cell = $this->cell;

        $appearance_ratio = $this->appearance_ratio;
        if (!$appearance_ratio)
            $appearance_ratio = 100;

        switch ($cell->cell_option) {
        case '1':
            $sex = $search->sex == '' ? '0' : $search->sex;
            $sql = "SELECT rep_reply_rate FROM m_reply_rate WHERE rep_age_cd=0 AND rep_sex_cd=$sex";
            $reply_rate = db_fetch1($sql);
            $finding_sample_num = $this->sample_num / ($appearance_ratio / 100);
            $this->send_num = round($finding_sample_num / ($reply_rate / 100));
            break;
        case '2':
            $send_cell = $cell->get_send_cell(true, $this->finding_flag == DBTRUE ? $this->appearance_ratio : 100);
            $cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);
            $this->send_num = 0;
            for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
                for ($age = $age_s; $age <= $age_e; $age++) {
                    for ($rate = $rate_s; $rate <= $rate_e; $rate++) {
                        $this->send_num += $send_cell[$sex][$age][$rate];
                    }
                }
            }
            break;
        }
    }

    // �ܥ��󥱡��Ȳ������ʸ����
    function res_cond_text($post_text, $question_text_flag = false, $sel_text_flag = true) {
        $t1 = '';
        if (is_array($this->res_cond)) {
            foreach ($this->res_cond as $qno => $res_cond) {
                $t2 = '';
                if ($qno && $res_cond->select_no) {
                    $question = &$this->enquete->question[$qno];

                    foreach (explode(',', $res_cond->select_no) as $sno) {
                        if ($t2 != '') {
                            switch ($res_cond->and_or) {
                            case 'A':
                            case 'F':
                                $t2 .= '��';
                                break;
                            case 'O':
                                $t2 .= '�ޤ���';
                                break;
                            }
                        }

                        switch ($question->question_type) {
                        case 1:     // SA
                        case 2:     // MA
                        case 7:     // �ץ������
                            if ($sel_text_flag)
                                $t2 .= '��' . $question->sel_text[$sno] . '��';
                            else
                                $t2 .= '��' . mb_convert_kana($sno, 'N') . '��';
                            break;
                        case 4:     // Matrix SA
                        case 5:     // Matrix MA
                            list($sno, $tno) = explode(':', $sno);
                            if ($sel_text_flag)
                                $t2 .= '��' . $question->hyousoku[$sno] . '��' . $question->hyoutou[$tno] . '��';
                            else
                                $t2 .= '��' . mb_convert_kana($sno, 'N') . '��' . mb_convert_kana($tno, 'N') . '��';
                            break;
                        }
                    }

                    if ($t2 != '') {
                        if ($question_text_flag)
                            $t3 = "�֣�" . mb_convert_kana($qno, 'N') . '��' . str_trim($question->question_text, 20) . '��';
                        else
                            $t3 = "��" . mb_convert_kana($qno, 'N');
                        $t2 = "{$t3}��{$t2}";
                    }
                }

                if ($res_cond->and_or == 'F')
                    $t2 .= '�Τ�';

                if ($res_cond->not_cond == DBTRUE)
                    $t2 .= '�ʳ�';

                if ($t2 != '') {
                    if ($t1 != '') {
                        if ($this->res_and_or == 'A')
                            $t1 .= '������';
                        else
                            $t1 .= '���ޤ���';
                    }

                    $t1 .= $t2;
                }
            }

            if ($t1 != '')
                $t1 .= $post_text;
        }

        return $t1;
    }

    // �ե�����ǥ�������׻�
    function get_finding_price($sample_num, $question_num) {
        // ����ե�����ǥ��󥰥���ץ������
        $sql = "SELECT MAX(pfp_sample_num) FROM m_finding_price";
        $max_sample_num = db_fetch1($sql);

        // ����ե�����ǥ��󥰥���ץ���ˤ���ߥå�
        if ($sample_num > $max_sample_num)
            $sample_num = $max_sample_num;

        // ����ե�����ǥ��󥰼��������
        $sql = "SELECT MAX(pfp_question_num) FROM m_finding_price";
        $max_question_num = db_fetch1($sql);

        // ����ե�����ǥ��󥰼�����ˤ���ߥå�
        if ($question_num > $max_question_num)
            $question_num = $max_question_num;

        // �ե�����ǥ����������
        $sql = "SELECT pfp_price FROM m_finding_price WHERE pfp_question_num>=$question_num AND pfp_sample_num>=$sample_num ORDER BY pfp_question_num,pfp_sample_num LIMIT 1";
        return db_fetch1($sql, 0) * 1000;
    }

    // �ܥ��󥱡��Ȥ�����׻�
    function get_enquete_price($sample_num, $question_num) {
        // �����ܥ��󥱡��ȥ���ץ������
        $sql = "SELECT MAX(pep_sample_num) FROM m_proenq_price";
        $max_sample_num = db_fetch1($sql);

        // �����ܥ��󥱡��ȥ���ץ���ˤ���ߥå�
        if ($sample_num > $max_sample_num)
            $sample_num = $max_sample_num;

        // �����ܥ��󥱡��ȼ��������
        $sql = "SELECT MAX(pep_question_num) FROM m_proenq_price";
        $max_question_num = db_fetch1($sql);

        // �����ܥ��󥱡��ȼ�����ˤ���ߥå�
        if ($question_num > $max_question_num)
            $question_num = $max_question_num;

        // �ܥ��󥱡����������
        $sql = "SELECT pep_price FROM m_proenq_price WHERE pep_question_num>=$question_num AND pep_sample_num>=$sample_num ORDER BY pep_question_num,pep_sample_num LIMIT 1";
        return db_fetch1($sql, 0) * 1000;
    }

    // �ե�����ǥ��󥰤��ܥ��󥱡��Ȥι������׻�
    function get_price($finding_num, $finding_question_num, $sample_num, $question_num) {
        // �ե�����ǥ�������
        if ($this->finding_flag == DBTRUE)
            $price1 = $this->get_finding_price($finding_num, $finding_question_num);
        else
            $price1 = 0;

        // �ܥ��󥱡�������
        $price2 = $this->get_enquete_price($sample_num, $question_num);

        return $price1 + $price2;
    }

    // �����ͤˤ��ȡ����������׻�
    function get_total_price() {
        // �ե�����ǥ��󥰿��׻�
        $finding_sample_num = round($this->sample_num / ($this->appearance_ratio / 100));

        // �ե�����ǥ��󥰼��������
        $finding_question_num = $this->enquete->get_question_num();

        // �ܥ��󥱡��ȼ��������
        $question_num = $this->enquete2->get_question_num();

        // �ե�����ǥ��󥰤��ܥ��󥱡��Ȥ�����׻�
        $enq_price = $this->get_price($finding_sample_num, $finding_question_num, $this->sample_num, $question_num);

        // �����������
        $cell_price = $this->cell->cell_num * 10000;

        // ��������������
        $image_num = 0;
        if ($this->finding_flag == DBTRUE)
            $image_num += $this->enquete->get_image_num();
        $image_num += $this->enquete2->get_image_num();
        $image_price = $image_num ? 5000 : 0;

        return $enq_price + $cell_price + $image_price;
    }

    // ʬ�������󥱡��Ȳ��������꡼�˥󥰾�������
    function adjust_question_no($main) {
        if ($main) {
            $enquete = &$this->enquete2;
            $enquete_id = $this->enquete2_id;
        } else {
            $enquete = &$this->enquete;
            $enquete_id = $this->enquete_id;
        }

        // ��������ֹ���б�ɽ����
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
            // ���԰�������
            if (is_array($enquete->question)) {
                foreach ($enquete->question as $qno => $question) {
					if (isset($new_qno_ary[$qno])) {
	                    $new_qno = $new_qno_ary[$qno];
	                    if ($new_qno > $unmatch_qno)
	                        $enquete->question[$qno]->page_break = DBFALSE;
					}
                }
            }

            // ���ʬ������
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

        if ($unmatch_qno >= 0 || $change_question_type) {
            // ���󥱡��Ȳ����������
            if (!$main && is_array($this->res_cond)) {
                $res_cond_ary = array();
                foreach ($this->res_cond as $qno => $res_cond) {
                    $org_qno = $org_qno_ary[$qno];
                    $new_qno = $new_qno_ary[$qno];
                    if ($new_qno && $enquete->question[$org_qno]->question_type == $enquete->question[$org_qno]->question_type_org)
                        $res_cond_ary[$new_qno] = $res_cond;
                }
                $this->res_cond = $res_cond_ary;
                $this->save_res_cond = true;
            }

            // ���꡼�˥󥰾������
            if (is_array($this->cleaning->cleaning_cond)) {
                foreach ($this->cleaning->cleaning_cond as $cond_no => $cleaning_cond) {
                    if (is_array($cleaning_cond->ans_cond)) {
                        foreach ($cleaning_cond->ans_cond as $seq_no => $ans_cond) {
                            if ($ans_cond->ans_enquete_id == $enquete_id) {
                                $org_qno = $org_qno_ary[$ans_cond->question_no];
                                $new_qno = $new_qno_ary[$ans_cond->question_no];
                                if ($new_qno && $enquete->question[$org_qno]->question_type == $enquete->question[$org_qno]->question_type_org)
                                    $this->cleaning->cleaning_cond[$cond_no]->ans_cond[$seq_no]->question_no = $new_qno;
                                else
                                    unset($this->cleaning->cleaning_cond[$cond_no]->ans_cond[$seq_no]);
                            }
                        }
                    }
                }
                $this->save_cleaning = true;
            }
        }
    }

	// ������᡼�륢�ɥ쥹����
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
}
?>