<?
/******************************************************
' System :Eco-footprint ����
' Content:ɽ����說�饹
'******************************************************/

class list_item_class {
	var $where;
	var $find;
	var $mb_count;
	var $condition;
	var $sql;
	var $from_join;

	var $cvs_data;
	var $colunm;
	var $oeder;
	var $sort_header;
	var $renew_flag;

	var $id;
	var $name;
	var $kana;
	var $mail_addr;
	var $sex;
	var $birthday;
	var $age;
	var $area;
	var $zip;
	var $todoufuken;
	var $address;

	var $keitai;
	var $sozai;
	var $hebel;
	var $chikunen;
	var $room;
	var $space;
	var $boiler;
	var $gas_kind;
	var $gas_type;
	var $wt_month;
	var $car;
	var $gcar;
	var $dcar;
	var $bike;

	var $fm_ctg;
	var $fm_detail;
	var $fm_num;
	var $fm_rel;
	var $fm_child;
	var $fm_youngest;
	var $fm_age;

	var $inp_gm;
	var $inp_ol;
	var $inp_gl;

	var $reg_date_kari;
	var $reg_date;
	var $entry;
	var $reg_inp;

	var $inp_y;
	var $inp_m;
	var $commit_m;
	var $inp_flag_gm, $inp_flag_ol, $inp_flag_gl;
	var $use_al, $use_el, $use_gs, $use_wt, $use_gm, $use_gl, $use_ol;
	var $sum_al, $sum_el, $sum_gs, $sum_wt, $sum_gl, $sum_ol, $sum_4total, $sum_6total;
	var $co2_al, $co2_el, $co2_gs, $co2_wt, $co2_gm, $co2_gl, $co2_ol, $co2_4total, $co2_6total;
	var $use_cut_al, $use_cut_el, $use_cut_gs, $use_cut_wt, $use_cut_gm, $use_cut_gl, $use_cut_ol;
	var $sum_cut_al, $sum_cut_el, $sum_cut_gs, $sum_cut_wt, $sum_cut_gl, $sum_cut_ol, $sum_4cut, $sum_6cut;
	var $co2_cut_al, $co2_cut_el, $co2_cut_gs, $co2_cut_wt, $co2_cut_gm, $co2_cut_gl, $co2_cut_ol, $co2_4cut, $co2_6cut;
	var $use_rui_al, $use_rui_cut_el, $use_rui_cut_gas, $use_rui_cut_gm, $use_rui_cut_gl, $use_rui_cut_ol;
	var $sum_al, $sum_rui_cut_el, $sum_rui_cut_gas, $sum_rui_cut_wt, $sum_rui_cut_gl, $sum_rui_cut_ol, $sum_4rui, $sum_6rui;
	var $co2_rui_al, $co2_rui_cut_el, $co2_rui_cut_gas, $co2_rui_cut_gm, $co2_rui_cut_gl, $co2_rui_cut_ol, $co2_4rui, $co2_6rui;

	// ���󥹥ȥ饯��
	function search_class() {
		$this->active = true;
		$this->step = '3';
		$this->family_rel_andor = 'O';
		$this->car_andor = 'O';;
		$this->commit_yet_highlow = '1';
		$this->commit_highlow = '1';
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
		global $where, $find, $mb_count, $condition, $sql, $from_join;
		global $id, $name, $kana, $mail_addr, $sex, $birthday, $age, $area, $zip, $todoufuken, $address;
		global $keitai, $sozai, $hebel, $chikunen, $room, $space, $boiler, $gas_kind, $gas_type, $wt_month, $car, $gcar, $dcar, $bike;
		global $fm_ctg, $fm_detail, $fm_num, $fm_child, $fm_youngest, $fm_age;
		global $inp_gm, $inp_ol, $inp_gl;
		global $reg_date_kari, $reg_date, $entry, $reg_inp;
		global $inp_y, $inp_m, $commit_m;
		global $inp_flag_gm, $inp_flag_ol, $inp_flag_gl;
		global $use_al, $use_el, $use_gs, $use_wt, $use_gm, $use_gl, $use_ol;
		global $sum_al, $sum_el, $sum_gs, $sum_wt, $sum_gl, $sum_ol, $sum_4total, $sum_6total;
		global $co2_al, $co2_el, $co2_gs, $co2_wt, $co2_gm, $co2_gl, $co2_ol,$co2_4total, $co2_6total;
		global $use_cut_al, $use_cut_el, $use_cut_gs, $use_cut_wt, $use_cut_gm, $use_cut_gl, $use_cut_ol;
		global $sum_cut_al, $sum_cut_el, $sum_cut_gs, $sum_cut_wt, $sum_cut_gl, $sum_cut_ol, $sum_4cut, $sum_6cut;
		global $co2_cut_al, $co2_cut_el, $co2_cut_gs, $co2_cut_wt, $co2_cut_gm, $co2_cut_gl, $co2_cut_ol, $co2_4cut, $co2_6cut;
		global $use_rui_al, $use_rui_el, $use_rui_gs, $use_rui_wt, $use_rui_gm, $use_rui_gl, $use_rui_ol;
		global $sum_rui_al, $sum_rui_el, $sum_rui_gs, $sum_rui_wt, $sum_rui_gl, $sum_rui_ol, $sum_4rui, $sum_6rui;
		global $co2_rui_al, $co2_rui_el, $co2_rui_gs, $co2_rui_wt, $co2_rui_gm, $co2_rui_gl, $co2_rui_ol, $co2_4rui, $co2_6rui;

		$this->where = $where;
		$this->find = $find;
		$this->mb_count = $mb_count;
		$this->condition = $condition;
		$this->sql = $sql;
		$this->from_join = $from_join;

		$this->id = $id;
		$this->name = $name;
		$this->kana = $kana;
		$this->mail_addr = $mail_addr;
		$this->sex = $sex;
		$this->birthday = $birthday;
		$this->age = $age;
		$this->area = $area;
		$this->zip = $zip;
		$this->todoufuken = $todoufuken;
		$this->address = $address;

		$this->keitai = $keitai;
		$this->sozai = $sozai;
		$this->hebel = $hebel;
		$this->chikunen = $chikunen;
		$this->room = $room;
		$this->space = $space;
		$this->boiler = $boiler;
		$this->gas_kind = $gas_kind;
		$this->gas_type = $gas_type;
		$this->wt_month = $wt_month;
		$this->car = $car;
		$this->gcar = $gcar;
		$this->dcar = $dcar;
		$this->bike = $bike;

		$this->fm_ctg = $fm_ctg;
		$this->fm_detail = $fm_detail;
		$this->fm_num = $fm_num;
		$this->fm_child = $fm_child;
		$this->fm_youngest = $fm_youngest;

		$this->inp_gm = $inp_gm;
		$this->inp_ol = $inp_ol;
		$this->inp_gl = $inp_gl;

		$this->reg_date_kari = $reg_date_kari;
		$this->reg_date = $reg_date;
		$this->entry = $entry;
		$this->reg_inp = $reg_inp;
		$this->inp_y = $inp_y;
		$this->inp_m = $inp_m;

		if ($this->inp_y != '') {
			$this->commit_m = $inp_y;

			$this->inp_flag_gm = $inp_flag_gm;
			$this->inp_flag_ol = $inp_flag_ol;
			$this->inp_flag_gl = $inp_flag_gl;

			$this->use_al = $use_al;
			$this->use_el = $use_el;
			$this->use_gs = $use_gs;
			$this->use_wt = $use_wt;
			$this->use_gm = $use_gm;
			$this->use_gl = $use_gl;
			$this->use_ol = $use_ol;
			$this->sum_al = $sum_al;
			$this->sum_el = $sum_el;
			$this->sum_gs = $sum_gs;
			$this->sum_wt = $sum_wt;
			$this->sum_gl = $sum_gl;
			$this->sum_ol = $sum_ol;
			$this->sum_4total = $sum_4total;
			$this->sum_6total = $sum_6total;
			$this->co2_al = $co2_al;
			$this->co2_el = $co2_el;
			$this->co2_gs = $co2_gs;
			$this->co2_wt = $co2_wt;
			$this->co2_gm = $co2_gm;
			$this->co2_gl = $co2_gl;
			$this->co2_ol = $co2_ol;
			$this->co2_4total = $co2_4total;
			$this->co2_6total = $co2_6total;

			$this->use_cut_al = $use_cut_al;
			$this->use_cut_el = $use_cut_el;
			$this->use_cut_gs = $use_cut_gs;
			$this->use_cut_wt = $use_cut_wt;
			$this->use_cut_gm = $use_cut_gm;
			$this->use_cut_gl = $use_cut_gl;
			$this->use_cut_ol = $use_cut_ol;
			$this->sum_cut_al = $sum_cut_al;
			$this->sum_cut_el = $sum_cut_el;
			$this->sum_cut_gs = $sum_cut_gs;
			$this->sum_cut_wt = $sum_cut_wt;
			$this->sum_cut_gl = $sum_cut_gl;
			$this->sum_cut_ol = $sum_cut_ol;
			$this->sum_4cut = $sum_4cut;
			$this->sum_6cut = $sum_6cut;
			$this->co2_cut_al = $co2_cut_al;
			$this->co2_cut_el = $co2_cut_el;
			$this->co2_cut_gs = $co2_cut_gs;
			$this->co2_cut_wt = $co2_cut_wt;
			$this->co2_cut_gm = $co2_cut_gm;
			$this->co2_cut_gl = $co2_cut_gl;
			$this->co2_cut_ol = $co2_cut_ol;
			$this->co2_4cut = $co2_4cut;
			$this->co2_6cut = $co2_6cut;

			$this->use_rui_al = $use_rui_al;
			$this->use_rui_el = $use_rui_el;
			$this->use_rui_gs = $use_rui_gs;
			$this->use_rui_wt = $use_rui_wt;
			$this->use_rui_gm = $use_rui_gm;
			$this->use_rui_gl = $use_rui_gl;
			$this->use_rui_ol = $use_rui_ol;
			$this->sum_rui_al = $sum_rui_al;
			$this->sum_rui_el = $sum_rui_el;
			$this->sum_rui_gs = $sum_rui_gs;
			$this->sum_rui_wt = $sum_rui_wt;
			$this->sum_rui_gl = $sum_rui_gl;
			$this->sum_rui_ol = $sum_rui_ol;
			$this->sum_4rui = $sum_4rui;
			$this->sum_6rui = $sum_6rui;
			$this->co2_rui_al = $co2_rui_al;
			$this->co2_rui_el = $co2_rui_el;
			$this->co2_rui_gs = $co2_rui_gs;
			$this->co2_rui_wt = $co2_rui_wt;
			$this->co2_rui_gm = $co2_rui_gm;
			$this->co2_rui_gl = $co2_rui_gl;
			$this->co2_rui_ol = $co2_rui_ol;
			$this->co2_4rui = $co2_4rui;
			$this->co2_6rui = $co2_6rui;
		}
	}

	function make_header() {

		// �ꥹ�Ⱦ������
		function get_list_info($col, $order_col, $col_name, &$column, &$order, &$sort_header) {
			$column[] = $col;
			$order[] = $order_col;
			$sort_header[] = $col_name;
		}

		if ($column == '') {
			$column = '';
			$order = '';
			$sort_header = '';

			// ���ID
			if ($this->id != '')
				get_list_info('mb_id', 'mb_id', '�ɣ�', $column, $order, $sort_header);

			// ���̾�ʴ�����
			if ($this->name != '')
				get_list_info('(mb_name1 || mb_name2) AS mb_name', 'mb_name', '��̾', $column, $order, $sort_header);

			// ���̾�ʥեꥬ�ʡ�
			if ($this->kana != '')
				get_list_info('(mb_name1_kana || mb_name2_kana) AS mb_kana', 'mb_kana', '�եꥬ��', $column, $order, $sort_header);

			// �᡼�륢�ɥ쥹
			if ($this->mail_addr != '')
				get_list_info('mb_mail_addr', 'mb_mail_addr', '�᡼�륢�ɥ쥹', $column, $order, $sort_header);

			// ����
			if ($this->sex != '')
				get_list_info('mb_sex', 'mb_sex', '����', $column, $order, $sort_header);

			// ��ǯ����
			if ($this->birthday != '')
				get_list_info('mb_birthday', 'mb_birthday', '��ǯ����', $column, $order, $sort_header);

			// ǯ��
			if ($this->age != '')
				get_list_info('DATE_PART(\'Y\',AGE(mb_birthday)) AS mb_age', 'mb_age', 'ǯ��', $column, $order, $sort_header);

			// �ｻ�ϰ�
			if ($this->area != '')
				get_list_info('mb_area_cd AS mb_area2', 'mb_area2', '�ｻ�ϰ�', $column, $order, $sort_header);

			// ͹���ֹ�
			if ($this->zip != '')
				get_list_info('mb_zip', 'mb_zip', '͹���ֹ�', $column, $order, $sort_header);

			// ��ƻ�ܸ�
			if ($this->todoufuken != '')
				get_list_info('mb_area_cd', 'mb_area_cd', '��ƻ�ܸ�', $column, $order, $sort_header);

			// ����
			if ($this->address != '')
				get_list_info('mb_address', 'mb_address', '����', $column, $order, $sort_header);

			// �������
			if ($this->keitai != '')
				get_list_info('mb_keitai_cd', 'mb_keitai_cd', '�������', $column, $order, $sort_header);

			// ���ﹽ¤
			if ($this->sozai != '')
				get_list_info('mb_sozai_cd', 'mb_sozai_cd', '���ﹽ¤', $column, $order, $sort_header);

			// �ء��٥�ϥ���
			if ($this->hebel != '')
				get_list_info('mb_hebel_flag', 'mb_hebel_flag', '�ء��٥�ϥ���', $column, $order, $sort_header);

			// ����ǯ��
			if ($this->chikunen != '')
				get_list_info('mb_chikunen_cd', 'mb_chikunen_cd', '����ǯ��', $column, $order, $sort_header);

		// ����ּ��
			if ($this->room != '')
				get_list_info('mb_room_cd', 'mb_room_cd', '�ּ��', $column, $order, $sort_header);

			// ��������
			if ($this->space != '')
				get_list_info('mb_space_cd', 'mb_space_cd', '�ｻ����', $column, $order, $sort_header);

			// ����勵����
			if ($this->boiler != '')
				get_list_info('mb_boiler_cd', 'mb_boiler_cd', '����勵����', $column, $order, $sort_header);

			// ������
			if ($this->gas_kind != '')
				get_list_info('mb_gas_kind_cd', 'mb_gas_kind_cd', '������', $column, $order, $sort_header);

			// ����������
			if ($this->gas_type != '')
				get_list_info('mb_gas_type_cd', 'mb_gas_type_cd', '����������', $column, $order, $sort_header);

			// ��ƻ���˷�
			if ($this->wt_month != '')
				get_list_info('mb_water_month', 'mb_water_month', '��ƻ�����', $column, $order, $sort_header);

			// �����
			if ($this->car != '')
				get_list_info('(mb_gcar_num+mb_dcar_num+mb_bike_num) AS mb_car_num', 'mb_car_num', '�����', $column, $order, $sort_header);

			// ��������
			if ($this->gcar != '')
				get_list_info('mb_gcar_num', 'mb_gcar_num', '��������', $column, $order, $sort_header);

			// �ǥ��������
			if ($this->dcar != '')
				get_list_info('mb_dcar_num', 'mb_dcar_num', '�ǥ��������', $column, $order, $sort_header);

			// �Х���
			if ($this->bike != '')
				get_list_info('mb_bike_num', 'mb_bike_num', '�Х���', $column, $order, $sort_header);

			// ��²���ƥ���
			if ($this->fm_ctg != '')
				get_list_info('mb_family_ctg', 'mb_family_ctg', '��²���ƥ���', $column, $order, $sort_header);

			// ��²��³����ǯ��
			if ($this->fm_detail != '')
				get_list_info('mb_seq_no as fm_detail', 'fm_detail', '��²��³����ǯ��', $column, $order, $sort_header);

			// �Ҥɤ��̵ͭ
			if ($this->fm_child != '')
				get_list_info('(SELECT COUNT(fm_mb_seq_no) AS fm FROM t_family WHERE fm_mb_seq_no=mb_seq_no AND fm_age < 20) AS fm_child', 'fm_child', '�Ҷ�(20��̤��)��̵ͭ', $column, $order, $sort_header);

			if ($this->fm_youngest != '')
				get_list_info('(SELECT MIN(fm_age) AS age FROM t_family WHERE fm_mb_seq_no=mb_seq_no) AS fm_youngest', 'fm_youngest', '��ǯ����²��ǯ��', $column, $order, $sort_header);

			// ��������
			if ($this->inp_ol != '')
				get_list_info('mb_ol_flag', 'mb_ol_flag', '��������', $column, $order, $sort_header);

			// ��������
			if ($this->inp_gm != '')
				get_list_info('mb_gm_flag', 'mb_gm_flag', '��������', $column, $order, $sort_header);

			// �����������
			if ($this->inp_gl != '')
				get_list_info('mb_gl_flag', 'mb_gl_flag', '�����������', $column, $order, $sort_header);

			// ����Ͽ��
			if ($this->reg_date_kari != '')
				get_list_info('mb_reg_kari_date', 'mb_reg_kari_date', '����Ͽ��', $column, $order, $sort_header);

			// ����Ͽ��
			if ($this->reg_date != '')
				get_list_info('mb_regist_date', 'mb_regist_date', '����Ͽ��', $column, $order, $sort_header);

			// ����в���
			if ($this->entry != '')
				get_list_info('DATE_PART(\'month\',AGE(mb_regist_month)) AS entry', 'entry', '����в���', $column, $order, $sort_header);

			if ($this->reg_inp != '') {
				$column[] = "(SELECT COUNT(CASE WHEN bd_auto_commit=3 THEN 1 ELSE null END) AS reg_inp FROM t_base_data WHERE bd_mb_seq_no=mb_seq_no) AS reg_inp";
				$order[] = 'reg_inp';
				$sort_header[] = '���ʴ�λ���';
			}

			// ���Ͼ������
			function get_data_info($kind, $eng, $col_name, &$column, &$order, &$sort_header) {
				$column[] = 'ba_' . $kind . '_' . $eng;
				$order[] = 'ba_' . $kind . '_' . $eng;
				$sort_header[] = $col_name;
			}

			// ���Ϸ�̾���
			if ($this->inp_y != '') {
				$this_month = $this->inp_y . "-" . $this->inp_m . "-01";
				$last_month = add_date($this_month, 0, -12, 0);
				$from_join = ' LEFT JOIN t_base_data ON mb_seq_no=bd_mb_seq_no AND bd_month=' . sql_date($this_month)
					. ' LEFT JOIN t_batch_data ON bd_seq_no=ba_bd_seq_no AND ba_ym=' . sql_date($this_month);
			}

			// ��λ��̵ͭ
			if ($this->commit_m != '' && $this->inp_y != '') {
				$column[] = "bd_auto_commit";
				$order[] = "bd_auto_commit";
				$sort_header[] = '��λ';

				// ����������
				if ($this->use_al != '') {
					$this->use_el = 'on';
					$this->use_gs = 'on';
					$this->use_wt = 'on';
					$this->use_gm = 'on';
					$this->use_gl = 'on';
					$this->use_ol = 'on';
				}
				// �������
				if ($this->sum_al != '') {
					$this->sum_el = 'on';
					$this->sum_gs = 'on';
					$this->sum_wt = 'on';
					$this->sum_gl = 'on';
					$this->sum_ol = 'on';
				}
				// CO2����
				if ($this->co2_al != '') {
					$this->co2_el = 'on';
					$this->co2_gs = 'on';
					$this->co2_wt = 'on';
					$this->co2_gm = 'on';
					$this->co2_gl = 'on';
					$this->co2_ol = 'on';
				}

				// �������ϥե饰
				if ($this->inp_flag_ol != '')
					get_list_info('bd_ol_inp_cd', 'bd_ol_inp_cd', '�������ώ̎׎���', $column, $order, $sort_header);

				// ����������ϥե饰
				if ($this->inp_flag_gl != '')
					get_list_info('bd_gl_inp_cd', 'bd_gl_inp_cd', '���ގ��؎����ώ̎׎���', $column, $order, $sort_header);

				// �������ϥե饰
				if ($this->inp_flag_gm != '')
					get_list_info('bd_gm_inp_cd', 'bd_gm_inp_cd', '�������ώ̎׎���', $column, $order, $sort_header);

				// �ŵ�������
				if ($this->use_el != '')
					get_data_info('use', 'ele', '�ŵ�������', $column, $order, $sort_header);

				// ����������
				if ($this->use_gs != '')
					get_data_info('use', 'gas', '����������', $column, $order, $sort_header);

				// ��ƻ������
				if ($this->use_wt != '')
					get_data_info('use', 'wtr', '��ƻ������', $column, $order, $sort_header);

				// ����������
				if ($this->use_ol != '')
					get_data_info('use', 'oil', '����������', $column, $order, $sort_header);

				// ������������
				if ($this->use_gl != '')
					get_data_info('use', 'gso', '���ގ��؎ݻ�����', $column, $order, $sort_header);

				// ���߻�����
				if ($this->use_gm != '')
					get_data_info('use', 'dst', '���߻�����', $column, $order, $sort_header);

				// �ŵ����
				if ($this->sum_el != '')
					get_data_info('sum', 'ele', '�ŵ����', $column, $order, $sort_header);

				// �������
				if ($this->sum_gs != '')
					get_data_info('sum', 'gas', '�������', $column, $order, $sort_header);

				// ��ƻ���
				if ($this->sum_wt != '')
					get_data_info('sum', 'wtr', '��ƻ���', $column, $order, $sort_header);

				// �������
				if ($this->sum_ol != '')
					get_data_info('sum', 'oil', '�������', $column, $order, $sort_header);

				// ���������
				if ($this->sum_gl != '')
					get_data_info('sum', 'gso', '���ގ��؎ݶ��', $column, $order, $sort_header);

				// �����׶��
				if ($this->sum_4total != '')
					get_data_info('sum', '4total', '4����', $column, $order, $sort_header);

				// �����׶��
				if ($this->sum_6total != '')
					get_data_info('sum', '6total', '6����', $column, $order, $sort_header);

				// �ŵ�CO2��
				if ($this->co2_el != '')
					get_data_info('co2', 'ele', '�ŵ�CO2��', $column, $order, $sort_header);

				// ����CO2��
				if ($this->co2_gs != '')
					get_data_info('co2', 'gas', '����CO2��', $column, $order, $sort_header);

				// ��ƻCO2��
				if ($this->co2_wt != '')
					get_data_info('co2', 'wtr', '��ƻCO2��', $column, $order, $sort_header);

				// ����CO2��
				if ($this->co2_ol != '')
					get_data_info('co2', 'oil', '����CO2��', $column, $order, $sort_header);

				// �������CO2��
				if ($this->co2_gl != '')
					get_data_info('co2', 'gso', '���ގ��؎�CO2��', $column, $order, $sort_header);

				// ����CO2��
				if ($this->co2_gm != '')
					get_data_info('co2', 'dst', '����CO2��', $column, $order, $sort_header);

				// ������CO2�ӽ���
				if ($this->co2_4total != '')
					get_data_info('co2', '4total', '4��CO2��', $column, $order, $sort_header);

				// ������CO2�ӽ���
				if ($this->co2_6total != '')
					get_data_info('co2', '6total', '6��CO2��', $column, $order, $sort_header);

				// �︺����������
				if ($this->use_cut_al != '') {
					$this->use_cut_el = 'on';
					$this->use_cut_gs = 'on';
					$this->use_cut_wt = 'on';
					$this->use_cut_gm = 'on';
					$this->use_cut_gl = 'on';
					$this->use_cut_ol = 'on';
				}
				// �︺�������
				if ($this->sum_cut_al != '') {
					$this->sum_cut_el = 'on';
					$this->sum_cut_gs = 'on';
					$this->sum_cut_wt = 'on';
					$this->sum_cut_gl = 'on';
					$this->sum_cut_ol = 'on';
				}
				// �︺CO2����
				if ($this->co2_cut_al != '') {
					$this->co2_cut_el = 'on';
					$this->co2_cut_gs = 'on';
					$this->co2_cut_wt = 'on';
					$this->co2_cut_gm = 'on';
					$this->co2_cut_gl = 'on';
					$this->co2_cut_ol = 'on';
				}

				// �ŵ��︺������
				if ($this->use_cut_el != '')
					get_data_info('use_cut', 'ele', '�ŵ��︺������', $column, $order, $sort_header);

				// �����︺������
				if ($this->use_cut_gs != '')
					get_data_info('use_cut', 'gas', '�����︺������', $column, $order, $sort_header);

				// ��ƻ�︺������
				if ($this->use_cut_wt != '')
					get_data_info('use_cut', 'wtr', '��ƻ�︺������', $column, $order, $sort_header);

				// �����︺������
				if ($this->use_cut_ol != '')
					get_data_info('use_cut', 'oil', '�����︺������', $column, $order, $sort_header);

				// �������︺������
				if ($this->use_cut_gl != '')
					get_data_info('use_cut', 'gso', '���ގ��؎ݺ︺������', $column, $order, $sort_header);

				// ���ߺ︺������
				if ($this->use_cut_gm != '')
					get_data_info('use_cut', 'dst', '���ߺ︺������', $column, $order, $sort_header);

				// �ŵ��︺���
				if ($this->sum_cut_el != '')
					get_data_info('sum_cut', 'ele', '�ŵ��︺���', $column, $order, $sort_header);

				// �����︺���
				if ($this->sum_cut_gs != '')
					get_data_info('sum_cut', 'gas', '�����︺���', $column, $order, $sort_header);

				// ��ƻ�︺���
				if ($this->sum_cut_wt != '')
					get_data_info('sum_cut', 'wtr', '��ƻ�︺���', $column, $order, $sort_header);

				// �����︺���
				if ($this->sum_cut_ol != '')
					get_data_info('sum_cut', 'oil', '�����︺���', $column, $order, $sort_header);

				// �������︺���
				if ($this->sum_cut_gl != '')
					get_data_info('sum_cut', 'gso', '���ގ��؎ݺ︺���', $column, $order, $sort_header);

				// �����׺︺���
				if ($this->sum_4cut != '')
					get_data_info('sum', '4cut', '4��︺���', $column, $order, $sort_header);

				// �����׺︺���
				if ($this->sum_6cut != '')
					get_data_info('sum', '6cut', '6��︺���', $column, $order, $sort_header);

				// �ŵ��︺CO2��
				if ($this->co2_cut_el != '')
					get_data_info('co2_cut', 'ele', '�ŵ��︺CO2��', $column, $order, $sort_header);

				// �����︺CO2��
				if ($this->co2_cut_gs != '')
					get_data_info('co2_cut', 'gas', '�����︺CO2��', $column, $order, $sort_header);

				// ��ƻ�︺CO2��
				if ($this->co2_cut_wt != '')
					get_data_info('co2_cut', 'wtr', '��ƻ�︺CO2��', $column, $order, $sort_header);

				// �����︺CO2��
				if ($this->co2_cut_ol != '')
					get_data_info('co2_cut', 'oil', '�����︺CO2��', $column, $order, $sort_header);

				// �������︺CO2��
				if ($this->co2_cut_gl != '')
					get_data_info('co2_cut', 'gso', '���ގ��؎ݺ︺CO2��', $column, $order, $sort_header);

				// ���ߺ︺CO2��
				if ($this->co2_cut_gm != '')
					get_data_info('co2_cut', 'dst', '���ߺ︺CO2��', $column, $order, $sort_header);

				// ������CO2�ӽ���
				if ($this->co2_4cut != '')
					get_data_info('co2', '4cut', '4��︺CO2��', $column, $order, $sort_header);

				// ������CO2�ӽ���
				if ($this->co2_6cut != '')
					get_data_info('co2', '6cut', '6��︺CO2��', $column, $order, $sort_header);

				// ���Ѻ︺����������
				if ($this->use_rui_al != '') {
					$this->use_rui_el = 'on';
					$this->use_rui_gs = 'on';
					$this->use_rui_wt = 'on';
					$this->use_rui_gm = 'on';
					$this->use_rui_gl = 'on';
					$this->use_rui_ol = 'on';
				}
				// ���Ѻ︺�������
				if ($this->sum_rui_al != '') {
					$this->sum_rui_el = 'on';
					$this->sum_rui_gs = 'on';
					$this->sum_rui_wt = 'on';
					$this->sum_rui_gl = 'on';
					$this->sum_rui_ol = 'on';
				}
				// ���Ѻ︺CO2����
				if ($this->co2_rui_al != '') {
					$this->co2_rui_el = 'on';
					$this->co2_rui_gs = 'on';
					$this->co2_rui_wt = 'on';
					$this->co2_rui_gm = 'on';
					$this->co2_rui_gl = 'on';
					$this->co2_rui_ol = 'on';
				}

				// ���ѻ���
				function get_ruiseki($kind, $eng, $col_name, &$column, &$order, &$sort_header, $inp_y, $inp_m) {
					$date = $inp_y . '-' . $inp_m . '-01';
					$col_org = 'ba_' . $kind . '_' . $eng;
					$col = $kind . "_rui_" . $eng;
					$column[] = "(SELECT SUM($col_org) AS $col FROM t_batch_data WHERE ba_ym <= " . sql_date($date) . " AND ba_mb_seq_no=mb_seq_no) AS $col";
					$order[] = $col;
					$sort_header[] = $col_name;
				}

				// �ŵ��︺������
				if ($this->use_rui_el != '')
					get_ruiseki('use_cut', 'ele', '�ŵ����Ѻ︺������', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �������Ѻ︺������
				if ($this->use_rui_gs != '')
					get_ruiseki('use_cut', 'gas', '�������Ѻ︺������', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// ��ƻ���Ѻ︺������
				if ($this->use_rui_wt != '')
					get_ruiseki('use_cut', 'wtr', '��ƻ���Ѻ︺������', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �������Ѻ︺������
				if ($this->use_rui_ol != '')
					get_ruiseki('use_cut', 'oil', '�������Ѻ︺������', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// ����������Ѻ︺������
				if ($this->use_rui_gl != '')
					get_ruiseki('use_cut', 'gso', '���ގ��؎����Ѻ︺������', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �������Ѻ︺������
				if ($this->use_rui_gm != '')
					get_ruiseki('use_cut', 'dst', '�������Ѻ︺������', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �ŵ����Ѻ︺���
				if ($this->sum_rui_el != '')
					get_ruiseki('sum_cut', 'ele', '�ŵ����Ѻ︺���', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �������Ѻ︺���
				if ($this->sum_rui_gs != '')
					get_ruiseki('sum_cut', 'gas', '�������Ѻ︺���', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// ��ƻ���Ѻ︺���
				if ($this->sum_rui_wt != '')
					get_ruiseki('sum_cut', 'wtr', '��ƻ���Ѻ︺���', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �������Ѻ︺���
				if ($this->sum_rui_ol != '')
					get_data_info('sum_cut', 'oil', '�������Ѻ︺���', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// ����������Ѻ︺���
				if ($this->sum_rui_gl != '')
					get_ruiseki('sum_cut', 'gso', '���ގ��؎����Ѻ︺���', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �������Ѻ︺���
				if ($this->sum_4rui != '')
					get_ruiseki('sum', '4cut', '4�����Ѻ︺���', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �������Ѻ︺���
				if ($this->sum_6rui != '')
					get_ruiseki('sum', '6cut', '6�����Ѻ︺���', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �ŵ��︺CO2��
				if ($this->co2_rui_el != '')
					get_ruiseki('co2_cut', 'ele', '�ŵ����Ѻ︺CO2��', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �������Ѻ︺CO2��
				if ($this->co2_rui_gs != '')
					get_ruiseki('co2_cut', 'gas', '�������Ѻ︺CO2��', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// ��ƻ���Ѻ︺CO2��
				if ($this->co2_rui_wt != '')
					get_ruiseki('co2_cut', 'wtr', '��ƻ���Ѻ︺CO2��', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �������Ѻ︺CO2��
				if ($this->co2_rui_ol != '')
					get_ruiseki('co2_cut', 'oil', '�������Ѻ︺CO2��', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// ����������Ѻ︺CO2��
				if ($this->co2_rui_gl != '')
					get_ruiseki('co2_cut', 'gso', '���ގ��؎����Ѻ︺CO2��', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// �������Ѻ︺CO2��
				if ($this->co2_rui_gm != '')
					get_ruiseki('co2_cut', 'dst', '�������Ѻ︺CO2��', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// ������CO2�ӽ���
				if ($this->co2_4rui != '')
					get_ruiseki('co2', '4cut', '4�����Ѻ︺CO2��', $column, $order, $sort_header, $this->inp_y, $this->inp_m);

				// ������CO2�ӽ���
				if ($this->co2_6rui != '')
					get_ruiseki('co2', '6cut', '6�����Ѻ︺CO2��', $column, $order, $sort_header, $this->inp_y, $this->inp_m);


			}

			$this->column = join(',', $column);
			$this->order = join(',', $order);
			$this->sort_header = join(',', $sort_header);
			$this->from_join = $from_join;
		}
	}

	function make_data($result, $nrow, &$str, &$csv_data) {
		// �Ϥ�/������
		function decode_yesno2($code) {
			if ($code == 1)
				$def = '�Ϥ�';
			elseif ($code == 0)
				$def = '������';
			else
				$def = '̤����';
			return $def;
		}

		// ���ϥե饰
		function decode_inp_flag($code) {
			if ($code == 1)
				$def = '���Ѥʤ�';
			elseif ($code == 2)
				$def = '���Ϥ��ʤ�';
			else
				$def = '';
			return $def;
		}

		function make_line_data($col_name, &$data, &$side) {
			$side = '';
			switch($col_name) {
				case 'mb_id':
					$side = 'left';
					break;
				case 'mb_sex':
					$data = decode_sex($data);
					$side = 'center';
					break;
				case 'mb_birthday';
					$data = format_date($data);
					$side = 'center';
					break;
				case 'mb_area2':
					$data = decode_area3($data);
					$side = 'center';
					break;
				case 'mb_zip':
					$data = substr($data, 0, 3) . "-" . substr($data, 3, 4);
					$side = 'center';
					break;
				case 'mb_area_cd':
					$data = decode_area($data);
					$side = 'center';
					break;
				case 'mb_keitai_cd':
					$data = decode_keitai($data);
					break;
				case 'mb_sozai_cd':
					$data = decode_sozai($data);
					break;
				case 'mb_hebel_flag':
					$data = decode_yesno2($data);
					break;
				case 'mb_chikunen_cd':
					$data = decode_chikunen($data);
					$side = 'left';
					break;
				case 'mb_room_cd':
					$data = decode_room($data);
					break;
				case 'mb_space_cd':
					$data = decode_space($data);
					break;
				case 'mb_boiler_cd':
					$data = decode_boiler($data);
					break;
				case 'mb_gas_kind_cd':
					$data = decode_gas_kind($data);
					break;
				case 'mb_gas_type_cd':
					$data = decode_gas_type($data);
					break;
				case 'mb_water_month':
					$data = decode_water($data);
					break;
				case 'mb_family_ctg':
					$data = decode_family_ctg($data);
					break;
				case 'fm_detail':
					$sql = "SELECT fr_name,fm_age from t_family left join m_family_rel on fm_family_rel_cd=fr_family_rel_cd where fm_mb_seq_no=$data;";
					$result = db_exec($sql);
					$nrow = pg_numrows($result);
					for ($i = 0; $i < $nrow; $i++) {
						$fetch = pg_fetch_object($result, $i);
						$family[] = $fetch->fr_name . '(' . $fetch->fm_age . ')';
					}
					if (is_array($family))
						$data = join('��', $family);
					else
						$data = $family;
					break;
				case 'fm_child':
					$data = $data == 0 ? '�ʤ�' : '����';
					break;
				case 'mb_gm_flag':
					$data = decode_input($data);
					break;
				case 'mb_ol_flag':
					$data = decode_input($data);
					break;
				case 'mb_gl_flag':
					$data = decode_input($data);
					break;
				case 'mb_reg_kari_date':
					$data = format_date($data);
					$side = 'center';
					break;
				case 'mb_regist_date':
					$data = format_date($data);
					$side = 'center';
					break;
				case 'bd_auto_commit';
					if ($data == 1)
						$data = '����';
					elseif ($data == 3)
						$data = '����';
					$side = 'center';
					break;
				case 'bd_gm_inp_cd':
					$data = decode_inp_flag($data);
					$side = 'center';
					break;
				case 'bd_ol_inp_cd':
					$data = decode_inp_flag($data);
					$side = 'center';
					break;
				case 'bd_gl_inp_cd':
					$data = decode_inp_flag($data);
					$side = 'center';
					break;
			}
			if ($side == '') {
				if (check_num($data))
					$side = 'right';
				else
					$side = 'left';
			}
		}

		for ($i = 0; $i < $nrow; $i++) {
			$str .= "<tr class='tc" . ($i % 2) . "'>\n";
			$fetch = pg_fetch_row($result, $i);
			$n = 0;
			$line_data = '';
			for ($n = 0; $n < pg_NumFields($result); $n++) {
				$data = array_shift($fetch);
				make_line_data(pg_fieldname($result,$n), $data, $side);
				$str .= "<td align=\"" . $side . "\"><nobr>$data</nobr></td>\n";
				$line_data[] = $data;
			}
			$str .= "</tr>\n";
			$csv_data[] = join(',',$line_data);
		}
		
		if ($csv_data)
			$this->csv_data = join("\r\n", $csv_data);
	}
}
?>