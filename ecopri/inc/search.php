<?
/******************************************************
' System :Eco-footprint ����
' Content:������說�饹
'******************************************************/

class search_class {
	var $active;

	var $step;
	var $sex;
	var $age_from;
	var $age_to;
	var $kyojuu;
	var $area2;
	var $keitai;
	var $sozai;
	var $hebel;
	var $chikunen;
	var $space;
	var $room;
	var $boiler;
	var $gas_kind;
	var $gas_type;
	var $water;
	var $gcar_from;
	var $gcar_to;
	var $dcar_from;
	var $dcar_to;
	var $bike_from;
	var $bike_to;
	var $car_andor;
	var $family;
	var $family_rel;
	var $family_rel_andor;
	var $child;
	var $child_youngest_from;
	var $child_youngest_to;
	var $child_age_from;
	var $child_age_to;
	var $gm;
	var $ol;
	var $gl;
	var $reg_kari_y_from;
	var $reg_kari_m_from;
	var $reg_kari_y_to;
	var $reg_kari_m_to;
	var $reg_y_from;
	var $reg_m_from;
	var $reg_y_to;
	var $reg_m_to;
	var $entry_from;
	var $entry_to;
	var $commit;
	var $commit_m_y;
	var $commit_m_m;
	var $commit_inp_ol;
	var $commit_inp_gm;
	var $commit_inp_gl;
	var $rank_eng;
	var $rank_co2sum;
	var $rank_highlow;
	var $rank;

	// ���󥹥ȥ饯��
	function search_class() {
		$this->active = true;
		$this->step = '3';
		$this->family_rel_andor = 'O';
		$this->car_andor = 'O';;
		$this->rank_highlow = '1';
	}

	// ʣ���ǡ���������
	function get_multi_data($name) {
		if ($name)
			$data = join(',', $name);

		return $data;
	}

	// �ե����फ��ǡ�������
	function get_form() {
		global $step_flag, $step;
		global $sex_flag, $sex;
		global $age_flag, $age_from, $age_to;
		global $kyojuu_flag, $kyojuu;
		global $area2_flag, $area2;
		global $keitai_flag, $keitai;
		global $sozai_flag, $sozai;
		global $hebel_flag, $hebel;
		global $chikunen_flag, $chikunen;
		global $room_flag, $room;
		global $space_flag, $space;
		global $boiler_flag, $boiler;
		global $gas_kind_flag, $gas_kind;
		global $gas_type_flag, $gas_type;
		global $water_flag, $water;
		global $car_flag, $gcar_from, $gcar_to, $dcar_from, $dcar_to, $bike_from, $bike_to, $car_andor;
		global $family_flag, $family;
		global $family_rel_flag, $family_rel, $family_rel_andor;
		global $child_flag, $child;
		global $child_youngest_flag, $child_youngest_from, $child_youngest_to;
		global $child_age_flag, $child_age_from, $child_age_to;
		global $gm_flag, $gm;
		global $ol_flag, $ol;
		global $gl_flag, $gl;
		global $reg_kari_flag, $reg_kari_y_from, $reg_kari_y_to, $reg_kari_m_from, $reg_kari_m_to;
		global $reg_flag, $reg_y_from, $reg_y_to, $reg_m_from, $reg_m_to;
		global $entry_flag, $entry_from, $entry_to;
		global $commit_flag, $commit; //, $commit_highlow;
		global $commit_m_flag, $commit_m_y, $commit_m_m, $commit_inp_ol, $commit_inp_gm, $commit_inp_gl;
		global $rank_flag, $rank_eng, $rank_highlow, $rank;

		// ����
//		$this->step = $step_flag ? $step : '';

		// ����
		$this->sex = $sex_flag ? $sex : '';

		// ǯ��
		$this->age_from = $age_flag ? $age_from : '';
		$this->age_to = $age_flag ? $age_to : '';

		// �ｻ�ϰ�
		$this->area2 = $area2_flag ? $this->get_multi_data($area2) : '';

		// ��ƻ�ܸ�
		$this->kyojuu = $kyojuu_flag ? $this->get_multi_data($kyojuu) : '';

		// �ｻ����
		$this->keitai = $keitai_flag ? $this->get_multi_data($keitai) : '';

		// �ｻ�Ǻ�
		$this->sozai = $sozai_flag ? $this->get_multi_data($sozai) : '';

		// �ء��٥�ϥ���
		$this->hebel = $hebel_flag ? $hebel : '';

		// ����ǯ��
		$this->chikunen = $chikunen_flag ? $this->get_multi_data($chikunen) : '';

		// ����ּ��
		$this->room = $room_flag ? $this->get_multi_data($room) : '';

		// ��������
		$this->space = $space_flag ? $this->get_multi_data($space) : '';

		// ����勵����
		$this->boiler = $boiler_flag ? $this->get_multi_data($boiler) : '';

		// ������
		$this->gas_kind = $gas_kind_flag ? $this->get_multi_data($gas_kind) : '';

		// ����������
		$this->gas_type = $gas_type_flag ? $this->get_multi_data($gas_type) : '';

		// ��ƻ���˷�
		$this->water = $water_flag ? $water : '';

		// �ֽ�ͭ
		$this->car_andor = $car_flag ? $car_andor : '';
		
		$this->gcar_from = $car_flag ? $gcar_from : '';
		$this->gcar_to = $car_flag ? $gcar_to : '';

		$this->dcar_from = $car_flag ? $dcar_from : '';
		$this->dcar_to = $car_flag ? $dcar_to : '';

		$this->bike_from = $car_flag ? $bike_from : '';
		$this->bike_to = $car_flag ? $bike_to : '';

		// ��²���ƥ��꡼
		$this->family = $family_flag ? $this->get_multi_data($family) : '';

		// ��²��³��
		$this->family_rel = $family_rel_flag ? $this->get_multi_data($family_rel) : '';
		$this->family_rel_andor = $family_rel_andor;

		// �Ҷ���̵ͭ
		$this->child = $child_flag ? $child : '';

		// ���ҡʺ�ǯ����²��ǯ��
		$this->child_youngest_from = $child_youngest_flag ? $child_youngest_from : '';
		$this->child_youngest_to = $child_youngest_flag ? $child_youngest_to : '';

		// �Ҷ��ʲ�²��ǯ��
		$this->child_age_from = $child_age_flag ? $child_age_from : '';
		$this->child_age_to = $child_age_flag ? $child_age_to : '';

		// ��������
		$this->gm = $gm_flag ? $gm : '';

		// ��������
		$this->ol = $ol_flag ? $ol : '';

		// �����������
		$this->gl = $gl_flag ? $gl : '';

		// ����Ͽ��
		$this->reg_kari_y_from = $reg_kari_flag ? $reg_kari_y_from : '';
		$this->reg_kari_m_from = $reg_kari_flag ? $reg_kari_m_from : '';
		$this->reg_kari_y_to = $reg_kari_flag ? $reg_kari_y_to : '';
		$this->reg_kari_m_to = $reg_kari_flag ? $reg_kari_m_to : '';

		// ����Ͽ��
		$this->reg_y_from = $reg_flag ? $reg_y_from : '';
		$this->reg_m_from = $reg_flag ? $reg_m_from : '';
		$this->reg_y_to = $reg_flag ? $reg_y_to : '';
		$this->reg_m_to = $reg_flag ? $reg_m_to : '';

		// ����в���
		$this->entry_from = $entry_flag ? $entry_from : '';
		$this->entry_to = $entry_flag ? $entry_to : '';

		// ���ʴ�λ�β��
		$this->commit = $commit_flag ? $commit : '';
//		$this->commit_highlow = $commit_flag ? $commit_highlow : '';

		// ���ʴ�λ��̵ͭ
		$this->commit_m_y = $commit_m_flag ? $commit_m_y : '';
		$this->commit_m_m = $commit_m_flag ? $commit_m_m : '';

		// ���ϥե饰
		$this->commit_inp_ol = $commit_m_flag ? $commit_inp_ol : '';
		$this->commit_inp_gm = $commit_m_flag ? $commit_inp_gm : '';
		$this->commit_inp_gl = $commit_m_flag ? $commit_inp_gl : '';

		// �︺�̡ʽ�̡�
		$this->rank_eng = $rank_flag ? $rank_eng : '';
		$this->rank_co2sum = $rank_flag ? $rank_co2sum : '';
		$this->rank_highlow = $rank_flag ? $rank_highlow : '';
		$this->rank = $rank_flag ? $rank : '';
	}

	// ����SQL����
	function make_sql() {

		// ���Խ���
//		$sql = "mb_step=3";

		// ����
		if ($this->step != '')
			and_join($sql, "mb_step=$this->step");

		// ����
		if ($this->sex != '')
			and_join($sql, "mb_sex=$this->sex");

		// ǯ��
		if ($this->age_from != '' && $this->age_to != '')
			and_join($sql, "DATE_PART('Y',AGE(mb_birthday)) BETWEEN $this->age_from AND $this->age_to");
		elseif ($this->age_from != '')
			and_join($sql, "DATE_PART('Y',AGE(mb_birthday))>=$this->age_from");
		elseif ($this->age_to != '')
			and_join($sql, "DATE_PART('Y',AGE(mb_birthday))<=$this->age_to");

		// �ｻ�ϰ�
		if ($this->area2 != '')
			and_join($sql, "EXISTS (SELECT aa_area2_cd FROM m_area LEFT JOIN m_area2 ON ar_area2_cd=aa_area2_cd WHERE mb_area_cd=ar_area_cd AND aa_area2_cd IN ($this->area2))");

		// �������
		if ($this->keitai != '')
			and_join($sql, $this->multi_cond('mb_keitai_cd', $this->keitai));

		// �����Ǻ�
		if ($this->sozai != '')
			and_join($sql, $this->multi_cond('mb_sozai_cd', $this->sozai));

		// �ء��٥�ϥ���
		if ($this->hebel != '')
			and_join($sql, "mb_hebel_flag=$this->hebel");

		// ����ǯ��
		if ($this->chikunen != '')
			and_join($sql, $this->multi_cond('mb_chikunen_cd', $this->chikunen));

		// ����ּ��
		if ($this->room != '')
			and_join($sql, $this->multi_cond('mb_room_cd', $this->room));

		// ��������
		if ($this->space != '')
			and_join($sql, $this->multi_cond('mb_space_cd', $this->space));

		// ����勵����
		if ($this->boiler != '')
			and_join($sql, $this->multi_cond('mb_boiler_cd', $this->boiler));

		// ������
		if ($this->gas_kind != '')
			and_join($sql, $this->multi_cond('mb_gas_kind_cd', $this->gas_kind));

		// ����������
		if ($this->gas_type != '')
			and_join($sql, $this->multi_cond('mb_gas_type_cd', $this->gas_type));

		// ��ƻ���˷�
		if ($this->water != '')
			and_join($sql, "mb_water_month=$this->water");

		// �ֽ�ͭ�ʥ������֡�
		if ($this->gcar_from != '' && $this->gcar_to != '')
			$array[] = "(mb_gcar_num BETWEEN $this->gcar_from AND $this->gcar_to)";
		elseif ($this->gcar_from != '')
			$array[] = "(mb_gcar_num>=$this->gcar_from)";
		elseif ($this->gcar_to != '')
			$array[] = "(mb_gcar_num<=$this->gcar_to)";

		// �ֽ�ͭ�ʥǥ�������֡�
		if ($this->dcar_from != '' && $this->dcar_to != '')
			$array[] = "(mb_dcar_num BETWEEN $this->dcar_from AND $this->dcar_to)";
		elseif ($this->dcar_from != '')
			$array[] = "(mb_dcar_num>=$this->dcar_from)";
		elseif ($this->dcar_to != '')
			$array[] = "(mb_dcar_num<=$this->dcar_to)";

		// �ֽ�ͭ�ʥХ�����
		if ($this->bike_from != '' && $this->bike_to != '')
			$array[] = "(mb_bike_num BETWEEN $this->bike_from AND $this->bike_to)";
		elseif ($this->bike_from != '')
			$array[] = "(mb_bike_num>=$this->bike_from)";
		elseif ($this->bike_to != '')
			$array[] = "(mb_bike_num<=$this->bike_to)";

		if ($array != '') {
			if ($this->car_andor == 'O')
				and_join($sql, '(' . implode(' OR ', $array) . ')');
			elseif ($this->car_andor == 'A')
				and_join($sql, '(' . implode(' AND ', $array) . ')');
		}

		// ��²���ƥ���
		if ($this->family != '')
			and_join($sql, $this->multi_cond('mb_family_ctg', $this->family));

		// �ｻ�ϰ�
		$temp = '';
		if ($this->kyojuu) {
			$ary = explode(',', $this->kyojuu);
			foreach ($ary as $item) {
				$ary2 = explode('/', $item);
				if (count($ary2) == 2) {
					or_join($temp, "zc_todoufuken='$ary2[0]'");
					and_join($temp, "zc_shikuchouson LIKE '$ary2[1]%'");
				} else
					or_join($temp, "zc_todoufuken='$item'");
			}
			if ($temp != '')
				$temp = "EXISTS (SELECT * FROM m_zip WHERE zc_zip_uniq=mb_zip AND ($temp))";
		}

		// �ｻ�ϰ衿��̳���ϰ� AND/OR
		if ($temp != '' && $temp2 != '') {
			if ($this->kinmu_andor == 'A') {
				and_join($sql, $temp);
				and_join($sql, $temp2);
			} else {
				or_join($temp, $temp2);
				and_join($sql, "($temp)");
			}
		} elseif ($temp != '')
			and_join($sql, $temp);
		elseif ($temp2 != '')
			and_join($sql, $temp2);

		// ��²��³��
		$temp = '';
		if ($this->family_rel != '') {
			$temp = $this->multi_cond('fm_family_rel_cd', $this->family_rel);
			if ($this->family_rel_andor == 'O')
				and_join($sql, "EXISTS (SELECT * FROM t_family WHERE fm_mb_seq_no=mb_seq_no AND $temp)");
			else {
				$cnt = count(explode(',', $this->family_rel));
				and_join($sql, "(SELECT COUNT(*) FROM t_family WHERE fm_mb_seq_no=mb_seq_no AND $temp)=$cnt");
			}
		}

		// �Ҷ���̵ͭ
		switch ($this->child) {
		case 't':
			and_join($sql, "EXISTS (SELECT * FROM t_family JOIN m_family_rel ON fr_family_rel_cd=fm_family_rel_cd WHERE fm_mb_seq_no=mb_seq_no AND fr_attb>=2)");
			break;
		case 'f':
			and_join($sql, "EXISTS (SELECT * FROM t_family WHERE fm_mb_seq_no_id=mb_seq_no) AND NOT EXISTS (SELECT * FROM t_family JOIN m_family_rel ON fr_family_rel_cd=fm_family_rel_cd WHERE fm_mb_seq_no=mb_seq_no AND fr_attb>=2)");
			break;
		}

		// ���ҡʺ�ǯ���ԡˤ�ǯ��
		$temp = '';
		if ($this->child_youngest_from != '' && $this->child_youngest_to != '')
			$temp = "fm_age BETWEEN $this->child_youngest_from AND $this->child_youngest_to";
		elseif ($this->child_youngest_from != '')
			$temp = "fm_age>=$this->child_youngest_from";
		elseif ($this->child_youngest_to != '')
			$temp = "fm_age<=$this->child_youngest_to";
		if ($temp != '') {
//			and_join($sql, "EXISTS (SELECT fm_mb_seq_no,MIN(fm_age) FROM t_family WHERE fm_mb_seq_no=mb_seq_no AND $temp GROUP BY fm_mb_seq_no)");
			$sub = "SELECT fm_mb_seq_no,MIN(fm_age) AS fm_age FROM t_family GROUP BY fm_mb_seq_no";
			and_join($sql, "EXISTS (SELECT fm_mb_seq_no FROM ($sub) AS f WHERE fm_mb_seq_no=mb_seq_no AND $temp GROUP BY fm_mb_seq_no)");
		}

		// �Ҷ��ʲ�²�ˤ�ǯ��
		$temp = '';
		if ($this->child_age_from != '' && $this->child_age_to != '')
			$temp = "fm_age BETWEEN $this->child_age_from AND $this->child_age_to";
		elseif ($this->child_age_from != '')
			$temp = "fm_age>=$this->child_age_from";
		elseif ($this->child_age_to != '')
			$temp = "fm_age<=$this->child_age_to";
		if ($temp != '')
			and_join($sql, "EXISTS (SELECT fm_mb_seq_no,fm_age FROM t_family WHERE fm_mb_seq_no=mb_seq_no AND $temp)");

		// ��������
		if ($this->gm != '') {
			if ($this->gm == '99')
				and_join($sql, "mb_gm_flag IS NULL");
			else
				and_join($sql, "mb_gm_flag=$this->gm");
		}

		// ��������
		if ($this->ol != '') {
			if ($this->ol == '99')
				and_join($sql, "mb_ol_flag IS NULL");
			else
				and_join($sql, "mb_ol_flag=$this->ol");
		}

		// �����������
		if ($this->gl != '') {
			if ($this->gl == '99')
				and_join($sql, "mb_gl_flag IS NULL");
			else
				and_join($sql, "mb_gl_flag=$this->gl");
		}

		// ����Ͽ��
		$temp = '';
		if ($this->reg_kari_y_from != '' && $this->reg_kari_y_to != '')
			$temp = "mb_reg_kari_date BETWEEN '{$this->reg_kari_y_from}-{$this->reg_kari_m_from}-01' AND '{$this->reg_kari_y_to}-{$this->reg_kari_m_to}-01'";
		elseif ($this->reg_kari_y_from != '')
			$temp = "mb_reg_kari_date>='{$this->reg_kari_y_from}-{$this->reg_kari_m_from}-01'";
		elseif ($this->reg_kari_y_to != '')
			$temp = "mb_reg_kari_date<='{$this->reg_kari_y_to}-{$this->reg_kari_m_to}-01'";
		if ($temp != '')
			and_join($sql, $temp);

		// ����Ͽ��
		$temp = '';
		if ($this->reg_y_from != '' && $this->reg_y_to != '')
			$temp = "mb_regist_date BETWEEN '{$this->reg_y_from}-{$this->reg_m_from}-01' AND '{$this->reg_y_to}-{$this->reg_m_to}-01'";
		elseif ($this->reg_y_from != '')
			$temp = "mb_regist_date>='{$this->reg_y_from}-{$this->reg_m_from}-01'";
		elseif ($this->reg_y_to != '')
			$temp = "mb_regist_date<='{$this->reg_y_to}-{$this->reg_m_to}-01'";
		if ($temp != '')
			and_join($sql, $temp);

		// Ϣ³��λ��/̤��λ��
		global $commit_flag, $commit_yet_flag;
		if ($commit_flag != '' || $commit_yet_flag != '') {
			$fetch = get_system_info(sy_batch_month);
			$batch_month = add_date($fetch->sy_batch_month, 0, -1, 0);
		}

		// ���񤫤�ηв���
		$temp = '';
		if ($this->entry_from != '' && $this->entry_to != '')
			$temp = "DATE_PART('month',AGE(mb_regist_month)) BETWEEN $this->entry_from AND $this->entry_to";
		elseif ($this->entry_from != '')
			$temp = "DATE_PART('month',AGE(mb_regist_month)) >= $this->entry_from";
		elseif ($this->entry_to != '')
			$temp = "DATE_PART('month',AGE(mb_regist_month)) < $this->entry_to";
		and_join($sql, $temp);

		// ���ʴ�λ���
		$temp = '';
		if($this->commit != 0) {
			if ($this->commit != '' && $this->commit != 1)
				$temp = "EXISTS (SELECT bd_mb_seq_no,count(*) FROM t_base_data WHERE bd_mb_seq_no=mb_seq_no AND bd_auto_commit=3 GROUP BY bd_mb_seq_no HAVING COUNT(bd_auto_commit) >= $this->commit)";
			if ($this->commit != '' && $this->commit == 1)
				$temp = "EXISTS (SELECT bd_mb_seq_no,count(*) FROM t_base_data WHERE bd_mb_seq_no=mb_seq_no AND bd_auto_commit=3 GROUP BY bd_mb_seq_no)";
			if ($temp != '')
				and_join($sql, $temp);
		}

		// ���ʴ�λ��̵ͭ
		$temp = '';
		if ($this->commit_m_y != '' && $this->commit_m_m != '') {
			$commit_m = "{$this->commit_m_y}-{$this->commit_m_m}-01";
			$temp = "EXISTS (SELECT count(*) FROM t_base_data WHERE bd_mb_seq_no=mb_seq_no AND bd_auto_commit=3 AND bd_month=" . sql_date($commit_m) . " GROUP BY bd_mb_seq_no)";
		}
		if ($temp != '')
			and_join($sql, $temp);

		// ���ϥե饰
		$temp = '';
		if ($this->commit_inp_ol != '') {
			$temp = "EXISTS (SELECT count(*) FROM t_base_data WHERE bd_mb_seq_no=mb_seq_no AND (bd_ol_inp_cd<>2 OR bd_ol_use>0) AND bd_month=" . sql_date($commit_m) . " GROUP BY bd_mb_seq_no)";
		if ($temp != '')
			and_join($sql, $temp);
			
		}

		$temp = '';
		if ($this->commit_inp_gm != '') {
			$temp = "EXISTS (SELECT count(*) FROM t_base_data WHERE bd_mb_seq_no=mb_seq_no AND (bd_gm_inp_cd<>2 OR bd_gm_use>0) AND bd_month=" . sql_date($commit_m) . " GROUP BY bd_mb_seq_no)";
		if ($temp != '')
			and_join($sql, $temp);
			
		}

		$temp = '';
		if ($this->commit_inp_gl != '') {
			$temp = "EXISTS (SELECT count(*) FROM t_base_data WHERE bd_mb_seq_no=mb_seq_no AND (bd_gl_inp_cd<>2 OR bd_gl_use>0) AND bd_month=" . sql_date($commit_m) . " GROUP BY bd_mb_seq_no)";
		if ($temp != '')
			and_join($sql, $temp);
			
		}
		return $sql;
	}

	// ʣ�����ʸ����
	function multi_cond($column, $cond) {
		if (mb_strpos($cond, ','))
			return "$column IN ($cond)";
		else
			return "$column=$cond";
	}

	// �����������
	function get_condition() {

		function decode_flag($code) {
			switch($code) {
			case 0:
				return '�ʤ�';
			case 1:
				return '����';
			default:
				return '̤����';
			}
		}
		// ����
		if ($this->step != '') {
			switch($this->step) {
			case 1:
				$temp = '����Ͽ��';
				break;
			case 2:
				$temp = '����Ͽ��';
				break;
			case 3:
				$temp = '����Ͽ';
				break;
			default:
				break;
			}
			$text .= '����Ͽ������' . $temp . "\n";
		}

		// ����
		if ($this->sex != '')
			$text .= '�����̡�' . decode_sex($this->sex) . "\n";

		// ǯ��
		if ($this->age_from != '' || $this->age_to != '') {
			$temp = '';
			if ($this->age_from != '')
				$temp = "{$this->age_from}��";
			$temp .= '��';
			if ($this->age_to != '')
				$temp .= "{$this->age_to}��";
			$text .= "��ǯ��:$temp\n";
		}

		// �ｻ�ϰ�
		if ($this->area2 != '') {
			$sql = "SELECT aa_area2_name FROM m_area2 WHERE aa_area2_cd IN ($this->area2) ORDER BY aa_area2_cd";
			$text .= '���ｻ�ϰ衧' . $this->get_name($sql) . "\n";
		}

		// ��ƻ�ܸ�
		if ($this->kyojuu != '')
			$text .= '���ｻ�ϰ衧' . str_replace('/', '', $this->kyojuu) . "\n";

		// �������
		if ($this->keitai != '') {
			$sql = "SELECT kt_keitai_name FROM m_keitai WHERE kt_keitai_cd IN ($this->keitai) ORDER BY kt_order";
			$text .= '��������֡�' . $this->get_name($sql) . "\n";
		}

		// �����Ǻ�
		if ($this->sozai != '') {
			$sql = "SELECT sz_sozai_name FROM m_sozai WHERE sz_sozai_cd IN ($this->sozai) ORDER BY sz_order";
			$text .= '�����ﹽ¤��' . $this->get_name($sql) . "\n";
		}

		// �ء��٥�ϥ���
		if ($this->hebel != '') {
			$temp = $this->hebel == 1?'�Ϥ�':'������';
			$text .= '���ء��٥�ϥ�����' . $temp . "\n";
		}

		// ����ǯ��
		if ($this->chikunen != '') {
			$sql = "SELECT cn_chikunen_text FROM m_chikunen WHERE cn_chikunen_cd IN ($this->chikunen) ORDER BY cn_chikunen_cd";
			$text .= '������ǯ����' . $this->get_name($sql) . "\n";
		}

		// ����ּ��
		if ($this->room != '') {
			$sql = "SELECT rm_room_name FROM m_room WHERE rm_room_cd IN ($this->room) ORDER BY rm_order";
			$text .= '������ּ�ꡧ' . $this->get_name($sql) . "\n";
		}

		// ��������
		if ($this->space != '') {
			$sql = "SELECT sp_space_text FROM m_space WHERE sp_space_cd IN ($this->space) ORDER BY sp_space_cd";
			$text .= '���������ѡ�' . $this->get_name($sql) . "\n";
		}

		// ����勵����
		if ($this->boiler != '') {
			$sql = "SELECT bt_boiler_name FROM m_boiler_type WHERE bt_boiler_type_cd IN ($this->boiler) ORDER BY bt_boiler_type_cd";
			$text .= '������勵���ס�' . $this->get_name($sql) . "\n";
		}

		// ������
		if ($this->gas_kind != '') {
			$sql = "SELECT gk_gas_kind_name FROM m_gas_kind WHERE gk_gas_kind_cd IN ($this->gas_kind) ORDER BY gk_order";
			$text .= '�������' . $this->get_name($sql) . "\n";
		}

		// ����������
		if ($this->gas_type != '') {
			$sql = "SELECT gt_gas_type_name FROM m_gas_type WHERE gt_gas_type_cd IN ($this->gas_type) ORDER BY gt_order";
			$text .= '�����������ס�' . $this->get_name($sql) . "\n";
		}

		// ��ƻ���˷�
		if ($this->water != '') {
			$temp = $this->water == 1?'�����':'������';
			$text .= '����ƻ���˷' . $temp . "\n";
		}

		// �ֽ�ͭ
		if ($this->gcar_from != '' || $this->gcar_to != '' || $this->dcar_from != '' || $this->dcar_to != '' || $this->bike_from != '' || $this->bike_to != '')
			$text .= '���ֽ�ͭ�������' . decode_andor($this->car_andor) . "\n";

		// �������ֽ�ͭ
		if ($this->gcar_from != '' || $this->gcar_to) {
			$temp = '';
			if ($this->gcar_from != '')
				$temp = "{$this->gcar_from}��";
			$temp .= '��';
			if ($this->gcar_to != '')
				$temp .= "{$this->gcar_to}��";
			$text .= "���������ֽ�ͭ��$temp\n";
		}

		// �ǥ�������ֽ�ͭ
		if ($this->dcar_from != '' || $this->dcar_to) {
			$temp = '';
			if ($this->dcar_from != '')
				$temp = "{$this->dcar_from}��";
			$temp .= '��';
			if ($this->dcar_to != '')
				$temp .= "{$this->dcar_to}��";
			$text .= "���ǥ�������ֽ�ͭ��$temp\n";
		}

		// �Х�����ͭ
		if ($this->bike_from != '' || $this->bike_to) {
			$temp = '';
			if ($this->bike_from != '')
				$temp = "{$this->bike_from}��";
			$temp .= '��';
			if ($this->bike_to != '')
				$temp .= "{$this->bike_to}��";
			$text .= "���Х�����ͭ��$temp\n";
		}

		// ��²���ƥ���
		if ($this->family != '') {
			$sql = "SELECT fc_text FROM m_family_ctg WHERE fc_family_ctg_cd IN ($this->family) ORDER BY fc_family_ctg_cd";
			$text .= '����²���ƥ��ꡧ' . $this->get_name($sql) . "\n";
		}

		// ��²��³��
		if ($this->family_rel != '') {
			$sql = "SELECT fr_name FROM m_family_rel WHERE fr_family_rel_cd IN ($this->family_rel) ORDER BY fr_order";
			$text .= '����²��³���������' . decode_andor($this->family_rel_andor) . $this->get_name($sql) . "\n";
		}

		// �Ҷ���̵ͭ
		if ($this->child != '') {
			switch ($this->child) {
			case 't':
				$temp = 'ͭ��';
				break;
			case '2':
				$temp = '̵��';
				break;
			default:
				$temp = '';
				break;
			}
			$text .= "���Ҷ���̵ͭ��$temp\n";
		}

		// ���ҡʺ�ǯ���ԡˤ�ǯ��
		if ($this->child_youngest_from != '' || $this->child_youngest_to != '') {
			$temp = '';
			if ($this->child_youngest_from != '')
				$temp = "{$this->child_youngest_from}��";
			$temp .= '��';
			if ($this->child_youngest_to != '')
				$temp .= "{$this->child_youngest_to}��";
			$text .= "����ǯ����²��ǯ��$temp\n";
		}

		// �Ҷ��ʲ�²�ˤ�ǯ��
		if ($this->child_age_from != '' || $this->child_age_to != '') {
			$temp = '';
			if ($this->child_age_from != '')
				$temp = "{$this->child_age_from}��";
			$temp .= '��';
			if ($this->child_age_to != '')
				$temp .= "{$this->child_age_to}��";
			$text .= "����²��ǯ��$temp\n";
		}

		// ��������
		if ($this->gm != '')
			$text .= '���������ϡ�' . decode_flag($this->gm) . "\n";

		// ��������
		if ($this->ol != '')
			$text .= '�����������ϡ�' . decode_flag($this->ol) . "\n";

		// �����������
		if ($this->gl != '')
			$text .= '������������ϡ�' . decode_flag($this->gl) . "\n";

		// ����Ͽ��
		if ($this->reg_kari_y_from != '' || $this->reg_kari_y_to != '') {
			$temp = '';
			if ($this->reg_kari_y_from != '')
				$temp = "{$this->reg_kari_y_from}ǯ{$this->reg_kari_m_from}��";
			$temp .= '��';
			if ($this->reg_kari_y_to != '')
				$temp .= "{$this->reg_kari_y_to}ǯ{$this->reg_kari_m_to}��";
			$text .= "������Ͽ�$temp\n";
		}

		// ����Ͽ��
		if ($this->reg_y_from != '' || $this->reg_y_to != '') {
			$temp = '';
			if ($this->reg_y_from != '')
				$temp = "{$this->reg_y_from}ǯ{$this->reg_m_from}��";
			$temp .= '��';
			if ($this->reg_y_to != '')
				$temp .= "{$this->reg_y_to}ǯ{$this->reg_m_to}��";
			$text .= "������Ͽ�$temp\n";
		}

		// ����в���
		if ($this->entry_from != '' || $this->entry_to != '') {
			$temp = '';
			if ($this->entry_from != '')
				$temp = "{$this->entry_from}����";
			$temp .= '��';
			if ($this->entry_to != '')
				$temp .= "{$this->entry_to}����";
			$text .= "������в�����$temp\n";
		}

		// ���ʴ�λ�β��
		if ($this->commit != '')
			$text .= '�����ʴ�λ�����' . $this->commit . "�� �ʾ�\n";

		// ���ʴ�λ��̵ͭ
		if ($this->commit_m_y != '') {
			$text .= "�����ʴ�λ��̵ͭ��{$this->commit_m_y}ǯ{$this->commit_m_m}�� �˼��ʴ�λ����";

			// ���ʴ�λ��̵ͭ
			if ($this->commit_inp_ol != '')
				$text .= "���ʡ����������Ϥ��ʤ��פ���������";

			if ($this->commit_inp_gm != '')
				$text .= "���ʡ֥��ߤ����Ϥ��ʤ��פ���������";

			if ($this->commit_inp_gl != '')
				$text .= "���ʡ֥����������Ϥ��ʤ��פ���������";
		}

		// ���̵��
		if ($text == '')
			$text = '���̵��';

		return $text;
	}

	// �ޥ�������̾�μ���
	function get_name($sql) {
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_row($result, $i);
			$name .= "\n����{$fetch[0]}��";
		}
		return $name;
	}
}
?>