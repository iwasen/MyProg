<?
/******************************************************
' System :�֤���������� �������������ӥ��׻�̳���ѥڡ���
' Content:�桼���������������饹
'******************************************************/

class search_class {
	var $sex_cd;
	var $age_from;
	var $age_to;
	var $dealer_cd;
	var $tantou_shop_cd;
	var $mail_recv_flag;
	var $sql;

	// ����ǡ���������
	function get_ary_data(&$name) {
		if ($name)
			$data = join(',', $name);

		return $data;
	}

	// ʣ���ǡ���������
	function get_multi_data(&$name) {
		$data = '';
		$ary = split("[,\r\n]", $name);
		foreach ($ary as $item) {
			$item = trim($item);
			if ($item != '') {
				if ($data != '')
					$data .= ',';
				$data .= $item;
			}
		}
		return $data;
	}

	// ʣ�����ʸ�����ʿ��͡�
	function multi_cond_num($column, $cond) {
		if (mb_strpos($cond, ','))
			return "$column IN ($cond)";
		else
			return "$column=$cond";
	}

	// ʣ�����ʸ������ʸ�����
	function multi_cond_char($column, $cond) {
		if (mb_strpos($cond, ','))
			return "$column IN ('" . str_replace(',', "','", $cond) . "')";
		else
			return "$column='$cond'";
	}

	// �ե����फ��ǡ�������
	function get_form() {
		// ����
		$this->sex_cd = $_POST['sex_cd_flag'] ? $_POST['sex_cd'] : '';

		// ǯ��
		$this->age_from = $_POST['age_flag'] ? $_POST['age_from'] : '';
		$this->age_to = $_POST['age_flag'] ? $_POST['age_to'] : '';

		// ����Ź������
		$this->dealer_cd = $_POST['dealer_cd_flag'] ? $this->get_multi_data($_POST['dealer_cd']) : '';

		// ô��Ź�ޥ�����
		$this->tantou_shop_cd = $_POST['tantou_shop_cd_flag'] ? $this->get_multi_data($_POST['tantou_shop_cd']) : '';

		// ����Ź�᡼����ɼ�
		$this->mail_recv_flag = $_POST['mail_recv_flag'];

		// �ɲ�SQL
		$this->sql = $_POST['sql_flag'] ? $_POST['sql'] : '';
	}

	// DB���鸡��������
	function read_db($search_id) {
		if ($search_id) {
			$sql = "SELECT * FROM t_search WHERE src_search_id=$search_id";
			$result = db_exec($sql);
			if (pg_numrows($result)) {
				$fetch = pg_fetch_object($result, 0);

				$this->sex_cd = $fetch->src_sex_cd;
				$this->age_from = $fetch->src_age_from;
				$this->age_to = $fetch->src_age_to;
				$this->dealer_cd = $fetch->src_dealer_cd;
				$this->tantou_shop_cd = $fetch->src_tantou_shop_cd;
				$this->mail_recv_flag = $fetch->src_mail_recv_flag;
				$this->sql = $fetch->src_sql;
			}
		}
	}

	// DB�˸��������¸
	function write_db($search_id = 0) {
		$rec['src_sex_cd'] = sql_char($this->sex_cd);
		$rec['src_age_from'] = sql_number($this->age_from);
		$rec['src_age_to'] = sql_number($this->age_to);
		$rec['src_dealer_cd'] = sql_char($this->dealer_cd);
		$rec['src_tantou_shop_cd'] = sql_char($this->tantou_shop_cd);
		$rec['src_mail_recv_flag'] = sql_number($this->mail_recv_flag);
		$rec['src_sql'] = sql_char($this->sql);

		if ($search_id)
			db_update('t_search', $rec, "src_search_id=$search_id");
		else {
			db_insert('t_search', $rec);
			$search_id = get_current_seq('t_search', 'src_search_id');
		}

		return $search_id;
	}

	// ����SQL����
	function make_sql(&$from, &$where) {
		$from = "t_user_personal JOIN t_user_status ON ups_user_id=ust_user_id JOIN t_user_manage ON ups_user_id=umg_user_id";

		// ���֥�����
		$where = "ust_status IN (1,2)";

		// ����
		if ($this->sex_cd != '')
			and_join($where, "ups_sex_cd='$this->sex_cd'");

		// ǯ��
		if ($this->age_from != '' && $this->age_to != '')
			and_join($where, "ups_age BETWEEN $this->age_from AND $this->age_to");
		elseif ($this->age_from != '')
			and_join($where, "ups_age>=$this->age_from");
		elseif ($this->age_to != '')
			and_join($where, "ups_age<=$this->age_to");

		// ����Ź������
		if ($this->dealer_cd != '')
			and_join($where, $this->multi_cond_char('umg_dealer_cd', $this->dealer_cd));

		// ô��Ź�ޥ�����
		if ($this->tantou_shop_cd != '')
			and_join($where, $this->multi_cond_char('umg_tantou_shop_cd', $this->tantou_shop_cd));

		// ����Ź�᡼����ɼ�
		if ($this->mail_recv_flag != '') {
			and_join($where, "(ust_status=1 OR ust_status=2)");
		}

		// �ɲ�SQL
		if ($this->sql != '')
			and_join($where, "($this->sql)");
	}

	// �����������
	function get_condition() {
		// ����
		if ($this->sex_cd != '')
			$text .= '�����̡�' . decode_sex_cd($this->sex_cd) . "\n";

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

		// ����Ź������
		if ($this->dealer_cd != '')
			$text .= "���о�ȯ������֡�$this->dealer_cd\n";

		// �ɲãӣѣ�
		if ($this->sql != '')
			$text .= "���ɲãӣѣ̡�$this->sql\n";

		return $text;
	}
}
?>