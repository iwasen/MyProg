<?
/******************************************************
' System :�������ơ�net����
' Content:������說�饹��Pro���󥱡������ѡ�
'******************************************************/

define('DEBUG', false);

class pro_search_class extends search_class{
    var $marketer_id;
    var $pro_enq_no;
    var $sample_num = 0;
    var $pro_enq;

    function get_monitor_num_by_ref($pro_enq) {
        $this->pro_enq = &$pro_enq;

        $mon_tbl = $this->get_monitor_table_name();
        return ($this->get_monitor_count($mon_tbl));
    }

    // ��˥���������
    function get_monitor_num($marketer_id, $pro_enq_no, $sample_num=0) {
        $this->marketer_id = $marketer_id;
        $this->pro_enq_no = $pro_enq_no;
        $this->sample_num = $sample_num;

        $this->pro_enq =& new pro_enquete_class;
        $this->pro_enq->read_db($this->marketer_id, $this->pro_enq_no);

        $mon_tbl = $this->get_monitor_table_name();
        return ($this->get_monitor_count($mon_tbl));
    }

    // ��˥����ơ��֥븡��
    function get_monitor_count($mon_tbl) {

        $monitor_num = 0;
        $cell   = &$this->pro_enq->cell;

        if ($cell->cell_option == '1') {
            list($select, $join, $groupby) = $cell->get_sql();
            $where = $this->make_sql();
            $sql = "SELECT COUNT(*)"
                    . " FROM $mon_tbl"
                    . " $join"
                    . " WHERE $where";

            $result = db_exec($sql);
            if (pg_numrows($result)) {
                $fetch = pg_fetch_row($result, 0);
                $monitor_num = $fetch[0];
            }

if(DEBUG) {
$this->pro_enq->calc_send_num();
echo "*** ".$mon_tbl . " " . "��ɸ ". $this->pro_enq->send_num. " ���� " . $monitor_num." ***<br>";
}
        } elseif ($cell->cell_option == '2') {

            $search_cell = $cell->get_search_cell($this->make_sql(), false, 100, $mon_tbl == 't_monitor' ? 't' : 'g');

            $sample_cell = $cell->get_send_cell(true, $this->pro_enq->finding_flag == DBTRUE ? $this->pro_enq->appearance_ratio : 100);
            $cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);

            if ($cell->rate_option == '1') {
                for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
                    for ($age = $age_s; $age <= $age_e; $age++) {
                        if ( $sample_cell[$sex][$age][0] > 0 && isset($search_cell[$sex][$age][0]) ) {
                                $monitor_num += $search_cell[$sex][$age][0];
if(DEBUG) {
echo $mon_tbl." "."��ɸ[".$sex."][".$age."][0]".$sample_cell[$sex][$age][0]." ����[".$sex."][".$age."][0]".$search_cell[$sex][$age][0]."<br>";
}
                        }
                    }
                }

            } else {
                for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
                    for ($age = $age_s; $age <= $age_e; $age++) {
                        for ($rate = $rate_s; $rate <= $rate_e; $rate++) {
                            if ( $sample_cell[$sex][$age][$rate] > 0 && isset($search_cell[$sex][$age][$rate]) ) {
                                    $monitor_num += $search_cell[$sex][$age][$rate];
if(DEBUG) {
echo $mon_tbl . " " . "��ɸ[".$sex."][".$age."][".$rate."]". $sample_cell[$sex][$age][$rate] . " ����[".$sex."][".$age."][".$rate."]".$search_cell[$sex][$age][$rate]."<br>";
}
                            }
                        }
                    }
                }
            }
        }

        return $monitor_num;
    }

    // ��˥����ơ��֥�̾����
    function get_monitor_table_name() {
/*
        $mon_tbl = 't_monitor';
        // GMO��˥�����¸�ߤ��ʤ�°�������ꤵ��Ƥ��뤫�ʶȼ����褯�Ԥ�����ӥˡ��褯�Ԥ������ѡ�����̣������Υ����å���
        if ($this->gyoushu == '' && $this->shokushu == '' && $this->conveni == '' && $this->super == '' && $this->genre == '') {

            $cell   = &$this->pro_enq->cell;
            if ($cell->cell_option == '1') {
                $monitor_num = $this->get_monitor_count($mon_tbl);

                // �оݼԿ������å�
                $this->pro_enq->calc_send_num();
                // ��ɸ����ץ���������ʤ���硢GMO��˥����ơ��֥�
                if ($this->pro_enq->send_num > $monitor_num) {
                    $mon_tbl = 'g_monitor';
                }
            } elseif ($cell->cell_option == '2') {
                // �����̸���������
                $search_cell = $cell->get_search_cell($this->make_sql()); // �и�Ψ̵�����ֿ�Ψ̵��
                // ��������ɸ����ץ������(�и�Ψͭ�ꡢ�ֿ�Ψͭ��)
                $sample_cell = $cell->get_send_cell(true, $this->pro_enq->finding_flag == DBTRUE ? $this->pro_enq->appearance_ratio : 100);
                $cell->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);

                // ��­���륻�뤬���뤫�����å�
                $short = false;
                if ($cell->rate_option == '1') {
                    for ($sex = $sex_s; $sex <= $sex_e && !$short; $sex++) {
                        for ($age = $age_s; $age <= $age_e && !$short; $age++) {
                            if ((int)$search_cell[$sex][$age][0] < (int)$sample_cell[$sex][$age][0])
                                $short = true;
                        }
                    }
                } else {
                    for ($sex = $sex_s; $sex <= $sex_e && !$short; $sex++) {
                        for ($age = $age_s; $age <= $age_e && !$short; $age++) {
                            for ($rate = $rate_s; $rate <= $rate_e && !$short; $rate++) {
                                if ((int)$search_cell[$sex][$age][$rate] < (int)$sample_cell[$sex][$age][$rate])
                                    $short = true;
                            }
                        }
                    }
                }

                if (count($search_cell) == 0 || $short) {
                    $mon_tbl = 'g_monitor';
                }
            }
        }
*/
		$mon_tbl = $this->type == 6 ? 'g_monitor' : 't_monitor';
        return $mon_tbl;
    }

    // ���ѥͥåȥ���ե饰����
    function get_target_flg() {
//        $target_flg = ($this->get_monitor_table_name() == 't_monitor') ? 't' : 'g';
		$target_flg = $this->type == 6 ? 'g' : 't';
        return $target_flg;
    }

    function get_target_flg_by_ref($pro_enq) {
        $this->pro_enq = &$pro_enq;

		return $this->get_target_flg();
    }

    function make_sql2(){ // ����������Ȳ��̤Ρ֥�˥��������פ���ʬ�ˤ�����GMO��˥����������б� 2006/05/30
        $temp = '';
        //�ｻ�ϰ�����
        $kyojuu = $this->kyojuu;
        $this->kyojuu = '';

        $sql = parent::make_sql();

        if ($kyojuu) {
            $ary = explode(',', $kyojuu);
            foreach ($ary as $item) {
                $ary2 = explode('/', $item);
                if (count($ary2) == 2) {
                    or_join($temp, "ar_area_name='$ary2[0]'");
                    and_join($temp, "ar_area_name LIKE '$ary2[1]%'");
                } else
                    or_join($temp, "ar_area_name='$item'");
            }
            if ($temp != '')
                $temp = "EXISTS (SELECT * FROM m_area WHERE ar_area_cd=mn_jitaku_area AND ($temp))";

            and_join($sql, $temp);
        }

        //�ｻ�ϰ���᤹
        $this->kyojuu = $kyojuu;

        return $sql;
    }
}
?>