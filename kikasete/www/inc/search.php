<?
/******************************************************
' System :�������ơ�net����
' Content:������說�饹
'******************************************************/

class search_class {
	var $type;
	var $sex;
	var $age_from;
	var $age_to;
	var $mikikon;
	var $shokugyou;
	var $gyoushu;
	var $shokushu;
	var $kyojuu;
	var $kinmu;
	var $kinmu_andor;
	var $genre;
	var $genre_andor;
	var $conveni;
	var $conveni_andor;
	var $super;
	var $super_andor;
	var $family_sex_m;
	var $family_sex_w;
	var $family_rel;
	var $family_rel_andor;
	var $family_age_from;
	var $family_age_to;
	var $child;
	var $child_age_from;
	var $child_age_to;
	var $sql;
	var $zip;
	var $jogai_hasshin;
	var $jogai_response;
	var $taishou_hasshin;
	var $taishou_response;
	var $member_list;
	var $html_mail;
	var $jitaku_area;
	var $age_cd;
	var $have_child;
	var $housing_form;
	var $have_car;
	var $ma_profile;
	var $ma_profile_andor;
	var $ma_profile_tbl;
	var $ma_profile_cd;
	var $spouse_flg;
	var $spouse_age_from;
	var $spouse_age_to;
	var $spouse_shokugyo;
	var $child_sex;
	var $child_gakunen;
	var $senior_flg;
	var $haimail_flag;
	var $taisho_myenq_list;
	var $jogai_myenq_list;
	var $special_panel;

	// ���󥹥ȥ饯��
	function search_class() {
		$this->kinmu_andor = 'O';
		$this->genre_andor = 'O';
		$this->conveni_andor = 'O';
		$this->super_andor = 'O';
		$this->family_rel_andor = 'O';
		$this->read_ma_profile();
	}

	// �ɲåץ�ե�����ơ��֥��ɤ߹���
	function read_ma_profile() {
		$this->ma_profile_tbl = array();
		$this->ma_profile_cd = array();

		// �ץ�ե������ɲù��ܥơ��֥��ɤ߹���
		$sql = "SELECT pfi_profile_id,pfi_profile_name,pfi_hidden_flag"
				. " FROM t_profile_item"
				. " ORDER BY pfi_order";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$this->ma_profile_tbl[$fetch->pfi_profile_id]->profile_name = $fetch->pfi_profile_name;
			$this->ma_profile_tbl[$fetch->pfi_profile_id]->hidden_flag = $fetch->pfi_hidden_flag;
		}

		// �ץ�ե����������ơ��֥��ɤ߹���
		$sql = "SELECT pfs_profile_cd,pfs_profile_id,pfs_select_text"
				. " FROM t_profile_sel"
				. " ORDER BY pfs_order";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$this->ma_profile_tbl[$fetch->pfs_profile_id]->sel[$fetch->pfs_profile_cd] = $fetch->pfs_select_text;
			$this->ma_profile_cd[$fetch->pfs_profile_cd] = $fetch->pfs_profile_id;
		}
	}

	// ʣ���ǡ���������
	function get_multi_data($name) {
		if ($name)
			$data = join(',', $name);

		return $data;
	}

	// ��˥�������
	function decode_monitor_type($code, $def='����ʤ�') {
		switch ($code) {
		case '0':
			return '����˥���';
		case '1':
			return '��˥����Τ�';
		case '2':
			return '�ꥵ�����ѡ��ȥʡ��Τ�';
		case '3':
			return '��˥���ͥ��';
		case '4':
			return '�ꥵ�����ѡ��ȥʡ�ͥ��';
        case '5':
            return '�����ƥ��֥�˥����Τ�';
        case '6':
            return 'GMO��˥���';
		}
		return $def;
	}

	// �ե����फ��ǡ�������
	function get_form() {
		// ����
		$this->type = $_POST['type_flag'] ? $_POST['type'] : '';

		// ����
		$this->sex = $_POST['sex_flag'] ? $_POST['sex'] : '';

		// ǯ��
		$this->age_from = $_POST['age_flag'] ? $_POST['age_from'] : '';
		$this->age_to = $_POST['age_flag'] ? $_POST['age_to'] : '';

		// ̤����
		$this->mikikon = $_POST['mikikon_flag'] ? $_POST['mikikon'] : '';

		// ����
		$this->shokugyou = $_POST['shokugyou_flag'] ? $this->get_multi_data($_POST['shokugyou']) : '';

		// �ȼ�
		$this->gyoushu = $_POST['gyoushu_flag'] ? $this->get_multi_data($_POST['gyoushu']) : '';

		// ����
		$this->shokushu = $_POST['shokushu_flag'] ? $this->get_multi_data($_POST['shokushu']) : '';

		// �ｻ�ϰ�
		$this->kyojuu = $_POST['kyojuu_flag'] ? $this->get_multi_data($_POST['kyojuu']) : '';

		// ��̳�ϰ�
		$this->kinmu = $_POST['kinmu_flag'] ? $this->get_multi_data($_POST['kinmu']) : '';
		$this->kinmu_andor = $_POST['kinmu_andor'];

		// ��̣������
		$this->genre = $_POST['genre_flag'] ? $this->get_multi_data($_POST['genre']) : '';
		$this->genre_andor = $_POST['genre_andor'];

		// �褯�Ԥ�����ӥ�
		$this->conveni = $_POST['conveni_flag'] ? $this->get_multi_data($_POST['conveni']) : '';
		$this->conveni_andor = $_POST['conveni_andor'];

		// �褯�Ԥ������ѡ�
		$this->super = $_POST['super_flag'] ? $this->get_multi_data($_POST['super']) : '';
		$this->super_andor = $_POST['super_andor'];

		// ��²������
		$this->family_sex_m = $_POST['family_sex_flag'] ? $_POST['family_sex_m'] : '';
		$this->family_sex_w = $_POST['family_sex_flag'] ? $_POST['family_sex_w'] : '';

		// ��²��³��
		$this->family_rel = $_POST['family_rel_flag'] ? $this->get_multi_data($_POST['family_rel']) : '';
		$this->family_rel_andor = $_POST['family_rel_andor'];

		// ��²��ǯ��
		$this->family_age_from = $_POST['family_age_flag'] ? $_POST['family_age_from'] : '';
		$this->family_age_to = $_POST['family_age_flag'] ? $_POST['family_age_to'] : '';

		// �Ҷ���̵ͭ
		$this->child = $_POST['child_flag'] ? $_POST['child'] : '';

		// �۶��Ԥ�̵ͭ
		$this->spouse_flg = $_POST['spouse_flg_flag'] ? $_POST['spouse_flg'] : '';

		// �۶��Ԥ�ǯ��
		$this->spouse_age_from = $this->spouse_flg == '1' ? $_POST['spouse_age_from'] : '';
		$this->spouse_age_to = $this->spouse_flg == '1' ? $_POST['spouse_age_to'] : '';

		// �۶��Ԥο���
		$this->spouse_shokugyo = $this->spouse_flg == '1' ? $_POST['spouse_shokugyo'] : '';

		// �Ҷ���̵ͭ
		$this->have_child = $_POST['have_child_flag'] ? $_POST['have_child'] : '';

		// �Ҷ�������
		$this->child_sex = $this->have_child == '1' ? $_POST['child_sex'] : '';

		// �Ҷ���ǯ��
		$this->child_age_from = $this->have_child == '1' ? $_POST['child_age_from'] : '';
		$this->child_age_to = $this->have_child == '1' ? $_POST['child_age_to'] : '';

		// �Ҷ��γ�ǯ
		$this->child_gakunen = $this->have_child == '1' ? $_POST['child_gakunen'] : '';

		// ���˥��ؤ�̵ͭ
		$this->senior_flg = $_POST['senior_flg_flag'] ? $_POST['senior_flg'] : '';

		// �������
		$this->housing_form = $_POST['housing_form_flag'] ? $_POST['housing_form'] : '';

		// �֤���ͭ
		$this->have_car = $_POST['have_car_flag'] ? $this->get_multi_data($_POST['have_car']) : '';

		// �ɲåץ�ե�����
		$ary_id = array();
		$ary_andor = array();
		foreach (array_keys($this->ma_profile_tbl) as $profile_id) {
			$base_name = "profile$profile_id";
			if ($_POST[$base_name . '_flag']) {
				if (isset($_POST[$base_name]))
					$ary_id = array_merge($_POST[$base_name], $ary_id);

				if ($_POST[$base_name . '_andor'] == 'A')
					$ary_andor[] = $profile_id;
			}
		}
		$this->ma_profile = join(',', $ary_id);
		$this->ma_profile_andor = join(',', $ary_andor);

		// �ӣѣ�
		$this->sql = $_POST['sql_flag'] ? $_POST['add_sql'] : '';

		// ͹���ֹ�
		$this->zip = $_POST['zip_flag'] ? $_POST['zip'] : '';

		// ����ȯ�������
		$this->jogai_hasshin = $_POST['jogai_hasshin_flag'] ? $_POST['jogai_hasshin'] : '';

		// �����쥹�ݥ󥹥����
		$this->jogai_response = $_POST['jogai_response_flag'] ? $_POST['jogai_response'] : '';

		// �о�ȯ�������
		$this->taishou_hasshin = $_POST['taishou_hasshin_flag'] ? $_POST['taishou_hasshin'] : '';

		// �оݥ쥹�ݥ󥹥����
		$this->taishou_response = $_POST['taishou_response_flag'] ? $_POST['taishou_response'] : '';

		// ���С��ꥹ��
		$this->member_list = $_POST['member_list_flag'] ? $_POST['member_list'] : '';

		// HTML�᡼�����
		$this->html_mail = $_POST['html_mail_flag'] ? $_POST['html_mail'] : '';

		// �Ϥ��᡼�����
		$this->haimail_flag = $_POST['haimail_flag'];
	}

	// DB���鸡��������
	function read_db($search_id) {
		// �������ơ��֥�
		$sql = "SELECT * FROM t_search WHERE sr_search_id=$search_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$this->type = $fetch->sr_type;
			$this->sex = $fetch->sr_sex;
			$this->age_from = $fetch->sr_age_from;
			$this->age_to = $fetch->sr_age_to;
			$this->mikikon = $fetch->sr_mikikon;
			$this->shokugyou = $fetch->sr_shokugyou;
			$this->gyoushu = $fetch->sr_gyoushu;
			$this->shokushu = $fetch->sr_shokushu;
			$this->kyojuu = $fetch->sr_kyojuu;
			$this->kinmu = $fetch->sr_kinmu;
			$this->kinmu_andor = $fetch->sr_kinmu_andor;
			$this->genre = $fetch->sr_genre;
			$this->genre_andor = $fetch->sr_genre_andor;
			$this->conveni = $fetch->sr_conveni;
			$this->conveni_andor = $fetch->sr_conveni_andor;
			$this->super = $fetch->sr_super;
			$this->super_andor = $fetch->sr_super_andor;
			$this->family_sex_m = $fetch->sr_family_sex_m;
			$this->family_sex_w = $fetch->sr_family_sex_w;
			$this->family_rel = $fetch->sr_family_rel;
			$this->family_rel_andor = $fetch->sr_family_rel_andor;
			$this->family_age_from = $fetch->sr_family_age_from;
			$this->family_age_to = $fetch->sr_family_age_to;
			$this->child = $fetch->sr_child;
			$this->child_age_from = $fetch->sr_child_age_from;
			$this->child_age_to = $fetch->sr_child_age_to;
			$this->sql = $fetch->sr_sql;
			$this->zip = $fetch->sr_zip;
			$this->jogai_hasshin = $fetch->sr_jogai_hasshin;
			$this->jogai_response = $fetch->sr_jogai_response;
			$this->taishou_hasshin = $fetch->sr_taishou_hasshin;
			$this->taishou_response = $fetch->sr_taishou_response;
			$this->member_list = $fetch->sr_member_list;
			$this->html_mail = $fetch->sr_html_mail;
			$this->jitaku_area = $fetch->sr_jitaku_area;
			$this->age_cd = $fetch->sr_age_cd;
			$this->have_child = $fetch->sr_have_child;
			$this->housing_form = $fetch->sr_housing_form;
			$this->have_car = $fetch->sr_have_car;
			$this->ma_profile = $fetch->sr_ma_profile;
			$this->ma_profile_andor = $fetch->sr_ma_profile_andor;
			$this->spouse_flg = $fetch->sr_spouse_flg;
			$this->spouse_age_from = $fetch->sr_spouse_age_from;
			$this->spouse_age_to = $fetch->sr_spouse_age_to;
			$this->spouse_shokugyo = $fetch->sr_spouse_shokugyo;
			$this->child_sex = $fetch->sr_child_sex;
			$this->child_gakunen = $fetch->sr_child_gakunen;
			$this->senior_flg = $fetch->sr_senior_flg;
			$this->haimail_flag = $fetch->sr_haimail_flag;
			$this->taisho_myenq_list = $fetch->sr_taisho_myenq_list;
			$this->jogai_myenq_list = $fetch->sr_jogai_myenq_list;
			$this->special_panel = $fetch->sr_special_panel;
		}
	}

	// DB�˸��������¸
	function write_db($search_id = '') {
		if ($search_id == '') {
			$sql = "INSERT INTO t_search (sr_type,sr_sex,sr_age_from,sr_age_to,sr_mikikon,sr_shokugyou,sr_gyoushu,sr_shokushu,sr_kyojuu,sr_kinmu,sr_kinmu_andor,sr_genre,sr_genre_andor,sr_conveni,sr_conveni_andor,sr_super,sr_super_andor,sr_family_sex_m,sr_family_sex_w,sr_family_rel,sr_family_rel_andor,sr_family_age_from,sr_family_age_to,sr_child,sr_child_age_from,sr_child_age_to,sr_sql,sr_zip,sr_jogai_hasshin,sr_jogai_response,sr_taishou_hasshin,sr_taishou_response,sr_member_list,sr_html_mail,sr_jitaku_area,sr_age_cd,sr_have_child,sr_housing_form,sr_have_car,sr_ma_profile,sr_ma_profile_andor,sr_spouse_flg,sr_spouse_age_from,sr_spouse_age_to,sr_spouse_shokugyo,sr_child_sex,sr_child_gakunen,sr_senior_flg,sr_haimail_flag,sr_taisho_myenq_list,sr_jogai_myenq_list,sr_special_panel) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		} else {
			$sql = "UPDATE t_search SET sr_type=%s,sr_sex=%s,sr_age_from=%s,sr_age_to=%s,sr_mikikon=%s,sr_shokugyou=%s,sr_gyoushu=%s,sr_shokushu=%s,sr_kyojuu=%s,sr_kinmu=%s,sr_kinmu_andor=%s,sr_genre=%s,sr_genre_andor=%s,sr_conveni=%s,sr_conveni_andor=%s,sr_super=%s,sr_super_andor=%s,sr_family_sex_m=%s,sr_family_sex_w=%s,sr_family_rel=%s,sr_family_rel_andor=%s,sr_family_age_from=%s,sr_family_age_to=%s,sr_child=%s,sr_child_age_from=%s,sr_child_age_to=%s,sr_sql=%s,sr_zip=%s,sr_jogai_hasshin=%s,sr_jogai_response=%s,sr_taishou_hasshin=%s,sr_taishou_response=%s,sr_member_list=%s,sr_html_mail=%s,sr_jitaku_area=%s,sr_age_cd=%s,sr_have_child=%s,sr_housing_form=%s,sr_have_car=%s,sr_ma_profile=%s,sr_ma_profile_andor=%s,sr_spouse_flg=%s,sr_spouse_age_from=%s,sr_spouse_age_to=%s,sr_spouse_shokugyo=%s,sr_child_sex=%s,sr_child_gakunen=%s,sr_senior_flg=%s,sr_haimail_flag=%s,sr_taisho_myenq_list=%s,sr_jogai_myenq_list=%s,sr_special_panel=%s WHERE sr_search_id=$search_id";
		}
		$sql = sprintf($sql,
				sql_number($this->type),
				sql_number($this->sex),
				sql_number($this->age_from),
				sql_number($this->age_to),
				sql_number($this->mikikon),
				sql_char($this->shokugyou),
				sql_char($this->gyoushu),
				sql_char($this->shokushu),
				sql_char($this->kyojuu),
				sql_char($this->kinmu),
				sql_char($this->kinmu_andor),
				sql_char($this->genre),
				sql_char($this->genre_andor),
				sql_char($this->conveni),
				sql_char($this->conveni_andor),
				sql_char($this->super),
				sql_char($this->super_andor),
				sql_bool($this->family_sex_m),
				sql_bool($this->family_sex_w),
				sql_char($this->family_rel),
				sql_char($this->family_rel_andor),
				sql_number($this->family_age_from),
				sql_number($this->family_age_to),
				sql_bool($this->child),
				sql_number($this->child_age_from),
				sql_number($this->child_age_to),
				sql_char($this->sql),
				sql_char($this->zip),
				sql_char($this->jogai_hasshin),
				sql_char($this->jogai_response),
				sql_char($this->taishou_hasshin),
				sql_char($this->taishou_response),
				sql_char($this->member_list),
				sql_bool($this->html_mail),
				sql_char($this->jitaku_area),
				sql_char($this->age_cd),
				sql_char($this->have_child),
				sql_char($this->housing_form),
				sql_char($this->have_car),
				sql_char($this->ma_profile),
				sql_char($this->ma_profile_andor),
				sql_number($this->spouse_flg),
				sql_number($this->spouse_age_from),
				sql_number($this->spouse_age_to),
				sql_char($this->spouse_shokugyo),
				sql_number($this->child_sex),
				sql_char($this->child_gakunen),
				sql_number($this->senior_flg),
				sql_bool($this->haimail_flag),
				sql_char($this->taisho_myenq_list),
				sql_char($this->jogai_myenq_list),
				sql_char($this->special_panel));
		db_exec($sql);

		if ($search_id == '')
			$search_id = get_currval('t_search_sr_search_id_seq');

		return $search_id;
	}

	// ����SQL����
	function make_sql() {
		// ���֥�����
		$sql = "mn_status=0";

		// �֥�å��ꥹ����Ͽ�Ԥ��оݤ��鳰��
		and_join($sql, 'mn_blacklist_flg IS NULL');

		// ����
		if ($this->type == 1 || $this->type == 2)
			and_join($sql, "mn_type=$this->type");
		elseif ($this->type == 5)	// �����ƥ��֥�˥���
			and_join($sql, "mn_name2<>''");

		// HTML�᡼�����
		if ($this->html_mail != '')
			and_join($sql, "mn_html_mail='$this->html_mail'");

		// �Ϥ��᡼�����
		if ($this->haimail_flag != '')
			and_join($sql, "mn_haimail_flag='$this->haimail_flag'");

		// ����
		if ($this->sex != '')
			and_join($sql, "mn_sex=$this->sex");

		// ̤����
		if ($this->mikikon != '')
			and_join($sql, "mn_mikikon=$this->mikikon");

		// ǯ��
		if ($this->age_from != '' && $this->age_to != '')
			and_join($sql, "DATE_PART('Y',AGE(mn_birthday)) BETWEEN $this->age_from AND $this->age_to");
		elseif ($this->age_from != '')
			and_join($sql, "DATE_PART('Y',AGE(mn_birthday))>=$this->age_from");
		elseif ($this->age_to != '')
			and_join($sql, "DATE_PART('Y',AGE(mn_birthday))<=$this->age_to");

		// ����
		if ($this->shokugyou != '')
			and_join($sql, $this->multi_cond('mn_shokugyou_cd', $this->shokugyou));

		// �ȼ�
		if ($this->gyoushu != '')
			and_join($sql, $this->multi_cond('mn_gyoushu_cd', $this->gyoushu));

		// ����
		if ($this->shokushu != '')
			and_join($sql, $this->multi_cond('mn_shokushu_cd', $this->shokushu));

		// �ӣѣ�
		if ($this->sql != '')
			and_join($sql, "($this->sql)");

		// ͹���ֹ�
		if ($this->zip != '') {
			$temp = '';
			$temp2 = '';
			$ary = split("[,\r\n]", $this->zip);
			foreach ($ary as $item) {
				$item = trim($item);
				if (strlen($item) == 7) {
					if ($temp != '')
						$temp .= ',';
					$temp .= "'$item'";
				} elseif (strlen($item) > 0 && strlen($item) < 7)
					or_join($temp2, "mn_jitaku_zip LIKE '$item%'");
			}
			if ($temp != '')
				$temp = $this->multi_cond('mn_jitaku_zip', $temp);
			or_join($temp, $temp2);
			if ($temp != '')
				and_join($sql, "($temp)");
		}

		// �ｻ�ϰ�
		if ($this->jitaku_area != '')
			and_join($sql, $this->multi_cond('mn_jitaku_area', $this->jitaku_area));

		// ǯ��
		if ($this->age_cd != '') {
			$temp = '';
			$age_from = $age_to = 0;
			$result = db_exec('SELECT ae_age_from,ae_age_to FROM m_age WHERE ' . $this->multi_cond('ae_age_cd', $this->age_cd) . " ORDER BY ae_age_cd");
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				if ($age_to == 0)
					$age_from = $fetch->ae_age_from;
				elseif ($age_to + 1 != $fetch->ae_age_from) {
					or_join($temp, "DATE_PART('Y',AGE(mn_birthday)) BETWEEN $age_from AND $age_to");
					$age_from = $fetch->ae_age_from;
				}
				$age_to = $fetch->ae_age_to;
			}
			or_join($temp, "DATE_PART('Y',AGE(mn_birthday)) BETWEEN $age_from AND $age_to");
			if ($temp != '')
				and_join($sql, "($temp)");
		}

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
				$temp = "EXISTS (SELECT * FROM m_zip WHERE zc_zip_uniq=mn_jitaku_zip AND ($temp))";
		}

		// ��̳���ϰ�
		$temp2 = '';
		if ($this->kinmu) {
			$ary = explode(',', $this->kinmu);
			foreach ($ary as $item) {
				$ary2 = explode('/', $item);
				if (count($ary2) == 2) {
					or_join($temp2, "zc_todoufuken='$ary2[0]'");
					and_join($temp2, "zc_shikuchouson LIKE '$ary2[1]%'");
				} else
					or_join($temp2, "zc_todoufuken='$item'");
			}
			if ($temp2 != '')
				$temp2 = "EXISTS (SELECT * FROM m_zip WHERE zc_zip_uniq=mn_kinmu_zip AND ($temp2))";
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

		// �۶��Ԥ�̵ͭ
		if ($this->spouse_flg != '')
			and_join($sql, "mn_spouse_flg=$this->spouse_flg");

		// �۶��Ԥ�ǯ��
		if ($this->spouse_age_from != '' && $this->spouse_age_to != '')
			and_join($sql, "DATE_PART('Y',AGE(mn_spouse_birthday)) BETWEEN $this->spouse_age_from AND $this->spouse_age_to");
		elseif ($this->spouse_age_from != '')
			and_join($sql, "DATE_PART('Y',AGE(mn_spouse_birthday))>=$this->spouse_age_from");
		elseif ($this->spouse_age_to != '')
			and_join($sql, "DATE_PART('Y',AGE(mn_spouse_birthday))<=$this->spouse_age_to");

		// �۶��Ԥο���
		if ($this->spouse_shokugyo != '')
			and_join($sql, $this->multi_cond('mn_spouse_shokugyo_cd', $this->spouse_shokugyo));

		// �Ҷ���̵ͭ
		if ($this->have_child != '')
			and_join($sql, $this->multi_cond('mn_have_child', $this->have_child));

		// �Ҷ�������
		$child_cond = '';
		if ($this->child_sex != '')
			and_join($child_cond, "mc_sex=$this->child_sex");

		// �Ҷ���ǯ��
		$temp = '';
		if ($this->child_age_from != '' && $this->child_age_to != '')
			$temp = "DATE_PART('Y',AGE(mc_birthday)) BETWEEN $this->child_age_from AND $this->child_age_to";
		elseif ($this->child_age_from != '')
			$temp = "DATE_PART('Y',AGE(mc_birthday))>=$this->child_age_from";
		elseif ($this->child_age_to != '')
			$temp = "DATE_PART('Y',AGE(mc_birthday))<=$this->child_age_to";
		if ($temp != '')
			and_join($child_cond, $temp);

		// �Ҷ��γ�ǯ
		if ($this->child_gakunen != '') {
			and_join($child_cond, "(SELECT gk_gakunen_cd FROM m_gakunen WHERE DATE_PART('Y',AGE(date_trunc('year', current_date - INTERVAL '3 months') + INTERVAL '3 months',mc_birthday)) BETWEEN gk_age_from AND gk_age_to) IN ($this->child_gakunen)");
		}

		if ($child_cond != NULL)
			and_join($sql, "EXISTS (SELECT * FROM t_monitor_child WHERE mc_monitor_id=mn_monitor_id AND $child_cond)");

		// ���˥��ؤ�̵ͭ
		if ($this->senior_flg != '')
			and_join($sql, "mn_senior_flg=$this->senior_flg");

		// �������
		if ($this->housing_form != '')
			and_join($sql, $this->multi_cond('mn_housing_form', $this->housing_form));

		// �֤ν�ͭ
		if ($this->have_car != '')
			and_join($sql, $this->multi_cond('mn_have_car', $this->have_car));

		// ��̣������
		if ($this->genre != '') {
			$temp = $this->multi_cond('dk_genre_cd', $this->genre);
			if ($this->genre_andor == 'O')
				and_join($sql, "EXISTS (SELECT * FROM t_genre WHERE dk_monitor_id=mn_monitor_id AND $temp)");
			else {
				$cnt = count(explode(',', $this->genre));
				and_join($sql, "(SELECT COUNT(*) FROM t_genre WHERE dk_monitor_id=mn_monitor_id AND $temp)=$cnt");
			}
		}

		// �褯�Ԥ�����ӥ�
		if ($this->conveni != '') {
			$temp = $this->multi_cond('dc_conveni_cd', $this->conveni);
			if ($this->conveni_andor == 'O')
				and_join($sql, "EXISTS (SELECT * FROM t_conveni WHERE dc_monitor_id=mn_monitor_id AND $temp)");
			else {
				$cnt = count(explode(',', $this->conveni));
				and_join($sql, "(SELECT COUNT(*) FROM t_conveni WHERE dc_monitor_id=mn_monitor_id AND $temp)=$cnt");
			}
		}

		// �褯�Ԥ������ѡ�
		if ($this->super != '') {
			$temp = $this->multi_cond('ds_super_cd', $this->super);
			if ($this->super_andor == 'O')
				and_join($sql, "EXISTS (SELECT * FROM t_super WHERE ds_monitor_id=mn_monitor_id AND $temp)");
			else {
				$cnt = count(explode(',', $this->super));
				and_join($sql, "(SELECT COUNT(*) FROM t_super WHERE ds_monitor_id=mn_monitor_id AND $temp)=$cnt");
			}
		}
/*
		// ��²������
		$temp = '';
		if ($this->family_sex_m == 't')
			and_join($temp, "(fr_sex=1 OR fr_sex=0 AND mn_sex=2)");
		if ($this->family_sex_w == 't')
			or_join($temp, "(fr_sex=2 OR fr_sex=0 AND mn_sex=1)");
		if ($temp != '')
			and_join($sql, "EXISTS (SELECT * FROM t_family JOIN m_family_rel ON fr_family_rel_cd=fm_family_rel_cd WHERE fm_monitor_id=mn_monitor_id AND ($temp))");

		// ��²��³��
		if ($this->family_rel != '') {
			$temp = $this->multi_cond('fm_family_rel_cd', $this->family_rel);
			if ($this->family_rel_andor == 'O')
				and_join($sql, "EXISTS (SELECT * FROM t_family WHERE fm_monitor_id=mn_monitor_id AND $temp)");
			else {
				$cnt = count(explode(',', $this->family_rel));
				and_join($sql, "(SELECT COUNT(*) FROM t_family WHERE fm_monitor_id=mn_monitor_id AND $temp)=$cnt");
			}
		}

		// ��²��ǯ��
		$temp = '';
		if ($this->family_age_from != '' && $this->family_age_to != '')
			$temp = "fm_age BETWEEN $this->family_age_from AND $this->family_age_to";
		elseif ($this->family_age_from != '')
			$temp = "fm_age>=$this->family_age_from";
		elseif ($this->family_age_to != '')
			$temp = "fm_age<=$this->family_age_to";
		if ($temp != '')
			and_join($sql, "EXISTS (SELECT * FROM t_family WHERE fm_monitor_id=mn_monitor_id AND $temp)");

		// �Ҷ���̵ͭ
		switch ($this->child) {
		case 't':
			and_join($sql, "EXISTS (SELECT * FROM t_family JOIN m_family_rel ON fr_family_rel_cd=fm_family_rel_cd WHERE fm_monitor_id=mn_monitor_id AND fr_attb>=1)");
			break;
		case 'f':
			and_join($sql, "EXISTS (SELECT * FROM t_family WHERE fm_monitor_id=mn_monitor_id) AND NOT EXISTS (SELECT * FROM t_family JOIN m_family_rel ON fr_family_rel_cd=fm_family_rel_cd WHERE fm_monitor_id=mn_monitor_id AND fr_attb>=1)");
			break;
		}

		// �Ҷ���ǯ��
		$temp = '';
		if ($this->child_age_from != '' && $this->child_age_to != '')
			$temp = "fr_attb>=1 AND fm_age BETWEEN $this->child_age_from AND $this->child_age_to";
		elseif ($this->child_age_from != '')
			$temp = "fr_attb>=1 AND fm_age>=$this->child_age_from";
		elseif ($this->child_age_to != '')
			$temp = "fr_attb>=1 AND fm_age<=$this->child_age_to";
		if ($temp != '')
			and_join($sql, "EXISTS (SELECT * FROM t_family JOIN m_family_rel ON fr_family_rel_cd=fm_family_rel_cd WHERE fm_monitor_id=mn_monitor_id AND $temp)");
*/
		// �ɲåץ�ե�����
		if ($this->ma_profile != '') {
			$ma_profile_ary = explode(',', $this->ma_profile);
			$ma_profile_andor_ary = explode(',', $this->ma_profile_andor);

			foreach ($this->ma_profile_tbl as $profile_id => $profile_data) {
				$ary = array();
				if (is_array($profile_data->sel)) {
					foreach (array_keys($profile_data->sel) as $profile_cd) {
						if (in_array($profile_cd, $ma_profile_ary))
							$ary[] = $profile_cd;
					}
				}
				$cnt = count($ary);
				if ($cnt > 0) {
					$temp = $this->multi_cond('mnp_profile_cd', join(',', $ary));
					if (in_array($profile_id, $ma_profile_andor_ary))
						and_join($sql, "(SELECT COUNT(*) FROM t_monitor_profile WHERE mnp_monitor_id=mn_monitor_id AND $temp)=$cnt");
					else
						and_join($sql, "EXISTS (SELECT * FROM t_monitor_profile WHERE mnp_monitor_id=mn_monitor_id AND $temp)");
				}
			}
		}

		// �о�ȯ�������
		if ($this->taishou_hasshin != '') {
			$temp = '';
			$ary = split("[,\r\n]", $this->taishou_hasshin);
			foreach ($ary as $item) {
				$item = trim($item);
				if ($item != '') {
					if ($temp != '')
						$temp .= ',';
					$temp .= $item;
				}
			}
			if ($temp != '')
				and_join($sql, "EXISTS (SELECT * FROM t_enquete JOIN t_enquete_list ON el_enquete_id=en_enquete_id WHERE el_monitor_id=mn_monitor_id AND " . $this->multi_cond('en_job_id', $temp) . ")");
		}

		// �оݥ쥹�ݥ󥹥����
		if ($this->taishou_response != '') {
			$temp = '';
			$ary = split("[,\r\n]", $this->taishou_response);
			foreach ($ary as $item) {
				$item = trim($item);
				if ($item != '') {
					if ($temp != '')
						$temp .= ',';
					$temp .= $item;
				}
			}
			if ($temp != '')
				and_join($sql, "EXISTS (SELECT * FROM t_enquete JOIN t_answer ON an_enquete_id=en_enquete_id WHERE an_monitor_id=mn_monitor_id AND " . $this->multi_cond('en_job_id', $temp) . ")");
		}

		// ����ȯ�������
		if ($this->jogai_hasshin != '') {
			$temp = '';
			$ary = split("[,\r\n]", $this->jogai_hasshin);
			foreach ($ary as $item) {
				$item = trim($item);
				if ($item != '') {
					if ($temp != '')
						$temp .= ',';
					$temp .= $item;
				}
			}
			if ($temp != '')
				and_join($sql, "NOT EXISTS (SELECT * FROM t_enquete JOIN t_enquete_list ON el_enquete_id=en_enquete_id WHERE el_monitor_id=mn_monitor_id AND " . $this->multi_cond('en_job_id', $temp) . ")");
		}

		// �����쥹�ݥ󥹥����
		if ($this->jogai_response != '') {
			$temp = '';
			$ary = split("[,\r\n]", $this->jogai_response);
			foreach ($ary as $item) {
				$item = trim($item);
				if ($item != '') {
					if ($temp != '')
						$temp .= ',';
					$temp .= $item;
				}
			}
			if ($temp != '')
				and_join($sql, "NOT EXISTS (SELECT * FROM t_enquete JOIN t_answer ON an_enquete_id=en_enquete_id WHERE an_monitor_id=mn_monitor_id AND " . $this->multi_cond('en_job_id', $temp) . ")");
		}

		// ���С��ꥹ��
		if ($this->member_list != '') {
			$temp = '';
			$ary = split("[,\r\n]", $this->member_list);
			foreach ($ary as $item) {
				$item = trim($item);
				if ($item != '') {
					if ($temp != '')
						$temp .= ',';
					$temp .= $item;
				}
			}
			if ($temp != '')
				and_join($sql, "EXISTS (SELECT * FROM t_member_list WHERE ml_monitor_id=mn_monitor_id AND " . $this->multi_cond('ml_group_id', $temp) . ")");
		}

		// My&Pro�оݥ��С��ꥹ��
		if ($this->taisho_myenq_list != '') {
			$temp = '';
			$ary = split("[,\r\n]", $this->taisho_myenq_list);
			foreach ($ary as $item) {
				$item = trim($item);
				if ($item != '') {
					if ($temp != '')
						$temp .= ',';
					$temp .= $item;
				}
			}
			if ($temp != '')
				and_join($sql, "EXISTS (SELECT * FROM t_myenq_member_list WHERE mml_monitor_id=mn_monitor_id AND " . $this->multi_cond('mml_group_id', $temp) . ")");
		}

		// My&Pro�������С��ꥹ��
		if ($this->jogai_myenq_list != '') {
			$temp = '';
			$ary = split("[,\r\n]", $this->jogai_myenq_list);
			foreach ($ary as $item) {
				$item = trim($item);
				if ($item != '') {
					if ($temp != '')
						$temp .= ',';
					$temp .= $item;
				}
			}
			if ($temp != '')
				and_join($sql, "NOT EXISTS (SELECT * FROM t_myenq_member_list WHERE mml_monitor_id=mn_monitor_id AND " . $this->multi_cond('mml_group_id', $temp) . ")");
		}

		// ���ڥ����ѥͥ�
		if ($this->special_panel != '') {
			$panels = array();
			$sql2 = 'SELECT pnl_panel_id,pnl_category_id FROM t_panel WHERE ' . $this->multi_cond('pnl_panel_id', $this->special_panel) . " ORDER BY pnl_panel_id";
			$result = db_exec($sql2);
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);
				$panels[$fetch->pnl_category_id][] = $fetch->pnl_panel_id;
			}

			foreach ($panels as $panel) {
				$panel_id = implode(',', $panel);
				and_join($sql, "EXISTS (SELECT * FROM t_panel_monitor_list WHERE pnm_monitor_id=mn_monitor_id AND pnm_panel_id IN ($panel_id))");
			}
		}

		return $sql;
	}

	// �����SQL������job_id�����
	function make_sql2($job_id) {
		$sql = "SELECT jb_parent_job_id,jb_job_type,jb_ct_id FROM t_job WHERE jb_job_id=$job_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			switch ($fetch->jb_job_type) {
			case 1:		// ���󥱡��ȥ쥹�ݥ󥹥����
				$sql = "SELECT jb_enquete_id FROM t_job WHERE jb_job_id=$fetch->jb_parent_job_id";
				$enquete_id = db_fetch1($sql);

				$sql2 = "EXISTS (SELECT * FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=mn_monitor_id)";

				$sql = "SELECT tk_teikei_qno,tk_teikei_andor,tk_teikei_ans FROM t_teikei WHERE tk_job_id=$job_id ORDER BY tk_teikei_qno";
				$result = db_exec($sql);
				$nrow = pg_numrows($result);
				for ($i = 0; $i < $nrow; $i++) {
					$fetch = pg_fetch_object($result, $i);
					$teikei_qno = $fetch->tk_teikei_qno;
					$teikei_andor = $fetch->tk_teikei_andor;
					$teikei_ans = $fetch->tk_teikei_ans;

					if ($teikei_ans != '') {
						$temp = "SELECT COUNT(*) FROM t_ans_select WHERE as_enquete_id=$enquete_id AND as_monitor_id=mn_monitor_id AND
as_question_no=$teikei_qno AND as_sel_no IN ($teikei_ans)";
						If ($teikei_andor == 'A')
							$temp = "($temp)=" . count(explode(',', $teikei_ans));
						else
							$temp = "($temp)>0";

						and_join($sql2, $temp);
					}
				}
				break;
			Case 2:		// ����å��쥹�ݥ󥹥����
				$ct_id = $fetch->jb_ct_id;
				if ($ct_id == '')
					$ct_id = -1;
				$sql2 = "EXISTS (SELECT * FROM l_click_counter WHERE lc_monitor_id=mn_monitor_id AND lc_ct_id IN ($ct_id))";
				break;
			}
		}

		and_join($sql2, $this->make_sql());
		return $sql2;
	}

	// �����SQL�����ʥѥ�᡼�������
	function make_sql3($job_type, $parent_job_id, $teikei_qno_ary, $teikei_andor_ary, $teikei_ans_ary, $ct_id_ary) {
		switch ($job_type) {
		case 1:		// ���󥱡��ȥ쥹�ݥ󥹥����
			$sql = "SELECT jb_enquete_id FROM t_job WHERE jb_job_id=$parent_job_id";
			$enquete_id = db_fetch1($sql);

			$sql2 = "EXISTS (SELECT * FROM t_answer WHERE an_enquete_id=$enquete_id AND an_monitor_id=mn_monitor_id)";

			if (is_array($teikei_qno_ary)) {
				foreach ($teikei_qno_ary as $i => $teikei_qno) {
					$teikei_andor = $teikei_andor_ary[$i];
					$teikei_ans = $this->get_multi_data($teikei_ans_ary[$i]);

					if ($teikei_ans != '') {
						$temp = "SELECT COUNT(*) FROM t_ans_select WHERE as_enquete_id=$enquete_id AND as_monitor_id=mn_monitor_id AND
	as_question_no=$teikei_qno AND as_sel_no IN ($teikei_ans)";
						If ($teikei_andor == 'A')
							$temp = "($temp)=" . count(explode(',', $teikei_ans));
						else
							$temp = "($temp)>0";

						and_join($sql2, $temp);
					}
				}
			}
			break;
		Case 2:		// ����å��쥹�ݥ󥹥����
			$ct_id = $this->get_multi_data($ct_id_ary);
			if ($ct_id == '')
				$ct_id = -1;
			$sql2 = "EXISTS (SELECT * FROM l_click_counter WHERE lc_monitor_id=mn_monitor_id AND lc_ct_id IN ($ct_id))";
			break;
		}

		and_join($sql2, $this->make_sql());
		return $sql2;
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
		// ����
		if ($this->type != '')
			$text[] = '�����̡�' . $this->decode_monitor_type($this->type);

		// HTML�᡼�����
		if ($this->html_mail != '')
			$text[] = '��HTML�᡼�롧' . ($this->html_mail == DBTRUE ? '�ۿ�OK' : '�ۿ�NG');

		// �Ϥ��᡼�����
		if ($this->haimail_flag != '')
			$text[] = '���Ϥ��᡼�������' . ($this->haimail_flag == DBTRUE ? '��������' : '�������ʤ�');

		// ����
		if ($this->sex != '')
			$text[] = '�����̡�' . decode_sex($this->sex);

		// ǯ��
		if ($this->age_from != '' || $this->age_to != '') {
			$temp = '';
			if ($this->age_from != '')
				$temp = "{$this->age_from}��";
			$temp .= '��';
			if ($this->age_to != '')
				$temp .= "{$this->age_to}��";
			$text[] = "��ǯ��:$temp";
		}

		// ̤����
		if ($this->mikikon != '')
			$text[] = '��̤������' . decode_mikikon($this->mikikon);

		// ����
		if ($this->shokugyou != '') {
			$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd IN ($this->shokugyou) ORDER BY sg_order";
			$text[] = '�����ȡ�' . $this->get_name($sql);
		}

		// �ȼ�
		if ($this->gyoushu != '') {
			$sql = "SELECT gs_gyoushu_name FROM m_gyoushu WHERE gs_gyoushu_cd IN ($this->gyoushu) ORDER BY gs_order";
			$text[] = '���ȼ' . $this->get_name($sql);
		}

		// ����
		if ($this->shokushu != '') {
			$sql = "SELECT ss_shokushu_name FROM m_shokushu WHERE ss_shokushu_cd IN ($this->shokushu) ORDER BY ss_order";
			$text[] = '�����' . $this->get_name($sql);
		}

		// �ｻ�ϰ�
		if ($this->kyojuu)
			$text[] = '���ｻ�ϰ衧' . str_replace('/', '', $this->kyojuu);

		// ��̳���ϰ�
		if ($this->kinmu)
			$text[] = '����̳���ϰ衧' . str_replace('/', '', $this->kinmu);

		// �ｻ�ϰ衿��̳���ϰ� AND/OR
		if ($this->kyojuu != '' && $this->kinmu != '')
			$text[] = '���ｻ�ϰ衿��̳���ϰ�ַ���' . decode_andor($this->kinmu_andor);

		// �۶��Ԥ�̵ͭ
		if ($this->spouse_flg != '')
			$text[] = '���۶��Ԥ�̵ͭ��' . decode_spouse_flg($this->spouse_flg);

		// �۶��Ԥ�ǯ��
		if ($this->spouse_age_from != '' || $this->spouse_age_to != '') {
			$temp = '';
			if ($this->spouse_age_from != '')
				$temp = "{$this->spouse_age_from}��";
			$temp .= '��';
			if ($this->spouse_age_to != '')
				$temp .= "{$this->spouse_age_to}��";
			$text[] = "���۶��Ԥ�ǯ��:$temp";
		}

		// �۶��Ԥο���
		if ($this->spouse_shokugyo != '') {
			$sql = "SELECT sg_shokugyou_name FROM m_shokugyou WHERE sg_shokugyou_cd IN ($this->spouse_shokugyo) ORDER BY sg_order";
			$text[] = '���۶��Ԥο��ȡ�' . $this->get_name($sql);
		}

		// �Ҷ���̵ͭ
		if ($this->have_child != '')
			$text[] = '���Ҷ���̵ͭ��' . multi_decode(decode_have_child, $this->have_child);

		// �Ҷ�������
		if ($this->child_sex != '')
			$text[] = '���Ҷ������̡�' . decode_child_sex($this->child_sex);

		// �Ҷ���ǯ��
		if ($this->child_age_from != '' || $this->child_age_to != '') {
			$temp = '';
			if ($this->child_age_from != '')
				$temp = "{$this->child_age_from}��";
			$temp .= '��';
			if ($this->child_age_to != '')
				$temp .= "{$this->child_age_to}��";
			$text[] = "���Ҷ���ǯ��:$temp";
		}

		// �Ҷ��γ�ǯ
		if ($this->child_gakunen != '')
			$text[] = '���Ҷ��γ�ǯ��' . multi_decode(decode_child_gakunen, $this->child_gakunen);

		// ���˥��ؤ�̵ͭ
		if ($this->senior_flg != '')
			$text[] = '�����˥��ؤ�̵ͭ��' . decode_senior_flg($this->senior_flg);

		// �������
		if ($this->housing_form != '')
			$text[] = '��������֡�' . multi_decode(decode_housing_form, $this->housing_form);

		// �֤ν�ͭ
		if ($this->have_car != '')
			$text[] = '���֤ν�ͭ��' . multi_decode(decode_have_car, $this->have_car);

		// ��̣������
		if ($this->genre != '') {
			$sql = "SELECT kg_name FROM m_genre WHERE kg_genre_cd IN ($this->genre) ORDER BY kg_order";
			$text[] = '����̣�����롧�����' . decode_andor($this->genre_andor) . $this->get_name($sql);
		}

		// �褯�Ԥ�����ӥ�
		if ($this->conveni != '') {
			$sql = "SELECT cv_name FROM m_conveni WHERE cv_conveni_cd IN ($this->conveni) ORDER BY cv_order";
			$text[] = '���褯�Ԥ�����ӥˡ������' . decode_andor($this->conveni_andor) . $this->get_name($sql);
		}

		// �褯�Ԥ������ѡ�
		if ($this->super != '') {
			$sql = "SELECT sp_name FROM m_super WHERE sp_super_cd IN ($this->super) ORDER BY sp_order";
			$text[] = '���褯�Ԥ������ѡ��������' . decode_andor($this->super_andor) . $this->get_name($sql);
		}
/*
		// ��²������
		if ($this->family_sex_m == 't' || $this->family_sex_w == 't') {
			$temp = '';
			if ($this->family_sex_m == 't')
				$temp = '����';
			if ($this->family_sex_w == 't') {
				if ($temp)
					$temp .= '��';
				$temp .= '����';
			}
			$text[] = "����²�����̡�$temp";
		}

		// ��²��³��
		if ($this->family_rel != '') {
			$sql = "SELECT fr_name FROM m_family_rel WHERE fr_family_rel_cd IN ($this->family_rel) ORDER BY fr_family_rel_cd";
			$text[] = '����²��³���������' . decode_andor($this->family_rel_andor) . $this->get_name($sql);
		}

		// ��²��ǯ��
		if ($this->family_age_from != '' || $this->family_age_to != '') {
			$temp = '';
			if ($this->family_age_from != '')
				$temp = "{$this->family_age_from}��";
			$temp .= '��';
			if ($this->family_age_to != '')
				$temp .= "{$this->family_age_to}��";
			$text[] = "����²��ǯ��$temp";
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
			$text[] = "���Ҷ���̵ͭ��$temp";
		}

		// �Ҷ���ǯ��
		if ($this->child_age_from != '' || $this->child_age_to != '') {
			$temp = '';
			if ($this->child_age_from != '')
				$temp = "{$this->child_age_from}��";
			$temp .= '��';
			if ($this->child_age_to != '')
				$temp .= "{$this->child_age_to}��";
			$text[] = "���Ҷ���ǯ��$temp";
		}
*/
		// �ӣѣ�
		if ($this->sql != '')
			$text[] = "���ɲãӣѣ̡�$this->sql";

		// ͹���ֹ�
		if ($this->zip != '')
			$text[] = "��͹���ֹ桧$this->zip";

		// �о�ȯ�������
		if ($this->taishou_hasshin != '')
			$text[] = "���о�ȯ������֡�$this->taishou_hasshin";

		// �оݥ쥹�ݥ󥹥����
		if ($this->taishou_response != '')
			$text[] = "���оݥ쥹�ݥ󥹥���֡�$this->taishou_response";

		// ����ȯ�������
		if ($this->jogai_hasshin != '')
			$text[] = "������ȯ������֡�$this->jogai_hasshin";

		// �����쥹�ݥ󥹥����
		if ($this->jogai_response != '')
			$text[] = "�������쥹�ݥ󥹥���֡�$this->jogai_response";

		// ���С��ꥹ��
		if ($this->member_list != '')
			$text[] = "�����С��ꥹ�ȡ�$this->member_list";

		// My&Pro�оݥ��С��ꥹ��
		if ($this->taisho_myenq_list != '')
			$text[] = "���оݥ��С��ꥹ�ȡ�$this->taisho_myenq_list";

		// My&Pro�������С��ꥹ��
		if ($this->jogai_myenq_list != '')
			$text[] = "���������С��ꥹ�ȡ�$this->jogai_myenq_list";

		// ���ڥ����ѥͥ�
		if ($this->special_panel != '')
			$text[] = "�����ڥ����ѥͥ롧$this->special_panel";

		// ���̵��
		if ($text)
			return join("\n", $text);
		else
			return '���̵��';
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

	// ��˥���������
	function get_monitor_num() {
		$sql = "SELECT COUNT(*) FROM " . $this->get_monitor_table() . " WHERE " . $this->make_sql();
		return db_fetch1($sql);
	}

	// ��˥����ơ��֥����
	function get_monitor_table() {
		if ($this->type == 6)
			return 'g_monitor';
		else
			return 't_monitor';
	}
}

class cell_class {
	var $cell_option;
	var $age_option;
	var $sex_option;
	var $rate_option;
	var $send_num;
	var $send_rate;
	var $cell_num;

	// ���󥹥ȥ饯��
	function cell_class() {
		$this->cell_option = 1;
		$this->age_option = 1;
		$this->sex_option = 1;
		$this->rate_option = 1;
		$this->send_num = array();
		$this->send_rate = array();
		$this->cell_num = 0;
	}

	// DB���饻��������
	function read_db($search_id, $send_num = 0) {
		// ȯ��������ơ��֥�
		$sql = "SELECT cla_age_option,cla_sex_option,cla_rate_option FROM t_cell_assign WHERE cla_search_id=$search_id";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$this->cell_option = 2;
			$this->age_option = $fetch->cla_age_option;
			$this->sex_option = $fetch->cla_sex_option;
			$this->rate_option = $fetch->cla_rate_option;

			// ȯ��������ơ��֥�
			$this->cell_num = 0;
			$sql = "SELECT cln_sex,cln_age,cln_send_num FROM t_cell_num WHERE cln_search_id=$search_id";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);

				$this->send_num[$fetch->cln_sex][$fetch->cln_age] = $fetch->cln_send_num;
				$this->cell_num++;
			}

			// ȯ�����ơ��֥�
			$sql = "SELECT clr_code,clr_send_rate FROM t_cell_rate WHERE clr_search_id=$search_id";
			$result = db_exec($sql);
			$nrow = pg_numrows($result);
			for ($i = 0; $i < $nrow; $i++) {
				$fetch = pg_fetch_object($result, $i);

				$this->send_rate[$fetch->clr_code] = $fetch->clr_send_rate;
			}
		} else
			$this->send_num[0][0] = $send_num;
	}

	// DB�˥��������¸
	function write_db($search_id) {
		db_begin_trans();

		db_delete('t_cell_assign', "cla_search_id=$search_id");
		db_delete('t_cell_num', "cln_search_id=$search_id");
		db_delete('t_cell_rate', "clr_search_id=$search_id");

		if ($this->cell_option == 2) {
			// ȯ��������ơ��֥�
			$rec['cla_search_id'] = sql_number($search_id);
			$rec['cla_age_option'] = sql_number($this->age_option);
			$rec['cla_sex_option'] = sql_number($this->sex_option);
			$rec['cla_rate_option'] = sql_number($this->rate_option);
			db_insert('t_cell_assign', $rec);

			// ȯ��������ơ��֥�
			if (is_array($this->send_num)) {
				foreach ($this->send_num as $sex => $ary) {
					foreach ($ary as $age => $send_num) {
						if ($send_num > 0) {
							$rec['cln_search_id'] = sql_number($search_id);
							$rec['cln_sex'] = sql_number($sex);
							$rec['cln_age'] = sql_number($age);
							$rec['cln_send_num'] = sql_number($send_num);
							db_insert('t_cell_num', $rec);
						}
					}
				}
			}

			// ȯ�����ơ��֥�
			if (is_array($this->send_rate)) {
				foreach ($this->send_rate as $code => $rate) {
						if ($rate > 0) {
						$rec['clr_search_id'] = sql_number($search_id);
						$rec['clr_code'] = sql_number($code);
						$rec['clr_send_rate'] = sql_number($rate);
						db_insert('t_cell_rate', $rec);
					}
				}
			}
		}

		db_commit_trans();
	}

	// ����Ϳ�����
	function get_search_cell($where, $reply_rate_flag = false, $appearance_ratio = 100, $mon_flg='t') {
		// ǯ�����̤��ֿ�Ψ����
		if ($reply_rate_flag)
			$reply_rate_ary = $this->get_reply_rete();

		$appearance_ratio /= 100;

		list($select, $join, $groupby) = $this->get_sql();
		$sql = "SELECT $select,COUNT(*) AS cnt"
				. " FROM ${mon_flg}_monitor"
				. " $join"
				. " WHERE $where"
				. " GROUP BY $groupby";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$sex = $fetch->sex;
			$age = $fetch->age;
			$cnt = $fetch->cnt;

			if ($reply_rate_flag)
				$cnt *= $reply_rate_ary[$sex][$age];

			$cnt = round($cnt * $appearance_ratio);

			$ary[$sex][$age][$fetch->rate] = $cnt;

			if ($this->rate_option != 1)
				$ary[$sex][$age][0] += $cnt;
		}

		return $ary;
	}

	// �оݼ����
	function get_search_list($where, $max_send_num, $reply_rate_flag = false, $appearance_ratio = 100, $mon_flg='t') {
		$send_cell = $this->get_send_cell($reply_rate_flag, $appearance_ratio);
		$member_ary = array();
		$send_count = 0;

		list($select, $join, $groupby) = $this->get_sql();

		$sql = "SELECT $select,mn_monitor_id"
				. " FROM ${mon_flg}_monitor"
				. " $join"
				. " WHERE $where"
				. " ORDER BY RANDOM()";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			if ($send_cell[$fetch->sex][$fetch->age][$fetch->rate] > 0) {
				$member_ary[] = $fetch->mn_monitor_id;
				$send_cell[$fetch->sex][$fetch->age][$fetch->rate]--;

				if (++$send_count >= $max_send_num)
					/* break */;
			}
		}

		return $member_ary;
	}

	// �ۿ���ǽ�оݼ����
	function get_search_list2($where, $max_send_num, $mon_flg='t') {
		$send_cell = $this->get_send_cell();
		$member_ary = array();
		$send_count = 0;

		list($select, $join, $groupby) = $this->get_sql();

		$sql = "SELECT $select,mn_monitor_id"
				. " FROM ${mon_flg}_monitor"
				. " $join"
				. " WHERE $where"
				. " ORDER BY RANDOM()";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			if ($send_cell[$fetch->sex][$fetch->age][$fetch->rate] > 0) {
				$member_ary[] = $fetch->mn_monitor_id;

				if ($max_send_num != 0 && ++$send_count >= $max_send_num)
					break;
			}
		}

		return $member_ary;
	}

	// �����SQL����
	function get_sql($date = '') {
		$select = array();
		$join = array();

		if ($date == '')
			$date = "current_date";
		else
			$date = "date '$date'";

		// ����
		if ($this->sex_option == 2)
			$select[] = "mn_sex AS sex";
		else
			$select[] = "0 AS sex";

		// ǯ��
		if ($this->age_option == 2) {
			// 5�й��
			$select[] = "ae_age_cd AS age";
			$join[] = "JOIN m_age ON DATE_PART('Y',AGE($date,mn_birthday)) BETWEEN ae_age_from AND ae_age_to";
		} elseif ($this->age_option == 3) {
			// 10�й��
			$select[] = "ge_age_cd AS age";
			$join[] = "JOIN m_age10 ON DATE_PART('Y',AGE($date,mn_birthday)) BETWEEN ge_age_from AND ge_age_to";
		} else
			$select[] = "0 AS age";

		// ̤���� or �ｻ�ϰ�
		if ($this->rate_option == 2)
			$select[] = "mn_mikikon AS rate";
		elseif ($this->rate_option == 3) {
			$select[] = "ar_area3_cd AS rate";
			$join[] = "JOIN m_area ON ar_area_cd=mn_jitaku_area";
		} else
			$select[] = "0 AS rate";

		return array(join(',', $select), join(' ', $join), "sex,age,rate");
	}

	// �ѿ��ϰϼ���
	function get_range(&$sex_s, &$sex_e, &$age_s, &$age_e, &$rate_s, &$rate_e, $ver = 1) {
		if ($this->sex_option == 2) {
			$sex_s = 1;
			$sex_e = 2;
		} else {
			$sex_s = 0;
			$sex_e = 0;
		}

		if ($this->age_option == 2) {
			// 5�й��
			$age_s = ($ver == 2) ? 3 : 1;
			$age_e = 14;
		} elseif ($this->age_option == 3) {
			// 10�й��
			$age_s = ($ver == 2) ? 2 : 1;;
			$age_e = 8;
		} else {
			$age_s = 0;
			$age_e = 0;
		}

		if ($this->rate_option == 2) {
			$rate_s = 1;
			$rate_e = 2;
		} elseif ($this->rate_option == 3) {
			$rate_s = 1;
			$rate_e = 10;
		} else {
			$rate_s = 0;
			$rate_e = 0;
		}
	}

	// �������������
	function get_send_cell($reply_rate_flag = false, $appearance_ratio = 100) {
		$this->get_range($sex_s, $sex_e, $age_s, $age_e, $rate_s, $rate_e);

		// ǯ�����̤��ֿ�Ψ����
		if ($reply_rate_flag)
			$reply_rate_ary = $this->get_reply_rete();

		$appearance_ratio /= 100;

		// ǯ�����̤�����������
		for ($sex = $sex_s; $sex <= $sex_e; $sex++) {
			for ($age = $age_s; $age <= $age_e; $age++) {
				$send_num = $this->send_num[$sex][$age] / $appearance_ratio;

				if ($reply_rate_flag)
					$send_num /= $reply_rate_ary[$sex][$age];

				$ary[$sex][$age][0] = ceil($send_num);
				if ($this->rate_option != 1) {
					for ($rate = $rate_s; $rate <= $rate_e; $rate++)
						$ary[$sex][$age][$rate] = ceil($send_num * $this->send_rate[$rate] / 100);
				}
			}
		}

		return $ary;
	}

	// ǯ�����̤��ֿ�Ψ����
	function get_reply_rete() {
		$age1_ary = array(0, 1, 2, 4, 6, 8, 10, 12, 14);
		$age2_ary = array(0, 1, 3, 5, 7, 9, 11, 13, 14);

		$sql = "SELECT rep_age_cd,rep_sex_cd,rep_reply_rate FROM m_reply_rate";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);

			$rep_reply_rate = $fetch->rep_reply_rate / 100;
			if ($this->age_option == 2) {
				$reply_rate_ary[$fetch->rep_sex_cd][$age1_ary[$fetch->rep_age_cd]] = $rep_reply_rate;
				$reply_rate_ary[$fetch->rep_sex_cd][$age2_ary[$fetch->rep_age_cd]] = $rep_reply_rate;
			} else
				$reply_rate_ary[$fetch->rep_sex_cd][$fetch->rep_age_cd] = $rep_reply_rate;
		}

		return $reply_rate_ary;
	}

	// �����Ѥ߿�����
	function get_sample_cell($enquete_id, $start_date, $mon_flg='t') {
		$sample_cell = array();

		list($select, $join, $groupby) = $this->get_sql($start_date);
		$sql = "SELECT $select,COUNT(*) AS cnt"
				. " FROM t_answer"
				. " JOIN ${mon_flg}_enq_monitor ON mn_enquete_id=an_enquete_id AND mn_monitor_id=an_monitor_id"
				. " $join"
				. " WHERE an_enquete_id=$enquete_id AND an_valid_flag"
				. " GROUP BY $groupby";
		$result = db_exec($sql);
		$nrow = pg_numrows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_object($result, $i);
			$sample_cell[$fetch->sex][$fetch->age] += $fetch->cnt;
		}

		return $sample_cell;
	}
}
?>