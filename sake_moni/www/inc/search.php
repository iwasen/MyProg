<?
/******************************************************
' System :お酒と買物のアンケートモニター共通
' Content:検索条件クラス
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

	// コンストラクタ
	function search_class() {
		$this->chain_andor = 'O';
	}

	// 複数データ一括取得
	function get_multi_data($name) {
		if ($name)
			$data = join(',', $name);

		return $data;
	}

	// DBから検索条件取得
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

	// DBに検索条件保存
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

	// 検索SQL生成
	function make_sql() {
		// 状態コード
		$sql = "mn_status=0";

		// 性別
		if ($this->sex != '')
			and_join($sql, "mn_sex=$this->sex");

		// 未既婚
		if ($this->mikikon != '')
			and_join($sql, "mn_mikikon=$this->mikikon");

		// 年齢
		if ($this->age_from != '' && $this->age_to != '')
			and_join($sql, "DATE_PART('Y',AGE(mn_birthday)) BETWEEN $this->age_from AND $this->age_to");
		elseif ($this->age_from != '')
			and_join($sql, "DATE_PART('Y',AGE(mn_birthday))>=$this->age_from");
		elseif ($this->age_to != '')
			and_join($sql, "DATE_PART('Y',AGE(mn_birthday))<=$this->age_to");

		// 職業
		if ($this->shokugyou != '')
			and_join($sql, $this->multi_cond('mn_shokugyou_cd', $this->shokugyou));

		// ＳＱＬ
		if ($this->sql != '')
			and_join($sql, "($this->sql)");

		// 居住地域
		if ($this->jitaku_area != '')
			and_join($sql, $this->multi_cond('mn_jitaku_area', $this->jitaku_area));

		// 年代
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

		// チェーン
		if ($this->chain != '') {
			$temp = $this->multi_cond('dc_chain_cd', $this->chain);
			if ($this->chain_andor == 'O')
				and_join($sql, "EXISTS (SELECT * FROM t_chain WHERE dc_monitor_id=mn_monitor_id AND $temp)");
			else {
				$cnt = count(explode(',', $this->chain));
				and_join($sql, "(SELECT COUNT(*) FROM t_chain WHERE dc_monitor_id=mn_monitor_id AND $temp)=$cnt");
			}
		}

		// 子供の有無
		if ($this->child)
			and_join($sql, "mn_child=$this->child");

		// 関連調査への参加
		if ($this->research)
			and_join($sql, "mn_research=$this->research");

		return $sql;
	}

	// 複数条件文処理
	function multi_cond($column, $cond) {
		if (mb_strpos($cond, ','))
			return "$column IN ($cond)";
		else
			return "$column=$cond";
	}

	// マスタから名称取得
	function get_name($sql) {
		$result = db_exec($sql);
		$nrow = pg_num_rows($result);
		for ($i = 0; $i < $nrow; $i++) {
			$fetch = pg_fetch_row($result, $i);
			$name .= "\n　「{$fetch[0]}」";
		}
		return $name;
	}

	// モニター数検索
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