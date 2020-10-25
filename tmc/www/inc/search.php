<?
/******************************************************
' System :「おクルマ選び お手伝いサービス」事務局用ページ
' Content:ユーザ検索条件処理クラス
'******************************************************/

class search_class {
	var $sex_cd;
	var $age_from;
	var $age_to;
	var $dealer_cd;
	var $tantou_shop_cd;
	var $mail_recv_flag;
	var $sql;

	// 配列データ一括取得
	function get_ary_data(&$name) {
		if ($name)
			$data = join(',', $name);

		return $data;
	}

	// 複数データ一括取得
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

	// 複数条件文処理（数値）
	function multi_cond_num($column, $cond) {
		if (mb_strpos($cond, ','))
			return "$column IN ($cond)";
		else
			return "$column=$cond";
	}

	// 複数条件文処理（文字列）
	function multi_cond_char($column, $cond) {
		if (mb_strpos($cond, ','))
			return "$column IN ('" . str_replace(',', "','", $cond) . "')";
		else
			return "$column='$cond'";
	}

	// フォームからデータ取得
	function get_form() {
		// 性別
		$this->sex_cd = $_POST['sex_cd_flag'] ? $_POST['sex_cd'] : '';

		// 年齢
		$this->age_from = $_POST['age_flag'] ? $_POST['age_from'] : '';
		$this->age_to = $_POST['age_flag'] ? $_POST['age_to'] : '';

		// 販売店コード
		$this->dealer_cd = $_POST['dealer_cd_flag'] ? $this->get_multi_data($_POST['dealer_cd']) : '';

		// 担当店舗コード
		$this->tantou_shop_cd = $_POST['tantou_shop_cd_flag'] ? $this->get_multi_data($_POST['tantou_shop_cd']) : '';

		// 販売店メール購読者
		$this->mail_recv_flag = $_POST['mail_recv_flag'];

		// 追加SQL
		$this->sql = $_POST['sql_flag'] ? $_POST['sql'] : '';
	}

	// DBから検索条件取得
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

	// DBに検索条件保存
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

	// 検索SQL生成
	function make_sql(&$from, &$where) {
		$from = "t_user_personal JOIN t_user_status ON ups_user_id=ust_user_id JOIN t_user_manage ON ups_user_id=umg_user_id";

		// 状態コード
		$where = "ust_status IN (1,2)";

		// 性別
		if ($this->sex_cd != '')
			and_join($where, "ups_sex_cd='$this->sex_cd'");

		// 年齢
		if ($this->age_from != '' && $this->age_to != '')
			and_join($where, "ups_age BETWEEN $this->age_from AND $this->age_to");
		elseif ($this->age_from != '')
			and_join($where, "ups_age>=$this->age_from");
		elseif ($this->age_to != '')
			and_join($where, "ups_age<=$this->age_to");

		// 販売店コード
		if ($this->dealer_cd != '')
			and_join($where, $this->multi_cond_char('umg_dealer_cd', $this->dealer_cd));

		// 担当店舗コード
		if ($this->tantou_shop_cd != '')
			and_join($where, $this->multi_cond_char('umg_tantou_shop_cd', $this->tantou_shop_cd));

		// 販売店メール購読者
		if ($this->mail_recv_flag != '') {
			and_join($where, "(ust_status=1 OR ust_status=2)");
		}

		// 追加SQL
		if ($this->sql != '')
			and_join($where, "($this->sql)");
	}

	// 検索条件内容
	function get_condition() {
		// 性別
		if ($this->sex_cd != '')
			$text .= '・性別：' . decode_sex_cd($this->sex_cd) . "\n";

		// 年齢
		if ($this->age_from != '' || $this->age_to != '') {
			$temp = '';
			if ($this->age_from != '')
				$temp = "{$this->age_from}才";
			$temp .= '〜';
			if ($this->age_to != '')
				$temp .= "{$this->age_to}才";
			$text .= "・年齢:$temp\n";
		}

		// 販売店コード
		if ($this->dealer_cd != '')
			$text .= "・対象発信ジョブ：$this->dealer_cd\n";

		// 追加ＳＱＬ
		if ($this->sql != '')
			$text .= "・追加ＳＱＬ：$this->sql\n";

		return $text;
	}
}
?>