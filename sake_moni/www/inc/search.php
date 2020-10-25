<?
/******************************************************
' System :�������ʪ�Υ��󥱡��ȥ�˥�������
' Content:������說�饹
'******************************************************/

class search_class {
	var $sex;
	var $age_from;
	var $age_to;
	var $mikikon;
	var $shokugyou;
	var $chain;
	var $chain_andor;
	var $child;
	var $sql;
	var $jitaku_area;
	var $age_cd;
	var $research;

	// ���󥹥ȥ饯��
	function search_class() {
		$this->chain_andor = 'O';
	}

	// ʣ���ǡ���������
	function get_multi_data($name) {
		if ($name)
			$data = join(',', $name);

		return $data;
	}

	// DB���鸡��������
	function read_db($search_id) {
		$sql = "SELECT * FROM t_search WHERE sr_search_id=$search_id";
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_object($result, 0);

			$this->sex = $fetch->sr_sex;
			$this->age_from = $fetch->sr_age_from;
			$this->age_to = $fetch->sr_age_to;
			$this->mikikon = $fetch->sr_mikikon;
			$this->shokugyou = $fetch->sr_shokugyou;
			$this->chain = $fetch->sr_chain;
			$this->chain_andor = $fetch->sr_chain_andor;
			$this->child = $fetch->sr_child;
			$this->sql = $fetch->sr_sql;
			$this->jitaku_area = $fetch->sr_jitaku_area;
			$this->age_cd = $fetch->sr_age_cd;
			$this->research = $fetch->sr_research;
		}
	}

	// DB�˸��������¸
	function write_db($search_id = '') {
		if ($search_id == '') {
			$sql = "INSERT INTO t_search (sr_sex,sr_age_from,sr_age_to,sr_mikikon,sr_shokugyou,sr_chain,sr_chain_andor,sr_child,sr_sql,sr_jitaku_area,sr_age_cd,sr_research) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)";
		} else {
			$sql = "UPDATE t_search SET sr_sex=%s,sr_age_from=%s,sr_age_to=%s,sr_mikikon=%s,sr_shokugyou=%s,sr_chain=%s,sr_chain_andor=%s,sr_child=%s,sr_sql=%s,sr_jitaku_area=%s,sr_age_cd=%s,sr_research=%s WHERE sr_search_id=$search_id";
		}
		$sql = sprintf($sql,
				sql_number($this->sex),
				sql_number($this->age_from),
				sql_number($this->age_to),
				sql_number($this->mikikon),
				sql_char($this->shokugyou),
				sql_char($this->chain),
				sql_char($this->chain_andor),
				sql_number($this->child),
				sql_char($this->sql),
				sql_char($this->jitaku_area),
				sql_char($this->age_cd),
				sql_number($this->research));

		db_exec($sql);

		if ($search_id == '')
			$search_id = get_currval('t_search_sr_search_id_seq');

		return $search_id;
	}

	// ����SQL����
	function make_sql() {
		// ���֥�����
		$sql = "mn_status=0";

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

		// �ӣѣ�
		if ($this->sql != '')
			and_join($sql, "($this->sql)");

		// �ｻ�ϰ�
		if ($this->jitaku_area != '')
			and_join($sql, $this->multi_cond('mn_jitaku_area', $this->jitaku_area));

		// ǯ��
		if ($this->age_cd != '') {
			$temp = '';
			$age_from = $age_to = 0;
			$result = db_exec('SELECT ae_age_from,ae_age_to FROM m_age WHERE ' . $this->multi_cond('ae_age_cd', $this->age_cd) . " ORDER bY ae_age_cd");
			$nrow = pg_num_rows($result);
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

		// ��������
		if ($this->chain != '') {
			$temp = $this->multi_cond('dc_chain_cd', $this->chain);
			if ($this->chain_andor == 'O')
				and_join($sql, "EXISTS (SELECT * FROM t_chain WHERE dc_monitor_id=mn_monitor_id AND $temp)");
			else {
				$cnt = count(explode(',', $this->chain));
				and_join($sql, "(SELECT COUNT(*) FROM t_chain WHERE dc_monitor_id=mn_monitor_id AND $temp)=$cnt");
			}
		}

		// �Ҷ���̵ͭ
		if ($this->child)
			and_join($sql, "mn_child=$this->child");

		// ��ϢĴ���ؤλ���
		if ($this->research)
			and_join($sql, "mn_research=$this->research");

		return $sql;
	}

	// ʣ�����ʸ����
	function multi_cond($column, $cond) {
		if (mb_strpos($cond, ','))
			return "$column IN ($cond)";
		else
			return "$column=$cond";
	}

	// �ޥ�������̾�μ���
	function get_name($sql) {
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_row($result, $i);
			$name .= "\n����{$fetch[0]}��";
		}
		return $name;
	}

	// ��˥���������
	function get_monitor_num() {
		$sql = "SELECT COUNT(*) FROM t_monitor WHERE " . $this->make_sql();
		$result = db_exec($sql);
		if (pg_num_rows($result)) {
			$fetch = pg_fetch_row($result, 0);
			$monitor_num = $fetch[0];
		}
		return $monitor_num;
	}
}
?>